<?php
include "includes/header.php";
if (!isset($_SESSION['codeSent'])){
    header('Location: index');
    exit();
}
?>




<div class="container-fluid container-md">
    <div class="row mt-5 justify-content-center">
        <div class="col-md-5 d-none d-md-block">
            <img src="assets/images/image.jpg" class="img-fluid h-100" alt="image">
        </div>
        <div class="col-10 col-md-5 py-3 bg-white">
            <h6 class="text-danger fw-bold pt-5">OTP Verification</h6>
            <p>You have been sent an OPT code kindly verify</p>
            <form action="" id="verifyOTPForm">
                <label>Code</label>
                <input type="text" name="code" class="form-control shadow-none rounded-0 mb-3" placeholder="Enter OTP Code">


                <div class="text-end">
                    <button id="verifyOTPBtn" class="btn btn-danger rounded-0 shadow-none px-4">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php
include "includes/footer.php";
?>
<script>
    $(document).on('submit', '#verifyOTPForm', function (e) {
        e.preventDefault();

        let button = $("#verifyOTPBtn");
        button.prop("disabled", true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> <span class="fw-light">Loading...</span>');
        let formData = new FormData(this);
        formData.append("verifyOTPBtn", true);

        $.ajax({
            type: "POST",
            url: "private/api/auth.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                //alert(response);
                let res = jQuery.parseJSON(response);

                if (res.status === 400) {
                    button.prop("disabled", false).html('Submit');
                    Swal.fire({
                        icon: "error",
                        text: res.message,
                    });
                } else if (res.status === 200) {

                    Swal.fire({
                        icon: "success",
                        text: res.message,
                    }).then((result) => {
                        if (result.isConfirmed) {

                            button.prop("disabled", false).html('Submit');
                            $('#verifyOTPForm')[0].reset();
                            window.location.href = 'reset_password';
                        }
                    });

                }
            }
        });
    });
</script>