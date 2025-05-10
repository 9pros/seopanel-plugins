function doSMEPluginAction(scriptUrl, scriptPos, scriptArgs, divId) {
	actionDiv = 'action' + divId;
	actVal = document.getElementById(actionDiv).value;
	scriptArgs += "&action=" + actVal;
	
	switch (actVal) {
		
		case "select":		
			break;
			
		case "edit":
		case "edit_status":
		case "editSocialMedia":
		case "edit_apps":
		case "manageStatus":
		case "edit_status":
		case "editSocialMedia":
		case "viewreport":
		case "connectionManager":
		case "editConnection":
		case "duplicateStatus":
			scriptDoLoad(scriptUrl, scriptPos, scriptArgs);
			break;
		
		case "connectSocialMedia":
			scriptDoLoad(scriptUrl, scriptPos, scriptArgs);
			break;
			
		default:
			/* check whether the system is demo or not */
			if(spdemo){
				if((actVal == 'Activate') || (actVal == 'Inactivate') || (actVal == 'delete') || (actVal == "postStatus")
				|| (actVal == 'Inactivate_Status') || (actVal == 'Activate_Status') || (actVal == 'delete_status')
				|| (actVal == 'InactivateSocialMedia') || (actVal == 'ActivateSocialMedia') || (actVal == 'doSMConnection')
				|| (actVal == 'deleteConnection') 
				) {
					alertDemoMsg();
					return false;
				}
			}
			confirmLoad(scriptUrl, scriptPos, scriptArgs);
			break;
			
	}
}

function selectAllSMEOptions(selectBoxId, selectAll) {	
	selectBox = document.getElementById(selectBoxId);
	for (var i = 0; i < selectBox.options.length; i++) { 
		selectBox.options[i].selected = selectAll; 
	}
}

function showStatusPreview(engineName) {
	
	share_image = $('input[name^="share_image"]').prop("value");
	share_title = $('input[name^="share_title"]').prop("value");
	share_url = $('input[name^="share_url"]').prop("value");
	share_description = $('textarea[name^="share_description"]').prop("value");
	share_tags = $('textarea[name^="share_tags"]').prop("value");
	share_tags = share_tags.replace(/ /g, "");
	share_tags = share_tags.replace(/,/g, " #");
	share_tags = "#" + share_tags;
	
	previewDiv = "preview_" +  engineName;
	$("#" + previewDiv).show();
	
	// show preview image
	if (share_image) {
		$("#" + previewDiv + " .prev_img").show();
		$("#" + previewDiv + " .prev_img img").prop("src", $("#img").prop("src"));
	}
	
	var message = "";
	if (engineName == 'Twitter') {
		message = share_title.substr(0, 280);
		
		tmp_msg = message +" "+ share_url;
		if (tmp_msg.length <= 280) {
			message += " " + share_url;
		}
		
		tmp_msg = message +" "+ share_tags;
		if (tmp_msg.length <= 280) {
			message += " " + share_tags;
		}
		
		tmp_msg = message +" "+ share_description;
		if (tmp_msg.length <= 280) {
			message += " " + share_description;
		}
		
		message = message.replace(share_url, "<a href='#'>" + share_url + "</a>");
		message = message.replace(share_tags, "<b>" + share_tags + "</b>");
	} else {
		
		if (engineName == 'Facebook' || engineName == 'LinkedIn') {
			if (share_image) {
				message = share_title + ". <a href='#'>" + share_url + "</a>";
			} else {
				message = share_title + ".";
			}
		} else {
			message = share_title;
		}		
		
		message += " " + share_description;
		message += ". <b>" + share_tags + "</b>";
	}	
	
	$("#" + previewDiv + " .prev_msg").html(message);
}

function countChar(val_id, div_id) {
    var len = $('#' + val_id).prop("value").length;
    $('#' + div_id).text(len);
};