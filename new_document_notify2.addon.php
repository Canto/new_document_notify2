<?php
/*
new_document_notify2.addon.php
notify after check new document
*/
if(!defined('__XE__')) exit();

if($called_position == 'before_module_proc' && Context::get('module') != 'admin' && Context::get('act') != 'getFileList' && Context::get('act')!='procFileUpload'){
	$delay = str_replace("ms","",$addon_info->delay);
	Context::addHtmlHeader('<script src="https://cdn.socket.io/socket.io-1.0.3.js"></script>');
	Context::addHtmlFooter('<script src="./addons/new_document_notify2/js/new_document_notify.js"></script>');
	Context::addCssFile('./addons/new_document_notify2/css/style.css');
	Context::addBodyHeader('<div id="notify-div"></div>');
	Context::addHtmlHeader('<script type="text/javascript">var socket = io("'.$addon_info->nitrous.'");var delay = '.$delay.'; </script>');
	if(in_array($this->mid,explode(",",$addon_info->module_id))){
		if(Context::get('act') == "dispBoardWrite" && Context::get('document_srl')){
			$_SESSION['notify_modify'] = 'modify';
		}else{
			if(Context::get('act') == 'procBoardInsertDocument' && $_SESSION['notify_modify'] == "modify"){

			}else{
				if($_SESSION['notify_type']=='write'){
					$title = $_SESSION['notify_title'];
					$srl =  $_SESSION['notify_srl'];
					unset($_SESSION['notify_type']);
					unset($_SESSION['notify_title']);
					unset($_SESSION['notify_srl']);
					$new_document_notify = "<script type='text/javascript'>
											socket.emit('sendToServer', {title :'".$title."' , document_srl : '".$srl."'});
											</script>";
					Context::addHtmlHeader($new_document_notify);
				}else if($_SESSION['notify_type']=="comment"){

					$title = $_SESSION['notify_title'];
					$srl = $_SESSION['notify_srl'];
					$name = $_SESSION['notify_name'];
					$type = $_SESSION['notify_type'];
					unset($_SESSION['notify_type']);
					unset($_SESSION['notify_title']);
					unset($_SESSION['notify_srl']);
					unset($_SESSION['notify_name']);
					$new_document_notify = "<script type='text/javascript'>
											socket.emit('sendToServer', {title :'".$title."' , document_srl : '".$srl."' , name : '".$name."' , type : '".$type."'});
										</script>";
					Context::addHtmlHeader($new_document_notify);
				}else{

					if($_SESSION['notify_type']=='comment') unset($_SESSION['notify_name']);
					unset($_SESSION['notify_modify']);
					unset($_SESSION['notify_type']);
					unset($_SESSION['notify_title']);
					unset($_SESSION['notify_srl']);
				}
			}
		}
	}
}else if($called_position == 'after_module_proc' && in_array($this->mid,explode(",",$addon_info->module_id))){

	if(Context::get('act') == 'procBoardInsertDocument'){
		$document_srl = $this->get('document_srl');
		$oDocument = Context::getRequestVars();
		$title = $oDocument->title;
		if($_SESSION['notify_modify'] != 'modify') $_SESSION['notify_type'] = 'write';
		$_SESSION['notify_title'] = strip_tags($title);
		$_SESSION['notify_srl'] = $document_srl;
	}else if(Context::get('act') == 'procBoardInsertComment' && $addon_info->comment == 'Y'){
		// get Document data
		$document_srl = $this->get('document_srl');
		$oDocumentModel = &getModel('document');
		$oDocument = $oDocumentModel->getDocument($document_srl);
		$title = $oDocument->get('title');
		// get Comment data
		$comment_srl = $this->get('comment_srl');
		$oComment = &getModel('comment')->getComment($comment_srl);
		$commenter = $oComment->nick_name;

		//set SESSION
		$_SESSION['notify_type'] = 'comment';
		$_SESSION['notify_title'] = strip_tags(cut_str($title,$addon_info->cut_title,'…'));
		$_SESSION['notify_srl'] = $this->mid.'/'.$document_srl.'#comment_'.$comment_srl;
		$_SESSION['notify_name'] = strip_tags($commenter);

	}

}
?>
