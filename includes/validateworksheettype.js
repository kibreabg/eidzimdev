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
    var maxlimit = $("#maxlimit");
    var maxlimitInfo = $("#maxlimitInfo");
    
    
    //On blur	
    name.blur(validateName);
    maxlimit.blur(validatemaxlimit);
    


    //On key press	
    name.keyup(validateName);
    maxlimit.keyup(validatemaxlimit);
    

    //On Submitting
    form.submit(function(){
        if(validateName() & validatemaxlimit()  )
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
            nameInfo.text("Please enter valid Worksheet Type Name!");
            nameInfo.addClass("error");
            return false;			
        }	
    }	
    
	
    function validatemaxlimit(){
        //if it's NOT valid
        if(maxlimit.val().length < 1){
            maxlimit.addClass("error");
            maxlimitInfo.text("Please select a  Max Limit!");
            maxlimitInfo.addClass("error");
            return false;
        }
        //if it's valid
        else{
            maxlimit.removeClass("error");
            maxlimitInfo.text("");
            maxlimitInfo.removeClass("error");
            return true;
        }
    }
	
});