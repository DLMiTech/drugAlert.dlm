<?php
include "../config/init.php";
$user = new Users();

if ($_SERVER['REQUEST_METHOD'] && isset($_POST['addUserBtn'])){
    $form_data = $user->filterInputData($_POST);

    $result = $user->postUser($form_data);
}elseif ($_SERVER['REQUEST_METHOD'] && isset($_GET['deleteUserId'])){
    $form_data = $user->filterInputData($_GET);

    $result = $user->deleteUser($form_data);
}elseif ($_SERVER['REQUEST_METHOD'] && isset($_GET['getUserById'])){
    $form_data = $user->filterInputData($_GET);

    $result = $user->getByIdUser($form_data);
}elseif ($_SERVER['REQUEST_METHOD'] && isset($_POST['updateUserBtn'])){
    $form_data = $user->filterInputData($_POST);

    $result = $user->putUser($form_data);
}elseif ($_SERVER['REQUEST_METHOD'] && isset($_POST['updateMyProfileBtn'])){
    $form_data = $user->filterInputData($_POST);

    $result = $user->putUserProfile($form_data);
}else{
    $result = "";
}



//sleep(1);
echo json_encode($result);
return;