<?php
    require_once('../../config.php');
    require_once('../../auth.php');
    require_once('../../authadmin.php');
    // require_once('php_function\commom_fn.php');
?>
<?php require_once '../../new_header.php';

    $ERP_SESS_ID = $_SESSION['ERP_SESS_ID'];
    $crntDate = date('Y-m-d');
    $crntMY = date('m-Y');
    $crntYM = date('Y-m');
    $crntM = date('m');
    $crntY = date('Y');
 ?>

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
        $bnsQry = mysqli_query($con,"SELECT * FROM `hr_bonus` WHERE created_by='$ERP_SESS_ID' ORDER BY id DESC LIMIT 1");
        $bnsQry_results = mysqli_num_rows($bnsQry);

        if($bnsQry_results == 0){
            $btnCheck = 1;
            $Error_message="NO RECORDS FOUND.";
        }else{
            $b_rows=mysqli_fetch_object($bnsQry);
            $b_status = $b_rows->b_status;
            $b_mode = $b_rows->b_mode;
            $applicable = $b_rows->applicable;
            $appVls_a = explode('-', $applicable);
            if ($appVls_a[1]!='' || $appVls_a[1]!=null) {
                $dtVls = $appVls_a[0].'-'.$appVls_a[1];
            }
            if ($b_status!=6) {
                if ($crntYM>=$dtVls) {
                    $btnCheck = 1;
                }else{
                    $btnCheck = 0;
                }
            }else{
                $btnCheck = 1;
            }
        }


        //********************Approved Start
        $aprovQry = mysqli_query($con,"SELECT * FROM `hr_bonus` WHERE created_by='$ERP_SESS_ID' AND b_status=1 ORDER BY id DESC LIMIT 1");
        $aprovQry_results = mysqli_num_rows($aprovQry);

        if($aprovQry_results == 0){
            $Error_message="NO RECORDS FOUND.";
            $aprovCrntMY = '0000-00';
        }else{
            $aprov_rows=mysqli_fetch_object($aprovQry);
            $aprov_mode = $aprov_rows->b_mode;
            $applicable = $aprov_rows->applicable;
            $applicable = explode('-',$applicable);
            $crntM = $applicable[1];
            switch ($aprov_mode) {
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

            if ($sltMnth==1) {
                // $aprovCrntMY = date('Y', strtotime('+1 year'));
                $aprovCrntMY = $applicable[0]+1;
                $aprovCrntM = $applicable[1]-1;
                if ($aprovCrntM < 10) {
                    $aprovCrntM = '0'.$aprovCrntM;
                }
                $aprovCrntMY = $aprovCrntMY.'-'.$aprovCrntM;
            }else{
                // $aprovCrntMY = date('Y');
                $aprovCrntMY = $applicable[0];
                $aprovCrntMY = $aprovCrntMY.'-'.$sltMnth;
            }

        }
        //********************Approved End
?>


    <div id="page-wrapper">
        <section class="top-sec">
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <h4 class="m-1">Add Bonus Request</h4>
              </div>
              <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                 <div class="list_icon text-right mt-1">
                     <a href="add_bonus_list.php"><i class="fa-solid fa-square-left fa-2xl"></i></a>
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
                                                    <option value="<?php echo $fetch->mstr_type_value;?>"><?php echo $fetch->mstr_type_name;?></option>
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
                                                <option value="CTC">CTC</option>
                                                <option value="Gross">Gross</option>
                                                <option value="Net Pay">Net Pay</option>
                                                <option value="Basic">Basic</option>
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
                                                    <option value="<?php echo $fetch->mstr_type_value;?>"><?php echo $fetch->mstr_type_name;?></option>
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
                                                <input type="hidden" id="applFormHidden" value="<?=$aprovCrntMY;?>">
                                                <input type="text" class="form-control applicableForm" name="applicableForm" id="bns_1" placeholder="Select Date" value="<?=$aprovCrntMY;?>" autocomplete="off" onkeyup="getMsgOnKeyupSingle(this.value)" onblur="applicableFormFn(this.value)">
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
                                            <input type="number" class="form-control" placeholder="Enter Bonus Percentage" name="bonusPer" id="bonusPer" step="0.01" min="0" max="100" onkeyup="getMsgOnKeyup(this.form, this.id)" errorMsg="Please select your Bonus(%)...!!">
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label>Based On</label>
                                            <select class="form-control" name="basedOn" id="basedOn" onchange="getMsgOnKeyup(this.form, this.id)" errorMsg="Please select your Based On...!!">
                                                <option value="">---Select---</option>
                                                <option value="Attendance">Attendance</option>
                                            </select>
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="row">
                                           <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"></div>
                                           <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bonusMode">
                                                        <div class="modesTitle" id="modesTitle">MONTHLY</div>
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
                                            <textarea class="form-control" placeholder="Enter Remarks" name="bonusReMark" id="bonusReMark" onkeyup="getMsgOnKeyup(this.form, this.id)" errorMsg="Please select your Remark...!!"></textarea>
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <br/>
                                        <?php

                                        // echo 'dtVls :- '.$dtVls;
                                        // echo 'btnCheck :- '.$crntYM;
                                            // if ($btnCheck==1) {
                                        ?>
                                            <button type="button" class="btn btn-success" onclick="bonusRequestSubmit(this.form)">Submit</button>
                                        <?php
                                            // }
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
    <?php require_once('../../new_footer.php'); ?>
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


   
  




