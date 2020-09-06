<?
/**
 * @author Mikhail Strovoyt
 */

class ManagerPanel extends Base
{	
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		Base::$tpl->assign_by_ref('oContent',$this);
		$oCurrency=new Currency();
		Base::$tpl->assign_by_ref('oCurrency', $oCurrency);
		Base::$oCurrency=$oCurrency;
	}
	//-----------------------------------------------------------------------------------------------
	function IsManager($sLogin,$sPassword)
	{
		$aUser=Auth::IsUser($sLogin,$sPassword,false,true);
		if ($aUser && $aUser['type_']=='manager')
		{
			if (!ManagerPanel::CheckAccessManagerPanel($aUser))
				return false;
			
			Auth::UpdateLastVisit($aUser);
			Auth::RefreshSession($aUser);
			Auth::IsAuth();
			/*
			Auth::$aUser=Auth::GetUserProfile($_SESSION['user']['id'],$_SESSION['user']['type_']);
			Auth::$sWhere=" and id_user='".Auth::$aUser[id]."'";
			*/
			return true;
		}
		return false;
	}
	//-----------------------------------------------------------------------------------------------
	function InitMP() {
		static $aListCurrency;
		
		if (!$aListCurrency) {
			$aListCurrency = Db::getAssoc("Select code,round(1/value,2) as val
					from currency where id!=1 and visible=1 order by num");
		}
		
		Base::$tpl->assign('aListCurrency',$aListCurrency);
	}
	//-----------------------------------------------------------------------------------------------
	public function SortTableMP() {
		if (!Base::$aRequest['sort'])
			Base::$aRequest['sort'] = 'price';
			
		if (!Base::$aRequest['way'])
			Base::$aRequest['way'] = 'up';
			
		Base::$tpl->assign('sTablePriceSort',Base::$aRequest['sort']);
		Base::$tpl->assign('sTablePriceSortWay',Base::$aRequest['way']);
	
		$sQueryString = Base::$sServerQueryString;
		// why not add search_phone, search_manager?
		if (Base::$aRequest['search']['manager'] && Base::$aRequest['is_popup'])
			$sQueryString .= '&search[manager]='.Base::$aRequest['search']['manager'];
		if (Base::$aRequest['search']['phone'] && Base::$aRequest['is_popup'])
			$sQueryString .= '&search[phone]='.Base::$aRequest['search']['phone'];
		
		$aSeoUrl = explode("&",str_replace("?","",str_replace("/?","",$sQueryString)));

		$aSeoUrlSave = $aSeoUrl;
		foreach($aSeoUrl as $iKey => $sValue) {
			if (!$sValue)
				unset($aSeoUrlSave[$iKey]);
				
			if (strpos($sValue, 'sort=') !== false || strpos($sValue, 'way=') !== false)
				unset($aSeoUrlSave[$iKey]);
		}
		$aSeoUrl = $aSeoUrlSave;
		$sSeoUrl = "/?".implode("&",$aSeoUrl);
		Base::$tpl->assign('sSeoUrl',$sSeoUrl);
	
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckAccessManagerPanel($aUser) {
		$iSave = $_SESSION['user']['id_user'];
		$_SESSION['user']['id_user'] = $aUser['id_user'];
		$iIdAction = Db::getOne("Select id from role_action where action_name='manager_panel'");
		if ($iIdAction) {
			if ($aUser['type_']=='manager' && (!$aUser['is_super_manager']
					&& (!Auth::CheckPermissions($iIdAction)))) {
				return false;
			}
		}
		$_SESSION['user']['id_user'] = $iSave;
		return true;
	}
	//-----------------------------------------------------------------------------------------------
	function CreateTopMenu() {
		if (Auth::$aUser['type_']!='manager')
			Base::Redirect("/");
		
		$aMenu = array(
			'manager_panel_manager_package_list'=>'Заказы',
			'manager_panel_purchase'=>'Закупка',
			'manager_panel_store'=>'Склад',
            'manager_panel_manager_new_orders'=>'Интернет заказы',
            'manager_panel_manager_call_me'=>'Звонки',
//            'manager_panel_user_edit'=>'Пользователи',
		);
		foreach ($aMenu as $sKey => $sTitle)
			if (!Content::CheckAccessManager(false,$sKey))
				unset($aMenu[$sKey]);
		
		Base::$tpl->assign('aMenuTop',$aMenu);
	}
}