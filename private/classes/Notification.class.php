<?php

    trait Notification{

        protected function send_sms($phone_number, $message): bool|string
        {
            // Arkesel API endpoint
            $api_endpoint = "https://sms.arkesel.com/sms/api";

            // Arkesel API key
            $api_key = "ZUtiYXFzUGZ5b1NzTnBIdkt5WlA";

            // Construct the API URL
            $url = $api_endpoint . "?action=send-sms&api_key=" . $api_key . "&to=" . urlencode($phone_number) . "&from=" . urlencode("D-ALERT") . "&sms=" . urlencode($message);

            // Initialize cURL session
            $ch = curl_init($url);

            // Set options for cURL session
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute cURL session
            $response = curl_exec($ch);

            // Close cURL session
            curl_close($ch);

            // Return the API response
            return $response;

        }


        protected function getBalance(): bool|string
        {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://sms.arkesel.com/api/v2/clients/balance-details',
                CURLOPT_HTTPHEADER => ['api-key: ZUtiYXFzUGZ5b1NzTnBIdkt5WlA'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ]);

            $response = curl_exec($curl);

            curl_close($curl);

            return $response;
        }


        protected function sendSMS($to, $message): bool
        {
            // API Endpoint
            $url = "https://sms.smsnotifygh.com/smsapi";

            // API Request Data
            $data = [
                'key' => "d5efbd45-cdea-4e39-9d25-04990ac77396",
                'to' => $to,
                'msg' => $message,
                'sender_id' => "DeLuke&Co.",
            ];

            // Initialize cURL session
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute cURL session and get the response
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                return false;
            }

            // Close cURL session
            curl_close($ch);

            // You can handle the API response as needed
            //return $response;

            // Check if the API response contains an error message
            $result = json_decode($response, true);
            if ($result && isset($result['error'])) {
                // API request failed
                return false;
            } else {
                // API request successful
                return true;
            }
        }

    }
