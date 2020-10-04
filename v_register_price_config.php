<!--
* v_register_price_config
* @Display Show user config
* @input    $scr_sug_id,$scr_age_min,$scr_age_max,$scr_cost
* @output   sug_id,age_min,age_max,total
* @author   Kanyarat Rodtong
* @create Date  2562-09-22
* @update   Weerapong Sooksangacharoen
* @update Date  2562-09-12
-->
<script src="//cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
<style>
    input[type=text]:disabled {
        background: #dddddd;
    }

    td {
        margin-top: 100px;
    }

    .switch {
        position: relative;
        display: inline-block;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 30px;
        left: 4px;
        bottom: 0px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: green;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px green;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 30px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .nav-item {
        cursor: pointer;
    }

    .btn-warning,
    .btn-danger {
        padding: 5px 10px;
        text-align: center;
    }

    @media only screen and (max-width: 768px) {

        /* For mobile phones: */
        [class*="col-"] {
            width: 100%;
        }

        th:nth-child(5),
        td:nth-of-type(5) {
            display: none;
        }
    }
</style>

<script>
    var row_number;
    $(document).ready(function() {
        $('#datatables').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            // responsive: true,
            'rowsGroup': [0, 3, 4, 5],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "ค้นหา",
            }
        });

        table();

        $("#max_age_youth").keyup(function() {
            if ($("#max_age_youth").val() == "") {
                $("#min_age_adult").val('')
            } else {
                $("#min_age_adult").val(Number($("#max_age_youth").val()) + Number(1))
            }
        });

        $("#max_age_youth").on('change', function() {
            if ($("#max_age_youth").val() == "") {
                $("#min_age_adult").val('')
            } else {
                $("#min_age_adult").val(Number($("#max_age_youth").val()) + Number(1))
            }
        });

        $("#min_age_adult").keyup(function() {
            if ($("#min_age_adult").val() == "") {
                $("#max_age_youth").val('')
            } else {
                $("#max_age_youth").val(Number($("#min_age_adult").val()) - Number(1))
            }
        });

        $("#min_age_adult").on('change', function() {
            if ($("#min_age_adult").val() == "") {
                $("#max_age_youth").val('')
            } else {
                $("#max_age_youth").val(Number($("#min_age_adult").val()) - Number(1))
            }
        });

        $('.clear').click(function() {
            $("#min_age_youth").val(0);
            $("#max_age_youth").val("");
            $("#cost_youth").val("");

            $("#min_age_adult").val("");
            $("#max_age_adult").val(99);
            $("#cost_adult").val("");
        });

        $('.submit').click(function() {
            if (validation()) {
                Swal.fire({
                    title: 'ต้องการบันทึกค่าสมัครสมาชิกหรือไม่?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4caf50',
                    cancelButtonColor: '#f44336',
                    confirmButtonText: 'ตกลง',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.value) {
                        Swal.fire({
                            title: 'เพิ่มเกณฑ์ค่าสมัครสมาชิกเสร็จสิ้น',
                            type: 'success',
                            confirmButtonColor: '#999999',
                            confirmButtonText: 'ปิด'
                        }).then((result) => {
                            register_price_config_insert();
                        });
                    }
                });
            }
        });
    });

    function table() {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() . "index.php/swm/backend/Swm_register_price_config/get_table_data_ajax"; ?>",
            dataType: "json",
            success: function(data) {
                let table = $('#datatables').DataTable();

                if (data.length != 0) {
                    row_number = 1;
                    $.each(data, function(index) {
                        $.each(data[index], function(index) {
                            table.row.add([
                                `<div class="text-center">${row_number}</div>`,
                                `   อายุ ${this.scr_age_min}
                                    ถึง ${this.scr_age_max} ปี`,
                                `<div class="text-right">${this.scr_cost}</div>`,
                                `<div class="togglebutton d-flex justify-content-center">
                                    <label class="switch">
                                        <input class="scr_switch slider" onclick="active_switch(event, ${this.scr_reference})" type="checkbox" ${(this.scr_is_active == 'Y') ? 'checked' : ''} value="${this.scr_reference}">
                                        <span class="toggle"></span>
                                    </label>
                                </div>`,
                                `<div class="text-center">${this.update_date}</div>`,
                                `<div class="text-center">
                                    ${(this.scr_is_active != 'Y') ?
                                        `<button type="button" rel="tooltip" onclick="edit_config(${this.scr_reference})" id="edt_${this.scr_reference}" class="btn btn-warning" data-placement="top" data-original-title="คลิกเพื่อแก้ไขข้อมูล">
                                            <i class="material-icons">edit</i>
                                        </button>
                                        <button type="button" rel="tooltip" onclick="delete_config(${this.scr_reference})" id="del_${this.scr_reference}" class="btn btn-danger" data-placement="top" data-original-title="คลิกเพื่อลบข้อมูล">
                                            <i class="material-icons">close</i>
                                        </button>`
                                    :
                                        `<button disabled type="button" rel="tooltip" onclick="edit_config(${this.scr_reference})" id="edt_${this.scr_reference}" class="btn btn-warning" data-placement="top" data-original-title="คลิกเพื่อแก้ไขข้อมูล">
                                            <i class="material-icons">edit</i>
                                        </button>
                                        <button disabled type="button" rel="tooltip" onclick="delete_config(${this.scr_reference})" id="del_${this.scr_reference}" class="btn btn-danger" data-placement="top" data-original-title="คลิกเพื่อลบข้อมูล">
                                            <i class="material-icons">close</i>
                                        </button>`}
                                </div>`
                            ]).draw();
                        });
                        row_number++
                    });
                }
            }
        });
    }
    //change status of register price
    function active_switch(e, scr_reference) {
        let current_state = $(e.target).prop('checked')
        if (!current_state) {
            $(e.target).prop('checked', true);
            swal({
                title: 'ไม่สามารถปิดได้',
                type: 'warning',
                confirmButtonColor: '#999999',
                confirmButtonText: 'ปิด'
            });
        } else {
            Swal.fire({
                title: 'เปิดการใช้งานค่าสมัครสมาชิกหรือไม่?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4caf50',
                cancelButtonColor: '#f44336',
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        title: 'เปิดการใช้งานค่าสมัครสมาชิกเสร็จสิ้น',
                        type: 'success',
                        confirmButtonColor: '#999999',
                        confirmButtonText: 'ปิด'
                    }).then((result) => {
                        $('.scr_switch').prop('checked', false);
                        $(e.target).prop('checked', true);

                        $('button').prop('disabled', false);
                        $(`#del_${scr_reference}`).prop('disabled', true);
                        $(`#edt_${scr_reference}`).prop('disabled', true);

                        register_price_config_change_active(scr_reference);
                    });
                }else if (result.dismiss === Swal.DismissReason.cancel) {
                    $(e.target).prop('checked', false);
                }
            });
        }
    }
    //delete register price 
    function delete_config(scr_reference) {
        Swal.fire({
            title: 'ต้องการลบค่าสมัครสมาชิกหรือไม่?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4caf50',
            cancelButtonColor: '#f44336',
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.value) {
                Swal.fire({
                    title: 'ลบเกณฑ์ค่าสมัครสมาชิกเสร็จสิ้น',
                    type: 'success',
                    confirmButtonColor: '#999999',
                    confirmButtonText: 'ปิด'
                }).then((result) => {
                    register_price_config_delete(scr_reference);
                });
            }
        });
    }
    //edit register price
    function edit_config(scr_reference) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url("index.php/swm/backend/Swm_register_price_config/get_register_cost_data_ajax"); ?>",
            data: {
                scr_reference: scr_reference
            },
            dataType: "json",
            success: function(response) {
                let youth_data = response[0];
                let adult_data = response[1];

                Swal.fire({
                    title: 'แก้ไขค่าใช้บริการ',
                    showCancelButton: true,
                    confirmButtonColor: '#4caf50',
                    cancelButtonColor: '#f44336',
                    confirmButtonText: 'ตกลง',
                    cancelButtonText: 'ยกเลิก',
                    html: ` <div class="container">
                                <div class="row">
                                    <label class="col-md-3 col-form-label">ช่วงอายุ</label>
                                    <input class="form-control" min=0 type="number" id="youth_min_age_edit" value="${youth_data.scr_age_min}" onkeypress="prevent_mathematics_sign(event)"> ปี
                                </div> 

                                <div class="row">
                                    <label class="col-md-3 col-form-label">ถึง</label>
                                    <input class="form-control" min=0 type="number" id="youth_max_age_edit" value="${youth_data.scr_age_max}" onkeypress="prevent_mathematics_sign(event)"> ปี
                                </div>

                                <div class="row">    
                                    <label class="col-md-3 col-form-label">ราคา</label>
                                    <input class="form-control" min="0" type="number" id="youth_cost_edit" value="${youth_data.scr_cost}">
                                    <label class="col-form-label">บาท</label>
                                </div>
                            </div>
                            <br> 
                            <div class="container">
                                <div class="row">
                                    <label class="col-md-3 col-form-label">ช่วงอายุ</label>
                                    <input class="form-control" min=0 type="number" id="adult_min_age_edit" value="${adult_data.scr_age_min}" onkeypress="prevent_mathematics_sign(event)"> ปี
                                </div>

                                <div class="row">
                                        <label class="col-md-3 col-form-label">ถึง</label>
                                        <input class="form-control" min=0 type="number" id="adult_max_age_edit" value="${adult_data.scr_age_max}" onkeypress="prevent_mathematics_sign(event)"> ปี
                                    </div>

                                <div class="row">    
                                        <label class="col-md-3 col-form-label">ราคา</label>
                                        <input class="form-control" min="0" type="number" id="adult_cost_edit" value="${adult_data.scr_cost}">
                                        <label class="col-form-label">บาท</label>
                                </div>
                            </div>
                            <input type="hidden" id="reference" value="${scr_reference}">`,
                    focusConfirm: false,
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo base_url("index.php/swm/backend/Swm_register_price_config/register_price_config_update_ajax"); ?>",
                            data: {
                                reference: $('#reference').val(),

                                min_age_youth: $('#youth_min_age_edit').val(),
                                max_age_youth: $('#youth_max_age_edit').val(),
                                cost_youth: $('#youth_cost_edit').val(),

                                min_age_adult: $('#adult_min_age_edit').val(),
                                max_age_adult: $('#adult_max_age_edit').val(),
                                cost_adult: $('#adult_cost_edit').val()
                            },
                            dataType: "json",
                            success: function(response) {
                                $('#datatables').DataTable().clear().draw();

                                table();
                            }
                        });
                    }
                });
            }
        });
    }

    function prevent_mathematics_sign(event) {
        let character = String.fromCharCode(event.which);

        if (!(/['0-9','.']/.test(character))) {
            event.preventDefault();
        }
    }
    //change status of register price
    function register_price_config_change_active(scr_reference) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url("index.php/swm/backend/Swm_register_price_config/register_price_config_change_active_ajax"); ?>",
            dataType: "json",
            success: function(result) {
                register_price_config_change(scr_reference)
            }
        });
    }
    //change register price
    function register_price_config_change(scr_reference) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url("index.php/swm/backend/Swm_register_price_config/register_price_config_change_ajax"); ?>",
            data: {
                scr_reference: scr_reference
            },
            dataType: "json",
            success: function(result) {}
        });
    }
    //insert register price
    function register_price_config_insert() {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url("index.php/swm/backend/Swm_register_price_config/register_price_config_insert_ajax"); ?>",
            data: {
                min_age_youth: $("#min_age_youth").val(),
                max_age_youth: $("#max_age_youth").val(),
                cost_youth: $("#cost_youth").val(),

                min_age_adult: $("#min_age_adult").val(),
                max_age_adult: $("#max_age_adult").val(),
                cost_adult: $("#cost_adult").val()
            },
            dataType: "json",
            success: function(response) {
                $('#datatables').DataTable().clear().draw();

                table();

            }
        });
    }
    //delete register price
    function register_price_config_delete(scr_reference) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url("index.php/swm/backend/Swm_register_price_config/register_price_config_delete_ajax"); ?>",
            data: {
                scr_reference: scr_reference
            },
            dataType: "json",
            success: function(response) {
                $('#datatables').DataTable().clear().draw();

                table();
            }
        });
    }
    //Check that the information is filled out or not.
    function validation() {
        if ($("#min_age_youth").val() == "" ||
            $("#max_age_youth").val() == "" ||
            $("#cost_youth").val() == "" ||

            $("#min_age_adult").val() == "" ||
            $("#max_age_adult").val() == "" ||
            $("#cost_adult").val() == "") {
            swal({
                title: 'ไม่สามารถปิดได้',
                type: 'warning',
                confirmButtonColor: '#999999',
                confirmButtonText: 'ปิด'
            });
            return false;
        } else {
            return true;
        }
    }
</script>

<div class="col-md-12">
    <div class="card">
        <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">
                <i class="material-icons">contacts</i>
            </div>
            <h4 class="card-title">กำหนดเกณฑ์ค่าสมัครสมาชิก</h4>
        </div>
        <div class="card-body">
            <div class="row d-flex justify-content-center">
                <label class="col-form-label">ช่วงอายุ ตั้งแต่</label>
                <input min=0 style="text-align:center;" readonly type="number" id="min_age_youth" name="min_age_youth" class="form-control" onkeypress="prevent_mathematics_sign(event)" value="0">
                <label class="col-form-label">ปี ถึง</label>
                <input min=0 style="text-align:center;" type="number" id="max_age_youth" name="max_age_youth" class="form-control" onkeypress="prevent_mathematics_sign(event)">
                <label class="col-form-label">ปี ราคา</label>
                <input min="0" style="text-align:center;" type="number" id="cost_youth" name="cost_youth" class="form-control" onkeypress="prevent_mathematics_sign(event)">
                <label class="col-form-label">บาท</label>
            </div>
            <div class="row d-flex justify-content-center">
                <label class="col-form-label">ช่วงอายุ ตั้งแต่</label>
                <input min=1 style="text-align:center;" type="number" id="min_age_adult" name="min_age_adult" class="form-control" onkeypress="prevent_mathematics_sign(event)">
                <label class="col-form-label">ปี ถึง</label>
                <input min=0 style="text-align:center;" readonly type="number" id="max_age_adult" name="max_age_adult" class="form-control" onkeypress="prevent_mathematics_sign(event)" value="99">
                <label class="col-form-label">ปี ราคา</label>
                <input min="0" style="text-align:center;" type="number" id="cost_adult" name="cost_adult" class="form-control" onkeypress="prevent_mathematics_sign(event)">
                <label class="col-form-label">บาท</label>
            </div>
            <div class="card-footer">
                <div class="mr-auto">
                    <button class="btn btn-inverse clear">เคลียร์</button>
                </div>
                <div class="ml-auto">
                    <button class="btn btn-success submit" rel="tooltip" data-placement="top" title='คลิกเพื่อบันทึกข้อมูล'>บันทึก</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-header card-header-info card-header-icon">
            <div class="card-icon">
                <i class="material-icons">assignment</i>
            </div>
            <h4 class="card-title">ตารางเกณฑ์ค่าสมัครสมาชิก</h4>
        </div>
        <div class="card-body">
            <div class="material-datatables table-responsive text-nowrap">
                <table id="datatables" class="table table-striped table-hover table-color-header table-border" style="width: 100%;">
                    <thead class="text-primary">
                        <tr>
                            <th class="text-center">ลำดับ</th>
                            <th>เกณฑ์อายุ</th>
                            <th>ค่าใช้บริการ (บาท)</th>
                            <th class="disabled-sorting text-center">สถานะการใช้งาน</th>
                            <th>วันที่แก้ไขข้อมูล</th>
                            <th class="disabled-sorting">ตัวดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-start col-md-6">
                <a href="<?php echo base_url() . 'index.php/swm/backend/Swm_main'; ?>" class="btn btn-inverse">
                    ย้อนกลับ
                </a>
            </div>
        </div>
    </div>
</div>