/***************************/
//@Author: Adrian "yEnS" Mato Gondelle & Ivan Guardado Castro
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

$(document).ready(function(){
	//global vars
	var form = $("#customForm");
	var daterun = $("#daterun");
	var daterunInfo = $("#daterunInfo");
		//On blur
	
	
daterun.blur(validateDaterun);

	//On key press
	
	
daterun.keyup(validateDaterun);
	
	
	//ON CLICK
	daterun.click(validateDaterun);
	
	//on change
	daterun.change(validateDaterun);
	
	
	//On Submitting
	form.submit(function(){
		if( validateDaterun() )
			return true
		else
			return false;
	});
	
	
	
	
	
	
	//ensure date run not null
	function validateDaterun(){
		//if it's NOT valid
		if(daterun.val().length < 1){
			daterun.addClass("error");
			daterunInfo.text("Enter Date Run!");
			daterunInfo.addClass("error");
			return false;
		}
		//if it's valid
		else{
			daterun.removeClass("error");
			daterunInfo.text("");
			daterunInfo.removeClass("error");
			return true;
		}
	}
	
	
	
});