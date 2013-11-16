<?php
	/*
	new_document_notify2.addon.php
	notify after check new document
	*/

	if($called_position == 'before_module_proc' && Context::get('module') != 'admin'){
		Context::addJsFile('./addons/new_document_notify2/js/socket.io.js');
		Context::loadFile(array("./addons/new_document_notify2/js/new_document_notify.js", 'body', '', -100000), true);
		
		if(FileHandler::hasContent('./addons/new_document_notify2/css/custom.css')) Context::addCssFile('./addons/new_document_notify2/css/custom.css');
		else Context::addCssFile('./addons/new_document_notify2/css/style.css');
		Context::addBodyHeader('<div id="notify-div"></div>');
		Context::addHtmlHeader('<script type="text/javascript">var socket = io.connect("'.$addon_info->nitrous.'");var delay = "'.$addon_info->delay.'";</script>');
		if($_SESSION['notify_type']=='write'){
			$title = $_SESSION['notify_title'];
			$srl =  $_SESSION['notify_srl'];
			unset($_SESSION['notify_type']);
			unset($_SESSION['notify_title']);
			unset($_SESSION['notify_srl']);
			$new_document_notify = sprintf("<script type='text/javascript'>
				var socket = io.connect('".$addon_info->nitrous."');
				socket.emit('sendToServer', {title :'".$title."' , document_srl : '".$srl."'});
				</script>");
			Context::addHtmlHeader($new_document_notify);
		}else{
			unset($_SESSION['notify_type']);
			unset($_SESSION['notify_title']);
			unset($_SESSION['notify_srl']);
		}
				
	}else if($called_position == 'after_module_proc' && Context::get('act') == 'procBoardInsertDocument' ){
			
			$document_srl = $this->get('document_srl');
			$oDocumentModel = &getModel('document');
			$oDocument = Context::getRequestVars();
			$title = $oDocument->title;
			if($oDocument->document_srl && $oDocument->status == 'PUBLIC') $_SESSION['notify_type'] = 'modify';
			else $_SESSION['notify_type'] = 'write';
			
			$_SESSION['notify_title'] = strip_tags($title);
			$_SESSION['notify_srl'] = $document_srl;
		
	}
?>
