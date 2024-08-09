<?php
include "includes/header.php";
?>




<div class="container-fluid container-md">
    <div class="row mt-5 justify-content-center">
        <div class="col-md-5 d-none d-md-block">
            <img src="assets/images/image.jpg" class="img-fluid h-100" alt="image">
        </div>
        <div class="col-10 col-md-5 py-3 bg-white">
            <h6 class="text-danger fw-bold pt-5">Forget Password</h6>
            <p>Enter your phone number to receive an OTP code !!</p>
            <form action="" id="sendOTPForm">
                <label>Phone</label>
                <input name="phone" type="text" class="form-control shadow-none rounded-0 mb-3" placeholder="Enter phone number">

                <div class="text-end">
                    <button id="sendOTPBtn" class="btn btn-danger rounded-0 shadow-none px-4">Submit</button>
                </div>

            </form>
        </div>
    </div>
</div>


<?php
include "includes/footer.php";
?>

<script>
    $(document).on('submit', '#sendOTPForm', function (e) {
        e.preventDefault();

        let button = $("#sendOTPBtn");
        button.prop("disabled", true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> <span class="fw-light">Loading...</span>');
        let formData = new FormData(this);
        formData.append("sendOTPBtn", true);

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
                            $('#sendOTPForm')[0].reset();
                            window.location.href = 'otp_verification';
                        }
                    });

                }
            }
        });
    });
</script>
