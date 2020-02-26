/***************************/
//@Author: Adrian "yEnS" Mato Gondelle & Ivan Guardado Castro
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

$(document).ready(function(){
	//global vars
	var form = $("#customForm");
	//facility code
	//var code= $("#code");
	//var codeInfo = $("#codeInfo");	
	//facility name
	var name= $("#name");
	var nameInfo = $("#nameInfo");
	//facility type
	
	//district
	var district= $("#district");
	var districtInfo = $("#districtInfo");	
	//lab info
	var lab= $("#lab");
	var labInfo = $("#labInfo");
	//telephone
/*	var telephone= $("#telephone");
	var telephoneInfo = $("#telephoneInfo");
	//fullname
	var fullname= $("#fullname");
	var fullnameInfo = $("#fullnameInfo");
	//contact telephone
	var contacttelephone= $("#contacttelephone");
	var contacttelephoneInfo = $("#contacttelephoneInfo");*/
	
	
	//On blur
	//code.blur(validateCode);
	name.blur(validateName);
	district.blur(validateDistrict);
	lab.blur(validateLab);
/*	telephone.blur(validateTelephone);
	fullname.blur(validateFullname);
	contacttelephone.blur(validateContacttelephone);*/
	//On key press
	//code.keyup(validateCode);
	name.keyup(validateName);
	district.keyup(validateDistrict);
	lab.keyup(validateLab);
/*	telephone.keyup(validateTelephone);
	fullname.keyup(validateFullname);
	contacttelephone.keyup(validateContacttelephone);*/
	
	//On Submitting validateCode()  &  & validateTelephone() & validateFullname() & validateContacttelephone()
	form.submit(function()
		{//
		if( validateName()  & validateDistrict() &validateLab()   )
			return true
		else
			return false;
	});
	
	//ensure valid faiclity code
	function validateCode(){
		//if it's NOT valid
		
		var a = $("#code").val();
		var filter = /^[0-9]+$/;
//if it's NOT valid
		
		
		//if it's valid email
	if(filter.test(a))
	{
			code.removeClass("error");
			codeInfo.text("");
			codeInfo.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			code.addClass("error");
			codeInfo.text("Enter a valid Facility Code");
			codeInfo.addClass("error");
			return false;
		}		
		
		
	}
	//ensure facility type
		function validateType(){
		//if it's NOT valid
		if(type.val().length < 1){
			type.addClass("error");
			typeInfo.text("Please Select Type!");
			typeInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			type.removeClass("error");
			typeInfo.text("");
			typeInfo.removeClass("error");
			return true;
		}
	}
	
	//ensure lab selected
		function validateLab(){
		//if it's NOT valid
		if(lab.val().length < 1){
			lab.addClass("error");
			labInfo.text("Please Select Lab!");
			labInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			lab.removeClass("error");
			labInfo.text("");
			labInfo.removeClass("error");
			return true;
		}
	}
	
	//ensure distrirct
		function validateDistrict(){
		//if it's NOT valid
		if(district.val().length < 1){
			district.addClass("error");
			districtInfo.text("Please  Select District!");
			districtInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			district.removeClass("error");
			districtInfo.text("");
			districtInfo.removeClass("error");
			return true;
		}
	}

	
//ensure  facility name corect
	function validateName()
	{
		
		var a = $("#name").val();
		var filter = /^[a-z A-Z 0-9'_-]+$/;
//if it's NOT valid
			
		//if it's valid 
	if(filter.test(a))
	{
			name.removeClass("error");
			nameInfo.text("");
			nameInfo.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			name.addClass("error");
			nameInfo.text("Please  enter valid Facility Name!");
			nameInfo.addClass("error");
			return false;
			
		}		
		
		
	
	}
	
	//ensure contactnames corect
	function validateFullname()
	{
		
		var a = $("#fullname").val();
		var filter = /^[a-z A-Z '_-]+$/;
//if it's NOT valid
		
		
		//if it's valid email
	if(filter.test(a))
	{
			fullname.removeClass("error");
			fullnameInfo.text("");
			fullnameInfo.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			fullname.addClass("error");
			fullnameInfo.text("Please  enter valid Contact Person Names!");
			fullnameInfo.addClass("error");
			return false;
			
		}		
		
	}
	//esnure valid facility telephone enetred
	function validateTelephone(){
		//if it's NOT valid
			
		var a = $("#telephone").val();
		var filter = /^[0-9+ ]+$/ ;
		//if it's valid email
	if((filter.test(a)) && (telephone.val().length > 8) )
	{
			telephone.removeClass("error");
			telephoneInfo.text("");
			telephoneInfo.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			telephone.addClass("error");
			telephoneInfo.text("Please  enter valid Facility Phone number");
			telephoneInfo.addClass("error");
			return false;
		}		
	
	}
	
		//esnure valid contact person telephone enetred	
	function validateContacttelephone(){
		//if it's NOT valid
			
		var a = $("#contacttelephone").val();
		var filter = /^[0-9+ ]+$/ ;
		//if it's valid email
	if(filter.test(a))
	{
			contacttelephone.removeClass("error");
			contacttelephoneInfo.text("");
			contacttelephoneInfo.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			contacttelephone.addClass("error");
			contacttelephoneInfo.text("Please  enter valid Contact Person Phone number");
			contacttelephoneInfo.addClass("error");
			return false;
		}		
		
		
		
	}

});