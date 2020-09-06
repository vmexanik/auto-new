<?php

$oObject=new Binotel();
$sPrefix='binotel_';

switch (Base::$aRequest['action'])
{
	case $sPrefix."input":
		$oObject->InputCalls();
		break;

	case $sPrefix."output":
	    $oObject->OutputCalls();
	    break;

    case $sPrefix."lost":
        $oObject->LostCalls();
        break;
        
	case $sPrefix."by_manager":
		$oObject->CallsByManager();
		break;

	case $sPrefix."by_number":
	    $oObject->CallsByNumber();
	    break;

    case $sPrefix."now":
        $oObject->CallsNow();
        break;

    case $sPrefix."users":
    case $sPrefix."users_delete":
        $oObject->Users();
        break;
        
    case $sPrefix."users_edit":
        $oObject->UserEdit();
        break;
        
    case $sPrefix."user_add":
        $oObject->UserAdd();
        break;
        
    case $sPrefix."user_import":
        $oObject->UserImport();
        break;

    case $sPrefix."managers":
        $oObject->Managers();
        break;

    case $sPrefix."make_call":
        $oObject->Call();
        break;
    
    case $sPrefix."comment_edit":
        $oObject->CommentEdit();
        break;
    
    case $sPrefix."analytics_trends":
        $oObject->AnalyticsTrends();
        break;
    
    case $sPrefix."analytics_load":
        $oObject->AnalyticsLoad();
        break;
    
    case $sPrefix."analytics_productivity":
        $oObject->AnalyticsProductivity();
        break;
        
    case $sPrefix."analytics_timeline":
        $oObject->AnalyticsTimeline();
        break;
        
    case $sPrefix."analytics_outgoing":
        $oObject->AnalyticsOutgoing();
        break;
        
    case $sPrefix."calls_reception":
        $oObject->CallsReception();
        break;
        
	default:
		$oObject->Index();
		break;

}
?>