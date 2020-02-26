/***************************/
//@Author: Adrian "yEnS" Mato Gondelle & Ivan Guardado Castro
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

$(document).ready(function () {
    //global vars
    var form = $("#customForm");

    //infant/sample id
    var requestno_no = $("#requestno_no");
    var requestno_year = $("#requestno_year");

    var nmrlstampno = $("#nmrlstampno");
    var nmrlstampnoInfo = $("#nmrlstampnoInfo");

    var pidInfo = $("#pidInfo");
    var confirmReqNo = $("#confirmReqNo");
    //infant/sample id
    var age = new Date();
    age = $("#dateofbirth");
    var ageInfo = $("#ageInfo");
    //no of spots
    var spot = $("#sspot");
    var spotInfo = $("#spotInfo");
    //recieved status
    var srecstatus = $("#srecstatus");
    var recstatusInfo = $("#recstatusInfo");
    //mother hiv
    var mhivstatus = $("#mhivstatus");
    var mhivstatusInfo = $("#mhivstatusInfo");
    //mother feeding
    var mbfeeding = $("#breastfeeding");
    var mbfeedingInfo = $("#mbfeedingInfo");
    //mother drug
    var mdrug = $("#mdrug");
    var mdrugInfo = $("#mdrugInfo");
    //infant prophylaxis
    var delivery = $("#delivery");
    var deliveryInfo = $("#deliveryInfo");
    //entry point
    var mentpoint = document.getElementsByName('mentpoint');
    var entrypointInfo = $("#entrypointInfo");
    //Test reason
    var testreason = document.getElementsByName('testreason');
    var testreasonInfo = $("#testreasonInfo");

    //Date collected
    var sdoc = $("#datecollected");
    var sdocInfo = $("#sdocInfo");
    //Date received
    var sdrec = $("#datereceived");
    var sdrecInfo = $("#sdrecInfo");
    //Date dispatched
    var pgender = $("#pgender");
    var pgenderInfo = $("#pgenderInfo");


    //rejectedreason
    var isRejected = $("#reject");
    var rejectedreason = $("#slctRejectedReason");
    var rejectedreasonInfo = $("#rejectedreasonInfo");
    //repeatreason
    var repeatreason = $("#repeatreason");
    var repeatreasonInfo = $("#repeatreasonInfo");
    //Facility
    var facility = $(".dhx_combo_input");
    var facilityInfo = $("#facilityInfo");

    //The form save button
    var addonly = $("#addonly");
    var saveadd = $("#saveadd");

    var result = $("#infantresult");
    var resultInfo = $("#infantresultInfo");

    var reqYear2 = $("#requestno_year2");
    var reqNo2 = $("#requestno_no2");
    var reqYear = $("#requestno_year");
    var reqNo = $("#requestno_no");

    //On blur	
    requestno_no.blur(validaterequestno_no);
    reqYear2.blur(validaterequestno_confirm);
    reqNo2.blur(validaterequestno_confirm);
    nmrlstampno.blur(validatenmrlstampno);
    requestno_year.blur(validaterequestno_year);
    age.blur(validateAge);
    spot.blur(validateSpot);
    srecstatus.blur(validateSrecstatus);
    mhivstatus.blur(validateMhivstatus);
    mbfeeding.blur(validateMbfeeding);
    mdrug.blur(validateMdrug);
    delivery.blur(validatedelivery);
    //mentpoint.blur(validateMentpoint);
    sdoc.blur(validateDatecollected);
    sdrec.blur(validateDateReceived);
    pgender.blur(validatepgender);
    sdrec.blur(validateDatecollected);
    //sdrec.blur(validateDaterecived);
    sdoc.blur(validateDatecollected);
    //facility.blur(validateFacility);
    result.blur(validateResult);
    //rejectedreason.blur(validateRejectedRepeatReasons);
    //repeatreason.blur(validateRejectedRepeatReasons);
    //
    //On key press
    requestno_no.keyup(validaterequestno_no);
    reqYear2.keyup(validaterequestno_confirm);
    reqNo2.keyup(validaterequestno_confirm);
    //nmrlstampno.keyup(validatenmrlstampno);
    requestno_year.keyup(validaterequestno_year);
    age.keyup(validateAge);
    spot.keyup(validateSpot);
    srecstatus.keyup(validateSrecstatus);
    mhivstatus.keyup(validateMhivstatus);
    mbfeeding.keyup(validateMbfeeding);
    mdrug.keyup(validateMdrug);
    delivery.keyup(validatedelivery);
    //mentpoint.keyup(validateMentpoint);
    sdoc.keyup(validateDatecollected);
    sdrec.keyup(validateDateReceived);
    pgender.keyup(validatepgender);
    sdrec.keyup(validateDatecollected);
    //sdrec.keyup(validateDaterecived);
    sdoc.keyup(validateDatecollected);
    //facility.keyup(validateFacility);
    result.keyup(validateResult);
    //rejectedreason.keyup(validateRejectedRepeatReasons);
    //repeatreason.keyup(validateRejectedRepeatReasons);
    //ON CLICK
    age.click(validateAge);
    sdoc.click(validateDatecollected);
    sdrec.click(validateDateReceived);
    pgender.click(validatepgender);
    sdrec.click(validateDatecollected);
    //sdrec.click(validateDaterecived);
    sdoc.click(validateDatecollected);

    // confirmatorypcr.click(validateConfirmatorypcr);
    //on chnage
    age.change(validateAge);
    sdoc.change(validateDatecollected);
    sdrec.change(validateDateReceived);
    pgender.change(validatepgender);

    sdrec.change(validateDatecollected);
    //sdrec.change(validateDaterecived);
    sdoc.change(validateDatecollected);

    //rejectedreason.change(validateRejectedRepeatReasons);
    //repeatreason.change(validateRejectedRepeatReasons);

    //& validateDaterecived()
    addonly.click(function () {
        if (validaterequestno_no() & validaterequestno_year() & validaterequestno_confirm() & validatedelivery() & validateMbfeeding() & validateDatecollected() & validateDateReceived() & validateDatecollected() & validateMentpoint() & validateTestReason() & validatenmrlstampno() & validateRejectedReason() & validateResult())
        {
            $("#addOnlyInd").val("true");
            form.submit();
            return true;
        } else
        {
            alert("Please enter the required fields!");
            validaterequestno_no();
            validaterequestno_confirm();
            validaterequestno_year();
            validatedelivery();
            validateMbfeeding();
            validateDatecollected();
            validateDateReceived();
            validatenmrlstampno();
            //validateFacility();
            validateDatecollected();
            //validateDaterecived();
            validateMentpoint();
            validateTestReason();
            validateRejectedReason();
            validateResult();
            return false;
        }
    });

    // & validateDaterecived()
    saveadd.click(function () {
        if (validaterequestno_no() & validaterequestno_year() & validaterequestno_confirm() & validatedelivery() & validateMbfeeding() & validateDatecollected() & validateDateReceived() & validatenmrlstampno() & validateDatecollected() & validateMentpoint() & validateTestReason() & validateRejectedReason() & validateResult())
        {
            $("#addSaveInd").val("true");
            form.submit();
            return true;
        } else
        {
            alert("Please enter the required fields!");
            validaterequestno_no();
            validaterequestno_confirm();
            validaterequestno_year();
            validatedelivery();
            validateMbfeeding();
            validateDatecollected();
            validateDateReceived();
            validatenmrlstampno();
            //validateFacility();
            validateDatecollected();
            //validateDaterecived();
            validateMentpoint();
            validateTestReason();
            validateRejectedReason();
            validateResult();
            return false;
        }
    });
    //On Submitting validateCat() && validateDatepicker()& validateRepeatforrejection()& validateRejectedRepeatReasons() & validateMhivstatus() & validateMbfeeding() & validateMdrug() & validatedelivery() &validateMentpoint()
    //    form.submit(function()
    //    {//& validateAge()
    //        if( validaterequestno_no() & validaterequestno_year()   & validatedelivery() & validateMbfeeding()  & validateDatecollected() & validateDateReceived()  & validatenmrlstampno() & validateDatecollected() &  validateDaterecived())
    //        {
    //            return true;
    //        }
    //        else
    //        {
    //            return false;
    //        }
    //    });

    function validateDatecollected() {
        //if it's NOT valid
        var stDate = new Date(age.val());
        var enDate = new Date(sdoc.val());
        var compDate = enDate.getTime() - stDate.getTime();
        if (compDate <= 0) {
            sdocInfo.addClass("error");
            sdocInfo.text("Please Enter Date greater than DOB.");
            sdocInfo.addClass("error");
            return false;
        }
        //if it's valid
        else {
            sdoc.removeClass("error");
            sdocInfo.text("");
            sdocInfo.removeClass("error");
            return true;
        }
    }

    function validateDaterecived() {
        //if it's NOT valid
        var rcDate = new Date(sdrec.val());
        var colDate = new Date(sdoc.val());
        var ageDate = new Date(age.val());

        //if( sdrec.val()<=sdoc.val()){
        if (rcDate <= colDate) {
            sdrec.addClass("error");
            sdrecInfo.text("Please Enter Date greater than DBS taken date.");
            sdrecInfo.addClass("error");
            return false;
        } else if (rcDate <= ageDate) {
            sdrec.addClass("error");
            sdrecInfo.text("Please Enter Date greater than DOB.");
            sdrecInfo.addClass("error");
            return false;
        }
        //if it's valid
        else {
            sdrec.removeClass("error");
            sdrecInfo.text("");
            sdrecInfo.removeClass("error");
            return true;
        }
    }

    //Facility validation
//    function validateFacility(){
//        //if it's NOT valid
//        if(facility.val().length < 1){
//            facility.addClass("error");
//            facilityInfo.text("Please enter referring facility!");
//            facilityInfo.addClass("error");
//            return false;
//        }
//        //if it's valid
//        else{
//            facility.removeClass("error");
//            facilityInfo.text("");
//            facilityInfo.removeClass("error");
//            return true;
//        }
//    }	

    //ensure facility selected
    function validateDatepicker() {
        //if it's NOT valid
        if (datepicker.val().length < 1) {
            datepicker.addClass("error");
            datepickerInfo.text("Please Enter DOB!");
            datepickerInfo.addClass("error");
            return false;
        }
        //if it's valid
        else {
            datepicker.removeClass("error");
            datepickerInfo.text("");
            datepickerInfo.removeClass("error");
            return true;
        }
    }

    //ensure infant/sample code not null
    function validatepgender()
    {

        //if it's NOT valid
        if (pgender.val().length < 1) {
            pgender.addClass("error");
            pgenderInfo.text("Please  Select Gender!");
            pgenderInfo.addClass("error");
            return false;
        }
        //if it's valid
        else {
            pgender.removeClass("error");
            pgenderInfo.text("");
            pgenderInfo.removeClass("error");
            return true;
        }
    }

    //ensure infant/sample code not null
    function validaterequestno_no()
    {

        //if it's NOT valid
        if (requestno_no.val().length < 1)
        {
            requestno_no.addClass("error");
            pidInfo.text("Please  enter #!");
            pidInfo.addClass("error");
            return false;
        }

        //if it's valid
        else {
            requestno_no.removeClass("error");
            pidInfo.text("");
            pidInfo.removeClass("error");
            return true;
        }
    }

    //ensure infant/sample code not null
    function validatenmrlstampno()
    {
        if (nmrlstampno.val() != undefined) {
            //if it's NOT valid
            if (nmrlstampno.val().length < 1)
            {
                nmrlstampno.addClass("error");
                nmrlstampnoInfo.text("Please enter Lab STAMP NO");
                nmrlstampnoInfo.addClass("error");
                return false;
            }

            //if it's valid
            else {
                nmrlstampno.removeClass("error");
                nmrlstampnoInfo.text("");
                nmrlstampnoInfo.removeClass("error");
                return true;
            }
        } else
        {
            return true;
        }
    }

    //ensure infant/sample code not null
    function validaterequestno_year()
    {

        var a = $("#requestno_year").val();
        var filter = /^[0-9_.]+$/;
        //if it's NOT valid

        if ((requestno_year.val().length > 4) || (requestno_year.val().length < 4) || (!(filter.test(a))))  //if it's NOT valid
        {
            requestno_year.addClass("error");
            pidInfo.text("Please  enter Valid Year!");
            pidInfo.addClass("error");
            return false;

        } else { //if it's valid email
            requestno_year.removeClass("error");
            pidInfo.text("");
            pidInfo.removeClass("error");
            return true;
        }
    }

    function validaterequestno_confirm()
    {
        if ((reqYear.val() !== reqYear2.val()) || (reqNo.val() !== reqNo2.val())) 
        {
            reqYear2.addClass("error");
            reqNo2.addClass("error");
            confirmReqNo.text("Enter same values as above!");
            confirmReqNo.addClass("error");
            return false;

        } else { 
            reqYear2.removeClass("error");
            reqNo2.removeClass("error");
            confirmReqNo.text("");
            confirmReqNo.removeClass("error");
            return true;
        }
    }

    //ensure infant/sample code not null
    function validateAge()
    {


        if (age.val().length < 1)
        {
            age.addClass("error");
            ageInfo.text("Please  enter Valid DOB!");
            ageInfo.addClass("error");
            return false;

        } else
        {
            age.removeClass("error");
            ageInfo.text("");
            ageInfo.removeClass("error");
            return true;
        }
    }

    //ensure mother hiv
    function validateMhivstatus() {
        //if it's NOT valid
        if (mhivstatus.val().length < 1) {
            mhivstatus.addClass("error");
            mhivstatusInfo.text("Please Select Mother HIV Status!");
            mhivstatusInfo.addClass("error");
            return false;
        }
        //if it's valid
        else {
            mhivstatus.removeClass("error");
            mhivstatusInfo.text("");
            mhivstatusInfo.removeClass("error");
            return true;
        }
    }

    //ensure mother drugs
    function validateMdrug() {
        //if it's NOT valid
        if (mdrug.val().length < 1) {
            mdrug.addClass("error");
            mdrugInfo.text("Please Select PMTCT Intervention!");
            mdrugInfo.addClass("error");
            return false;
        }
        //if it's valid
        else {
            mdrug.removeClass("error");
            mdrugInfo.text("");
            mdrugInfo.removeClass("error");
            return true;
        }
    }

    //ensure mother feeding type
    function validateMbfeeding() {
        //if it's NOT valid
        if (mbfeeding.val().length < 1) {
            mbfeeding.addClass("error");
            mbfeedingInfo.text("Please Select Feeding !");
            mbfeedingInfo.addClass("error");
            return false;
        }
        //if it's valid
        else {
            mbfeeding.removeClass("error");
            mbfeedingInfo.text("");
            mbfeedingInfo.removeClass("error");
            return true;
        }
    }
    //ensure mother entry point
    function validateMentpoint() {
        var isChecked = false;
        for (var i = 0; i < mentpoint.length; i++) {
            if (mentpoint[i].checked == false) {
                isChecked = false;
            } else {
                isChecked = true;
                break;
            }
        }
        if (isChecked == false) {
            //mentpoint[i].addClass("error");
            entrypointInfo.text("Please Select Entry Point!");
            entrypointInfo.addClass("error");
            return false;
        } else {
            //mentpoint[i].removeClass("error");
            entrypointInfo.text("");
            entrypointInfo.removeClass("error");
            return true;
        }
    }
    //Ensure test reason is entered 
    function validateTestReason() {
        var isChecked = false;
        for (var i = 0; i < testreason.length; i++) {
            if (testreason[i].checked == false) {
                isChecked = false;
            } else {
                isChecked = true;
                break;
            }
        }
        if (isChecked == false) {
            //mentpoint[i].addClass("error");
            testreasonInfo.text("Please Enter Reason for Test!");
            testreasonInfo.addClass("error");
            return false;
        } else {
            //mentpoint[i].removeClass("error");
            testreasonInfo.text("");
            testreasonInfo.removeClass("error");
            return true;
        }
    }

    //validate infant prophylaxis

    function validatedelivery() {
        //if it's NOT valid
        if (delivery.val().length < 1) {
            delivery.addClass("error");
            deliveryInfo.text("Please Select Mode of Delivery!");
            deliveryInfo.addClass("error");
            return false;
        }
        //if it's valid
        else {
            delivery.removeClass("error");
            deliveryInfo.text("");
            deliveryInfo.removeClass("error");
            return true;
        }
    }

    function validateResult() {
        if (result.val() != undefined) {
            if (result.val().length < 1) {
                result.addClass("error");
                resultInfo.text("Please Enter Result for Sample Test!");
                resultInfo.addClass("error");
                return false;
            }
            //if it's valid
            else {
                result.removeClass("error");
                resultInfo.text("");
                resultInfo.removeClass("error");
                return true;
            }
        } else
        {
            return true;
        }
    }
    //ensure no of spots selected
    function validateSpot() {
        //if it's NOT valid
        if (spot.val().length < 1) {
            spot.addClass("error");
            spotInfo.text("Please Select Number of Spots!");
            spotInfo.addClass("error");
            return false;
        }
        //if it's valid
        else {
            spot.removeClass("error");
            spotInfo.text("");
            spotInfo.removeClass("error");
            return true;
        }
    }
    //ensure received status not null; must select
    function validateSrecstatus() {
        //if it's NOT valid
        if (srecstatus.val().length < 1) {
            srecstatus.addClass("error");
            recstatusInfo.text("Please Select Received Status!");
            recstatusInfo.addClass("error");
            return false;
        }
        //if it's valid
        else
        {
            srecstatus.removeClass("error");
            recstatusInfo.text("");
            recstatusInfo.removeClass("error");
            validateRejectedRepeatReasons();
            return true;

        }
    }

    //ensure received status not null; must select
    function validateRejectedRepeatReasons()
    {
        //if it's NOT valid
        if ((srecstatus.val() == "3") && (rejectedreason.val() == ""))
        {
            rejectedreason.addClass("error");
            rejectedreasonInfo.text("Please Select Reason for Rejection!");
            rejectedreasonInfo.addClass("error");
            return false;
        } else if ((srecstatus.val() == "3") && (repeatreason.val().length < 1))
        {
            repeatreason.addClass("error");
            repeatreasonInfo.text("Please Select Reason for Repeat!");
            repeatreasonInfo.addClass("error");
            return false;
        } else
        {

            rejectedreason.removeClass("error");
            rejectedreasonInfo.text("");
            rejectedreasonInfo.removeClass("error");
            repeatreason.removeClass("error");
            repeatreasonInfo.text("");
            repeatreasonInfo.removeClass("error");
            return true;
        }
    }

    function validateRejectedReason()
    {
        if (rejectedreason.length !== 0)
        {
            if (rejectedreason.val().length < 1 && isRejected.is(':checked'))
            {
                rejectedreason.addClass("error");
                rejectedreasonInfo.text("Please Select Reason for Rejection!");
                rejectedreasonInfo.addClass("error");
                return false;
            } else {
                rejectedreason.removeClass("error");
                rejectedreasonInfo.text("");
                rejectedreasonInfo.removeClass("error");
                return true;
            }
        } else
        {
            return true;
        }
    }

    //validate date colleceted
    function validateDatecollected() {
        //it's NOT valid
        if (sdoc.val().length < 1) {
            sdoc.addClass("error");
            sdocInfo.text("Please Enter Date Collected");
            sdocInfo.addClass("error");
            return false;
        }
        //it's valid
        else {
            sdoc.removeClass("error");
            sdocInfo.text("");
            sdocInfo.removeClass("error");

            return true;
        }

    }

    function validateConfirmDates() {
        if (ddispatched.val().length < 1)
        {
            ddispatched.removeClass("error");
            ddispatchedInfo.text("");
            ddispatchedInfo.removeClass("error");
            return true;
        } else
        {
            //are NOT valid
            if (sdoc.val() >= sdrec.val()) {
                sdoc.addClass("error");
                sdocInfo.text("Invalid Date Collected!,Should not be greater than date received.");
                sdocInfo.addClass("error");
                return false;
            } else if (ddispatched.val() > sdrec.val()) {
                ddispatched.addClass("error");
                ddispatchedInfo.text("Invalid Date Dispatched!,Should not be Greater than Date Received.");
                ddispatchedInfo.addClass("error");
                return false;
            }
            //are valid
            else {
                sdoc.removeClass("error");
                sdocInfo.text("");
                sdocInfo.removeClass("error");
                ddispatched.removeClass("error");
                ddispatchedInfo.text("");
                ddispatchedInfo.removeClass("error");
                return true;

            }

        }
    }

    //validate date received
    function validateDateReceived() {
        var a = $("#password");
        var b = $("#confirmpassword");

        //it's NOT valid
        if (sdrec.val().length < 1) {
            sdrec.addClass("error");
            sdrecInfo.text("Please Enter Date Received");
            sdrecInfo.addClass("error");
            return false;
        }
        //it's valid
        else {
            sdrec.removeClass("error");
            sdrecInfo.text("");
            sdrecInfo.removeClass("error");
            //validateConfirmDates();
            return true;
        }
    }
    //validate date dispatched from facility

    function validateDispatchfromfacility() {

        if (ddispatched.val().length < 1)
        {
            ddispatched.removeClass("error");
            ddispatchedInfo.text("");
            ddispatchedInfo.removeClass("error");
            return true;
        } else
        {

            //are NOT valid
            if (ddispatched.val() < sdoc.val()) {
                ddispatched.addClass("error");
                ddispatchedInfo.text("Invalid Date Dispatched!,Should not be less than Date Collected.");
                ddispatchedInfo.addClass("error");
                return false;
            }
            //are valid
            else {
                ddispatched.removeClass("error");
                ddispatchedInfo.text("");
                ddispatchedInfo.removeClass("error");
                return true;
            }
        }
    }


//validate passwords
    /*function validateRepeatforrejection(){
     
     //it's NOT valid  && (confirmatorypcr.val()=="Y"))
     if(repeatforrejection.val().length > 0)
     {
     repeatforrejection.removeClass("error");
     repeatInfo.text("");
     repeatInfo.removeClass("error");
     validateConfirmatorypcr();
     return true;
     }
     
     }*/
//function validateConfirmatorypcr()
//{

//are NOT valid
    /*if( (confirmatorypcr.val()=="Y") && (repeatforrejection.val()=="Y")  )
     {
     confirmatorypcr.addClass("error");
     confirmatoryInfo.text("Select only one option!");
     confirmatoryInfo.addClass("error");
     return false;
     }
     //are valid
     else 
     {
     confirmatorypcr.removeClass("error");
     confirmatoryInfo.text("");
     confirmatoryInfo.removeClass("error");
     return true;
     }
     }
     */


});