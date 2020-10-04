<!-- 
* v_user_price_config
* @Display Show user price config
* @input    $scp_sug_id,$scp_age_min,$scp_age_max,$scp_cost
* @output   sug_id,age_min,age_max,total
* @author   Weerapong Sooksangacharoen
* @create Date  2562-05-17
* @update   Weerapong Sooksangacharoen
* @update Date  2562-09-12
-->
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
        width: 60px;
        height: 30;
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

    .slider.round {
        border-radius: 30px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .nav-item {
        cursor: pointer;
    }
</style>

<script>
    $(document).ready(function() {
        $('#tab_user_data').hide();

        $("#tab_user").click(function() {
            $("#tab_user_data").show();
            $("#tab_member_data").hide();
        });

        $("#tab_member").click(function() {
            $("#tab_member_data").show();
            $("#tab_user_data").hide();
        });

        $("input[name='max_age_youth']").keyup(function() {
            if ($("input[name='max_age_youth']").val() == "") {
                $("input[name='min_age_adult']").val('')
            } else {
                $("input[name='min_age_adult']").val(Number($("input[name='max_age_youth']").val()) + Number(1))
            }
        });

        $("input[name='max_age_youth']").on('change', function() {
            if ($("input[name='max_age_youth']").val() == "") {
                $("input[name='min_age_adult']").val('')
            } else {
                $("input[name='min_age_adult']").val(Number($("input[name='max_age_youth']").val()) + Number(1))
            }
        });

        $('.sw_member').on('change', function(e) {
            e.preventDefault();
            let current_state = $(e.target).prop('checked')
            if (!current_state) {
                $(e.target).prop('checked', true)
                swal({
                    title: 'ไม่สามารถปิดได้',
                    type: 'warning',
                    confirmButtonColor: '#999999',
                    confirmButtonText: 'ปิด'
                });
            } else {
                Swal.fire({
                    title: 'เปิดการใช้งานค่าใช้บริการหรือไม่?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4caf50',
                    cancelButtonColor: '#f44336',
                    confirmButtonText: 'ตกลง',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.value) {
                        Swal.fire({
                            title: 'เปิดการใช้งานค่าใช้บริการเสร็จสิ้น',
                            type: 'success',
                            confirmButtonColor: '#999999',
                            confirmButtonText: 'ปิด'
                        }).then((result) => {
                            $('.sw_member').prop('checked', false);
                            $(e.target).prop('checked', true);

                            var scp_reference = $(this).val();
                            var user_status = $("#user_status").val();

                            user_price_config_change_active(user_status, scp_reference)
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        $(e.target).prop('checked', false);
                    }
                });
            }
        })

        $('.sw_nonmember').on('change', function(e) {
            e.preventDefault();
            let current_state = $(e.target).prop('checked')
            if (!current_state) {
                $(e.target).prop('checked', true)
                swal({
                    title: 'ไม่สามารถปิดได้',
                    type: 'warning',
                    confirmButtonColor: '#999999',
                    confirmButtonText: 'ปิด'
                });
            } else {
                Swal.fire({
                    title: 'เปิดการใช้งานค่าใช้บริการหรือไม่?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4caf50',
                    cancelButtonColor: '#f44336',
                    confirmButtonText: 'ตกลง',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.value) {
                        Swal.fire({
                            title: 'เปิดการใช้งานค่าใช้บริการเสร็จสิ้น',
                            type: 'success',
                            confirmButtonColor: '#999999',
                            confirmButtonText: 'ปิด'
                        }).then((result) => {
                            $('.sw_nonmember').prop('checked', false);
                            $(e.target).prop('checked', true);

                            var scp_reference = $(this).val();
                            var user_status_nonmember = $("#user_status_nonmember").val();

                            user_price_config_change_active(user_status_nonmember, scp_reference)
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        $(e.target).prop('checked', false);
                    }
                });
            }
        })

        $('.clear').click(function() {
            $("input[name='min_age_youth']").val(0);
            $("input[name='max_age_youth']").val("");
            $("input[name='cost_youth']").val("");

            $("input[name='min_age_adult']").val("");
            $("input[name='max_age_adult']").val(99);
            $("input[name='cost_adult']").val("");
        });

        $('.submit').click(function() {
            if (validation()) {
                Swal.fire({
                    title: 'ต้องการบันทึกค่าใช้บริการหรือไม่?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4caf50',
                    cancelButtonColor: '#f44336',
                    confirmButtonText: 'ตกลง',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.value) {
                        Swal.fire({
                            title: 'เพิ่มเกณฑ์ค่าใช้บริการเสร็จสิ้น',
                            type: 'success',
                            confirmButtonColor: '#999999',
                            confirmButtonText: 'ปิด'
                        }).then((result) => {
                            user_price_config_insert();
                        });
                    }
                });
            }
        });

        $('.del-config').click(function() {
            Swal.fire({
                title: 'ต้องการลบค่าใช้บริการหรือไม่?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4caf50',
                cancelButtonColor: '#f44336',
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        title: 'ลบเกณฑ์ค่าใช้บริการเสร็จสิ้น',
                        type: 'success',
                        confirmButtonColor: '#999999',
                        confirmButtonText: 'ปิด'
                    }).then((result) => {
                        user_price_config_delete($(this).val());
                    });
                }
            });
        });

        $('.edit').click(function() {
            Swal.fire({
                title: 'แก้ไขค่าใช้บริการ',
                showCancelButton: true,
                confirmButtonColor: '#4caf50',
                cancelButtonColor: '#f44336',
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ยกเลิก',
                html: ` <div class="container ">
                            <div class="row">
                                <label class="col-md-3 col-form-label">เกณฑ์อายุ</label>
                                <input class="form-control" min=0 type="number" id="youth_min_age_edit" value="${$(this).parent().parent().children().children(`.youth_min_age_${$(this).val()}`).html()}" onkeypress="prevent_mathematics_sign(event)"> ปี
                            </div>` +

                    ` <div class="row">
                                <label class="col-md-3 col-form-label">ถึง</label>
                                <input class="form-control" min=0 type="number" id="youth_max_age_edit" value="${$(this).parent().parent().children().children(`.youth_max_age_${$(this).val()}`).html()}" onkeypress="prevent_mathematics_sign(event)"> ปี
                            </div>` +

                    ` <div class="row">    
                                <label class="col-md-3 col-form-label">ราคา</label>
                                <input class="form-control" min="0" type="number" id="youth_cost_edit" value="${$(this).parent().parent().children(`.youth_cost_${$(this).val()}`).html()}">
                                <label class="col-form-label">บาท</label>
                            </div>
                        </div>` +
                    `<br> 
                        <div class="container">
                            <div class="row">
                                <label class="col-md-3 col-form-label">เกณฑ์อายุ</label>
                                <input class="form-control" min=0 type="number" id="adult_min_age_edit" value="${$(this).parent().parent().parent().children().children().children(`.adult_min_age_${$(this).val()}`).html()}" onkeypress="prevent_mathematics_sign(event)"> ปี
                            </div>` +

                    ` <div class="row">
                                <label class="col-md-3 col-form-label">ถึง</label>
                                <input class="form-control" min=0 type="number" id="adult_max_age_edit" value="${$(this).parent().parent().parent().children().children().children(`.adult_max_age_${$(this).val()}`).html()}" onkeypress="prevent_mathematics_sign(event)"> ปี
                            </div>` +

                    ` <div class="row">    
                                <label class="col-md-3 col-form-label">ราคา</label>
                                <input class="form-control" min="0" type="number" id="adult_cost_edit" value="${$(this).parent().parent().parent().children().children(`.adult_cost_${$(this).val()}`).html()}">
                                <label class="col-form-label">บาท</label>
                            </div>
                        </div>` +
                    `<input type="hidden" id="reference" value="${$(this).val()}">`,
                focusConfirm: false,
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url("index.php/swm/backend/Swm_user_price_config/user_price_config_update_ajax"); ?>",
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
                            location.reload();
                        }
                    });
                }
            })
        });
    });

    function prevent_mathematics_sign(event) {
        let ch = String.fromCharCode(event.which);

        if (!(/['0-9','.']/.test(ch))) {
            event.preventDefault();
        }
    }

    function user_price_config_change_active(user_status, scp_reference) {
        $.ajax({

            type: "POST",
            url: "<?php echo base_url("index.php/swm/backend/Swm_user_price_config/user_price_config_change_active_ajax"); ?>",
            data: {
                scp_sug_id: user_status
            },
            dataType: "json",
            success: function(result) {
                user_price_config_change(scp_reference)

            }

        });
    }

    function user_price_config_change(scp_reference) {

        $.ajax({

            type: "POST",
            url: "<?php echo base_url("index.php/swm/backend/Swm_user_price_config/user_price_config_change_ajax"); ?>",
            data: {
                scp_reference: scp_reference
            },
            dataType: "json",
            success: function(result) {
                location.reload();
            }

        });

    }

    function user_price_config_insert() {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url("index.php/swm/backend/Swm_user_price_config/user_price_config_insert_ajax"); ?>",
            data: {
                user_group: $("#user_group").val(),
                min_age_youth: $("input[name='min_age_youth']").val(),
                max_age_youth: $("input[name='max_age_youth']").val(),
                cost_youth: $("input[name='cost_youth']").val(),

                min_age_adult: $("input[name='min_age_adult']").val(),
                max_age_adult: $("input[name='max_age_adult']").val(),
                cost_adult: $("input[name='cost_adult']").val()
            },
            dataType: "json",
            success: function(response) {
                location.reload();
            }
        });
    }

    function user_price_config_delete(scp_reference) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url("index.php/swm/backend/Swm_user_price_config/user_price_config_delete_ajax"); ?>",
            data: {
                scp_reference: scp_reference
            },
            dataType: "json",
            success: function(response) {
                location.reload();
            }
        });
    }

    function validation() {
        if ($("#user_group").val() == null ||
            $("input[name='min_age_youth']").val() == "" ||
            $("input[name='max_age_youth']").val() == "" ||
            $("input[name='cost_youth']").val() == "" ||

            $("input[name='min_age_adult']").val() == "" ||
            $("input[name='max_age_adult']").val() == "" ||
            $("input[name='cost_adult']").val() == "") {
            swal({
                title: 'กรุณากรอกข้อมูลให้ครบถ้วน',
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
            <h4 class="card-title">กำหนดเกณฑ์ค่าเข้าใช้บริการ</h4>
        </div>
        <div class="card-body">
            <div class="col-lg-5 col-md-6 col-sm-3">
                <div class="dropdown bootstrap-select">
                    <select id="user_group" class="selectpicker" data-size="4" data-style="btn btn-primary btn-round" title="สถานะผู้ใช้งาน" tabindex="-98">
                        <option selected="true" disabled="disabled">สถานะผู้ใช้งาน</option>
                        <option value="2">สมาชิก</option>
                        <option value="1">ผู้ใช้ทั่วไป</option>
                    </select>
                </div>
            </div>
            <div class="row justify-content-center">
                <label class="col-form-label">ช่วงอายุ ตั้งแต่</label>
                <input readonly min=0 style="text-align:center;" type="number" name="min_age_youth" class="form-control" value="0" onkeypress="prevent_mathematics_sign(event)">
                <label class="col-md-0 col-form-label">ปี ถึง</label>
                <input min=0 style="text-align:center;" type="number" name="max_age_youth" class="form-control" onkeypress="prevent_mathematics_sign(event)">
                <label class="col-md-0 col-form-label">ปี ราคา</label>
                <input min="0" style="text-align:center;" type="number" name="cost_youth" class="form-control" onkeypress="prevent_mathematics_sign(event)">
                <label class="col-md-0 col-form-label">บาท</label>
            </div>
            <div class="row justify-content-center">
                <label class="col-form-label">ช่วงอายุ ตั้งแต่</label>
                <input min=0 style="text-align:center;" type="number" name="min_age_adult" class="form-control" onkeypress="prevent_mathematics_sign(event)">
                <label class="col-md-0 col-form-label">ปี ถึง</label>
                <input readonly min=0 style="text-align:center;" type="number" name="max_age_adult" class="form-control" value="99" onkeypress="prevent_mathematics_sign(event)">
                <label class="col-md-0 col-form-label">ปี ราคา</label>
                <input min="0" style="text-align:center;" type="number" name="cost_adult" class="form-control" onkeypress="prevent_mathematics_sign(event)">
                <label class="col-md-0 col-form-label">บาท</label>
            </div>
            <div class="card-footer">
                <div class="mr-auto">
                    <button class="btn btn-inverse clear">
                        เคลียร์
                    </button>
                </div>
                <div class="ml-auto">
                    <button class="btn btn-success submit" rel="tooltip" data-placement="top" title="" data-original-title="คลิกเพื่อบันทึกข้อมูล">บันทึก</button>
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
            <h4 class="card-title">ตารางเกณฑ์ค่าเข้าใช้บริการ</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <ul class="nav nav-pills nav-pills-primary" role="tablist">
                    <li class="nav-item" id="tab_member">
                        <a class="nav-link active" data-toggle="tab">
                            สมาชิก
                        </a>
                    </li>
                    <li class="nav-item" id="tab_user">
                        <a class="nav-link" data-toggle="tab">
                            ผู้ใช้ทั่วไป
                        </a>
                    </li>
                </ul>
            </div>
            <div id="tab_member_data" class="table-responsive material-datatables">
                <table class="table table-striped table-hover table-color-header table-border datatables">
                    <thead class="text-primary">
                        <tr>
                            <th class="text-center">ลำดับ</th>
                            <th class="disabled-sorting">เกณฑ์อายุ</th>
                            <th>ค่าใช้บริการ (บาท)</th>
                            <th class="text-center">สถานะการใช้งาน</th>
                            <th>วันที่แก้ไขข้อมูล</th>
                            <th class="disabled-sorting">ตัวดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;
                        foreach ($tmp_arr as $row) {
                            $i++;

                            foreach ($row as $ind => $sub_row) {
                                if ($ind == 0) {
                        ?>
                                    <tr>
                                        <td class="text-center" rowspan="<?php echo count($row); ?>"><?php echo $i; ?></td>
                                        <td>
                                            อายุ
                                            <span class="youth_min_age_<?php echo $sub_row->scp_reference; ?>"><?php echo $sub_row->scp_age_min; ?></span>
                                            ถึง
                                            <span class="youth_max_age_<?php echo $sub_row->scp_reference; ?>"><?php echo $sub_row->scp_age_max; ?></span>
                                            ปี
                                        </td>
                                        <td class="youth_cost_<?php echo $sub_row->scp_reference; ?> text-right"><?php echo number_format((float) $sub_row->scp_cost, 2, '.', ''); ?></td>
                                        <td class="text-center" rowspan="<?php echo count($row); ?>">
                                            <div class="togglebutton">
                                                <label class="switch">
                                                    <input type="checkbox" class="sw_member" <?php echo ($sub_row->scp_is_active == 'Y') ? 'checked' : ''; ?> value="<?php echo $sub_row->scp_reference; ?>">
                                                    <span class="toggle"></span>
                                                </label>
                                                <input id="user_status" type="hidden" class="sw_member" value="<?php echo $sub_row->scp_sug_id; ?>">
                                            </div>
                                        </td>
                                        <td rowspan="<?php echo count($row); ?>" class="text-center"><?php echo fullDateTH3($sub_row->update_date); ?></td>
                                        <td rowspan="<?php echo count($row); ?>" class="td-actions text-center">
                                            <?php if ($sub_row->scp_is_active != 'Y') { ?>
                                                <button type="button" value="<?php echo $sub_row->scp_reference; ?>" rel="tooltip" class="btn btn-warning edit" data-placement="top" data-original-title="คลิกเพื่อแก้ไขข้อมูล">
                                                    <i class="material-icons">edit</i>
                                                </button>
                                                <button type="button" rel="tooltip" class="btn btn-danger del-config" data-placement="top" value="<?php echo $sub_row->scp_reference; ?>" data-original-title="คลิกเพื่อลบข้อมูล">
                                                    <i class="material-icons">close</i>
                                                </button>
                                            <?php } else { ?>
                                                <button disabled type="button" rel="tooltip" class="btn btn-warning edit" data-placement="top" data-original-title="คลิกเพื่อแก้ไขข้อมูล">
                                                    <i class="material-icons">edit</i>
                                                </button>
                                                <button disabled type="button" rel="tooltip" class="btn btn-danger" data-placement="top" data-original-title="คลิกเพื่อลบข้อมูล">
                                                    <i class="material-icons">close</i>
                                                </button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } else { ?>
                                    <tr>
                                        <td>
                                            อายุ
                                            <span class="adult_min_age_<?php echo $sub_row->scp_reference; ?>"><?php echo $sub_row->scp_age_min; ?></span>
                                            ถึง
                                            <span class="adult_max_age_<?php echo $sub_row->scp_reference; ?>"><?php echo $sub_row->scp_age_max; ?></span>
                                            ปี
                                        </td>
                                        <td class="adult_cost_<?php echo $sub_row->scp_reference; ?> text-right"><?php echo number_format((float) $sub_row->scp_cost, 2, '.', ''); ?></td>
                                    </tr>
                        <?php }
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
            <div id="tab_user_data" class="table-responsive material-datatables">
                <table class="table table-striped table-hover table-color-header table-border datatables">
                    <thead class="text-primary">
                        <tr>
                            <th class="text-center">ลำดับ</th>
                            <th class="disabled-sorting">เกณฑ์อายุ</th>
                            <th>ค่าใช้บริการ (บาท)</th>
                            <th class="text-center">สถานะการใช้งาน</th>
                            <th>วันที่แก้ไขข้อมูล</th>
                            <th class="disabled-sorting">ตัวดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0;

                        foreach ($tmp_arr_nonmember as $row) {
                            $i++;

                            foreach ($row as $ind => $sub_row) {
                                if ($ind == 0) {
                        ?>
                                    <tr>
                                        <td class="text-center" rowspan="<?php echo count($row); ?>"><?php echo $i; ?></td>
                                        <td>
                                            อายุ
                                            <span class="youth_min_age_<?php echo $sub_row->scp_reference; ?>"><?php echo $sub_row->scp_age_min; ?></span>
                                            ถึง
                                            <span class="youth_max_age_<?php echo $sub_row->scp_reference; ?>"><?php echo $sub_row->scp_age_max; ?></span>
                                            ปี
                                        </td>
                                        <td class="youth_cost_<?php echo $sub_row->scp_reference; ?> text-right"><?php echo number_format((float) $sub_row->scp_cost, 2, '.', ''); ?></td>
                                        <td class="text-center" rowspan="<?php echo count($row); ?>">
                                            <div class="togglebutton">
                                                <label class="switch">
                                                    <input type="checkbox" class="sw_nonmember" <?php echo ($sub_row->scp_is_active == 'Y') ? 'checked' : ''; ?> value="<?php echo $sub_row->scp_reference; ?>">
                                                    <span class="toggle"></span>
                                                </label>
                                                <input id="user_status_nonmember" type="hidden" class="sw_nonmember" value="<?php echo $sub_row->scp_sug_id; ?>">
                                            </div>
                                        </td>
                                        <td rowspan="<?php echo count($row); ?>" class="text-center"><?php echo fullDateTH3($sub_row->update_date); ?></td>
                                        <td rowspan="<?php echo count($row); ?>" class="td-actions text-center">
                                            <?php if ($sub_row->scp_is_active != 'Y') { ?>
                                                <button type="button" value="<?php echo $sub_row->scp_reference; ?>" rel="tooltip" class="btn btn-warning edit" data-placement="top" title="" data-original-title="คลิกเพื่อแก้ไขข้อมูล">
                                                    <i class="material-icons">edit</i>
                                                </button>
                                                <button type="button" rel="tooltip" class="btn btn-danger del-config" data-placement="top" title="" value="<?php echo $sub_row->scp_reference; ?>" data-original-title="คลิกเพื่อลบข้อมูล">
                                                    <i class="material-icons">close</i>
                                                </button>
                                            <?php } else { ?>
                                                <button disabled type="button" rel="tooltip" class="btn btn-warning edit" data-placement="top" title="" data-original-title="คลิกเพื่อแก้ไขข้อมูล">
                                                    <i class="material-icons">edit</i>
                                                </button>
                                                <button disabled type="button" rel="tooltip" class="btn btn-danger" data-placement="top" title="" data-original-title="คลิกเพื่อลบข้อมูล">
                                                    <i class="material-icons">close</i>
                                                </button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } else { ?>
                                    <tr>
                                        <td>
                                            อายุ
                                            <span class="adult_min_age_<?php echo $sub_row->scp_reference; ?>"><?php echo $sub_row->scp_age_min; ?></span>
                                            ถึง
                                            <span class="adult_max_age_<?php echo $sub_row->scp_reference; ?>"><?php echo $sub_row->scp_age_max; ?></span>
                                            ปี
                                        </td>
                                        <td class="adult_cost_<?php echo $sub_row->scp_reference; ?> text-right"><?php echo number_format((float) $sub_row->scp_cost, 2, '.', ''); ?></td>
                                    </tr>
                        <?php }
                            }
                        } ?>
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