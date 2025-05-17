$('#bonusPer_backup').keypress(function (e) {
    var regex = new RegExp(/^[0-9\.]+$/);
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }
    else {
        e.preventDefault();
        return false;
    }
});



//*************Demo code for getSingleMontsList()
    /*
    var getVls = getSingleMontsList(1,0);

    var mVls = getVls.split(',');
    var tag = '';
    var num = 1;
    for (var i = 0; i < mVls.length; i++) {
        if (num!=mVls.length) {
            tag += '<li>'+mVls[i]+'</li>\n';
        }
        num++;
    }
    console.log(tag);
    */
//*************Demo code for getSingleMontsList


function getSingleMontsList(months,ModeConut='0'){
    var stMth = months;
    var endMth = ModeConut;
    var resVls = '';
    var checkMonth = 0;
    for (var i = 0; i < 12; i++) {
        if (checkMonth != stMth) {
            var gapCnt = parseInt(stMth)+parseInt(endMth);
            if (gapCnt>12) {
                var getResult = parseInt(gapCnt)-12;
            }else{
                var getResult = gapCnt;
            }
            var firstMonth = getMonthName(stMth);
            var stMth = parseInt(gapCnt)+1;
            if (stMth>12) {
                var stMth = parseInt(stMth)-12;
            }else{
                var stMth = stMth;
            }
            resVls += firstMonth+',';
        }
        checkMonth = months;        
    }
 return resVls;

}



function getMontsList(months,ModeConut){
    var stMth = months;
    var endMth = ModeConut;
    var resVls = '';
    var checkMonth = 0;
    for (var i = 0; i < 12; i++) {
        if (checkMonth != stMth) {
            var gapCnt = parseInt(stMth)+parseInt(endMth);
            if (gapCnt>12) {
                var getResult = parseInt(gapCnt)-12;
            }else{
                var getResult = gapCnt;
            }
            var firstMonth = getMonthName(stMth);
            var lastMonth = getMonthName(getResult);
            var stMth = parseInt(gapCnt)+1;
            if (stMth>12) {
                var stMth = parseInt(stMth)-12;
            }else{
                var stMth = stMth;
            }
            resVls += firstMonth+'-'+lastMonth+',';
        }
        checkMonth = months;        
    }
 return resVls;

}




function getMonthName(n){
    if (n==1) {
        var monthVls = 'January';
    }else if (n==2) {
        var monthVls = 'February';
    }else if (n==3) {
        var monthVls = 'March';
    }else if (n==4) {
        var monthVls = 'April';
    }else if (n==5) {
        var monthVls = 'May';
    }else if (n==6) {
        var monthVls = 'June';
    }else if (n==7) {
        var monthVls = 'July';
    }else if (n==8) {
        var monthVls = 'August';
    }else if (n==9) {
        var monthVls = 'September';
    }else if (n==10) {
        var monthVls = 'October';
    }else if (n==11) {
        var monthVls = 'November';
    }else if (n==12) {
        var monthVls = 'December';
    }
    return monthVls;
}





// ************************** Validation

function getMsgOnKeyup(formName, ids='0'){
    var thisVls;
    if (ids==0) {
        // var div = $('#checkVlsOnSubmit').is(":visible");
        var div = $('#checkVlsOnSubmit').val();
        if (div == undefined) {
            var month = jQuery('<input/>');
            month.attr('type', 'hidden');
            month.attr('name', 'checkVlsOnSubmit');
            month.attr('id', 'checkVlsOnSubmit');
            month.attr('value', '1');
            month.appendTo(formName);
            thisVls = $('#checkVlsOnSubmit').val();
        }else{
            thisVls = $('#'+ids).val();
        }
    }else{
        thisVls = $('#'+ids).val();
    }
    if (thisVls=='' || thisVls==null) {
        var numVls = 0;
    }else{
        var numVls = 1;
    }
    var idsName = ids+'_msg';
    var msgVls = $('#'+ids).attr('errorMsg');
    var checkNum = formName.elements['checkVlsOnSubmit'].value;
    showHideErrorMsg(ids, idsName, numVls, msgVls, checkNum);
}

function showHideErrorMsg(ids, idsName, numVls, msgVls, checkNum){
    if (checkNum==1) {
        if (numVls=='0') {
            var div = $('#'+idsName).is(":visible");
            if (div == false) {
                $("#"+ids).css("border", "2px solid #ec1313");
                $("#"+ids).after("<br id='"+idsName+"_br'/><span style='color: red; position: absolute; margin: -15px 0px 0px 5px;' id='"+idsName+"'></span>");
            }
            $('#'+idsName).html(msgVls);
        }else{
            $("#"+ids).css("border", "1px solid #ccc");
            // $("#"+ids).show().delay(6000).fadeOut();
            $("#"+idsName).remove();
            $("#"+idsName+"_br").remove();
            $('#'+idsName).html('');
        }
    }
}



//**************Multiple Validation (Custom Code)
function checkMultiVls(){
        var eduSlVlsChange = $('#eduSlVlsChange').val();
        var checkVlsOnSubmit = $('#checkVlsOnSubmit').val();
        var checkValidation;
        if (checkVlsOnSubmit==1) {
            var checkEduVls=[];
            for (var i = 0; i <= eduSlVlsChange; i++) {
                var board_name = $('#board_name_'+i).val();
                var course_name = $('#course_name_'+i).val();
                var percentage = $('#percentage_'+i).val();
                if (board_name!=undefined) {
                    if (board_name=='' || course_name=='' || percentage=='') {
                        checkEduVls.push(0);
                    }else{
                        checkEduVls.push(1);
                    }
                }
            }
            //-------------------
            if(checkEduVls.includes(0)==true){
                $('#academicErrorMsg').html('Please fillup your Academic Details...!!');
                checkValidation=0;
            }else{
                $('#academicErrorMsg').html('');
                checkValidation=1;
            }
            return checkValidation;
        }
    }



//*********************** CheckBox Check

//--------Save check values Start

/*
<input type="hidden" id="getCount">
<input type="hidden" id="idsVls_bonus">
<input type="hidden" id="saveAllIdsVls_bonus">
<input type="checkbox" class="bonusAll" id="bonusCheck" value="" onclick="allCheckFn('All','bonus'), saveIdsVls('All', '0','bonus')" style="position: absolute;">
<input type="checkbox" class="bonusCheck" name="anyKeys[]" id="listId_<?=$i?>" value="<?=$idVls?>" onclick="allCheckFn(<?=$i?>,'bonus'), saveIdsVls(<?=$i?>, <?=$idVls?>,'bonus')">
*/

function allCheckFn(vls,keys){;
    var ele=document.getElementsByClassName(keys+'Check');
    var eleId=document.getElementById(keys+'Check');
    if (vls=='All') {
        for(var i=0; i<ele.length; i++){
            if(ele[i].type=='checkbox'){
                if (eleId.checked==true) {
                    ele[i].checked=true;
                }else{
                    ele[i].checked=false;
                }
            }  
        }
    }else{
        var counter=0;
        for(var i=0; i<ele.length; i++){
            if(ele[i].type=='checkbox'){
                if (ele[i].checked==true) {
                    counter++;
                }
            }  
        }
        if (ele.length==counter) {
            eleId.checked=true;
        }else{
            eleId.checked=false;
        }
    }
}



function saveIdsVls(slNo, ids, keys){
    var saveAllIdsVls = $('#saveAllIdsVls_'+keys).val();
    var saveId = $('#idsVls_'+keys).val();
    var listSlIdVls = $('#listId_'+slNo).val();
    var allCheck = document.getElementById(keys+'Check').checked;

    if (slNo=='All') {
        if (allCheck==true) {
            $('#idsVls_'+keys).val(saveAllIdsVls);
        }else{
            $('#idsVls_'+keys).val('');
        }
    }else{
        var listSlId = document.getElementById('listId_'+slNo).checked;
        if (saveId!='') {
            if (listSlId==true) {
                saveId += ', '+ids;
                $('#idsVls_'+keys).val(saveId);
            }else{
                var saveIdSpl = saveId.split(',');
                if (saveIdSpl[1]!=undefined && saveIdSpl[1]!='') {
                    var datas = '';
                    var counter=0;
                    for (var i = 0; i < saveIdSpl.length; i++) {
                        counter=i+1;
                        if (saveIdSpl[i].trim()!=listSlIdVls.trim()) {
                            if (saveIdSpl.length==counter) {
                                datas += saveIdSpl[i];
                            }else{
                                datas += saveIdSpl[i]+', ';
                            }
                        }
                    }
                    $('#idsVls_'+keys).val(datas);
                }else{
                    if (saveIdSpl[0]==saveId) {
                        $('#idsVls_'+keys).val('');
                    }else{
                        $('#idsVls_'+keys).val(ids);
                    }
                }
            }
        }else{
            $('#idsVls_'+keys).val(ids);
        }
    }
}

//--------Save check values End




//********************Date
$('#id_date').datetimepicker({
    allowInputToggle: true,
    showClose: true,
    showClear: true,
    showTodayButton: true,
    format: "DD/MM/YYYY",
    //format: "DD/MM/YYYY hh:mm:ss A",
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




