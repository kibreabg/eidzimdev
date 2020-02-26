/***************************/
//@Author: Adrian "yEnS" Mato Gondelle & Ivan Guardado Castro
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

$(document).ready(function(){
	//global vars
	var form = $("#customForm");
	var cat = $("#cat");  //facility 
	var codeInfo = $("#codeInfo");//facility  error div
	var pid = $("#pid");  //sample
	var pidInfo = $("#pidInfo");
	var srecstatus = $("#srecstatus");//received status
	var recstatusInfo = $("#recstatusInfo");
	var sspot = $("#sspot");//spots
	var spotInfo = $("#spotInfo");
		
	//On blur
	cat.blur(validatefacility);
	pid.blur(validatesamplecode);
	sspot.blur(validatespots);
	srecstatus.blur(validatereceivedstatus);

	//On key press
	cat.keyup(validatefacility);
	pid.keyup(validatesamplecode);
	sspot.keyup(validatespots);
	srecstatus.keyup(validatereceivedstatus);
	//On Submitting
	form.submit(function(){
		if(validatefacility() & validatesamplecode() & validatespots() & validatereceivedstatus() )
			return true
		else
			return false;
	});
	
		function validatesamplecode(){
			
			var a = $("#pid").val();
			var filter = /^[a-zA-Z0-9.-_]+$/;
		//if it's valid
		
			if(filter.test(a))
		{
			pid.removeClass("error");
			pidInfo.text("");
			pidInfo.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			pid.addClass("error");
			pidInfo.text("Please  enter Sample/Infant ID!");
			pidInfo.addClass("error");
			return false;
		}		
		/*if(pid.val().length < 1){
			pid.addClass("error");
			pidInfo.text("Please  enter Sample/Infant ID!");
			pidInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			pid.removeClass("error");
			pidInfo.text("");
			pidInfo.removeClass("error");
			return true;
		}*/
	}
	
			  //validate facility, must select one
		
		function validatefacility(){
		//if it's NOT valid
		if(cat.val().length < 1){
			cat.addClass("error");
			codeInfo.text("Please  Select A Facility");
			codeInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			cat.removeClass("error");
			codeInfo.text("");
			codeInfo.removeClass("error");
			return true;
		}
		
		
	  //validate no of spots not to be null..must select one
		
		function validatespots(){
		//if it's NOT valid
		if(sspot.val().length < 1){
			sspot.addClass("error");
			spotInfo.text("Please  Select Number of spots");
			spotInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			sspot.removeClass("error");
			spotInfo.text("");
			spotInfo.removeClass("error");
			return true;
		}
		
		 //validate received spits not to be null..must select one
		function validatereceivedstatus(){
		//if it's NOT valid
		if(srecstatus.val().length < 1){
			srecstatus.addClass("error");
			recstatusInfo.text("Please  Select Received Status");
			recstatusInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			srecstatus.removeClass("error");
			recstatusInfo.text("");
			recstatusInfo.removeClass("error");
			return true;
		}
	}
	
	
	
});