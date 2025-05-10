function doPluginAction(scriptUrl, scriptPos, scriptArgs, actionDiv) {
	actVal = document.getElementById(actionDiv).value;
	scriptArgs += "&action=" + actVal; 
	switch (actVal) {
		case "select":		
			break;
		
		case "editService":
			scriptDoLoad(scriptUrl, scriptPos, scriptArgs);
			break;
	
		default:
			/* check whether the system is demo or not */
			if(spdemo){
				if((actVal == 'deleteService') || (actVal == 'Activate') || (actVal == 'Inactivate')){
					alertDemoMsg();
					return false;
				}
			}
		
			confirmLoad(scriptUrl, scriptPos, scriptArgs);
			break;
	}
}

function changeServiceType(identifier) {
	
	if (identifier == 'deathbycaptcha') {
		$('.login_username').show();
		$('.login_pass').show();
		$('.api_key').hide();
		$("input:text[name='api_key']").val('');
	} else {
		$('.api_key').show();
		$('.login_username').hide();
		$('.login_pass').hide();
		$("input:text[name='username']").val('');
		$("input:password[name='password']").val('');
	}
	
}



