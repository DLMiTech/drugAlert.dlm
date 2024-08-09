<?php
include "../config/init.php";
$auth = new Authentication();

if ($_SERVER['REQUEST_METHOD'] && isset($_POST['loginBtn'])){
    $form_data = $auth->filterInputData($_POST);

    $result = $auth->loginUser($form_data);
}elseif ($_SERVER['REQUEST_METHOD'] && isset($_GET['logoutBtn'])){

    $result = $auth->logoutUser();
}elseif ($_SERVER['REQUEST_METHOD'] && isset($_POST['sendOTPBtn'])){
    $form_data = $auth->filterInputData($_POST);

    $result = $auth->sendOPTCode($form_data);
}elseif ($_SERVER['REQUEST_METHOD'] && isset($_POST['verifyOTPBtn'])){
    $form_data = $auth->filterInputData($_POST);

    $result = $auth->verifyOPTCode($form_data);
}elseif ($_SERVER['REQUEST_METHOD'] && isset($_POST['passwordRestBtn'])){
    $form_data = $auth->filterInputData($_POST);

    $result = $auth->passwordRest($form_data);
}elseif ($_SERVER['REQUEST_METHOD'] && isset($_POST['changePasswordBtn'])){
    $form_data = $auth->filterInputData($_POST);

    $result = $auth->changePassword($form_data);
}else{
    $result = "";
}



//sleep(1);
echo json_encode($result);
return;
