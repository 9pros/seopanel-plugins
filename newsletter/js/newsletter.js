function doPluginAction(scriptUrl, scriptPos, scriptArgs, divId) {
	actionDiv = 'action' + divId;
	actVal = document.getElementById(actionDiv).value;
	scriptArgs += "&action=" + actVal;
	switch (actVal) {
		case "select":		
			break;
		
		case "editcampaign":
		case "newslettermanager":
		case "newNL":
		case "editNL":
		case "editEL":
		case "editEmail":
		case "importemail":
		case "managerEmail":
		case "gensubscribecode":
		case "createNL":
		case "reportmanager":
		case "testmail":
			scriptDoLoad(scriptUrl, scriptPos, scriptArgs);
			break;
	
		default:
			/* check whether the system is demo or not */
			if(spdemo){
				if((actVal == 'deletecampaign') || (actVal == 'Activate') || (actVal == 'Inactivate')
				|| (actVal == 'ActivateNL') || (actVal == 'InactivateNL') || (actVal == 'deleteNL') || (actVal == 'startsendnewsletter')
				|| (actVal == 'ActivateEL') || (actVal == 'InactivateEL') || (actVal == 'deleteEL')
				|| (actVal == 'ActivateEmail') || (actVal == 'InactivateEmail') || (actVal == 'deleteEmail')) {
					alertDemoMsg();
				}
			}
			confirmLoad(scriptUrl, scriptPos, scriptArgs);
			break;
	}
}