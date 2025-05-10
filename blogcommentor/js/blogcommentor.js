function doBlogCommenterPluginAction(scriptUrl, scriptPos, scriptArgs, divId) {
	actionDiv = 'action' + divId;
	actVal = document.getElementById(actionDiv).value;
	scriptArgs += "&action=" + actVal;
	switch (actVal) {
		case "select":		
			break;
		
		case "editproject":
		case "viewreports":
		case "copyproject":
			scriptDoLoad(scriptUrl, scriptPos, scriptArgs);
			break;
			
		case "checkStatus":
			if(spdemo){
				alertDemoMsg();
			} else {
				scriptPos = 'status_' + divId;
				scriptDoLoad(scriptUrl, scriptPos, scriptArgs);
			}
			break;
			
		case "checkSubmission":
			if(spdemo){
				alertDemoMsg();
			} else {
				scriptPos = 'approved_' + divId;
				scriptDoLoad(scriptUrl, scriptPos, scriptArgs);
			}
			break;
			
		case "submitcomment":
			if(spdemo){
				alertDemoMsg();
			} else {
				scriptPos = 'submitted_' + divId;
				confirmBlogSubmit(scriptUrl, scriptPos, scriptArgs);
			}
			break;
	
		default:
			/* check whether the system is demo or not */
			if(spdemo){
				if((actVal == 'deleteproject') || (actVal == 'Activate') || (actVal == 'Inactivate') || (actVal == 'linkactivate') || (actVal == 'linkinactivate') || (actVal == 'deletebloglink')){
					alertDemoMsg();
				}
			}
			confirmLoad(scriptUrl, scriptPos, scriptArgs);
			break;
	}
}

function confirmBlogSubmit(scriptUrl, scriptPos, scriptArgs) {

	if (chkObject('wantblogsubmit')) {
		wantproceed = "We recommend you to use cron for blog submission to get better results.";
	}
	
	var agree = confirm(wantblogsubmit);
	if (agree)
		return scriptDoLoad(scriptUrl, scriptPos, scriptArgs);
	else
		return false;
}