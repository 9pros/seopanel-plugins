function doPluginAction(scriptUrl, scriptPos, scriptArgs, divId) {
	actionDiv = 'action' + divId;
	actVal = document.getElementById(actionDiv).value;
	scriptArgs += "&action=" + actVal;
	switch (actVal) {
		case "select":		
			break;
		
		case "editPaymentGateway":
			scriptDoLoad(scriptUrl, scriptPos, scriptArgs);
			break;
	
		default:
			
			// check whether the system is demo or not
			if(spdemo){
				if ( (actVal == 'activatePG') || (actVal == 'inactivatePG')) {
					alertDemoMsg();
				}
			}
		
			confirmLoad(scriptUrl, scriptPos, scriptArgs);
			break;
	}
}