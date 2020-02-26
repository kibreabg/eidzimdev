/***************************/
//@Author: Adrian "yEnS" Mato Gondelle & Ivan Guardado Castro
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

$(document).ready(function(){
	//global vars
	var form = $("#customForm");
	//account type
	var account= $("#account");
	var accountInfo = $("#accountInfo");	
	//lab
	var lab= $("#lab");
	var labInfo = $("#labInfo");
	//user name
	var username= $("#username");
	var usernameInfo = $("#usernameInfo");	
	//password
	var password= $("#password");
	var passwordInfo = $("#passwordInfo");	
	//confirm password
	var confirmpassword= $("#confirmpassword");
	var confirmpasswordInfo = $("#confirmpasswordInfo");
	//surname
	var surname= $("#surname");
	var surnameInfo = $("#surnameInfo");
	//other names
	var oname= $("#oname");
	var onameInfo = $("#onameInfo");
	//email
	var email= $("#email");
	var emailInfo = $("#emailInfo");
	
	
	//On blur
	account.blur(validateAccount);
	if(account.val()==1)
	{
	lab.blur(validateLab);
	}
	username.blur(validateUsername);
	password.blur(validatePassword);
	confirmpassword.blur(validateConfirmpassword);
	surname.blur(validateSurname);
	oname.blur(validateOname);
	email.blur(validateEmail);
	//On key press
	account.keyup(validateAccount);
	if(account.val()==1)
	{
	lab.keyup(validateLab);
	}
	username.keyup(validateUsername);
	password.keyup(validatePassword);
	confirmpassword.keyup(validateConfirmpassword);
	surname.keyup(validateSurname);
	oname.keyup(validateOname);
	email.keyup(validateEmail);
	
	//On Submitting  
	form.submit(function()
		{//
		if(validateAccount()  & validateUsername() & validatePassword() & validateConfirmpassword() & validateSurname() &validateOname() & validateEmail() )
			return true
		else
			return false;
	});
	
	//ensure account type
		function validateAccount(){
		//if it's NOT valid
		if(account.val().length < 1){
			account.addClass("error");
			accountInfo.text("Please Select User Group!");
			accountInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			account.removeClass("error");
			accountInfo.text("");
			accountInfo.removeClass("error");
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
	
	//ensure username entered
		function validateUsername(){
		//if it's NOT valid
		if(username.val().length < 1){
			username.addClass("error");
			usernameInfo.text("Please Enter Username!");
			usernameInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			username.removeClass("error");
			usernameInfo.text("");
			usernameInfo.removeClass("error");
			return true;
		}
	}
	//validate passwords
	function validatePassword(){
		var a = $("#password");
		var b = $("#confirmpassword");

		//it's NOT valid
		if(password.val().length <4){
			password.addClass("error");
			passwordInfo.text("Please enter Password, Remember: At least 5 characters");
			passwordInfo.addClass("error");
			return false;
		}
		//it's valid
		else{			
			password.removeClass("error");
			passwordInfo.text("");
			passwordInfo.removeClass("error");
			validateConfirmpassword();
			return true;
		}
	}
	function validateConfirmpassword(){
		
		//are NOT valid
		if( password.val() != confirmpassword.val() ){
			confirmpassword.addClass("error");
			confirmpasswordInfo.text("Passwords doesn't match!");
			confirmpasswordInfo.addClass("error");
			return false;
		}
		//are valid
		else{
			confirmpassword.removeClass("error");
			confirmpasswordInfo.text("");
			confirmpasswordInfo.removeClass("error");
			return true;
		}
	}
	
	
//ensure surname corect
	function validateSurname()
	{
		
		var a = $("#surname").val();
		var filter = /^[a-z A-Z '_-]+$/;
//if it's NOT valid
		
		
		//if it's valid email
	if(filter.test(a))
	{
			surname.removeClass("error");
			surnameInfo.text("");
			surnameInfo.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			surname.addClass("error");
			surnameInfo.text("Please  enter Surname!");
			surnameInfo.addClass("error");
			return false;
			
		}		
		
		
	
	}
	
	//ensure othe rnames corect
	function validateOname()
	{
		
		var a = $("#oname").val();
		var filter = /^[a-z A-Z '_-]+$/;
//if it's NOT valid
		
		
		//if it's valid email
	if(filter.test(a))
	{
			oname.removeClass("error");
			onameInfo.text("");
			onameInfo.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			oname.addClass("error");
			onameInfo.text("Please  enter Other Names!");
			onameInfo.addClass("error");
			return false;
			
		}		
		
	}
	
	//validation email
	function validateEmail(){
		//testing regular expression
		var a = $("#email").val();
		var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
		//if it's valid email
		if(filter.test(a)){
			email.removeClass("error");
			emailInfo.text("");
			emailInfo.removeClass("error");
			return true;
		}
		//if it's NOT valid
		else{
			email.addClass("error");
			emailInfo.text("Type a valid e-mail please ");
			emailInfo.addClass("error");
			return false;
		}
	}

});