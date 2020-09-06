<?php

require_once(SERVER_PATH.'/class/module/CallMe.php');

$oCallMe=new CallMe();

switch (Base::$aRequest['action'])
{
    case "call_me_show_manager":
        $oCallMe->ShowManager();
        break;
    
    default:
		$oCallMe->Send();
		break;	
}
?>