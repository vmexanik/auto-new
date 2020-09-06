<?php

$oObject=new ContactForm();
$sPreffix='contact_form';

switch (Base::$aRequest['action'])
{
	//	default:
	//		if (Base::$aRequest['form_code'] && $_POST['is_post']) Base::$sText.=$oContactForm->ProcessForm('contact_form');
	//		else Base::$sText.=$oContactForm->OutputForm('contact_form');
	//		break;

	case $sPreffix."_call":
		$oObject->Call();
		break;

	default:
		$oObject->Index();
		break;
}
?>