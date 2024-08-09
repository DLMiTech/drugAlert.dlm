<?php
include "includes/header.php";
?>


<div class="container-fluid container-md">
    <div class="row mt-5 justify-content-center">
        <div class="col-md-5 d-none d-md-block">
            <img src="assets/images/image.jpg" class="img-fluid h-100" alt="image">
        </div>
        <div class="col-10 col-md-5 py-3 bg-white">
            <h6 class="text-danger fw-bold">Welcome!</h6>
            <p>Please login into your account.</p>
            <form action="" id="loginForm">
                <label>Username</label>
                <input type="text" name="username" class="form-control shadow-none rounded-0 mb-3" placeholder="Enter username">

                <label>Password</label>
                <input type="password" name="password" class="form-control shadow-none rounded-0 mb-3" placeholder="Enter password" id="passwordInput">

                <div class="d-flex justify-content-between align-content-center mb-3">
                    <div>
                        <input class="form-check-input shadow-none" type="checkbox" value="" id="showPasswordCheckbox">
                        <label class="form-check-label" for="flexCheckDefault">
                            Show password
                        </label>
                    </div>

                    <a href="forget_password">Forget Password?</a>
                </div>

                <div class="text-end">
                    <button id="loginBtn" class="btn btn-danger rounded-0 shadow-none px-4">Sign In</button>
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
        const showPasswordCheckbox = document.getElementById('showPasswordCheckbox');

        showPasswordCheckbox.addEventListener('change', function() {
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    });

    $(document).on('submit', '#loginForm', function (e) {
        e.preventDefault();

        let button = $("#loginBtn");
        button.prop("disabled", true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> <span class="fw-light">Loading...</span>');
        let formData = new FormData(this);
        formData.append("loginBtn", true);

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
                    button.prop("disabled", false).html('ADD USER');
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

                            button.prop("disabled", false).html('ADD USER');
                            $('#loginForm')[0].reset();
                            window.location.href = 'dashboard/index';
                        }
                    });

                }
            }
        });
    });
</script>