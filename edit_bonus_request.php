<?php
    require_once('../../config.php');
    require_once('../../auth.php');
    require_once('../../authadmin.php');
    // require_once('php_function\commom_fn.php');
    $ERP_SESS_ID = $_SESSION['ERP_SESS_ID'];
    $crntDate = date('Y-m-d');
    $crntMY = date('m-Y');
    $crntYM = date('Y-m');
    $crntM = date('m');
    $crntY = date('Y');
?>
<?php require_once '../../new_header.php' ?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.8/js/intlTelInput-jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/16.0.8/css/intlTelInput.css" />
<style type="text/css">
    .iti {
    position: relative;
    display: block;
}

.formTitle{
    background: #0b6fcc;
    color: #fff;
    font-size: 15px;
    font-weight: bold;
    padding: 3px 10px;
    border-radius: 15px;
}
.bonusMode{
    background: #9fc5e9;
    color: #000;
    border: 1px solid #000;
    padding: 4.5px 18px;
}
.bonusModeBody{
    border: 1px solid #000;
    padding: 3px 18px;
    font-weight: bold;
}
.modesTitle{
    font-size: 15px;
    text-align: center;
}

.bonusModeBody input{
    margin: 1px 0px 0px -17px;
    position: absolute;
}
.bgColorOfMode{
    background: #cfcccc !important;
}

.hiddenInput{
    display: none;
}

</style>

<?php
if (isset($_GET['ids'])) {
    $bns_id = $_GET['ids'];
    $viewid = $_GET['viewid'];
    $sqlqry = mysqli_query($con, "SELECT * FROM hr_bonus WHERE id='$bns_id'");
    $rows = mysqli_fetch_object($sqlqry);
    $up_b_type = $rows->b_type;
    $up_b_on = $rows->b_on;
    $up_b_mode = $rows->b_mode;
    $up_applicable = $rows->applicable;
    $up_b_per = $rows->b_per;
    $up_based_on = $rows->based_on;
    $up_remarks = $rows->remark_msg;

    if ($viewid==1) {
        $linkVls = 'add_bonus_list.php';
    }else{
        $linkVls = 'manage_bonus_list.php';
    }


}



//********************************

// $bnsQry = mysqli_query($con,"SELECT * FROM `hr_bonus` WHERE created_by='$ERP_SESS_ID' ORDER BY id DESC LIMIT 1");
// $bnsQry_results = mysqli_num_rows($bnsQry);

// if($bnsQry_results == 0){
//     $Error_message="NO RECORDS FOUND.";
// }else{
//     $b_rows=mysqli_fetch_object($bnsQry);
//     $b_status = $b_rows->b_status;
//     $b_mode = $b_rows->b_mode;
//     $applicable = $b_rows->applicable;
//     $appVls = explode('/', $applicable);
//     $dtVls = $appVls[1].'-'.$appVls[0];
//     if ($crntYM<=$dtVls) {
//         $btnCheck = 0;
//     }else{
//         $btnCheck = 1;
//     }
// }



?>






    <div id="page-wrapper">
        <section class="top-sec">
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <h4 class="m-1">Edit Bonus Request</h4>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                 <div class="list_icon text-right mt-1">
                     <a href="<?=$linkVls;?>"><i class="fa-solid fa-square-left fa-2xl"></i></a>
                   </div>
              </div>
            </div>



            <div class="row">
                <!-- <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"></div> -->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                   <div class="panel panel-default">
                        <div class="panel-body p-1">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin: 5px">
                                <!-- <div class="formTitle">Add Bonus Form</div> -->
                                <br/>
                                 <!-- onSubmit="" -->
                                <form class="form-row" name="bonusRequestFormData" id="bonusRequestFormData">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input type="hidden" name="bonus_hidden_id" id="bonus_hidden_id" value="<?php echo $bns_id;?>">
                                        <input type="hidden" name="viewids" id="viewids" value="<?php echo $viewid;?>">
                                        <div class="form-group">
                                            <label>Bonus Type</label>
                                            <select class="form-control" name="bonusType" id="bonusType" onchange="getMsgOnKeyup(this.form, this.id)" errorMsg="Please select your Bonus Type...!!">
                                                <option value="">---Select---</option>
                                                <?php
                                                    $result = mysqli_query($con,"SELECT * FROM `master_type_dtls` WHERE parent_name='master-bonustype' ORDER BY `id` DESC");
                                                    $total_results = mysqli_num_rows($result);

                                                    if($total_results == 0){ $Error_message="NO RECORDS FOUND."; }           // Set For Record Not Found 

                                                    $i=1; 
                                                    while($fetch=mysqli_fetch_object($result)) {
                                                ?>
                                                    <option value="<?php echo $fetch->mstr_type_value;?>" <?php if($up_b_type==$fetch->mstr_type_value){echo "selected";} ?>><?php echo $fetch->mstr_type_name;?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Bonus Calc On</label>
                                            <select class="form-control" name="bonusOn" id="bonusOn" onchange="getMsgOnKeyup(this.form, this.id)" errorMsg="Please select your Bonus Calc On...!!">
                                                <option value="">---Select---</option>
                                                <option value="CTC" <?php if($up_b_on=='CTC'){echo "selected";} ?>>CTC</option>
                                                <option value="Gross" <?php if($up_b_on=='Gross'){echo "selected";} ?>>Gross</option>
                                                <option value="Net Pay" <?php if($up_b_on=='Net Pay'){echo "selected";} ?>>Net Pay</option>
                                                <option value="Basic" <?php if($up_b_on=='Basic'){echo "selected";} ?>>Basic</option>
                                            </select>
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Bonus Mode</label>
                                            <select class="form-control" name="bonusMode" id="bonusMode" onchange="getMsgOnKeyup(this.form, this.id)" errorMsg="Please select your Bonus Mode...!!">
                                                <option value="">---Select---</option>
                                                <?php
                                                    $result = mysqli_query($con,"SELECT * FROM `master_type_dtls` WHERE parent_name='master-bonusmode'");
                                                    $total_results = mysqli_num_rows($result);

                                                    if($total_results == 0){ $Error_message="NO RECORDS FOUND."; }           // Set For Record Not Found 

                                                    $i=1; 
                                                    while($fetch=mysqli_fetch_object($result)) {
                                                ?>
                                                    <option value="<?php echo $fetch->mstr_type_value;?>" <?php if($up_b_mode==$fetch->mstr_type_value){echo "selected";} ?>><?php echo $fetch->mstr_type_name;?></option>
                                                <?php
                                                    }
                                                ?>
                                                <!-- <option value="1">Monthly</option>
                                                <option value="2">Quarterly</option>
                                                <option value="3">Half Yearly</option>
                                                <option value="4">Yearly</option> -->
                                            </select>
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Applicable From</label>
                                            
                                            <div class="input-group date" id="datetimepicker1">
                                                <input type="hidden" id="applFormHidden" value="<?=$up_applicable;?>">
                                                <input type="text" class="form-control applicableForm" name="applicableForm" id="bns_1" placeholder="Select Date" value="<?=$up_applicable;?>" autocomplete="off" onkeyup="getMsgOnKeyupSingle(this.value)" onblur="applicableFormFn(this.value)">
                                                <span class="input-group-addon">
                                                  <i class="fa-duotone fa-calendar-lines"></i>
                                                </span>
                                            </div>
                                            <br/><span style='color: red; position: absolute; margin: -15px 0px 0px 5px;' id='applicableFormMsg'></span>
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Bonus(%)</label>
                                            <input type="number" class="form-control" placeholder="Enter Bonus Percentage" name="bonusPer" id="bonusPer" step="0.01" min="0" max="100" onkeyup="getMsgOnKeyup(this.form, this.id)" errorMsg="Please select your Bonus(%)...!!" value="<?=$up_b_per;?>">
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Based On</label>
                                            <select class="form-control" name="basedOn" id="basedOn" onchange="getMsgOnKeyup(this.form, this.id)" errorMsg="Please select your Based On...!!">
                                                <option value="">---Select---</option>
                                                <option value="Attendance" <?php if($up_based_on=='Attendance'){echo "selected";} ?>>Attendance</option>
                                            </select>
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="row">
                                           <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
                                           <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <?php
                                                    $result = mysqli_query($con,"SELECT * FROM `master_type_dtls` WHERE parent_name='master-bonusmode' AND mstr_type_value='$up_b_mode'");
                                                    $fetch=mysqli_fetch_object($result);
                                                    ?>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bonusMode">
                                                        <div class="modesTitle" id="modesTitle"><?=$fetch->mstr_type_name;?></div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusMode">
                                                        From
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusMode">
                                                        To
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="row getMonthDiv" id="getMonthDiv">
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                January
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                January
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                February
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                February
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                March
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                March
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                April
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                April
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                May
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                May
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                June
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                June
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                July
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                July
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                August
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                August
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                September
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                September
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                October
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                October
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                November
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                November
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                December
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 bonusModeBody">
                                                                December
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                           </div>
                                       </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <br/>
                                        <div class="form-group">
                                            <label>Remark</label>
                                            <textarea class="form-control" placeholder="Enter Remarks" name="bonusReMark" id="bonusReMark" onkeyup="getMsgOnKeyup(this.form, this.id)" errorMsg="Please select your Remark...!!"><?=$up_remarks;?></textarea>
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <br/>
                                        <?php
                                            if ($bns_id != '') {
                                        ?>
                                            <button type="button" class="btn btn-success" onclick="bonusRequestSubmit(this.form, 'update')">Submit</button>
                                        <?php
                                            }
                                        ?>
                                        
                                    </div>
                                </form>
                            </div>  
                         </div>
                    </div>
                </div>
            </div>

        </section>
    </div>

    <script>
        //countrycode js ------------>
        $(function() {
          $("#country").change(function() {
            let countryCode = $(this).find('option:selected').data('country-code');
            let value = "+" + $(this).val();
            $('#txtPhone').val(value).intlTelInput("setCountry", countryCode);
          });
          
          var code = "+91";
          $('#txtPhone').val(code).intlTelInput();
        });


    </script>
    <!-- /#page-wrapper -->
<?php require_once('../../new_footer.php'); ?>

<script type="text/javascript">
$(document).ready(function(){
    applicableFormFn('<?=$up_applicable;?>');
});
</script>

<script>
    $('#bns_1').datetimepicker({
        allowInputToggle: true,
        showClose: true,
        showClear: true,
        showTodayButton: true,
        format: 'YYYY-MM',
        icons: {
            time:'fa-sharp fa-solid fa-alarm-clock',
            date:'fa-duotone fa-trash-can',
            up:'fa fa-chevron-up',
            down:'fa fa-chevron-down',
            previous:'fa fa-chevron-left',
            next:'fa fa-chevron-right',
            today:'fa fa-chevron-up',
            clear:'fa fa-trash',
            close:'fa fa-close'
        },
    });
</script>

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> -->
<script src="js_function/js_function.js"></script>
<script src="js_function/commom_fn.js"></script>


   
  




