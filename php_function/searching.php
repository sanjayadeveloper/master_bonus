<?php
require_once('../../../auth.php');
require_once('../../../config.php');
include_once('../../../workflownotif.php');
include_once('../../../approvalmatrixfunction.php');
include_once('bonus_function.php');
$sessionid = $_SESSION['ERP_SESS_ID'];


if (isset($_POST['action'])) {
    $action = $_POST['action'];
}


if ($action=='searchedBonuslist') {
    $frm_date = $_POST['frm_date'];
    $to_date = $_POST['to_date'];
    $bonus_type = $_POST['bonus_type'];
    $bonus_mode = $_POST['bonus_mode'];

    

    $allQryVls = "";

    if (!empty($frm_date)) {
        if (!empty($to_date)) {
            $allQryVls .= " AND a.b_date>='$frm_date'";
        }else{
            $allQryVls .= " AND a.b_date='$frm_date'";
        }
    }else{
        $allQryVls .= "";
    }
    if (!empty($to_date)) {
        if (!empty($frm_date)) {
            $allQryVls .= " AND a.b_date<='$to_date'";
        }else{
            $allQryVls .= " AND a.b_date='$to_date'";
        }
    }else{
        $allQryVls .= "";
    }
    if (!empty($bonus_type)) {
        $allQryVls .= " AND a.b_type='$bonus_type'";
    }else{
        $allQryVls .= "";
    }
    if (!empty($bonus_mode)) {
        $allQryVls .= " AND a.b_mode='$bonus_mode'";
    }else{
        $allQryVls .= "";
    }

    $bnsQry = mysqli_query($con,"SELECT a.id as ids,a.*,b.*,c.*,c.id as empIds FROM hr_bonus a, master_type_dtls b, mstr_emp c WHERE a.b_type=b.mstr_type_value AND a.created_by=c.id $allQryVls ORDER BY a.id DESC");
    $bnsQry_results = mysqli_num_rows($bnsQry);
    if ($bnsQry_results>0) {
        $counter=0;
        while($rows=mysqli_fetch_object($bnsQry)){
            
    // echo $frm_date.'---'.$to_date.'---'.$bonus_type.'---'.$bonus_mode.'---'.$bnsQry_results.'---'.$rows->created_by;
    // exit();
            //********Approved User Access
            $getApproverList = getApproverList($con, $menuid, $rows->stage_no);
            // if ($rows->created_by==$sessionid || $getApproverList==$sessionid) {
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
                                    if($stsVls=='6'){
                                        $color = 'color:Red';
                                    }else{
                                        $color = 'color:Green';
                                    }
                                    
                                }
                            ?>
                            <span style="<?php echo $color; ?>"><b><?php echo $data; ?></b></span>
                        </td>
                        <td><?=$rows_b->action_on;?></td>
                        <td class="text-center">
                            <?php if (($stsVls == '0' || $stsVls == '3') && $rows->created_by == $sessionid) { ?>
                                <a href="edit_bonus_request.php?ids=<?php echo $rows->ids; ?>&viewid=1" style="text-decoration:none;" class="btn btn-warning btn-xs"><b>Edit</b></a>
                            <?php } ?>
                           <a href="bonus_view.php?ids=<?=$rows->ids;?>&viewid=1" class="btn btn-primary btn-xs fw-bolder">View</a>
                        </td>
                    </tr>

<?php
            // }
        }
    }else{
?>
                    <tr>
                        <td class="" colspan="11" style="text-align: center; font-size: 15px;">--- <span style="color: red;">Data Not Found</span> ---</td>
                    </tr>
<?php
    }
}


if ($action=='appr_searchedBonuslist') {
    $frm_date = $_POST['appr_frm_date'];
    $to_date = $_POST['appr_to_date'];
    $bonus_type = $_POST['appr_bonus_type'];
    $bonus_mode = $_POST['appr_bonus_mode'];

    $appr_allQryVls = "";

    if (!empty($frm_date)) {
        if (!empty($to_date)) {
            $appr_allQryVls .= " AND a.b_date>='$frm_date'";
        }else{
            $appr_allQryVls .= " AND a.b_date='$frm_date'";
        }
    }else{
        $appr_allQryVls .= "";
    }
    if (!empty($to_date)) {
        if (!empty($frm_date)) {
            $appr_allQryVls .= " AND a.b_date<='$to_date'";
        }else{
            $appr_allQryVls .= " AND a.b_date='$to_date'";
        }
    }else{
        $appr_allQryVls .= "";
    }
    if (!empty($bonus_type)) {
        $appr_allQryVls .= " AND a.b_type='$bonus_type'";
    }else{
        $appr_allQryVls .= "";
    }
    if (!empty($bonus_mode)) {
        $appr_allQryVls .= " AND a.b_mode='$bonus_mode'";
    }else{
        $appr_allQryVls .= "";
    }

    $bnsQry = mysqli_query($con,"SELECT a.id as ids,a.*,b.*,c.*,c.id as empIds FROM hr_bonus a, master_type_dtls b, mstr_emp c WHERE a.b_type=b.mstr_type_value AND a.b_status='1' AND a.created_by=c.id $appr_allQryVls ORDER BY a.id DESC");
    $bnsQry_results = mysqli_num_rows($bnsQry);
    if ($bnsQry_results>0) {
        $counter=0;
        while($rows=mysqli_fetch_object($bnsQry)){
            //********Approved User Access
            $getApproverList = getApproverList($con, $menuid, $rows->stage_no);
            if ($rows->created_by==$sessionid || $getApproverList==$sessionid) {
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
                           <a href="bonus_view.php?ids=<?=$rows->ids;?>&viewid=1" class="btn btn-primary btn-xs fw-bolder">View</a>
                        </td>
                    </tr>

<?php
            }
        }
    }else{
?>
                    <tr>
                        <td class="" colspan="11" style="text-align: center; font-size: 15px;">--- <span style="color: red;">Data Not Found</span> ---</td>
                    </tr>
<?php
    }
}



if ($action=='rej_bonuslistSrcBtnFns') {
    $frm_date = $_POST['rej_frm_date'];
    $to_date = $_POST['rej_to_date'];
    $bonus_type = $_POST['rej_bonus_type'];
    $bonus_mode = $_POST['rej_bonus_mode'];
    
    $rej_allQryVls = "";

    if (!empty($frm_date)) {
        if (!empty($to_date)) {
            $rej_allQryVls .= " AND a.b_date>='$frm_date'";
        }else{
            $rej_allQryVls .= " AND a.b_date='$frm_date'";
        }
    }else{
        $rej_allQryVls .= "";
    }
    if (!empty($to_date)) {
        if (!empty($frm_date)) {
            $rej_allQryVls .= " AND a.b_date<='$to_date'";
        }else{
            $rej_allQryVls .= " AND a.b_date='$to_date'";
        }
    }else{
        $rej_allQryVls .= "";
    }
    if (!empty($bonus_type)) {
        $rej_allQryVls .= " AND a.b_type='$bonus_type'";
    }else{
        $rej_allQryVls .= "";
    }
    if (!empty($bonus_mode)) {
        $rej_allQryVls .= " AND a.b_mode='$bonus_mode'";
    }else{
        $rej_allQryVls .= "";
    }

    $bnsQry = mysqli_query($con,"SELECT a.id as ids,a.*,b.*,c.*,c.id as empIds FROM hr_bonus a, master_type_dtls b, mstr_emp c WHERE a.b_type=b.mstr_type_value AND a.b_status='6' AND a.created_by=c.id $rej_allQryVls ORDER BY a.id DESC");
    $bnsQry_results = mysqli_num_rows($bnsQry);
    if ($bnsQry_results>0) {
        $counter=0;
        while($rows=mysqli_fetch_object($bnsQry)){
            //********Approved User Access
            $getApproverList = getApproverList($con, $menuid, $rows->stage_no);
            if ($rows->created_by==$sessionid || $getApproverList==$sessionid) {
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
                           <a href="bonus_view.php?ids=<?=$rows->ids;?>&viewid=1" class="btn btn-primary btn-xs fw-bolder">View</a>
                        </td>
                    </tr>

<?php
            }
        }
    }else{
?>
                    <tr>
                        <td class="" colspan="11" style="text-align: center; font-size: 15px;">--- <span style="color: red;">Data Not Found</span> ---</td>
                    </tr>
<?php
    }
}

if ($action=='pnd_searchedBonuslist') {
    $frm_date = $_POST['pnd_frm_date'];
    $to_date = $_POST['pnd_to_date'];
    $bonus_type = $_POST['pnd_bonus_type'];
    $bonus_mode = $_POST['pnd_bonus_mode'];
    
    $pnd_allQryVls = "";

    if (!empty($frm_date)) {
        if (!empty($to_date)) {
            $pnd_allQryVls .= " AND a.b_date>='$frm_date'";
        }else{
            $pnd_allQryVls .= " AND a.b_date='$frm_date'";
        }
    }else{
        $pnd_allQryVls .= "";
    }
    if (!empty($to_date)) {
        if (!empty($frm_date)) {
            $pnd_allQryVls .= " AND a.b_date<='$to_date'";
        }else{
            $pnd_allQryVls .= " AND a.b_date='$to_date'";
        }
    }else{
        $pnd_allQryVls .= "";
    }
    if (!empty($bonus_type)) {
        $pnd_allQryVls .= " AND a.b_type='$bonus_type'";
    }else{
        $pnd_allQryVls .= "";
    }
    if (!empty($bonus_mode)) {
        $pnd_allQryVls .= " AND a.b_mode='$bonus_mode'";
    }else{
        $pnd_allQryVls .= "";
    }

    $bnsQry = mysqli_query($con,"SELECT a.id as ids,a.*,b.*,c.*,c.id as empIds FROM hr_bonus a, master_type_dtls b, mstr_emp c WHERE a.b_type=b.mstr_type_value AND (a.b_status='0' || a.b_status='2') AND a.created_by=c.id $pnd_allQryVls ORDER BY a.id DESC");
    $bnsQry_results = mysqli_num_rows($bnsQry);
    if ($bnsQry_results>0) {
        $counter=0;
        while($rows=mysqli_fetch_object($bnsQry)){
            //********Approved User Access
            $getApproverList = getApproverList($con, $menuid, $rows->stage_no);
            if ($rows->created_by==$sessionid || $getApproverList==$sessionid) {
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
                        <td class="" colspan="11" style="text-align: center; font-size: 15px;">--- <span style="color: red;">Data Not Found</span> ---</td>
                    </tr>
<?php
    }
}

if ($action=='rechk_searchedBonuslist') {
    $frm_date = $_POST['rechk_frm_date'];
    $to_date = $_POST['rechk_to_date'];
    $bonus_type = $_POST['rechk_bonus_type'];
    $bonus_mode = $_POST['rechk_bonus_mode'];
    
    $rechk_allQryVls = "";

    if (!empty($frm_date)) {
        if (!empty($to_date)) {
            $rechk_allQryVls .= " AND a.b_date>='$frm_date'";
        }else{
            $rechk_allQryVls .= " AND a.b_date='$frm_date'";
        }
    }else{
        $rechk_allQryVls .= "";
    }
    if (!empty($to_date)) {
        if (!empty($frm_date)) {
            $rechk_allQryVls .= " AND a.b_date<='$to_date'";
        }else{
            $rechk_allQryVls .= " AND a.b_date='$to_date'";
        }
    }else{
        $rechk_allQryVls .= "";
    }
    if (!empty($bonus_type)) {
        $rechk_allQryVls .= " AND a.b_type='$bonus_type'";
    }else{
        $rechk_allQryVls .= "";
    }
    if (!empty($bonus_mode)) {
        $rechk_allQryVls .= " AND a.b_mode='$bonus_mode'";
    }else{
        $rechk_allQryVls .= "";
    }

    $bnsQry = mysqli_query($con,"SELECT a.id as ids,a.*,b.*,c.*,c.id as empIds FROM hr_bonus a, master_type_dtls b, mstr_emp c WHERE a.b_type=b.mstr_type_value AND a.b_status='3' AND a.created_by=c.id $rechk_allQryVls ORDER BY a.id DESC");
    $bnsQry_results = mysqli_num_rows($bnsQry);
    if ($bnsQry_results>0) {
        $counter=0;
        while($rows=mysqli_fetch_object($bnsQry)){
            //********Approved User Access
            $getApproverList = getApproverList($con, $menuid, $rows->stage_no);
            if ($rows->created_by==$sessionid || $getApproverList==$sessionid) {
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
                           <a href="bonus_view.php?ids=<?=$rows->ids;?>&viewid=2" class="btn btn-primary btn-xs fw-bolder">View</a>
                        </td>
                    </tr>

<?php
            }
        }
    }else{
?>
                    <tr>
                        <td class="" colspan="11" style="text-align: center; font-size: 15px;">--- <span style="color: red;">Data Not Found</span> ---</td>
                    </tr>
<?php
    }
}

if ($action=='hold_searchedBonuslist') {
    $frm_date = $_POST['hold_frm_date'];
    $to_date = $_POST['hold_to_date'];
    $bonus_type = $_POST['hold_bonus_type'];
    $bonus_mode = $_POST['hold_bonus_mode'];
    
    $hold_allQryVls = "";

    if (!empty($frm_date)) {
        if (!empty($to_date)) {
            $hold_allQryVls .= " AND a.b_date>='$frm_date'";
        }else{
            $hold_allQryVls .= " AND a.b_date='$frm_date'";
        }
    }else{
        $hold_allQryVls .= "";
    }
    if (!empty($to_date)) {
        if (!empty($frm_date)) {
            $hold_allQryVls .= " AND a.b_date<='$to_date'";
        }else{
            $hold_allQryVls .= " AND a.b_date='$to_date'";
        }
    }else{
        $hold_allQryVls .= "";
    }
    if (!empty($bonus_type)) {
        $hold_allQryVls .= " AND a.b_type='$bonus_type'";
    }else{
        $hold_allQryVls .= "";
    }
    if (!empty($bonus_mode)) {
        $hold_allQryVls .= " AND a.b_mode='$bonus_mode'";
    }else{
        $hold_allQryVls .= "";
    }

    $bnsQry = mysqli_query($con,"SELECT a.id as ids,a.*,b.*,c.*,c.id as empIds FROM hr_bonus a, master_type_dtls b, mstr_emp c WHERE a.b_type=b.mstr_type_value AND a.b_status='4' AND a.created_by=c.id $hold_allQryVls ORDER BY a.id DESC");
    $bnsQry_results = mysqli_num_rows($bnsQry);
    if ($bnsQry_results>0) {
        $counter=0;
        while($rows=mysqli_fetch_object($bnsQry)){
            //********Approved User Access
            $getApproverList = getApproverList($con, $menuid, $rows->stage_no);
            if ($rows->created_by==$sessionid || $getApproverList==$sessionid) {
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
                        <td class="" colspan="11" style="text-align: center; font-size: 15px;">--- <span style="color: red;">Data Not Found</span> ---</td>
                    </tr>
<?php
    }
}
?>