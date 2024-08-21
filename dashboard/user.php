<?php
include "header.php";
?>


<section>
    <div class="">
        <h6>Add And Manage Users</h6>
    </div>
    <hr class="text-danger border-2">

    <div class="card p-3 shadow bg-light mb-3">
        <form action="" id="addUserForm">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label>Full name:</label>
                        <input type="text" name="name" placeholder="Enter user full name" class="form-control shadow-none">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label>Username:</label>
                        <input type="text" name="username" placeholder="Enter username" class="form-control shadow-none">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label>Phone number:</label>
                        <input type="text" name="phone" placeholder="Enter phone number" class="form-control shadow-none">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label>User role:</label>
                        <select name="role" id="role" class="form-select shadow-none">
                            <option value="">Select user role</option>
                            <option value="3">Admin</option>
                            <option value="2">Manager</option>
                            <option value="1">Seller</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label>Password:</label>
                        <input type="password" name="password" placeholder="Enter password" class="form-control shadow-none">
                    </div>
                </div>

                <div class="text-end">
                    <button id="addUserBtn" class="btn btn-danger">ADD USER</button>
                </div>
            </div>
        </form>
    </div>

    <hr class="text-danger border-2">

    <div class="table-responsive">
        <table class="table table-sm table-striped align-middle" id="addUserTable">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Username</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody>
            <?php
            $user = new Users();
            $users = $user->getAllUser();

            if ($users !== false){
                if (count($users) > 0){
                    $no = 0;
                    foreach ($users as $row){
                        $no++;
                        ?>
                        <tr>
                            <td><?= $no;?></td>
                            <td><?= $row['name'];?></td>
                            <td><?= $row['username'];?></td>
                            <td><?= $row['contact'];?></td>
                            <td>
                                <?php
                                if ($row['role'] === 2){
                                    ?><span class="badge text-bg-success">Manager</span><?php
                                }elseif ($row['role'] === 3){
                                    ?><span class="badge text-bg-primary">Admin</span><?php
                                }elseif ($row['role'] === 1){
                                    ?><span class="badge text-bg-info">seller</span><?php
                                }
                                ?>
                            </td>
                            <td>
                                <button value="<?= $row['user_id']?>" type="button" class="btn btn-sm btn-success editBtn"><i class="bi bi-pencil-square"></i></button>
                                <button value="<?= $row['user_id']?>" type="button" class="btn btn-sm btn-danger deleteBtn"><i class="bi bi-trash-fill"></i></button>
                            </td>
                        </tr>
                        <?php
                    }
                }else{
                    ?>
                    <p class="text-center text-danger">No User Added Yet</p>
                    <?php
                }
            }
            ?>

            </tbody>
        </table>
    </div>
</section>


<!-- Modal -->
<div class="modal fade" id="updateUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Update User</h1>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="updateUserForm">
                    <div class="row">
                        <input type="hidden" id="editId" name="user_id" placeholder="Enter user full name" class="form-control shadow-none">
                        <div class="col-12">
                            <div class="mb-3">
                                <label>Full name:</label>
                                <input type="text" id="editName" name="name" placeholder="Enter user full name" class="form-control shadow-none">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Phone number:</label>
                                <input type="text" id="editPhone" name="phone" placeholder="Enter phone number" class="form-control shadow-none">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>User role:</label>
                                <select name="role" id="editRole" class="form-select shadow-none">
                                    <option value="">Select user role</option>
                                    <option value="3">Admin</option>
                                    <option value="2">Manager</option>
                                    <option value="1">Seller</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-end">
                            <button id="updateUserBtn" class="btn btn-danger">UPDATE USER</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<?php
include "footer.php";
?>

<script>
    $(document).on('submit', '#addUserForm', function (e) {
        e.preventDefault();

        let button = $("#addUserBtn");
        button.prop("disabled", true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> <span class="fw-light">Loading...</span>');
        let formData = new FormData(this);
        formData.append("addUserBtn", true);

        $.ajax({
            type: "POST",
            url: "../private/api/users.php",
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
                    button.prop("disabled", false).html('ADD USER');
                    Swal.fire({
                        icon: "success",
                        text: res.message,
                    });

                    $('#addUserForm')[0].reset();
                    $('#addUserTable').load(location.href + " #addUserTable");
                }
            }
        });
    });

    $(document).on('click', '.deleteBtn', function (){
        let user_id = $(this).val();

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirms deletion, execute the AJAX request
                $.ajax({
                    type: "GET",
                    url: "../private/api/users.php?deleteUserId=" + user_id,
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
                            $('#addUserTable').load(location.href + " #addUserTable");
                        }
                    }
                });

            }
        });


    });

    $(document).on('click', '.editBtn', function (){
        let user_id = $(this).val();

        $.ajax({
            type: "GET",
            url: "../private/api/users.php?getUserById=" + user_id,

            success: function (response){
                let res = jQuery.parseJSON(response);

                if(res.status === 400){
                    Swal.fire({
                        icon: "error",
                        text: res.message,
                    });
                }else if(res.status === 200){
                    $('#editId').val(res.data.user_id);
                    $('#editName').val(res.data.name);
                    $('#editRole').val(res.data.role);
                    $('#editPhone').val(res.data.contact);
                    // $('#editPassword').val(res.data.password);
                    $('#updateUserModal').modal('show');
                }
            }
        });
    });

    $(document).on('submit', '#updateUserForm', function (e) {
        e.preventDefault();

        let button = $("#updateUserBtn");
        button.prop("disabled", true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> <span class="fw-light">Loading...</span>');
        let formData = new FormData(this);
        formData.append("updateUserBtn", true);

        $.ajax({
            type: "POST",
            url: "../private/api/users.php",
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
                    button.prop("disabled", false).html('ADD USER');
                    Swal.fire({
                        icon: "success",
                        text: res.message,
                    });

                    $('#updateUserForm')[0].reset();
                    $('#updateUserModal').modal('hide');
                    $('#addUserTable').load(location.href + " #addUserTable");
                }
            }
        });
    });
</script>
