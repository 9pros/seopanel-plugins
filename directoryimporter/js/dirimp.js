function showAdvancedFields(div) {
	showDiv(div);
	document.getElementById('show_advanced').value = '1';
}

function doDIAction(scriptUrl1, scriptPos1, scriptUrl2, scriptPos2, actionDiv) {
	actVal = document.getElementById(actionDiv).value;
	scriptArgs = "&action=" + actVal;
	switch (actVal) {
	
		case "select":		
			break;
		
		case "editdir":
			scriptDoLoad(scriptUrl1, scriptPos1, scriptArgs);
			break;
		
		case "checkdir":
			if(spdemo){
				alertDemoMsg();
			}
			confirmLoad(scriptUrl2, scriptPos2, scriptArgs);
			break;
	
		default:
			/* check whether the system is demo or not */
			if(spdemo){
				if((actVal == 'deletedir')){
					alertDemoMsg();
				}
			}
			confirmLoad(scriptUrl1, scriptPos1, scriptArgs);
			break;
	}
}