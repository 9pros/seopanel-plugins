function doASPluginAction(scriptUrl, scriptPos, scriptArgs, actionDiv) {
	actVal = document.getElementById(actionDiv).value;
	scriptArgs += "&action=" + actVal; 
	switch (actVal) {
		case "select":		
			break;
		
		case "editArticle":
		case "copyArticle":
		case "edit":
		case "editwebsite":
		case "Article":
			scriptDoLoad(scriptUrl, scriptPos, scriptArgs);
			break;
	
		default:
			/* check whether the system is demo or not */
			if(spdemo){
				if((actVal == 'deletePorject') || (actVal == 'Activate') || (actVal == 'Inactivate')){
					alertDemoMsg();
					return false;
				}
			}
		
			confirmLoad(scriptUrl, scriptPos, scriptArgs);
			break;
	}
}

var spinnerTextArea;
function addSearchResultsToArticle() {
	var articleContent = "";
	$('input[name="cont_selected"]:checked').each(function() {
	   articleContent += $('#content_area_' + this.value).text();
	});
  
	spinnerTextArea.value(articleContent);
	$('#dialogContent').dialog("close");
}