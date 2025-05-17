<?php
require_once('../../auth.php');
require_once('../../config.php');
require_once '../../new_header.php';
include_once('../../workflownotif.php');
include_once('../../approvalmatrixfunction.php');
include_once('php_function/bonus_function.php');


$sessionid = $_SESSION['ERP_SESS_ID'];
$maintable = "hr_bonus";
$histrytable = "hr_bonus_history";
$status = "act_status";
$stage = "stage_no";
$created_by = "created_by";
$pending_count = gettotal_pendingcount($con, $maintable, $status, $stage, $menuid, '', $created_by, $histrytable);
if ($pending_count[0] > 0) {
    $pcount = $pending_count[0];
} else {
    $pcount = 0;
}

?>
<style>
.toggles {
    display: flex;
    flex-direction: column;
    position: absolute;
    background: #efefef;
    min-width: 200px;
    padding: 10px 20px;
    border-radius: 10px;
    right: 0;
    box-shadow: 0 10px 20px -10px rgba(0, 0, 0, 0.1);
}
.table_column_filter3,.table_column_filter4,.table_column_filter5{
    right: 0;
    padding: 0;
    box-shadow: unset;
    border: unset;
    top: 60px;
    position: absolute;
    z-index: 9999;
    display: none;
}
.table_column_filter3.open,.table_column_filter4.open,.table_column_filter5.open{
    display:block;
}

/* #listing_tableID th,
#listing_tableID td {
    display: none;
} */

#listing_tableID3 th.actv,
#listing_tableID3 td.actv,
#listing_tableID4 th.actv,
#listing_tableID4 td.actv,
#listing_tableID5 th.actv,
#listing_tableID5 td.actv {
    display: table-cell;
}
/**.table_column_filter */
.table_column_filter3 .toggles input, .table_column_filter4 .toggles input, .table_column_filter5 .toggles input {
    margin: 0;
    margin-right: 10px;
}
.table_column_filter3 .toggles span, .table_column_filter4 .toggles span, .table_column_filter5 .toggles span {
  font-size: 12px;
}
</style>
    <div id="page-wrapper"> 
        <section class="top-sec animatePageIn" id="pageContent">

            <input type="hidden" id="frmDates">
            <input type="hidden" id="toDates">
            <input type="hidden" id="bonusTypes">
            <input type="hidden" id="bonusModes">
            
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-1">
                  <div class="panel tabbed-panel panel-info">
                     <div class="panel-heading clearfix p-0">
                        <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12 pull-left px-2">
                           <ul class="nav nav-tabs">
                             <!-- echo 'pcount :- '.$pcount; -->
                                <li class="active" onclick="activeMenu('Pending')"><a href="#listing_reqtab3" data-toggle="tab" aria-expanded="false">Pending <span class="badge"><?php echo $count =  checkpendingcount($con, $sessionid, '0,2');?></span></a></li>
                                <li class="" onclick="activeMenu('Re-Check')"><a href="#listing_reqtab4" data-toggle="tab" aria-expanded="false">Re-Check <span class="badge"><?php echo $count =  checkbonuscount($con, $sessionid, '3'); ?></span></a></li>
                                <li class="" onclick="activeMenu('Hold')"><a href="#listing_reqtab5" data-toggle="tab" aria-expanded="false">Hold <span class="badge"><?php echo $count =  checkbonuscount($con, $sessionid, '4'); ?></span></a></li>
                           </ul>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <h5 class="fw-bolder m-0 pt-1 text-center">Bonus Requested List</h5>
                            <!-- <marquee><p class="mb-0 text-danger">Listing Design Listing Design</p></marquee> -->
                        </div>
                        <div class=" col-lg-4 col-md-4 col-sm-6 col-xs-12 panel-title pull-right p-0">
                           <div class="list_icon text-right mr-1">
                                <a class="btn btn-social-icon" data-toggle="tooltip" data-placement="top" title="Download PDF" onclick="allDownloadWithPDF()"><i class="fa-solid fa-file-pdf" style="color: #c12f2f;"></i></a>
                                <a class="btn btn-social-icon" data-toggle="tooltip" data-placement="top" title="Download Excel with all field" onclick="allDownloadWithExcel()"><i class="fa-sharp fa-solid fa-file-excel fa-lg" style="color: #28c76f;"></i></a>
                                <a href="index.php"><i class="fa-duotone fa-square-left fa-2xl"></i></a>
                            </div>
                        </div>
                     </div>

                     <div class="panel-body p-0">
                        <div class="tab-content">
                           <div class="tab-pane fade active in" id="listing_reqtab3">
                                <div class="panel-heading">
                                   <div class="row">
                                      <form class="" name="searchBonuslist3" id="searchBonuslist3">
                                           <div class="form-row">
                                             <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                                <div class="form-group mb-1">
                                                   <!-- <label for="pname">Form Date</label> -->
                                                   <div class="input-group">
                                                      <input type="text" name="pnd_frm_date" class="form-control pnd_frm_date" id="id_date1" value="<?=$pnd_frm_date;?>" placeholder="Form Date">
                                                      <span class="input-group-addon"><i class="fa-duotone fa-calendar-days" style="--fa-primary-color: #0a0a0a; --fa-secondary-color: #030303;"></i></span>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                                <div class="form-group mb-1">
                                                   <!-- <label for="pname">To Date</label> -->
                                                   <div class="input-group">
                                                      <input type="text" name="pnd_to_date" class="form-control pnd_to_date" id="id_date2" value="<?=$pnd_to_date;?>" placeholder="To Date">
                                                      <span class="input-group-addon"><i class="fa-duotone fa-calendar-days" style="--fa-primary-color: #0a0a0a; --fa-secondary-color: #030303;"></i></span>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                                <div class="form-group mb-1">
                                                   <!-- <label for="pname">Assigned By</label> -->
                                                   <select name="pnd_bonus_type" id="pnd_bonus_type" class="form-control selectized">
                                                      <option value="" selected="selected">Bonus Type</option>
                                                <?php
                                                    $result = mysqli_query($con,"SELECT * FROM `master_type_dtls` WHERE parent_name='master-bonustype' ORDER BY `id` DESC");
                                                    $total_results = mysqli_num_rows($result);

                                                    if($total_results == 0){ $Error_message="NO RECORDS FOUND."; }           // Set For Record Not Found 

                                                    $i=1; 
                                                    while($fetch=mysqli_fetch_object($result)) {
                                                ?>
                                                    <option value="<?php echo $fetch->mstr_type_value;?>" <?php if($pnd_bonus_type==$fetch->mstr_type_value){ echo 'selected';}?> ><?php echo $fetch->mstr_type_name;?></option>
                                                <?php
                                                    }
                                                ?>
                                                   </select>
                                                </div>
                                             </div>
                                             <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                                <div class="form-group mb-1">
                                                   <!-- <label for="pname">Assigned To</label> -->
                                                   <select name="pnd_bonus_mode" id="pnd_bonus_mode" class="form-control selectized">
                                                      <option value="" selected="selected">Bonus Mode</option>
                                                <?php
                                                    $result = mysqli_query($con,"SELECT * FROM `master_type_dtls` WHERE parent_name='master-bonusmode'");
                                                    $total_results = mysqli_num_rows($result);

                                                    if($total_results == 0){ $Error_message="NO RECORDS FOUND."; }           // Set For Record Not Found 

                                                    $i=1; 
                                                    while($fetch=mysqli_fetch_object($result)) {
                                                ?>
                                                    <option value="<?php echo $fetch->mstr_type_value;?>" <?php if($pnd_bonus_mode==$fetch->mstr_type_value){ echo 'selected';}?>><?php echo $fetch->mstr_type_name;?></option>
                                                <?php
                                                    }
                                                ?>
                                                   </select>
                                                </div>
                                             </div>
                                             <div class="col-lg-1 col-md-2 col-sm-3 col-xs-6">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-success form-control" onClick="pnd_bonuslistSrcBtnFns()">Search</button>
                                                </div>
                                             </div>
                                             <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                                <!-- <div class="form-group input-group">
                                                      <input type="text" id="search_input_all" onkeyup="FilterkeyWord_all_table()" placeholder="Search.." class="form-control">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" type="button"><i class="fa-duotone fa-magnifying-glass"></i>
                                                        </button>
                                                    </span>
                                                </div> -->
                                             </div>
                                             <div class="col-lg-1 col-md-2 col-sm-3 col-xs-6">
                                                <div class="dropdown">
                                                         <span class="table_filtericon3 mb-1" style="width: fit-content; margin-left: auto; display: block;"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-filter btn btn-success form-control" aria-hidden="true"></i>
                                                        </span>
                                                    <div class="table_column_filter3">
                                                        <div class="toggles">
                                                            <label>
                                                                <input type="checkbox" value="all" >
                                                                <span>All</span>
                                                            </label>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                             </div>
                                           </div>
                                      </form>
                                   </div>
                               </div>
                                <div class="">
                                    <table class="table table-striped table-bordered table-hover w-100" id="listing_tableID3">
                                        <thead class="bg-dark">
                                            <tr>
                                                <th class="sorting">SN</th>
                                                <th class="text-center sorting">Date</th>
                                                <th class="text-center sorting">Bonus Type</th>
                                                <th class="text-center sorting">Bonus On</th>
                                                <th class="text-center sorting">Bonus Mode</th>
                                                <th class="text-center sorting">Applicable Form</th>
                                                <th class="text-center sorting">Bonus (%)</th>
                                                <th class="text-center sorting">Status</th>
                                                <th class="text-center sorting">Pending With</th>
                                                <th class="text-center sorting">Status Dt.</th>
                                                <th class="text-center sorting">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pnd_approvedBody">

                        <?php
                            // $bnsQry = mysqli_query($con,"SELECT a.id as ids,a.*,b.*,c.*,c.id as empIds FROM hr_bonus a, master_type_dtls b, mstr_emp c WHERE a.b_type=b.mstr_type_value AND (a.b_status='0' || a.b_status='2') AND a.created_by='$sessionid' AND a.created_by=c.id ORDER BY a.id DESC");
                            $bnsQry = mysqli_query($con,"SELECT a.id as ids,a.*,b.*,c.*,c.id as empIds FROM hr_bonus a, master_type_dtls b, mstr_emp c WHERE a.b_type=b.mstr_type_value AND (a.b_status='0' || a.b_status='2') AND a.created_by=c.id $pnd_allQryVls ORDER BY a.id DESC");
                            $bnsQry_results = mysqli_num_rows($bnsQry);
                            if ($bnsQry_results>0) {
                                $counter=0;
                                while($rows=mysqli_fetch_object($bnsQry)){
                                    //********Approved User Access
                                    $getApproverList = getApproverList($con, $menuid, $rows->stage_no);
                                    // echo 'Surya : '.$rows->created_by.'----'.$sessionid.'----'.$rows->stage_no.'----'.$menuid.'----'.$getApproverList;
                                    // $rows->created_by==$sessionid || 
                                    if ($getApproverList==$sessionid) {
                                    //********Approved User Access
                                    $counter++;
                                    $refid = $rows->ids;
                                    $getfield = "remarks"; //to fetch approved by id from details table
                                    $dateView = date('d-m-Y', strtotime($rows->b_date));
                                    $stsVls = $rows->b_status;
                                    $refcolmn = "act_status";

                                    // echo 'empIds :- '.$rows->empIds;
                                    // echo 'hr_bonus_history, bns_id, '. $refid.', '.$getfield.', '.$refcolmn.'---';

                                    if($stsVls == '0'){
                                          $status = 'Request Raised';
                                          $color = 'color:Orange';
                                    }else{
                                        $status =  getstatus($con, 'hr_bonus_history', 'bns_id', $refid, $getfield, $refcolmn);
                                        $color = 'color:green';
                                    }
                                    $bnsQry_a = mysqli_query($con,"SELECT * FROM master_type_dtls WHERE mstr_type_value='$rows->b_mode'");
                                    $rows_a=mysqli_fetch_object($bnsQry_a);

                                    $bnsQry_b = mysqli_query($con,"SELECT * FROM hr_bonus_history WHERE bns_id='$rows->ids' ORDER BY id DESC LIMIT 1"); //*****New Added
                                    $rows_b=mysqli_fetch_object($bnsQry_b);
                        ?>
                                            <tr>
                                                <td><?=$counter;?></td>
                                                <td><?=$dateView;?></td>
                                                <td><?=$rows->mstr_type_name;?></td>
                                                <td><?=$rows->b_on;?></td>
                                                <td><?=$rows_a->mstr_type_name;?></td>
                                                <td><?=$rows->applicable;?></td>
                                                <td><?=$rows->b_per;?></td>
                                                <td style="<?php echo $color; ?>"><?=$status;?></td>
                                                <td>
                                                    <?php
                                                        $empid = $rows->created_by;
                                                        $deptid = getdeptid($con, $empid);
                                                        $stage_no = $rows->stage_no;

                                                        // echo $menuid.', '.$stage_no.', '.$stsVls.', '.''.', '.''.', '.$deptid.', '.''.', '.$empid.'<br/>';


                                                        if ($stsVls == 0 || $stsVls == 2) {
                                                            $data =  payliststatuswith($con, $menuid, $stage_no, $stsVls, '', '', $deptid, '', $empid);
                                                            $color = 'color:Red';
                                                        } else {
                                                            $data = statuswithother($con, 'hr_bonus_history', 'bns_id', $rows->ids, $stsVls, 'action_by');
                                                            $color = 'color:Green';
                                                        }
                                                    ?>
                                                    <span style="<?php echo $color; ?>"><b><?php echo $data; ?></b></span>
                                                </td>
                                                <td><?=$rows_b->action_on;?></td>
                                                <td class="text-center">
                                                    <?php if ($stsVls == '0' && $rows->created_by == $sessionid) { ?>
                                                        <a href="edit_bonus_request.php?ids=<?php echo $rows->ids; ?>&viewid=2" style="text-decoration:none;" class="btn btn-warning btn-xs"><b>Edit</b></a>
                                                    <?php } ?>
                                                    <a href="bonus_view.php?ids=<?=$rows->ids;?>&viewid=2" class="btn btn-primary btn-xs fw-bolder">View</a>
                                                </td>
                                            </tr>

                        <?php
                                    }
                                }
                            }else{
                        ?>
                                            <tr>
                                                <td class="" colspan="11" style="text-align: center; font-size: 15px;">--- <span style="color: red;">Data Not found</span> ---</td>
                                            </tr>
                        <?php
                            }
                        ?>

                                        </tbody>
                                    </table>

                                    <!-- <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                      <div class="rows_count">Showing 1 to 10 of 30 entries</div>
                                   </div>

                                   <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <div class='pagination-container'>
                                          <nav>
                                             <ul class="pagination">
                                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a></li>
                                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item" aria-current="page"><a class="page-link" href="#">2</a></li>
                                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                              </ul>
                                          </nav>
                                        </div>
                                   </div> -->

                                </div>
                           </div>
                           <div class="tab-pane fade" id="listing_reqtab4">
                                <div class="panel-heading">
                                   <div class="row">
                                      <form class="" name="searchBonuslist4" id="searchBonuslist4">
                                           <div class="form-row">
                                             <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                                <div class="form-group mb-1">
                                                   <!-- <label for="pname">Form Date</label> -->
                                                   <div class="input-group">
                                                      <input type="text" name="rechk_frm_date" class="form-control rechk_frm_date" id="id_date3" value="<?=$rechk_frm_date;?>" placeholder="Form Date">
                                                      <span class="input-group-addon"><i class="fa-duotone fa-calendar-days" style="--fa-primary-color: #0a0a0a; --fa-secondary-color: #030303;"></i></span>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                                <div class="form-group mb-1">
                                                   <!-- <label for="pname">To Date</label> -->
                                                   <div class="input-group">
                                                      <input type="text" name="rechk_to_date" class="form-control rechk_to_date" id="id_date4" value="<?=$rechk_to_date;?>" placeholder="To Date">
                                                      <span class="input-group-addon"><i class="fa-duotone fa-calendar-days" style="--fa-primary-color: #0a0a0a; --fa-secondary-color: #030303;"></i></span>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                                <div class="form-group mb-1">
                                                   <!-- <label for="pname">Assigned By</label> -->
                                                   <select name="rechk_bonus_type" id="rechk_bonus_type" class="form-control selectized">
                                                      <option value="" selected="selected">Bonus Type</option>
                                                <?php
                                                    $result = mysqli_query($con,"SELECT * FROM `master_type_dtls` WHERE parent_name='master-bonustype' ORDER BY `id` DESC");
                                                    $total_results = mysqli_num_rows($result);

                                                    if($total_results == 0){ $Error_message="NO RECORDS FOUND."; }           // Set For Record Not Found 

                                                    $i=1; 
                                                    while($fetch=mysqli_fetch_object($result)) {
                                                ?>
                                                    <option value="<?php echo $fetch->mstr_type_value;?>" <?php if($rechk_bonus_type==$fetch->mstr_type_value){ echo 'selected';}?> ><?php echo $fetch->mstr_type_name;?></option>
                                                <?php
                                                    }
                                                ?>
                                                   </select>
                                                </div>
                                             </div>
                                             <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                                <div class="form-group mb-1">
                                                   <!-- <label for="pname">Assigned To</label> -->
                                                   <select name="rechk_bonus_mode" id="rechk_bonus_mode" class="form-control selectized">
                                                      <option value="" selected="selected">Bonus Mode</option>
                                                <?php
                                                    $result = mysqli_query($con,"SELECT * FROM `master_type_dtls` WHERE parent_name='master-bonusmode'");
                                                    $total_results = mysqli_num_rows($result);

                                                    if($total_results == 0){ $Error_message="NO RECORDS FOUND."; }           // Set For Record Not Found 

                                                    $i=1; 
                                                    while($fetch=mysqli_fetch_object($result)) {
                                                ?>
                                                    <option value="<?php echo $fetch->mstr_type_value;?>" <?php if($rechk_bonus_mode==$fetch->mstr_type_value){ echo 'selected';}?>><?php echo $fetch->mstr_type_name;?></option>
                                                <?php
                                                    }
                                                ?>
                                                   </select>
                                                </div>
                                             </div>
                                             <div class="col-lg-1 col-md-2 col-sm-3 col-xs-6">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-success form-control" onClick="rechk_bonuslistSrcBtnFns()">Search</button>
                                                </div>
                                             </div>
                                             <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                                <!-- <div class="form-group input-group">
                                                      <input type="text" id="search_input_all" onkeyup="FilterkeyWord_all_table()" placeholder="Search.." class="form-control">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" type="button"><i class="fa-duotone fa-magnifying-glass"></i>
                                                        </button>
                                                    </span>
                                                </div> -->
                                             </div>
                                             <div class="col-lg-1 col-md-2 col-sm-3 col-xs-6">
                                                <div class="dropdown">
                                                         <span class="table_filtericon4 mb-1" style="width: fit-content; margin-left: auto; display: block;"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-filter btn btn-success form-control" aria-hidden="true"></i>
                                                        </span>
                                                    <div class="table_column_filter4">
                                                        <div class="toggles">
                                                            <label>
                                                                <input type="checkbox" value="all" >
                                                                <span>All</span>
                                                            </label>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                             </div>
                                           </div>
                                      </form>
                                   </div>
                               </div>
                                <div class="">
                                    <table class="table table-striped table-bordered table-hover w-100" id="listing_tableID4">
                                        <thead class="bg-dark">
                                            <tr>
                                                <th class="sorting">SN</th>
                                                <th class="text-center sorting">Date</th>
                                                <th class="text-center sorting">Bonus Type</th>
                                                <th class="text-center sorting">Bonus On</th>
                                                <th class="text-center sorting">Bonus Mode</th>
                                                <th class="text-center sorting">Applicable Form</th>
                                                <th class="text-center sorting">Bonus (%)</th>
                                                <th class="text-center sorting">Status</th>
                                                <th class="text-center sorting">Pending With</th>
                                                <th class="text-center sorting">Status Dt.</th>
                                                <th class="text-center sorting">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="rechk_approvedBody">

                        <?php
                            // $bnsQry = mysqli_query($con,"SELECT a.id as ids,a.*,b.*,c.*,c.id as empIds FROM hr_bonus a, master_type_dtls b, mstr_emp c WHERE a.b_type=b.mstr_type_value AND a.b_status='3' AND a.created_by='$sessionid' AND a.created_by=c.id ORDER BY a.id DESC");
                            $bnsQry = mysqli_query($con,"SELECT a.id as ids,a.*,b.*,c.*,c.id as empIds FROM hr_bonus a, master_type_dtls b, mstr_emp c WHERE a.b_type=b.mstr_type_value AND a.b_status='3' AND a.created_by=c.id $rechk_allQryVls ORDER BY a.id DESC");
                            $bnsQry_results = mysqli_num_rows($bnsQry);
                            if ($bnsQry_results>0) {
                                $counter=0;
                                while($rows=mysqli_fetch_object($bnsQry)){
                                    //********Approved User Access
                                    $getApproverList = getApproverList($con, $menuid, $rows->stage_no);
                                    // $rows->created_by==$sessionid || 
                                    if ($getApproverList==$sessionid) {
                                    //********Approved User Access
                                    $counter++;
                                    $refid = $rows->ids;
                                    $getfield = "remarks"; //to fetch approved by id from details table
                                    $dateView = date('d-m-Y', strtotime($rows->b_date));
                                    $stsVls = $rows->b_status;
                                    $refcolmn = "act_status";

                                    // echo 'empIds :- '.$rows->empIds;
                                    // echo 'hr_bonus_history, bns_id, '. $refid.', '.$getfield.', '.$refcolmn.'---';

                                    if($stsVls == '0'){
                                          $status = 'Request Raised';
                                          $color = 'color:Orange';
                                    }else{
                                        $status =  getstatus($con, 'hr_bonus_history', 'bns_id', $refid, $getfield, $refcolmn);
                                        $color = 'color:green';
                                    }
                                    $bnsQry_a = mysqli_query($con,"SELECT * FROM master_type_dtls WHERE mstr_type_value='$rows->b_mode'");
                                    $rows_a=mysqli_fetch_object($bnsQry_a);

                                    $bnsQry_b = mysqli_query($con,"SELECT * FROM hr_bonus_history WHERE bns_id='$rows->ids' ORDER BY id DESC LIMIT 1"); //*****New Added
                                    $rows_b=mysqli_fetch_object($bnsQry_b);
                        ?>
                                            <tr>
                                                <td><?=$counter;?></td>
                                                <td><?=$dateView;?></td>
                                                <td><?=$rows->mstr_type_name;?></td>
                                                <td><?=$rows->b_on;?></td>
                                                <td><?=$rows_a->mstr_type_name;?></td>
                                                <td><?=$rows->applicable;?></td>
                                                <td><?=$rows->b_per;?></td>
                                                <td style="<?php echo $color; ?>"><?=$status;?></td>
                                                <td>
                                                    <?php
                                                        $empid = $rows->created_by;
                                                        $deptid = getdeptid($con, $empid);
                                                        $stage_no = $rows->stage_no;

                                                        // echo $menuid.', '.$stage_no.', '.$stsVls.', '.''.', '.''.', '.$deptid.', '.''.', '.$empid.'<br/>';

                                                        if ($stsVls == 0 || $stsVls == 2) {
                                                            $data =  payliststatuswith($con, $menuid, $stage_no, $stsVls, '', '', $deptid, '', $empid);
                                                            $color = 'color:Red';
                                                        } else {
                                                            $data = statuswithother($con, 'hr_bonus_history', 'bns_id', $rows->ids, $stsVls, 'action_by');
                                                            $color = 'color:Green';
                                                        }
                                                    ?>
                                                    <span style="<?php echo $color; ?>"><b><?php echo $data; ?></b></span>
                                                </td>
                                                <td><?=$rows_b->action_on;?></td>
                                                <td class="text-center">
                                                    <?php if ($stsVls == '3' && $rows->created_by == $sessionid) { ?>
                                                        <a href="edit_bonus_request.php?ids=<?php echo $rows->ids; ?>&viewid=2" style="text-decoration:none;" class="btn btn-warning btn-xs"><b>Edit</b></a>
                                                    <?php } ?>
                                                   <?php
                                                        if($stsVls == '3'){
                                                            if($rows->created_by == $sessionid){
                                                    ?>
                                                        <a href="bonus_view.php?ids=<?=$rows->ids;?>&viewid=2" class="btn btn-primary btn-xs fw-bolder">View</a>
                                                    <?php
                                                            }  
                                                        }else{
                                                    ?>
                                                        <a href="bonus_view.php?ids=<?=$rows->ids;?>&viewid=2" class="btn btn-primary btn-xs fw-bolder">View</a>
                                                    <?php
                                                        }
                                                    ?>
                                                </td>
                                            </tr>

                        <?php
                                    }
                                }
                            }else{
                        ?>
                                            <tr>
                                                <td class="" colspan="11" style="text-align: center; font-size: 15px;">--- <span style="color: red;">Data Not found</span> ---</td>
                                            </tr>
                        <?php
                            }
                        ?>

                                        </tbody>
                                    </table>

                                    <!-- <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                      <div class="rows_count">Showing 1 to 10 of 30 entries</div>
                                   </div>

                                   <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <div class='pagination-container'>
                                          <nav>
                                             <ul class="pagination">
                                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a></li>
                                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item" aria-current="page"><a class="page-link" href="#">2</a></li>
                                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                              </ul>
                                          </nav>
                                        </div>
                                   </div> -->

                                </div>
                           </div>
                           <div class="tab-pane fade" id="listing_reqtab5">
                                <div class="panel-heading">
                                   <div class="row">
                                      <form class="" name="searchBonuslist5" id="searchBonuslist5">
                                           <div class="form-row">
                                             <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                                <div class="form-group mb-1">
                                                   <!-- <label for="pname">Form Date</label> -->
                                                   <div class="input-group">
                                                      <input type="text" name="hold_frm_date" class="form-control hold_frm_date" id="id_date5" value="<?=$hold_frm_date;?>" placeholder="Form Date">
                                                      <span class="input-group-addon"><i class="fa-duotone fa-calendar-days" style="--fa-primary-color: #0a0a0a; --fa-secondary-color: #030303;"></i></span>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                                <div class="form-group mb-1">
                                                   <!-- <label for="pname">To Date</label> -->
                                                   <div class="input-group">
                                                      <input type="text" name="hold_to_date" class="form-control hold_to_date" id="id_date6" value="<?=$hold_to_date;?>" placeholder="To Date">
                                                      <span class="input-group-addon"><i class="fa-duotone fa-calendar-days" style="--fa-primary-color: #0a0a0a; --fa-secondary-color: #030303;"></i></span>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                                <div class="form-group mb-1">
                                                   <!-- <label for="pname">Assigned By</label> -->
                                                   <select name="hold_bonus_type" id="hold_bonus_type" class="form-control selectized">
                                                      <option value="" selected="selected">Bonus Type</option>
                                                <?php
                                                    $result = mysqli_query($con,"SELECT * FROM `master_type_dtls` WHERE parent_name='master-bonustype' ORDER BY `id` DESC");
                                                    $total_results = mysqli_num_rows($result);

                                                    if($total_results == 0){ $Error_message="NO RECORDS FOUND."; }           // Set For Record Not Found 

                                                    $i=1; 
                                                    while($fetch=mysqli_fetch_object($result)) {
                                                ?>
                                                    <option value="<?php echo $fetch->mstr_type_value;?>" <?php if($hold_bonus_type==$fetch->mstr_type_value){ echo 'selected';}?> ><?php echo $fetch->mstr_type_name;?></option>
                                                <?php
                                                    }
                                                ?>
                                                   </select>
                                                </div>
                                             </div>
                                             <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                                <div class="form-group mb-1">
                                                   <!-- <label for="pname">Assigned To</label> -->
                                                   <select name="hold_bonus_mode" id="hold_bonus_mode" class="form-control selectized">
                                                      <option value="" selected="selected">Bonus Mode</option>
                                                <?php
                                                    $result = mysqli_query($con,"SELECT * FROM `master_type_dtls` WHERE parent_name='master-bonusmode'");
                                                    $total_results = mysqli_num_rows($result);

                                                    if($total_results == 0){ $Error_message="NO RECORDS FOUND."; }           // Set For Record Not Found 

                                                    $i=1; 
                                                    while($fetch=mysqli_fetch_object($result)) {
                                                ?>
                                                    <option value="<?php echo $fetch->mstr_type_value;?>" <?php if($hold_bonus_mode==$fetch->mstr_type_value){ echo 'selected';}?>><?php echo $fetch->mstr_type_name;?></option>
                                                <?php
                                                    }
                                                ?>
                                                   </select>
                                                </div>
                                             </div>
                                             <div class="col-lg-1 col-md-2 col-sm-3 col-xs-6">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-success form-control" onClick="hold_bonuslistSrcBtnFns()">Search</button>
                                                </div>
                                             </div>
                                             <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6">
                                                <!-- <div class="form-group input-group">
                                                      <input type="text" id="search_input_all" onkeyup="FilterkeyWord_all_table()" placeholder="Search.." class="form-control">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" type="button"><i class="fa-duotone fa-magnifying-glass"></i>
                                                        </button>
                                                    </span>
                                                </div> -->
                                             </div>
                                             <div class="col-lg-1 col-md-2 col-sm-3 col-xs-6">
                                                <div class="dropdown">
                                                         <span class="table_filtericon5 mb-1" style="width: fit-content; margin-left: auto; display: block;"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-filter btn btn-success form-control" aria-hidden="true"></i>
                                                        </span>
                                                    <div class="table_column_filter5">
                                                        <div class="toggles">
                                                            <label>
                                                                <input type="checkbox" value="all" >
                                                                <span>All</span>
                                                            </label>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                             </div>
                                           </div>
                                      </form>
                                   </div>
                               </div>
                                <div class="">
                                    <table class="table table-striped table-bordered table-hover w-100" id="listing_tableID5">
                                        <thead class="bg-dark">
                                            <tr>
                                                <th class="sorting">SN</th>
                                                <th class="text-center sorting">Date</th>
                                                <th class="text-center sorting">Bonus Type</th>
                                                <th class="text-center sorting">Bonus On</th>
                                                <th class="text-center sorting">Bonus Mode</th>
                                                <th class="text-center sorting">Applicable Form</th>
                                                <th class="text-center sorting">Bonus (%)</th>
                                                <th class="text-center sorting">Status</th>
                                                <th class="text-center sorting">Pending With</th>
                                                <th class="text-center sorting">Status Dt.</th>
                                                <th class="text-center sorting">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="hold_approvedBody">

                        <?php
                            // $bnsQry = mysqli_query($con,"SELECT a.id as ids,a.*,b.*,c.*,c.id as empIds FROM hr_bonus a, master_type_dtls b, mstr_emp c WHERE a.b_type=b.mstr_type_value AND a.b_status='4' AND a.created_by='$sessionid' AND a.created_by=c.id ORDER BY a.id DESC");
                            $bnsQry = mysqli_query($con,"SELECT a.id as ids,a.*,b.*,c.*,c.id as empIds FROM hr_bonus a, master_type_dtls b, mstr_emp c WHERE a.b_type=b.mstr_type_value AND a.b_status='4' AND a.created_by=c.id $hold_allQryVls ORDER BY a.id DESC");
                            $bnsQry_results = mysqli_num_rows($bnsQry);
                            if ($bnsQry_results>0) {
                                $counter=0;
                                while($rows=mysqli_fetch_object($bnsQry)){
                                    //********Approved User Access
                                    $getApproverList = getApproverList($con, $menuid, $rows->stage_no);
                                    // $rows->created_by==$sessionid || 
                                    if ($getApproverList==$sessionid) {
                                    //********Approved User Access
                                    $counter++;
                                    $refid = $rows->ids;
                                    $getfield = "remarks"; //to fetch approved by id from details table
                                    $dateView = date('d-m-Y', strtotime($rows->b_date));
                                    $stsVls = $rows->b_status;
                                    $refcolmn = "act_status";

                                    // echo 'empIds :- '.$rows->empIds;
                                    // echo 'hr_bonus_history, bns_id, '. $refid.', '.$getfield.', '.$refcolmn.'---';

                                    if($stsVls == '0'){
                                          $status = 'Request Raised';
                                          $color = 'color:Orange';
                                    }else{
                                        $status =  getstatus($con, 'hr_bonus_history', 'bns_id', $refid, $getfield, $refcolmn);
                                        $color = 'color:green';
                                    }
                                    $bnsQry_a = mysqli_query($con,"SELECT * FROM master_type_dtls WHERE mstr_type_value='$rows->b_mode'");
                                    $rows_a=mysqli_fetch_object($bnsQry_a);

                                    $bnsQry_b = mysqli_query($con,"SELECT * FROM hr_bonus_history WHERE bns_id='$rows->ids' ORDER BY id DESC LIMIT 1"); //*****New Added
                                    $rows_b=mysqli_fetch_object($bnsQry_b);
                        ?>
                                            <tr>
                                                <td><?=$counter;?></td>
                                                <td><?=$dateView;?></td>
                                                <td><?=$rows->mstr_type_name;?></td>
                                                <td><?=$rows->b_on;?></td>
                                                <td><?=$rows_a->mstr_type_name;?></td>
                                                <td><?=$rows->applicable;?></td>
                                                <td><?=$rows->b_per;?></td>
                                                <td style="<?php echo $color; ?>"><?=$status;?></td>
                                                <td>
                                                    <?php
                                                        $empid = $rows->created_by;
                                                        $deptid = getdeptid($con, $empid);
                                                        $stage_no = $rows->stage_no;

                                                        // echo $menuid.', '.$stage_no.', '.$stsVls.', '.''.', '.''.', '.$deptid.', '.''.', '.$empid.'<br/>';

                                                        if ($stsVls == 0 || $stsVls == 2) {
                                                            $data =  payliststatuswith($con, $menuid, $stage_no, $stsVls, '', '', $deptid, '', $empid);
                                                            $color = 'color:Red';
                                                        } else {
                                                            $data = statuswithother($con, 'hr_bonus_history', 'bns_id', $rows->ids, $stsVls, 'action_by');
                                                            $color = 'color:Green';
                                                        }
                                                    ?>
                                                    <span style="<?php echo $color; ?>"><b><?php echo $data; ?></b></span>
                                                </td>
                                                <td><?=$rows_b->action_on;?></td>
                                                <td class="text-center">
                                                   <a href="bonus_view.php?ids=<?=$rows->ids;?>&viewid=2" class="btn btn-primary btn-xs fw-bolder">View</a>
                                                </td>
                                            </tr>

                        <?php
                                    }
                                }
                            }else{
                        ?>
                                            <tr>
                                                <td class="" colspan="11" style="text-align: center; font-size: 15px;">--- <span style="color: red;">Data Not found</span> ---</td>
                                            </tr>
                        <?php
                            }
                        ?>

                                        </tbody>
                                    </table>

                                    <!-- <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                      <div class="rows_count">Showing 1 to 10 of 30 entries</div>
                                   </div>

                                   <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <div class='pagination-container'>
                                          <nav>
                                             <ul class="pagination">
                                                <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a></li>
                                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item" aria-current="page"><a class="page-link" href="#">2</a></li>
                                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                              </ul>
                                          </nav>
                                        </div>
                                   </div> -->

                                </div>
                           </div>

                        </div>
                     </div>
                  </div>
                </div>
           </div>
        </section>
    </div>
    <!-- /#page-wrapper -->
<?php require_once('../../new_footer.php'); ?>
<script src="js_function/listing_tableID.js"></script>

<script>
$(document).ready(function(){
    inActiveMenuId('Pending');
});
</script>
<script src="js_function/js_function.js"></script>
<script src="js_function/commom_fn.js"></script>




