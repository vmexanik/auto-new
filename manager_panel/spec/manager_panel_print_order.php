<?

/**
 * @author Vladimir Fedorov
 */

class AManagerPanelPrintOrder extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->Admin();
		//Base::$aRequest['not_change_top_menu']=1;
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		if (!Base::$aRequest['id'])
			return;
	
		$aCartPackage=Db::GetRow(Base::GetSql('CartPackage',array(
			'where'=>"and cp.id='".Base::$aRequest['id']."'")));
		$aUserCart=Db::GetAll(Base::GetSql("Part/Search",array(
			"where"=>" and c.id_cart_package='".Base::$aRequest['id']."' and c.type_='order'",
		)));
		$aCustomer=Db::GetRow(Base::GetSql('Customer',array(
			'id'=>($aCartPackage['id_user']? $aCartPackage['id_user']:-1),
		)));
		if (!$aUserCart || !$aCartPackage) 
			return;

		$aAccount=Db::GetRow(Base::GetSql('Account',array('where'=>" and is_active=1")));
		if (!$aAccount) 
			return;
		$aCartPackage['price_total_string']=Currency::CurrecyConvert(Currency::PrintPrice($aCartPackage['price_total'],1,2,'<none>'),
			Base::GetConstant('global:base_currency'));
		$aCartPackage['nds']=round((Currency::PrintPrice($aCartPackage['price_total'],1,2,'<none>')/118*18),2);
	
		Base::$tpl->assign('aUserCart',$aUserCart);
		Base::$tpl->assign('aCartPackage',$aCartPackage);
		Base::$tpl->assign('aCustomer',$aCustomer);
		Base::$tpl->assign('aAccount',$aAccount);
		Base::$tpl->assign('aActiveAccount',$aAccount);
	
		if (Base::$aRequest['no_article'])
			PrintContent::Append(Base::$tpl->fetch('cart/package_print_no_article.tpl'));
		else
			PrintContent::Append(Base::$tpl->fetch('cart/package_print.tpl'));
		
		Base::$oResponse->addScript ("window.open('/?action=print_content&return=cart_package_list');");
		
		//Base::Redirect('?action=print_content&return=cart_package_list');
	}
}
