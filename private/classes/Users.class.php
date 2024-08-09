<?php

class Users extends Database{
    use Validate;
    use Notification;
    private string $tableName = "users_tb";
    private array $columns = ['username', 'name', 'contact', 'password', 'role'];

    public function postUser($form_data): array
    {
        $name = $form_data['name'];
        $username = $form_data['username'];
        $phone = $form_data['phone'];
        $role = $form_data['role'];
        $password = $form_data['password'];

        if (empty($name) || empty($username) || empty($phone) || empty($role) || empty($password)){
            return [
                'status' => 400,
                'message' => 'All input files are required.'
            ];
        }elseif (!$this->validateName($name)){
            return [
                'status' => 400,
                'message' => 'Name allows characters, hyphens, and spaces.'
            ];
        }elseif (!$this->validatePhoneNumber($phone)){
            return [
                'status' => 400,
                'message' => 'Phone number allows 10 to 13 digits.'
            ];
        }elseif (!$this->validatePassword($password)){
            return [
                'status' => 400,
                'message' => 'Password required at least 6 characters.'
            ];
        }else{

            $condition = [
                'username' => $username,
            ];
            $result = $this->selectData($this->tableName, '*', $condition);
            if ($result){
                return [
                    'status' => 400,
                    'message' => 'User with this username already exist.'
                ];
            }

            $condition = [
                'contact' => $phone
            ];
            $result = $this->selectData($this->tableName, '*', $condition);
            if ($result){
                return [
                    'status' => 400,
                    'message' => 'User with this phone number already exist.'
                ];
            }



            $message = "Hello ".$username. "\nYour account have been created successfully.\nLogin using your username and password -> ".$password. ".\n\nThank You!!!";
            $response = $this->send_sms($phone, $message);
            $responseData = json_decode($response, true);

            if ($responseData['code'] === 'ok') {
                $values = [$username, $name, $phone, password_hash($password, PASSWORD_DEFAULT), $role];
                $result = $this->insertData($this->tableName, $this->columns, $values);
                if (!$result){
                    return [
                        'status' => 400,
                        'message' => 'Error adding new user.'
                    ];
                }

                return [
                    'status' => 200,
                    'message' => 'User created successfully.'
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
    }

    public function getAllUser(): false|array
    {
        return $this->selectData($this->tableName, '*', array(), null, null, 'user_id', 'DESC');
    }

    public function getMe($user_id): false|array
    {
        $condition = [
            'user_id' => $user_id
        ];
        return $this->selectData($this->tableName, '*', $condition);
    }

    public function countTotalUsers(){
        return $this->countRows('users_tb');
    }

    public function getByIdUser($form_data): false|array
    {
        $user_id = $form_data['getUserById'];

        $condition = [
            'user_id' => $user_id
        ];

        $data = $this->selectData($this->tableName, '*', $condition);
        if ($data){
            return [
                'status' => 200,
                'data' => $data[0]
            ];
        }

        return [
            'status' => 400,
            'message' => 'External error'
        ];

    }

    public function putUser($form_data): array
    {

        $user_id = $form_data['user_id'];
        $name = $form_data['name'];
        $phone = $form_data['phone'];
        $role = $form_data['role'];

        if (empty($name) || empty($phone) || empty($role)){
            return [
                'status' => 400,
                'message' => 'All input files are required.'
            ];
        }elseif (!$this->validateName($name)){
            return [
                'status' => 400,
                'message' => 'Name allows characters, hyphens, and spaces.'
            ];
        }elseif (!$this->validatePhoneNumber($phone)){
            return [
                'status' => 400,
                'message' => 'Phone number allows 10 to 13 digits.'
            ];
        }else{
            $condition = [
                'user_id' => $user_id
            ];
            $data = [
                'name' => $name,
                'contact' => $phone,
                'role' => $role
            ];
            $result = $this->updateData($this->tableName, $data, $condition);
            if (!$result){
                return [
                    'status' => 400,
                    'message' => 'Error update user.'
                ];
            }

            return [
                'status' => 200,
                'message' => 'User updated successfully.'
            ];
        }


    }


    public function putUserProfile($form_data): array
    {

        $user_id = $form_data['user_id'];
        $name = $form_data['name'];
        $phone = $form_data['phone'];

        if (empty($name) || empty($phone)){
            return [
                'status' => 400,
                'message' => 'All input files are required.'
            ];
        }elseif (!$this->validateName($name)){
            return [
                'status' => 400,
                'message' => 'Name allows characters, hyphens, and spaces.'
            ];
        }elseif (!$this->validatePhoneNumber($phone)){
            return [
                'status' => 400,
                'message' => 'Phone number allows 10 to 13 digits.'
            ];
        }else{
            $condition = [
                'user_id' => $user_id
            ];
            $data = [
                'name' => $name,
                'contact' => $phone,
            ];
            $result = $this->updateData($this->tableName, $data, $condition);
            if (!$result){
                return [
                    'status' => 400,
                    'message' => 'Error update user.'
                ];
            }

            return [
                'status' => 200,
                'message' => 'User updated successfully.'
            ];
        }


    }

    public function deleteUser($form_data): array
    {
        $user_id = $form_data['deleteUserId'];

        $condition = [
            'user_id' => $user_id
        ];
        $result = $this->deleteData($this->tableName, $condition);

        if (!$result){
            return [
                'status' => 400,
                'message' => 'Error deleting user.'
            ];
        }

        return [
            'status' => 200,
            'message' => 'User deleted successfully.'
        ];
    }
}
