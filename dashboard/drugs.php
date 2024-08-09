<?php
include "header.php";
$drug = new Drug();
$drugs = $drug->getAllDrug();
?>


<section>
    <div class="">
        <h6>Add And Manage Drugs</h6>
    </div>
    <hr class="text-danger border-2">


    <div class="row">
        <div class="col-md-6">
            <form action="" id="addDrugForm">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label>Drug name:</label>
                            <input type="text" name="name" placeholder="Enter drug name" class="form-control shadow-none">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label>Category:</label>
                            <select name="category" id="role" class="form-select shadow-none">
                                <option value="">Select category</option>
                                <option value="Capsule">Capsule</option>
                                <option value="Syrup">Syrup</option>
                                <option value="Tablet">Tablet</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <textarea name="prescription" id="" cols="30" rows="3" class="form-control shadow-none" placeholder="Drug prescription ......"></textarea>
                    </div>

                    <div class="text-end">
                        <button id="addDrugBtn" class="btn btn-danger">ADD DRUG</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-6">
            <div class="card p-2">
                <table class="table table-striped table-sm" id="addDrugTable">
                    <tbody>
                    <?php
                    if (count($drugs) > 0){
                        foreach ($drugs as $drug){
                            ?>
                            <tr>
                                <td>
                                    <p class="m-0"><?= $drug['name']?> <small class="text-primary"><?= $drug['category']?></small></p>
                                    <small><?= $drug['prescription']?></small>
                                    <div class="">
                                        <button value="<?= $drug['drug_id']?>" type="button" class="btn btn-sm btn-success editBtn"><i class="bi bi-pencil-square"></i></button>
                                        <button value="<?= $drug['drug_id']?>" type="button" class="btn btn-sm btn-danger deleteBtn"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    }else{
                        ?><p class="text-center text-danger">No Drug Added Yet</p><?php
                    }
                    ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>


<!-- Modal -->
<div class="modal fade" id="updateDrugModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Update Drug</h1>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="updateDrugForm">
                    <div class="row">
                        <input type="hidden" id="editId" name="drug_id" placeholder="Enter user full name" class="form-control shadow-none">
                        <div class="col-12">
                            <div class="mb-3">
                                <label>Drug name:</label>
                                <input type="text" id="editName" name="name" placeholder="Enter drug name" class="form-control shadow-none">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label>Category:</label>
                                <select name="category" id="editCategory" class="form-select shadow-none">
                                    <option value="">Select category</option>
                                    <option value="Capsule">Capsule</option>
                                    <option value="Syrup">Syrup</option>
                                    <option value="Tablet">Tablet</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <textarea name="prescription" id="editPrescription" cols="30" rows="3" class="form-control shadow-none" placeholder="Drug prescription ......"></textarea>
                        </div>

                        <div class="text-end">
                            <button id="updateDrugBtn" class="btn btn-danger">UPDATE DRUG</button>
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
    $(document).on('submit', '#addDrugForm', function (e) {
        e.preventDefault();

        let button = $("#addDrugBtn");
        button.prop("disabled", true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> <span class="fw-light">Loading...</span>');
        let formData = new FormData(this);
        formData.append("addDrugBtn", true);

        $.ajax({
            type: "POST",
            url: "../private/api/drug.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                //alert(response);
                let res = jQuery.parseJSON(response);

                if (res.status === 400) {
                    button.prop("disabled", false).html('ADD DRUG');
                    Swal.fire({
                        icon: "error",
                        text: res.message,
                    });
                } else if (res.status === 200) {
                    button.prop("disabled", false).html('ADD DRUG');
                    Swal.fire({
                        icon: "success",
                        text: res.message,
                    });

                    $('#addDrugForm')[0].reset();
                    $('#addDrugTable').load(location.href + " #addDrugTable");
                }
            }
        });
    });

    $(document).on('click', '.editBtn', function (){
        let drug_id = $(this).val();

        $.ajax({
            type: "GET",
            url: "../private/api/drug.php?getDrugById=" + drug_id,

            success: function (response){
                let res = jQuery.parseJSON(response);

                if(res.status === 400){
                    Swal.fire({
                        icon: "error",
                        text: res.message,
                    });
                }else if(res.status === 200){
                    $('#editId').val(res.data.drug_id);
                    $('#editName').val(res.data.name);
                    $('#editCategory').val(res.data.category);
                    $('#editPrescription').val(res.data.prescription);

                    $('#updateDrugModal').modal('show');
                }
            }
        });
    });


    $(document).on('submit', '#updateDrugForm', function (e) {
        e.preventDefault();

        let button = $("#updateDrugBtn");
        button.prop("disabled", true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> <span class="fw-light">Loading...</span>');
        let formData = new FormData(this);
        formData.append("updateDrugBtn", true);

        $.ajax({
            type: "POST",
            url: "../private/api/drug.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                //alert(response);
                let res = jQuery.parseJSON(response);

                if (res.status === 400) {
                    button.prop("disabled", false).html('UPDATE DRUG');
                    Swal.fire({
                        icon: "error",
                        text: res.message,
                    });
                } else if (res.status === 200) {
                    button.prop("disabled", false).html('UPDATE DRUG');
                    Swal.fire({
                        icon: "success",
                        text: res.message,
                    });

                    $('#updateDrugForm')[0].reset();
                    $('#updateDrugModal').modal('hide');
                    $('#addDrugTable').load(location.href + " #addDrugTable");
                }
            }
        });
    });


    $(document).on('click', '.deleteBtn', function (){
        let drug_id = $(this).val();

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
                    url: "../private/api/drug.php?deleteDrugId=" + drug_id,
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
                            $('#addDrugTable').load(location.href + " #addDrugTable");
                        }
                    }
                });

            }
        });


    });
</script>


