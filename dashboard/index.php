<?php
include "header.php";
$users = new Users();
$drugs = new Drug();
$totalUser = $users->countTotalUsers();
$totalDrugs = $drugs->countTotalDrugs();
$totalAdmin = $drugs->countTotalAdmin();
?>


<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between">
        <div class="mt-2 d-none d-sm-block">
            <h4 class="mb-0">Welcome <?= $_SESSION['username'];?></h4>
        </div>
        <div class=""></div>

        <div class="date-time">
            <div class="date">
                <div class="color me-2"></div>
                <div class="name">
                    <p class="greeting m-0"></p>
                </div>
            </div>
            <div class="time">
                <p class="m-0 me-2" id="date"></p>
                <p class="m-0" id="time"></p>
            </div>
        </div>
    </div>
    <hr class="text-danger border-2">

    <div class="row">
        <div class="col mb-3">
            <div class="card rounded-0 shadow p-3 text-center">
                <p class="">Alert will be sent automatically, but you can click on this button to send alerts manually </p>
                <div class="">
                    <button class="btn btn-danger sendAlertBtn">Send Alert Manually <i class="bi bi-send"></i></button>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="card shadow rounded-0 py-4 px-2" style="border-left: 4px solid #cc372e">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <i class="bi bi-people-fill" style="font-size: 2rem; color: #cc372e"></i>
                        <p class="mb-0">Total Users</p>
                    </div>
                    <h1 class="mb-0"><?= $totalUser; ?></h1>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="card shadow rounded-0 py-4 px-2" style="border-left: 4px solid #118ed0">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <i class="bi bi-capsule" style="font-size: 2rem; color: #118ed0"></i>
                        <p class="mb-0">Total Drugs</p>
                    </div>
                    <h1 class="mb-0"><?= $totalDrugs; ?></h1>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-sm-6 mb-3">
            <div class="card shadow rounded-0 py-4 px-2" style="border-left: 4px solid #ffc500">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <i class="bi bi-chat-left-text-fill" style="font-size: 2rem; color: #ffc500"></i>
                        <p class="mb-0">Alert To Send</p>
                    </div>
                    <h1 class="mb-0"><?= $totalAdmin; ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
include "footer.php";
?>

<script>

    $(document).on('click', '.sendAlertBtn', function (){
        let formData = new FormData();
        formData.append("sendAlert", true);

        Swal.fire({
            title: "Are you sure?",
            text: "You are about to send SMS alert to all patient.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, send!"
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirms deletion, execute the AJAX request
                $.ajax({
                    type: "POST",
                    url: "../private/api/alert.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let res = jQuery.parseJSON(response);
                        if (res.status === 400) {
                            Swal.fire({
                                icon: "error",
                                text: res.message,
                            });
                        } else if (res.status === 200) {
                            Swal.fire({
                                icon: "success",
                                text: res.message,
                            });
                        }
                    }
                });

            }
        });


    });
</script>


