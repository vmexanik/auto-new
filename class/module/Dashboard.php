<?

/**
 * @author Mikhail Starovoyt
 * @author Oleg Maki
 */

class Dashboard extends Base
{

	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		//Base::$aTopPageTemplate=array('panel/tab_manager.tpl'=>'travel_sheet');

		Auth::NeedAuth();
		Base::$tpl->assign('bHideRightColumn',true);
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$sMethod=ucfirst(Auth::$aUser['type_']);
		if (method_exists($this,$sMethod)) $this->$sMethod();
	}
	//-----------------------------------------------------------------------------------------------
	public function Customer()
	{
		Resource::Get()->Add('/css/dashboard.css');
		Base::$tpl->assign_by_ref("oString", new String());

		//if(Content::IsAutopartmaster()) $sVisible='_autopartmaster';
		$aDashboardMessage=Base::$language->GetLocalizedAll(array(
		'table'=>'news',
		'where'=>" and t.section='site' and t.visible".$sVisible."=1 order by t.num asc, t.id desc limit 3",
		));
		Base::$tpl->assign('aDashboardMessage',$aDashboardMessage);

		Base::$tpl->assign('aDashboardOrder',array(
		'all_except_archive'=>Db::GetOne("select count(*) from cart as c where type_='order' and c.order_status in (
			'pending','new','work','confirmed','road','store','office_sent','office_received')".Auth::$sWhere),
		'refused'=>Db::GetOne("select count(*) from cart as c  where type_='order' and c.order_status in (
			'refused')".Auth::$sWhere),
		'pending'=>Db::GetOne("select count(*) from cart as c  where type_='order' and c.order_status in (
			'pending')".Auth::$sWhere),
		'store'=>Db::GetOne("select count(*) from cart as c  where type_='order' and c.order_status in (
			'store')".Auth::$sWhere),
		));

		$aDataIncome=String::FilterRequestData(Base::$aRequest,array('status'));

		switch ($aDataIncome['status']){
			case 'refused':
				$sWhere.=" and c.order_status in ('refused') ";
				break;
			case 'pending':
				$sWhere.=" and c.order_status in ('pending') ";
				break;
			case 'store':
				$sWhere.=" and c.order_status in ('store') ";
				break;

			case 'all_except_archive':
			default:
				$sWhere.=" and c.order_status in ('pending','new','work','confirmed','road','store'
						,'office_sent','office_received') ";
				break;

		}
		$sWhere.=" and c.id_user='".Auth::$aUser['id']."'";

		$oTable=new Table();
		$oTable->sWidth='100%';
		$oTable->sSql=Base::GetSql("Part/Search",array(
		"where"=>$sWhere,
		"cart_log_join"=>$bCartJoin,
		"sSearchType"=>"sticker",
		));

		$oTable->aOrdered="order by c.post desc";
		$oTable->aColumn=array(
// 		'id'=>array('sTitle'=>'#/Order #'),
// 		'code'=>array('sTitle'=>'CartCodeStatus'),
// 		'name'=>array('sTitle'=>'Name/Customer_Id'),
// 		'term'=>array('sTitle'=>'Term'),
// 		'number'=>array('sTitle'=>'Number'),
// 		'price'=>array('sTitle'=>'Price'),
// 		'total'=>array('sTitle'=>'Total'),
		);
		$oTable->sDataTemplate='dashboard/row_order.tpl';
		$oTable->sSubtotalTemplate='dashboard/subtotal_order.tpl';
		$oTable->iRowPerPage=4;
		$oTable->bStepperVisible=false;
        $oTable->sTemplateName='table/index2.tpl';
		Base::$tpl->assign('sDashboardOrder',$oTable->GetTable());


		$oTable=new Table();
		$oTable->sWidth='100%';
		$oTable->sSql="select * from vin_request where 1=1 ".Auth::$sWhere;
		$oTable->aOrdered="order by post_date desc";
		$oTable->aColumn=array(
// 		'id'=>array('sTitle'=>'#'),
// 		'order_status'=>array('sTitle'=>'Order Status'),
// 		'vin'=>array('sTitle'=>'VIN'),
// 		'post'=>array('sTitle'=>'Post'),
// 		'action'=>array('sTitle'=>'Manager add Comment'),
// 		'action2'=>array(),
		);
		$oTable->sDataTemplate='cart/row_vin_request.tpl';
		$oTable->iRowPerPage=4;
		$oTable->bStepperVisible=false;
		$oTable->sSubtotalTemplate='dashboard/subtotal_vin_request.tpl';
		Base::$tpl->assign('sDashboardVinRequest',$oTable->GetTable("Vin requests"));


		$oTable=new Table();
		$oTable->sWidth='100%';
		$oTable->iRowPerPage=10;
		$oTable->sSql="select psl.*	from price_search_log as psl where psl.id_user='".Auth::$aUser['id']."'";
		$oTable->aOrdered="order by psl.post_date desc";
		$oTable->aColumn=array(
// 		'cat_name'=>array('sTitle'=>'Make'),
// 		'code'=>array('sTitle'=>'Code'),
// 		'post'=>array('sTitle'=>'Date'),
// 		'action'=>array('sTitle'=>''),
		);
		$oTable->sDataTemplate='price_search_log/row_price_search_log.tpl';
		$oTable->bStepperVisible=false;
		$oTable->sSubtotalTemplate='dashboard/subtotal_price_search_log.tpl';
		Base::$tpl->assign('sDashboardPriceSearchLog',$oTable->GetTable('Price search log'));


		Base::$sText.=Base::$tpl->fetch("dashboard/customer.tpl");
	}
	//-----------------------------------------------------------------------------------------------
	public function User()
	{
		Base::$bXajaxPresent=true;
		Auth::NeedAuth('manager');

		$sId = Base::$aRequest['id'];
		if(!$sId) return;

		$aUserCustomer = Db::GetRow(Base::GetSql("Customer",array(
		'id'=>$sId,
		'join_delivery_type'=> 1,
		'join_rating'=> 1,
		'join_rating_quality'=>1,
		)));
		if(!$aUserCustomer) return;

		//save to history
		DashboardHistory::Create(array(
		"id_user"=>$sId,
		"user_login"=> $aUserCustomer['login'],
		"id_user_manager"=>Auth::$aUser['id']
		));

		Base::$tpl->assign('aUserCustomer',$aUserCustomer);
		$sUserWhere=" and id_user='".$sId."'";
		Base::$tpl->assign('sId',$sId);
		Base::$tpl->assign_by_ref("oString", new String());

		if($aUserCustomer['is_allow_manager_login']){
			$oUser = new User();
			$sLoginLink=$oUser->GetLoginLinkHash($aUserCustomer);
			Base::$tpl->assign('sLoginLink',$sLoginLink);
			Base::$tpl->assign('sServerName',SERVER_NAME);
		}
		/////////////sound handler////////////////////////
		$oSound= new Sound();
		if(Base::$aRequest['action']=='dashboard_user_sound_upload'){
			$oSound->UploadCustomerSound();
		}

		Base::$tpl->assign('aDashboardOrder',array(
		'all_except_archive'=>Db::GetOne("select count(*) from cart as c where type_='order' and c.order_status in (
			'pending','new','work','confirmed','road','store','office_sent','office_received')".$sUserWhere),
		'refused'=>Db::GetOne("select count(*) from cart as c  where type_='order' and c.order_status in (
			'refused')".$sUserWhere),
		'pending'=>Db::GetOne("select count(*) from cart as c  where type_='order' and c.order_status in (
			'pending')".$sUserWhere),
		'store'=>Db::GetOne("select count(*) from cart as c  where type_='order' and c.order_status in (
			'store')".$sUserWhere),
		));

		$aDataIncome=String::FilterRequestData(Base::$aRequest,array('status'));

		switch ($aDataIncome['status']){
			case 'refused':
				$sWhere.=" and c.order_status in ('refused') ";
				break;
			case 'pending':
				$sWhere.=" and c.order_status in ('pending') ";
				break;
			case 'store':
				$sWhere.=" and c.order_status in ('store') ";
				break;
			case 'all_except_archive':
			default:
				$sWhere.=" and c.order_status in ('pending','new','work','confirmed','road','store','office_sent'
						,'office_received') ";
				break;

		}
		$sWhere.=" and c.id_user='".$sId."'";

		/////////////////////////////////////orders////////////////////
		$oTable=new Table();
		$oTable->sWidth='99%';
		$oTable->sSql=Base::GetSql("Part/Search",array(
		"where"=>$sWhere,
		"sSearchType"=>"sticker",
		));
		$oTable->aOrdered="order by c.post desc";
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'#/Order #'),
		'code'=>array('sTitle'=>'CartCodeStatus'),
		'name'=>array('sTitle'=>'Name/Customer_Id'),
		'term'=>array('sTitle'=>'Term'),
		'number'=>array('sTitle'=>'Number'),
		'price'=>array('sTitle'=>'Price'),
		'total'=>array('sTitle'=>'Total'),
		);
		$oTable->sDataTemplate='dashboard/row_order.tpl';
		$oTable->sSubtotalTemplate='dashboard/user_subtotal_order.tpl';
		$oTable->iRowPerPage=4;
		$oTable->bStepperVisible=false;
		Base::$tpl->assign('sDashboardOrder',$oTable->GetTable());

		///////////////////////////////////cart packs////////////////////
		$oTable=new Table();
		$oTable->sSql=Base::GetSql("CartPackage",array(
		"not_is_archive"=>1,
		"id_user"=>$sId,
		"join_delivery_rate_request"=>true,
		"order"=>"order by cp.post desc"
		));
		$oTable->aColumn=array(
		'code'=>array('sTitle'=>'Code'),
		'order_status'=>array('sTitle'=>'Order Status'),
		'price_total'=>array('sTitle'=>'Total'),
		'name_customer'=>array('sTitle'=>'Name'),
		'customer_comment'=>array('sTitle'=>'Comment'),
		'post'=>array('sTitle'=>'Date'),
		'action'=>array(),
		);
		$oTable->sDataTemplate='dashboard/user_row_cart_package.tpl';
		$oTable->sSubtotalTemplate='dashboard/user_subtotal_cart_package.tpl';
		$oTable->iRowPerPage=4;
		$oTable->bStepperVisible=false;
		Base::$tpl->assign('sDashboardCartPackage',$oTable->GetTable());

		///////////////////////////////////sound content/////////////////////
		$sSoundCustomerUploadContent=$oSound->GetHtmlCodeCustomer(array(
		'login'=>$aUserCustomer['login'],
		'action'=>'dashboard_user_sound_upload'
		));
		Base::$tpl->assign('sSoundCustomerUploadContent',$sSoundCustomerUploadContent);
		///////////////////////////////////invoices////////////////////
		$aDashboardInvoice=Db::GetAll(Base::GetSql('InvoiceCustomer',array(
		'id_user'=>$sId,
		'order'=>" order by ic.id desc limit 0,5",
		)));
		Base::$tpl->assign('aDashboardInvoiceList',$aDashboardInvoice);

		///////////////////////////////////vin rs////////////////////
		$aDashboardVinRequest=Db::GetAll("select * from vin_request where 1=1 "
		.$sUserWhere." order by post desc limit 5");
		Base::$tpl->assign('aDashboardVinRequest',$aDashboardVinRequest);

		///////////////////////////////////search log////////////////////
		$aDashboardPriceSearchLog=Db::GetAll("select psl.*	from price_search_log as psl where psl.id_user='"
		.$sId."' order by psl.post_date desc limit 5");
		Base::$tpl->assign('aDashboardPriceSearchLog',$aDashboardPriceSearchLog);

		$aPartnerRegion = Db::GetAll(Base::GetSql("PartnerRegion",array(
		'id_language'=>1,
		'where'=>" and pr.visible='1'",
		'order'=>" order by pr.name",
		)));
		Base::$tpl->assign('aPartnerRegion',$aPartnerRegion);

		Base::$sText.=Base::$tpl->fetch("dashboard/user_customer.tpl");
	}
	//-----------------------------------------------------------------------------------------------
	public function PartnerRegionChange()
	{
		Auth::NeedAuth('manager');
		if (!Base::$aRequest['id'] || !Base::$aRequest['id_user']) {
			Base::$oResponse->AddAlert('Error fields set');
			return;
		}

		Db::AutoExecute('user_customer',array('id_partner_region'=>Base::$aRequest['id']),'UPDATE',
		" id_user='".Base::$aRequest['id_user']."' and id_partner_region='0'");
		$aCustomer=Db::GetRow(Base::GetSql('Customer',array('id'=>Base::$aRequest['id_user'])));
		Base::$oResponse->AddAssign('partner_region_td_id','innerHTML',$aCustomer['partner_region_name']);
	}
	//-----------------------------------------------------------------------------------------------

}
?>