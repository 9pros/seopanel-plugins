function doPluginAction(scriptUrl, scriptPos, scriptArgs, divId) {
	actionDiv = 'action' + divId;
	actVal = document.getElementById(actionDiv).value;
	scriptArgs += "&action=" + actVal;
	switch (actVal) {
		case "select":		
			break;
		
		case "editproject":
		case "viewreports":
		case "editSB":
			scriptDoLoad(scriptUrl, scriptPos, scriptArgs);
			break;
	
		default:
			/* check whether the system is demo or not */
			if(spdemo){
				if((actVal == 'deleteproject') || (actVal == 'Activate') || (actVal == 'Inactivate') || (actVal == 'ActivateSB') || (actVal == 'InactivateSB') || (actVal == 'deleteSB')){
					alertDemoMsg();
				}
			}
			confirmLoad(scriptUrl, scriptPos, scriptArgs);
			break;
	}
}

function openWindow(scriptUrl, windowName, width, height){
	window.open(scriptUrl, windowName, 'width='+width+',height='+height+',status=yes,scrollbars=yes,left=300,top=250,screenX=0,screenY=100');
}