<?php
include "header.php";
$drug = new Drug();
$drugs = $drug->getAllDrug();
?>
<style>
.dropdown-container {
position: relative;
display: inline-block;
}



.dropdown-list {
display: none;
position: absolute;
background-color: #f9f9f9;
min-width: 100%;
box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
z-index: 1;
border: 1px solid #ccc;
border-radius: 4px;
max-height: 250px;
overflow-y: auto;
}

.dropdown-list div {
padding: 8px;
cursor: pointer;
}

.dropdown-list div:hover {
background-color: #ddd;
}
</style>

<section>
    <div class="">
        <h6>Drugs Administering</h6>
    </div>
    <hr class="text-danger border-2">

    <div class="row mb-3">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 dropdown-container">
                            <input type="text" class="dropdown-search form-select shadow-none mb-2" id="itemName" placeholder="Search drug name...">
                            <div class="dropdown-list" id="dropdownList">
                                <?php
                                if (count($drugs) > 0){
                                    foreach ($drugs as $drug){
                                        ?>
                                        <div data-value="<?= $drug['name'];?>"><?= $drug['name'];?></div>
                                        <?php
                                    }
                                }else{
                                    ?><p class="text-center text-danger">No Drug Added Yet</p><?php
                                }
                                ?>

                            </div>
                        </div>
                        <div class="col-md-6">

                        </div>
                        <div class="col-md-4">
                            <input type="number" id="itemQty" class="form-control shadow-none mb-2" placeholder="Quantity">
                        </div>
                        <div class="col-md-4">
                            <input type="number" id="itemDosage" class="form-control shadow-none mb-2" placeholder="Dosage">
                        </div>
                        <div class="col-md-4">
                            <select name="itemTimes" id="itemTimes" class="form-select shadow-none mb-2">
                                <option value="">Select number of times</option>
                                <option value="1">Once daily</option>
                                <option value="2">Twice daily</option>
                                <option value="3">Thrice daily</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <button id="btn-add-row" class="btn btn-success">Add To Cart</button>
                                <button id="btn-delete-all-rows" class="btn btn-danger">Remove All</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="patientDrugsForm">
            <hr class="my-3">

            <div class="col-12">
                <div class="table-responsive">

                    <table id="product_table" class="table table-sm">
                        <thead>
                        <tr>
                            <th width="20%">Drug name</th>
                            <th width="10%">Quantity</th>
                            <th width="10%">Dosage</th>
                            <th width="15%">No of times</th>
                            <th width="10%">Action</th>
                        </tr>
                        </thead>
                        <tbody id="product_tbody"></tbody>
                    </table>

                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header text-white" style="background: #1b263b">
                        <h6 class="fw-lighter mb-0">Patient Details</h6>
                    </div>

                    <div class="card-body p-2">
                        <div class="row">
                            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'];?>">
                            <div class="col-md-6">
                                <label class="fw-light">Patient name </label>
                                <input name="patient_name" id="patient_name" type="text" placeholder="Patient name" class="form-control shadow-none mb-3">
                            </div>

                            <div class="col-md-6">
                                <label class="fw-light">Phone number</label>
                                <input name="phone" id="phone" type="text" placeholder="Phone number" class="form-control shadow-none mb-3">
                            </div>


                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mb-2 mt-3">
                <button type="submit" id="patientDrugsBtn" class="btn btn-danger">Submit</button>
            </div>
        </form>
    </div>
</section>



<?php
include "footer.php";
?>

<script>
    document.getElementById('itemName').addEventListener('focus', function() {
        document.getElementById('dropdownList').style.display = 'block';
    });

    document.getElementById('itemName').addEventListener('blur', function() {
        setTimeout(() => {
            document.getElementById('dropdownList').style.display = 'none';
        }, 100);
    });

    document.getElementById('itemName').addEventListener('input', function() {
        const filter = this.value.toLowerCase();
        const dropdownItems = document.getElementById('dropdownList').getElementsByTagName('div');
        Array.from(dropdownItems).forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(filter)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    const dropdownItems = document.getElementById('dropdownList').getElementsByTagName('div');
    Array.from(dropdownItems).forEach(item => {
        item.addEventListener('mousedown', function() {
            document.getElementById('itemName').value = this.textContent;
            document.getElementById('dropdownList').style.display = 'none';
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#btn-add-row").click(function () {
            // Get values from input fields
            let itemName = $("#itemName").val();
            let itemQty = $("#itemQty").val();
            let itemDosage = $("#itemDosage").val();
            let itemTimes = $("#itemTimes").val();

            if (!itemName || !itemQty || !itemDosage || !itemTimes) {
                Swal.fire({
                    icon: 'error',
                    text: 'Please fill in all fields!'
                });
                return;
            }

            let row = "<tr> " +
                "<td><input type='text' value='" + itemName + "' required name='drug_name[]' class='border-0 form-control shadow-none' readonly></td> " +
                "<td><input type='text' value='" + itemQty + "' name='qty[]' class='border-0 form-control shadow-none' readonly></td> " +
                "<td><input type='text' value='" + itemDosage + "' required name='dosage[]' class='border-0 form-control shadow-none' readonly></td> " +
                "<td><input type='text' value='" + itemTimes + "' required name='times[]' class='border-0 form-control shadow-none' readonly></td> " +
                "<td><input type='button' value='x' class='btn btn-danger btn-sm btn-row-remove'> </td> " +
                "</tr>";
            $("#product_tbody").append(row);

            // Clear input values after adding them to the row
            $("#itemName").val('');
            $("#itemQty").val('');
            $("#itemDosage").val('');
            $("#itemTimes").val('');

        });

        $("body").on("click", ".btn-row-remove", function () {

            Swal.fire({
                title: "Are you sure?",
                text: "You are about to remove row from items!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, remove!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).closest("tr").remove();
                }
            });
        });

        // Add a new button click event for "Delete All Rows"
        $("#btn-delete-all-rows").click(function () {

            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to remove all rows?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, remove!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#product_tbody").empty();
                }
            });
        });
    });


    $(document).on('submit', '#patientDrugsForm', function (e){
        e.preventDefault();

        let button = $("#patientDrugsBtn");
        button.prop("disabled", true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> <span class="fw-light">Loading...</span>');

        let formData = new FormData(this);
        formData.append("patientDrugsBtn", true);

        console.log(formData);

        $.ajax({
            type: "POST",
            url: "../private/api/drug.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response){
                let res = jQuery.parseJSON(response);

                if (res.status === 400) {
                    button.prop("disabled", false).text("Submit");
                    Swal.fire({
                        icon: "error",
                        text: res.message,
                    });
                } else if (res.status === 200) {
                    button.prop("disabled", false).text("Submit");
                    Swal.fire({
                        icon: "success",
                        text: res.message,
                    });

                    $('#patientDrugsForm')[0].reset();
                    $("#product_tbody").empty(); // Remove all rows from the table body
                }

            }
        });
    });
</script>