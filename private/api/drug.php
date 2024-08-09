<?php
include "../config/init.php";
$drug = new Drug();

if ($_SERVER['REQUEST_METHOD'] && isset($_POST['addDrugBtn'])){
    $form_data = $drug->filterInputData($_POST);

    $result = $drug->addNewDrug($form_data);
}elseif ($_SERVER['REQUEST_METHOD'] && isset($_GET['getDrugById'])){
    $form_data = $drug->filterInputData($_GET);

    $result = $drug->getDrugById($form_data);
}elseif ($_SERVER['REQUEST_METHOD'] && isset($_POST['updateDrugBtn'])){
    $form_data = $drug->filterInputData($_POST);

    $result = $drug->putDrug($form_data);
}elseif ($_SERVER['REQUEST_METHOD'] && isset($_GET['deleteDrugId'])){
    $form_data = $drug->filterInputData($_GET);

    $result = $drug->deleteDrug($form_data);
}elseif ($_SERVER['REQUEST_METHOD'] && isset($_POST['patientDrugsBtn'])){

    $result = $drug->patientDrugs($_POST);
}
else{
    $result = "";
}



echo json_encode($result);
return;
