<?php
class Drug extends Database{
    use Validate;
    private string $tableName = "drug_tb";
    private array $columns = ['name', 'category', 'prescription'];


    public function countTotalDrugs(){
        return $this->countRows('drug_tb');
    }

    public function countTotalAdmin(){
        $condition = [
            'alert_status' => 1
        ];
        $data = $this->selectData('administering', '*', $condition);
        $condition = [
            'duration_date' => ''
        ];
        return $this->countRows('administering');
    }

    public function addNewDrug(mixed $form_data): array
    {
        $name = $form_data['name'];
        $category = $form_data['category'];
        $prescription = $form_data['prescription'];

        if (empty($name)){
            return [
                'status' => 400,
                'message' => 'Name is required to add drug'
            ];
        }

        $condition =[
            'name' => $name,
            'category' => $category
        ];
        $result = $this->selectData($this->tableName, '*', $condition);
        if ($result){
            return [
                'status' => 400,
                'message' => 'This drug under category already added.'
            ];
        }
        $values = [$name, $category, $prescription];
        $result = $this->insertData($this->tableName, $this->columns, $values);
        if (!$result){
            return [
                'status' => 400,
                'message' => 'Error.'
            ];
        }
        return [
            'status' => 200,
            'message' => 'Drud added successfully.'
        ];
    }

    public function getAllDrug(): false|array
    {
        return $this->selectData($this->tableName);
    }

    public function getDrugById(mixed $form_data): array
    {
        $drug_id = $form_data['getDrugById'];

        $condition = [
            'drug_id' => $drug_id
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

    public function putDrug(mixed $form_data): array
    {
        $drug_id = $form_data['drug_id'];
        $name = $form_data['name'];
        $category = $form_data['category'];
        $prescription = $form_data['prescription'];

        if (empty($name)){
            return [
                'status' => 400,
                'message' => 'Name is required to add drug'
            ];
        }

        $condition = [
            'drug_id' => $drug_id
        ];
        $updateData = [
            'name' => $name,
            'category' => $category,
            'prescription' => $prescription
        ];
        $result = $this->updateData($this->tableName, $updateData, $condition);
        if (!$result){
            return [
                'status' => 400,
                'message' => 'Error update drug.'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Drug updated successfully.'
        ];
    }

    public function deleteDrug(mixed $form_data): array
    {
        $drug_id = $form_data['deleteDrugId'];

        $condition = [
            'drug_id' => $drug_id
        ];
        $result = $this->deleteData($this->tableName, $condition);

        if (!$result){
            return [
                'status' => 400,
                'message' => 'Error deleting drug.'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Drug deleted successfully.'
        ];
    }

    public function patientDrugs(mixed $form_data): array
    {

        $patient_name = $form_data['patient_name'];
        $phone = $form_data['phone'];
        $user_id = $form_data['user_id'];

        if (empty($patient_name) || empty($phone)){
            return [
                'status' => 400,
                'message' => 'Enter patient name, phone number and duration.'
            ];
        }

        if (!isset($form_data['drug_name'])){
            return [
                'status' => 400,
                'message' => 'Enter one or more drug to submit.'
            ];
        }



        $max_qty = null;
        $max_dos = null;
        $max_tim = null;


        $message = '';

        for ($i = 0; $i < count($form_data['drug_name']); $i++){
            $name = $form_data['drug_name'][$i];
            $dosage = $form_data['dosage'][$i];
            $times = $form_data['times'][$i];
            $qty = $form_data['qty'][$i];
            $message .= $name . " => " . $dosage . " dosage X " . $times . " times a day.\n";

            if ($max_qty === null || $qty > $max_qty) {
                $max_qty = $qty;
                $max_dos = $dosage;
                $max_tim = $times;
            }
        }

        $no_of_days =  (((12*$max_qty)/$max_dos)/$max_tim);

        $currentDate = new DateTime();
        $currentDate->modify("+{$no_of_days} days");
        $durationDate = $currentDate->format('Y-m-d');



        $administeringColumn = ['patient_name', 'phone', 'user_id', 'message', 'duration_date'];
        $administeringValues = [$patient_name, $phone, $user_id, $message, $durationDate];

        $administering_id = $this->returnLastId('administering', $administeringColumn, $administeringValues);



        $allValues = [];
        for ($i = 0; $i < count($form_data['drug_name']); $i++) {
            $name = $form_data['drug_name'][$i];
            $qty = $form_data['qty'][$i];
            $dosage = $form_data['dosage'][$i];
            $times = $form_data['times'][$i];
            $allValues[] = [$administering_id, $name, $qty, $dosage, $times];
        }

        $columns = ['administering_id', 'drug_name', 'qty', 'dosage', 'times'];
        $result = $this->insertMultipleData('administer_drugs', $columns, $allValues);
        if (!$result){
            return [
                'status' => 400,
                'message' => 'Error.'
            ];
        }

        return [
            'status' => 200,
            'message' => 'Administering successfully.'
        ];
    }

}
