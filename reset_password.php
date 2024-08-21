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
                <h6 class="text-danger fw-bold">Password Reset</h6>
                <p>Reset your password and login successfully</p>
                <form action="" id="passwordRestForm">
                    <label>Password</label>
                    <input name="password1" type="password" class="form-control shadow-none rounded-0 mb-3" placeholder="Enter password" id="passwordInput">

                    <label>Confirm Password</label>
                    <input name="password2" type="password" class="form-control shadow-none rounded-0 mb-3" placeholder="Confirm password" id="confirmPasswordInput">



                    <div class="d-flex align-content-center justify-content-between">
                        <div>
                            <input class="form-check-input shadow-none" type="checkbox" value="" id="showPasswordCheckbox">
                            <label class="form-check-label" for="flexCheckDefault">
                                Show password
                            </label>
                        </div>

                        <button id="passwordRestBtn" class="btn btn-danger rounded-0 shadow-none px-4">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<?php
include "includes/footer.php";
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const passwordInput = document.getElementById('passwordInput');
        const confirmPasswordInput = document.getElementById('confirmPasswordInput');
        const showPasswordCheckbox = document.getElementById('showPasswordCheckbox');

        showPasswordCheckbox.addEventListener('change', function() {
            passwordInput.type = this.checked ? 'text' : 'password';
            confirmPasswordInput.type = this.checked ? 'text' : 'password';
        });

    });


    $(document).on('submit', '#passwordRestForm', function (e) {
        e.preventDefault();

        let button = $("#passwordRestBtn");
        button.prop("disabled", true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> <span class="fw-light">Loading...</span>');
        let formData = new FormData(this);
        formData.append("passwordRestBtn", true);

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
                            $('#passwordRestForm')[0].reset();
                            window.location.href = 'index';
                        }
                    });

                }
            }
        });
    });
</script>
