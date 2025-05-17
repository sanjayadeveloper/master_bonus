<?php
    require_once('../../../config.php');
    require_once('../../../auth.php');
    include_once ('../../../workflownotif.php');
    include_once('../../../approvalmatrixfunction.php');

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    $ERP_SESS_ID = $_SESSION['ERP_SESS_ID'];
    $sessionid = $ERP_SESS_ID;
    $crntDate = date('Y-m-d');
    $crntMY = date('m-Y');
    $crntM = date('m');
    $crntY = date('Y');

}



if ($action=='sessionArrayCheck') {
    print_r($_SESSION);
}


if ($action=='modeCheck') {
    $ModeVls = $_POST['ModeVls'];

    //********************Approved Start
    $aprovQry = mysqli_query($con,"SELECT * FROM `hr_bonus` WHERE created_by='$ERP_SESS_ID' AND b_status=1 ORDER BY id DESC LIMIT 1");
    $aprovQry_results = mysqli_num_rows($aprovQry);

    if($aprovQry_results == 0){
        $res['dt'] = '0000-00';
    }else{
        $aprov_rows=mysqli_fetch_object($aprovQry);
        $aprov_mode = $aprov_rows->b_mode;
        if ($aprov_mode!=$ModeVls) {
            switch ($ModeVls) {
                case 'monthly':
                    if ($crntM==12) {
                        $sltMnth = '01';
                    }else{
                        $sltMnth = '0'.$crntM+1;
                    }
                    break;
                
                case 'quarterly':
                    if ($crntM >= 1 && $crntM <= 3) {
                        $sltMnth = '04';
                    }elseif ($crntM >= 4 && $crntM <= 6) {
                        $sltMnth = '07';
                    }elseif ($crntM >= 7 && $crntM <= 9) {
                        $sltMnth = 10;
                    }elseif ($crntM >= 10 && $crntM <= 12) {
                        $sltMnth = 1;
                    }
                    break;
                
                case 'half yearly':
                    if ($crntM >= 1 && $crntM <= 6) {
                        $sltMnth = '07';
                    }elseif ($crntM >= 7 && $crntM <= 12) {
                        $sltMnth = 1;
                    }
                    break;
                
                default:
                    $sltMnth = 1;
                    break;
            }
        }else{
            if ($crntM<10) {
                $crntM = $crntM+1;
            }else{
                $crntM = '0'.$crntM+1;
            }
            $sltMnth = '';
        }
        

        if ($sltMnth=='') {
            // $res['dt'] = date($crntM.'/Y');
        }elseif ($sltMnth==1) {
            $aprovCrntMY = date('Y', strtotime('+1 year'));
            // $res['dt'] = '01/'.$aprovCrntMY;
        }else{
            // $res['dt'] = date($sltMnth.'/Y');
        }
    }
    //********************Approved End

    $aprovQry_a = mysqli_query($con,"SELECT * FROM `master_type_dtls` WHERE mstr_type_value='$ModeVls'");
    $aprovQry_a_results = mysqli_num_rows($aprovQry_a);

    if($aprovQry_a_results == 0){
        $Error_message="NO RECORDS FOUND.";
    }else{
        $aprov_rows=mysqli_fetch_object($aprovQry_a);
        $res['type_name'] = $aprov_rows->mstr_type_name;
    }

    echo json_encode($res);
}


if ($action=='bonusRequestSubmit') {
    $bonusType = $_POST['bonusType'];
    $bonusOn = $_POST['bonusOn'];
    $bonusMode = $_POST['bonusMode'];
    $applicableForm = $_POST['applicableForm'];
    $bonusPer = $_POST['bonusPer'];
    $basedOn = $_POST['basedOn'];
    $bonusReMark = $_POST['bonusReMark'];
    
    $entry_date = date('Y-m-d H:i:s');
    $sessionid = $_SESSION['ERP_SESS_ID'];
    $ip_addr = $_SERVER['REMOTE_ADDR'];
    
    
    if (isset($_POST['update'])) {
        $bonus_id = $_POST['bonus_id'];
        $last_id = $bonus_id;
        $qry = "UPDATE `hr_bonus` SET `b_date`='$crntDate', `b_type`='$bonusType', `b_on`='$bonusOn', `b_mode`='$bonusMode', `applicable`='$applicableForm', `b_per`='$bonusPer', `based_on`='$basedOn', `b_status`='0', `stage_no`='0', `created_by`='$ERP_SESS_ID', `remark_msg`='$bonusReMark' WHERE `id`='$bonus_id'";
        $qryChk = mysqli_query($con, "SELECT * FROM hr_bonus WHERE id='$bonus_id'");
        $rowVls = mysqli_fetch_object($qryChk);
        $created_by = $rowVls->created_by;
    }else{
        $qry = "INSERT INTO `hr_bonus`(`b_date`, `b_type`, `b_on`, `b_mode`, `applicable`, `b_per`, `based_on`, `stage_no`, `created_by`, `remark_msg`) VALUES ('$crntDate','$bonusType','$bonusOn','$bonusMode','$applicableForm','$bonusPer','$basedOn','0','$ERP_SESS_ID','$bonusReMark')";
        $created_by = $sessionid;
    }

    // $qry = "INSERT INTO `hr_bonus`(`b_date`, `b_type`, `b_on`, `b_mode`, `applicable`, `b_per`, `based_on`, `stage_no`, `created_by`, `remarks`) VALUES ('$crntDate','$bonusType','$bonusOn','$bonusMode','$applicableForm','$bonusPer','$basedOn','0','$ERP_SESS_ID','$bonusReMark')";

    $sqlqry = mysqli_query($con, $qry);
    if ($sqlqry) {
        if($bonus_id!=''){
            $last_id = $bonus_id;
            $remarks = "Request Update";
        }else{
            $last_id = mysqli_insert_id($con);
            $remarks = "Request Raised";
        }
        $query1 = "INSERT INTO `hr_bonus_history` (`bns_id`,`bh_type`,`bh_on`,`bh_mode`,`bh_applicable`,`bh_per`,`bh_based_on`,`reason`,`created_by`,`action_on`,`action_by`,`remarks`,`stage_no`,`act_status`,`ip_addr`)VALUES('$last_id', '$bonusType', '$bonusOn', '$bonusMode', '$applicableForm', '$bonusPer', '$basedOn', '$bonusReMark', '$ERP_SESS_ID', '$entry_date', '$created_by', '$remarks', '0', '0', '$ip_addr')";
        $qryChk = mysqli_query($con,$query1);
        //--------Notification
        if (!isset($_POST['update'])) {
            $created_on = date('Y-m-d H:i:s');
            $url = 'master_hr/bonus/add_bonus_list.php';
            $menuid = getMenuById($con, $url);
            $stage_no = $nstatus = 0;
            $emp_id = $_SESSION['ERP_SESS_ID'];
            $deptid = getdeptid($con, $emp_id);
            $reciever = getapprover($con, $menuid, '0', '0', '', '', $deptid, '', $emp_id);
            $notificationmsg = "Bonus request type ".$bonusType." is pending for approve on your site.";
            saveNotice($con, $last_id, "Bonus Request", "0", "master_hr/bonus/manage_bonus_list.php", "0", $notificationmsg, $reciever, $emp_id, $created_on);
        }
        //--------/Notification
        $res = 1;
    }else{
        $res = 0;
    }
    echo $res;
}


?>