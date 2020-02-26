/***************************/
//@Author: Adrian "yEnS" Mato Gondelle & Ivan Guardado Castro
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

$(document).ready(function(){
	//global vars
	var form = $("#customForm");
	//current password
	var cpassword= $("#cpassword");
	var cpasswordInfo = $("#cpasswordInfo");	
	//new password
	var password= $("#password");
	var passwordInfo = $("#passwordInfo");
	//confirmuse
	var confirmp= $("#confirm");
	var confirmInfo = $("#confirmInfo");	
		
	
	//On blur
	cpassword.blur(validateCpassword);
	password.blur(validatePassword);
	confirmp.blur(validateConfirmp);

	//On key press
	cpassword.keyup(validateCpassword);
	password.keyup(validatePassword);
	confirmp.keyup(validateConfirmp);
	
	
	//On Submitting  
	form.submit(function()
		{//
		if( validateCpassword() & validatePassword() & validateConfirmp())
			return true
		else
			return false;
	});
	
	//ensure current password nto null
		function validateCpassword(){
		//if it's NOT valid
		if(cpassword.val().length < 1){
			cpassword.addClass("error");
			cpasswordInfo.text("Please Enter Current Password!");
			cpasswordInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			cpassword.removeClass("error");
			cpasswordInfo.text("");
			cpasswordInfo.removeClass("error");
			return true;
		}
	}
	
	
	//validate passwords
	function validatePassword(){
		

		//it's NOT valid
		if(password.val().length <4){
			password.addClass("error");
			passwordInfo.text("Please enter Password, Remember: At least 4 characters");
			passwordInfo.addClass("error");
			return false;
		}
		//it's valid
		else{			
			password.removeClass("error");
			passwordInfo.text("");
			passwordInfo.removeClass("error");
			validateConfirmp();
			return true;
		}
	}
	function validateConfirmp(){
		
		//are NOT valid
		if( password.val() != confirmp.val() ){
			confirmp.addClass("error");
			confirmInfo.text("Passwords don't match!");
			confirmInfo.addClass("error");
			return false;
		}
		//are valid
		else{
			confirmp.removeClass("error");
			confirmInfo.text("");
			confirmInfo.removeClass("error");
			return true;
		}
	}
	
	


});