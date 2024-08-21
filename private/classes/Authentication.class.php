<?php
    class Authentication extends Database {
        use Notification;
        use Validate;
        private string $tableName = "users_tb";
        private string $allColumns = "*";
        private string $conditionColumn = "username";

        public function loginUser($form_data): array
        {
            $username = $form_data['username'];
            $password = $form_data['password'];

            if (empty($username) || empty($password)){
                sleep(1);
                return [
                    'status' => 400,
                    'message' => 'Username and password required to login.'
                ];
            }else{
                $condition = [
                    $this->conditionColumn => $username
                ];
                $result = $this->selectData($this->tableName, $this->allColumns, $condition);
                if (!$result){
                    sleep(1);
                    return [
                        'status' => 400,
                        'message' => 'Invalid username or password.'
                    ];
                }
                if (!password_verify($password, $result[0]['password'])){
                    sleep(1);
                    return [
                        'status' => 400,
                        'message' => 'Invalid username or password.'
                    ];
                }

                $_SESSION['user_id'] = $result[0]['user_id'];
                $_SESSION['role'] = $result[0]['role'];
                $_SESSION['username'] = $result[0]['username'];
                sleep(1);
                return [
                    'status' => 200,
                    'message' => 'Your login is successfully.'
                ];
            }
        }

        public function logoutUser(): array
        {
            unset($_SESSION['user_id']);
            unset($_SESSION['role']);
            unset($_SESSION['username']);
            return [
                'status' => 200,
                'message' => 'Your logout is successfully.'
            ];
        }

        public function sendOPTCode(mixed $form_data): array
        {
            $phone = $form_data['phone'];

            if (empty($phone)){
                return [
                    'status' => 400,
                    'message' => 'Enter your phone number.'
                ];
            }

            $condition = [
                'contact' => $phone
            ];
            $result = $this->selectData($this->tableName, $this->allColumns, $condition);
            if (!$result){
                sleep(1);
                return [
                    'status' => 400,
                    'message' => 'Sorry, You are not a member.'
                ];
            }

            $code = rand(100000, 999999);
            $message = "Hello ".$result[0]['username']. "\nEnter this code to reset your password.\nDon't share your code with anyone.\n ".$code. ".\n\nThank You!!!";
            $response = $this->send_sms($phone, $message);
            $responseData = json_decode($response, true);

            if ($responseData['code'] === 'ok') {

                $updateData = [
                    'code' => password_hash($code, PASSWORD_DEFAULT)
                ];
                $result = $this->updateData($this->tableName, $updateData, $condition);

                if (!$result){
                    return [
                        'status' => 400,
                        'message' => 'Error updating you. Retry.'
                    ];
                }

                $_SESSION['codeSent'] = $phone;
                return [
                    'status' => 200,
                    'message' => 'OTP code sent successful.'
                ];
            } elseif ($responseData['code'] === '103') {
                return [
                    'status' => 400,
                    'message' => 'Invalid phone number.'
                ];
            }elseif ($responseData['code'] === '105') {
                return [
                    'status' => 400,
                    'message' => 'Insufficient balance.'
                ];
            }elseif ($responseData['code'] === '104') {
                return [
                    'status' => 400,
                    'message' => 'Phone coverage not active.'
                ];
            }elseif ($responseData['code'] === '102') {
                return [
                    'status' => 400,
                    'message' => 'Authentication failed.'
                ];
            } else {
                return [
                    'status' => 400,
                    'message' => 'External error.'
                ];
            }

        }

        public function verifyOPTCode(mixed $form_data): array
        {
            $code = $form_data['code'];
            $phone = $_SESSION['codeSent'];
            if (empty($code)){
                return [
                    'status' => 400,
                    'message' => 'Enter code to reset password.'
                ];
            }
            $condition = [
                'contact' => $phone
            ];
            $result = $this->selectData($this->tableName, $this->allColumns, $condition);

            if (!$result){
                return [
                    'status' => 400,
                    'message' => 'External error! Retry.'
                ];
            }

            if (!password_verify($code, $result[0]['code'])){
                return [
                    'status' => 400,
                    'message' => 'Wrong verification code. Retry.'
                ];
            }


            $updateData = [
                'code' => ''
            ];
            $result = $this->updateData($this->tableName, $updateData, $condition);

            if (!$result){
                return [
                    'status' => 400,
                    'message' => 'Error updating you. Retry.'
                ];
            }

            return [
                'status' => 200,
                'message' => 'Code verified successful.'
            ];

        }

        public function passwordRest(mixed $form_data): array
        {
            $password1 = $form_data['password1'];
            $password2 = $form_data['password2'];

            if (empty($password1) || empty($password2)){
                return [
                    'status' => 400,
                    'message' => 'Password and confirm password required.'
                ];
            }elseif (!$this->validatePassword($password1)){
                return [
                    'status' => 400,
                    'message' => 'Password required at least 6 characters.'
                ];
            }elseif ($password1 !== $password2){
                return [
                    'status' => 400,
                    'message' => 'Password and confirm password not matching.'
                ];
            }else{
                $condition = [
                    'contact' => $_SESSION['codeSent']
                ];
                $updateData = [
                    'password' => password_hash($password1, PASSWORD_DEFAULT)
                ];
                $result = $this->updateData($this->tableName, $updateData, $condition);

                if (!$result){
                    return [
                        'status' => 400,
                        'message' => 'Error updating password. Retry.'
                    ];
                }

                unset($_SESSION['codeSent']);
                return [
                    'status' => 200,
                    'message' => 'Password reset successful. Login'
                ];

            }
        }

        public function changePassword(mixed $form_data)
        {
            $user_id = $form_data['user_id'];
            $old_password = $form_data['old_password'];
            $new_password = $form_data['new_password'];
            $c_new_password = $form_data['c_new_password'];

            if (empty($old_password) || empty($new_password) || empty($c_new_password)){
                return [
                    'status' => 400,
                    'message' => 'All inputs are required to change password'
                ];
            }elseif (!$this->validatePassword($new_password)){
                return [
                    'status' => 400,
                    'message' => 'Password required at least 6 characters.'
                ];
            }elseif ($new_password !== $c_new_password){
                return [
                    'status' => 400,
                    'message' => 'Password and confirm password not matching.'
                ];
            }

            $condition = [
                'user_id' => $user_id
            ];
            $result = $this->selectData($this->tableName, '*', $condition);
            if (!$result){
                return [
                    'status' => 400,
                    'message' => 'Error'
                ];
            }
            if (!password_verify($old_password, $result[0]['password'])){
                return [
                    'status' => 400,
                    'message' => 'Wrong old password.'
                ];
            }
            $updateData = [
                'password' => password_hash($new_password, PASSWORD_DEFAULT)
            ];
            $this->updateData($this->tableName, $updateData, $condition);
            sleep(1);
            return [
                'status' => 200,
                'message' => 'Password changed successfully.'
            ];
        }
    }
