<?php
class Alert extends Database {
    use Notification;


    public function sendAlert(mixed $form_data): array
    {
        $condition = [
            'alert_status' => 1
        ];
        $data = $this->selectData('administering', '*', $condition);

        // Get the current date
        $currentDate = new DateTime();
        $currentDateFormatted = $currentDate->format('Y-m-d');

        foreach ($data as $record) {
            $durationDate = new DateTime($record['duration_date']);
            if ($durationDate > $currentDate) {
                $to = $record['phone'];
                $message = $record['message'];
                $this->send_sms($to, $message);
            }
        }


        return [
            'status' => 200,
            'message' => 'All active patient notify successfully'
        ];
    }
}
