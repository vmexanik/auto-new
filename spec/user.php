<?php

$oObject=new User();
$sPrefix='user_';

switch (Base::$aRequest['action'])
{
	case $sPrefix.'login':
		$oObject->Login();
		break;

	case $sPrefix.'do_login':
		$oObject->DoLogin();
		break;
		
	/*case $sPrefix.'loginza_login':
		$oObject->LoginzaLogin();
		break;*/
		
	case $sPrefix.'ulogin_login':
		$oObject->UloginLogin();
		break;

	case $sPrefix.'logout':
		$oObject->Logout();
		break;

	case $sPrefix.'new_account':
		$oObject->NewAccount();
		break;

	case $sPrefix.'confirm_registration':
		$oObject->ConfirmRegistration();
		break;

	case $sPrefix.'restore_password':
		$oObject->RestorePassword();
		break;

	case $sPrefix.'profile':
		$oObject->Profile();
		break;

	case $sPrefix.'change_password':
		$oObject->ChangePassword();
		break;

	case $sPrefix.'new_password':
		$oObject->NewPassword();
		break;

	case $sPrefix.'check_login':
		$oObject->CheckLogin();
		break;
		
	case $sPrefix.'change_level_price':
		$oObject->ChangeLevelPrice();
		break;
		
	default:
		$oObject->Login();
		break;
}

?>