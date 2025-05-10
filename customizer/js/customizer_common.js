function doCUST_PluginAction(scriptUrl, scriptPos, scriptArgs, actionDiv) {
	
	actVal = document.getElementById(actionDiv).value;console.log(actVal);
	scriptArgs += "&action=" + actVal; 
	switch (actVal) {
		case "select":
			break;
		
		case "editblog":
		case "editmenu":
		case "menuitemmanager":
		case "editmenuitem":
		case "menutranslation":
			scriptDoLoad(scriptUrl, scriptPos, scriptArgs);
			break;
	
		default:
			/* check whether the system is demo or not */
			if(spdemo){
				if((actVal == 'deleteblog') || (actVal == 'Activate') || (actVal == 'Inactivate') || (actVal == 'deletemenuitem')){
					alertDemoMsg();
					return false;
				}
			}
		
			confirmLoad(scriptUrl, scriptPos, scriptArgs);
			break;
	}
}

