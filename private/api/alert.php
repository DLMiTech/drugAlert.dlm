<?php
include "../config/init.php";
$alert = new Alert();

if ($_SERVER['REQUEST_METHOD'] && isset($_POST['sendAlert'])){
    $form_data = $alert->filterInputData($_POST);

    $result = $alert->sendAlert($form_data);
}else{
    $result = "";
}



//sleep(1);
echo json_encode($result);
return;

