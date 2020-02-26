/***************************/
//@Author: Adrian "yEnS" Mato Gondelle & Ivan Guardado Castro
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

$(document).ready(function(){
    //global vars
    var form = $("#customForm");
    var name = $("#name");
    var nameInfo = $("#nameInfo");    
    var email = $("#email");
    var emailInfo = $("#emailInfo");
    var priority = $("#priority");
    var priorityInfo = $("#priorityInfo");
    
    //On blur	
    name.blur(validateName);
    email.blur(validateEmail);
    priority.blur(validatePriority);


    //On key press	
    name.keyup(validateName);
    email.keyup(validateEmail);
    priority.change(validatePriority);

    //On Submitting
    form.submit(function(){
        if(validateName() & validateEmail() & validatePriority() )
            return true
        else
            return false;
    });

    function validateName(){
        //if it's NOT valid
		
        var a = $("#name").val();
        var filter = /^[a-z A-Z '_-]+$/;
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
            nameInfo.text("Please enter valid Lab Name!");
            nameInfo.addClass("error");
            return false;			
        }	
    }	
    
    function validateEmail(){
        
        
        //if it's NOT valid
		
        var a = $("#email").val();
        var atpos = a.indexOf("@");
        var dotpos = a.lastIndexOf(".");
        
        if (atpos<1 || dotpos<atpos+2 || dotpos+2>=a.length)
        {
            email.addClass("error");
            emailInfo.text("Please enter valid Email!");
            emailInfo.addClass("error");
            return false;
        }
        
        else{
            email.removeClass("error");
            emailInfo.text("");
            emailInfo.removeClass("error");
            return true;
        }
    }
	
    function validatePriority(){
        //if it's NOT valid
        if(priority.val().length < 1){
            priority.addClass("error");
            priorityInfo.text("Please select a priority level!");
            priorityInfo.addClass("error");
            return false;
        }
        //if it's valid
        else{
            priority.removeClass("error");
            priorityInfo.text("");
            priorityInfo.removeClass("error");
            return true;
        }
    }
	
});