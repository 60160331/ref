
<div class="card">
    <div class="card-header card-header-info card-header-icon">
        <div class="card-icon">
            <i class="material-icons">assignment</i>
        </div>
        <h4 class="card-title">รายการคำร้องแจ้งปัญหา</h4>
    </div>
    <div class = "card-body">
    <div class="row">
                <ul class="nav nav-pills nav-pills-primary" role="tablist">
                    <li class="nav-item" id="tab_member">
                        <a class="nav-link active show" data-toggle="tab">
                            ในเขต
                        </a>
                    </li>
                    <li class="nav-item" id="tab_user">
                        <a class="nav-link" data-toggle="tab">
                            นอกเขต
                        </a>
                    </li>
                </ul>
    </div>
    <div class="card-body material-datatables">
        <table id="datatables" class="table table-striped table-color-header table-hover table-border" cellspacing="0" width="100%" style="width:100%">
            <thead class="text-primary">
                <tr role="row">
                <th width="5%">ลำดับ</th>
                        <th width="10%">วันที่</th>
                        <th width="10%">เวลา</th>
                        <th width="10%">ประเภท</th>
                        <th width="15%">รายละเอียด</th>
                        <th width="17%">ประเภทความคิดเห็น</th>
                        <th width="19%">ชื่อ-นามสกุล</th>
                    </tr>
            </thead>
                <tbody> 
                <?php $index = 0; foreach($rs_comp as $row){ $index++;?>        
                    <tr>
                        <td class="text-center"><?php echo $index;?></td>
                        <td class="text-center"><?php echo abbreDate2( substr($row->cp_update,0,10) );?></td>
                        <td class="text-center"><?php echo substr($row->cp_update,11,5);?>&nbsp น.</td>
                        <td class="text-center"><?php echo $row->cc_type;?></td>
                        <td class=""><?php echo $row->tp_complain;?></td>
                        <td><?php echo $row->cp_complain;?></td>
                        <!-- <td ><?php echo $row->ff_name;?></td> -->
                        <td><?php echo $row->pf_name."   ". $row->ps_fname."   ". $row->ps_lname; ?></td>
                    </tr>

                <?php  } ?>

                </tbody>
            </table>
            </div>   
        </div>	
    </div>
        <div class="panel-footer">
            <div class="col-md-3">
                <button class="btn btn-inverse btn_iserl tooltips" title="คลิกปุ่มเพื่อย้อนกลับ" onclick="window.location='<?php echo site_url('/'.$dir.'/Home');?>'"><span>ย้อนกลับ</span></button>
            </div>	
        </div>
    </div>	
</div>	
