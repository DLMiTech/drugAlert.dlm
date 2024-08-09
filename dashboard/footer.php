<script src="../assets/js/jquery-3.7.1.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
<script src="../assets/js/sweetalert.js"></script>
</body>
</html>

<script>
    $(document).on('click', '#logoutBtn', function (){
        // let user_id = $(this).val();

        Swal.fire({
            title: "Confirm logout",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Logout!"
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    type: "GET",
                    url: "../private/api/auth.php?logoutBtn",
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
                            }).then((result) => {
                                if (result.isConfirmed) {

                                    window.location.href = '../index';
                                }
                            });

                        }
                    }
                });

            }
        });


    });
</script>


<script>
    let requestSent = false;

    function sendPostRequest() {
        // Define the form data to be sent
        let formData = new FormData();
        formData.append("sendAlert", true);


        $.ajax({
            type: "POST",
            url: "../private/api/alert.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                let res = jQuery.parseJSON(response);

                if (res.status === 400) {
                    Swal.fire({
                        icon: "error",
                        text: res.message,
                    });
                } else if (res.status === 200) {

                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error: ', status, error);
            }
        });

        requestSent = true; // Set the flag to true after sending the request
    }

    setInterval(() => {
        let now = new Date();
        let hour = now.getHours();
        let minutes = now.getMinutes();

        if ((hour === 10 || hour === 12 || hour === 13) && minutes === 31 && !requestSent) {
            sendPostRequest();
        } else if (minutes !== 31) {
            requestSent = false;
        }
    }, 1000);
</script>