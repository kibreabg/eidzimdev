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
	var province = $("#province");
	var provInfo = $("#provInfo");
		//On blur
	
	name.blur(validateName);
	province.blur(validateProvince);


	//On key press
	
	name.keyup(validateName);
	province.keyup(validateProvince);

	//On Submitting
	form.submit(function(){
		if(validateName() & validateProvince() )
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
			nameInfo.text("Please  enter valid District Name!");
			nameInfo.addClass("error");
			return false;
			
		}		
		
	}
	
	
	
		function validateProvince(){
		//if it's NOT valid
		if(province.val().length < 1){
			province.addClass("error");
			provInfo.text("Please  select Province!");
			provInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			province.removeClass("error");
			provInfo.text("");
			provInfo.removeClass("error");
			return true;
		}
	}
	
});