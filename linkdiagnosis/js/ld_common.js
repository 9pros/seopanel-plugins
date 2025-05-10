function doPluginAction(scriptUrl, scriptPos, scriptArgs, actionDiv) {
	actVal = document.getElementById(actionDiv).value;
	scriptArgs += "&action=" + actVal;
	switch (actVal) {
		case "select":		
			break;
		
		case "editproject":
		case "editreport":
		case "reports":
		case "viewreport":
        case "importBacklinks":
			scriptDoLoad(scriptUrl, scriptPos, scriptArgs);
			break;
	
		default:
			/* check whether the system is demo or not */
			if(spdemo){
				if((actVal == 'verifybacklinks') || (actVal == 'rerunreport') || (actVal == 'deleteproject') || (actVal == 'deletereport') 
					|| (actVal == 'Activate') || (actVal == 'Inactivate') || (actVal == 'activateSearchEngine') || (actVal == 'inactivateSearchEngine')){
					alertDemoMsg();
					return false;
				}
			}
		
			confirmLoad(scriptUrl, scriptPos, scriptArgs);
			break;
	}
}


function showReportSelectBox(scriptUrl, scriptPos, scriptArgs, actionDiv) {
	
	actVal = document.getElementById(actionDiv).value;
	scriptArgs += "&project_id=" + actVal;
	scriptDoLoad(scriptUrl, scriptPos, scriptArgs);
}

function showAnchorSelectBox(scriptUrl, scriptPos, scriptArgs, actionDiv) {
	
	actVal = document.getElementById(actionDiv).value;
	scriptArgs += "&report_id=" + actVal;
	scriptDoLoad(scriptUrl, scriptPos, scriptArgs);
}