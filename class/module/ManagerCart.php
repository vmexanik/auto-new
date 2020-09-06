<?

class ManagerCart extends Base
{
	private $sCustomerSql;

	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		Base::$bXajaxPresent=true;
		Auth::NeedAuth('manager');
		Base::$aData['template']['bWidthLimit']=false;

		if (Auth::$aUser['is_super_manager'] || Auth::$aUser['is_sub_manager'])
		$this->sCustomerSql="SELECT id_user from user_customer";
		else $this->sCustomerSql="SELECT id_user from user_customer where id_manager='".Auth::$aUser['id']."'";

		Base::$aData['template']['bWidthLimit']=false;
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
	    if(Auth::$aUser['is_super_manager'])
	        $sWhereManager = ' ';
	    else
	        $sWhereManager = " and uc.id_manager='".Auth::$aUser['id_user']."' ";
	    
		Base::$aTopPageTemplate=array('panel/tab_manager_cart.tpl'=>'cart');

		if (Base::$aRequest['is_post']) {

			if (!Base::$aRequest['code'] || !Base::$aRequest['price'] || !Base::$aRequest['name']
			|| !Base::$aRequest['term'] || !Base::$aRequest['item_code']) {
				Form::ShowError("Please, fill the required fields");
				Base::$aRequest['action']='manager_cart_add';
				Base::$tpl->assign('aData',$aData=Base::$aRequest);
			}
			else {
				if (!Base::$aRequest['id']) {
					//[----- INSERT -----------------------------------------------------]
					$sQuery="insert into cart(type_,id_user,code,number,price,name,term
						,item_code,cat_name,provider_name,weight,post)
        			        values('cart','".Base::$aRequest['id_user']."','".Base::$aRequest['code']."','".Base::$aRequest['namber']."'
        			        ,'".Base::$aRequest['price']."','".Base::$aRequest['name']."','".Base::$aRequest['name']."'
        			        ,'".Base::$aRequest['item_code']."','".Base::$aRequest['cat_name']."'
        			       ,'".Base::$aRequest['weight']."'
        			        	,UNIX_TIMESTAMP())";
					//[----- END INSERT -------------------------------------------------]
				} else {
					//[----- UPDATE -----------------------------------------------------]
					$sQuery="update cart set
						id_user='".Base::$aRequest['id_user']."',
						code='".Base::$aRequest['code']."',
						number='".Base::$aRequest['number']."',
						price='".Base::$aRequest['price']."',
						name='".Base::$aRequest['name']."',
						term='".Base::$aRequest['term']."',
						item_code='".Base::$aRequest['item_code']."',
						cat_name='".Base::$aRequest['cat_name']."',
						weight='".Base::$aRequest['weight']."'
                        		where id='".Base::$aRequest['id']."'
                        			and type_='cart'
                        			and id_user in (".$this->sCustomerSql.") ";
					//[----- END UPDATE -------------------------------------------------]
				}
				Base::$db->Execute($sQuery);
				Base::Redirect("/?action=manager_cart");
			}
		}

		if (Base::$aRequest['action']=='manager_cart_add' || Base::$aRequest['action']=='manager_cart_edit') {
			if (Base::$aRequest['action']=='manager_cart_edit') {
				$aCart=Base::$db->getRow("
					select c.*,u.login, uc.name as customer_name
					from cart c
					inner join user u on c.id_user=u.id
					inner join user_customer uc on uc.id_user=u.id
					where 1=1 and c.type_='cart'
						and c.id='".Base::$aRequest['id']."'
						and c.id_user in (".$this->sCustomerSql.")");
				if (!$aCart) Base::Redirect('/?action=manager_cart');
				Base::$tpl->assign('aData',$aData=$aCart);
				foreach ($aCart as $aValue)
			        $aCustomer[$aValue['id']]=$aValue['login'].' - '.$aValue['name'];
			}

			$aField['id_user']=array('title'=>'Customer','type'=>'select','options'=>$aCustomer,'selected'=>Base::$aRequest['id_user'],'name'=>'id_user','szir'=>1);
			$aField['code']=array('title'=>'Code','type'=>'input','value'=>$aData['code'],'name'=>'code','szir'=>1);
			$aField['number']=array('title'=>'Number','type'=>'input','value'=>$aData['number'],'name'=>'number','szir'=>1);
			$aField['price']=array('title'=>'Price','type'=>'input','value'=>$aData['price'],'name'=>'price','szir'=>1);
			$aField['name']=array('title'=>'Name','type'=>'input','value'=>$aData['name'],'name'=>'name','szir'=>1);
			$aField['term']=array('title'=>'Term','type'=>'input','value'=>$aData['term'],'name'=>'term','szir'=>1);
			$aField['weight']=array('title'=>'Weight','type'=>'input','value'=>$aData['weight'],'name'=>'weight','szir'=>1);
			$aField['item_code']=array('title'=>'Item Code','type'=>'input','value'=>$aData['item_code'],'name'=>'item_code','szir'=>1);
			$aField['cat_name']=array('title'=>'Cat name','type'=>'input','value'=>$aData['cat_name'],'name'=>'cat_name');
			if(Auth::$aUser['is_super_manager'] || Auth::$aUser['is_sub_manager'])
			    $aField['provider_name']=array('title'=>'Provider name','type'=>'text','value'=>$aData['provider_name']);
			
			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=>"Cart item",
			//'sContent'=>Base::$tpl->fetch('manager_cart/form_cart.tpl'),
			'aField'=>$aField,
			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>'manager_cart',
			'sReturnButton'=>'<< Return',
			'sReturnAction'=>'manager_cart',
			'sError'=>$sError,
			);
			$oForm=new Form($aData);
			Base::$sText.=$oForm->getForm();
			return;
		}

		Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(uc.name,' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
		    Language::getMessage('tel.')." ',uc.phone))) name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where u.visible=1 and uc.name is not null and trim(uc.name)!=''
		".$sWhereManager."
		order by uc.name"));
		
		if (Base::$aRequest['action']=='manager_cart_delete') {
			Base::$db->Execute("delete from cart where
				type_='cart'
				and id='".Base::$aRequest['id']."'
				and id_user in (".$this->sCustomerSql.") ");
		}

		$ChangesFor=array(
		    ''=>Language::GetMessage('All Periods'),
		    '1'=>Language::GetMessage('Yestarday'),
		    '7'=>Language::GetMessage('Week'),
		    '30'=>Language::GetMessage('Month'),
		);
		Resource::Get()->Add('/js/select_search.js');
		
		$aField['search_login']=array('title'=>'Customer','type'=>'select','options'=>$aNameUser,'name'=>'search_login','selected'=>Base::$aRequest['search_login'],'class'=>'select_name_user');
		$aField['search_changes']=array('title'=>'Changes For','type'=>'select','options'=>$ChangesFor,'name'=>'search_changes','selected'=>Base::$aRequest['search_changes']);
		$aField['search_id']=array('title'=>'cart #','type'=>'input','value'=>Base::$aRequest['search_id'],'name'=>'search_id');
		$aField['search_name']=array('title'=>'Name','type'=>'input','value'=>Base::$aRequest['search_name'],'name'=>'search_name');
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		
		$aData=array(
		'sHeader'=>"method=get",
		//'sTitle'=>"Cart Items",
		//'sContent'=>Base::$tpl->fetch('manager_cart/form_cart_search.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'manager_cart',
		'sReturnButton'=>'Clear',
		'sReturnAction'=>'manager_cart',
		'bIsPost'=>0,
		'sWidth'=>'54%',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		$aData['sSearchForm']=$oForm->getForm();

		// --- search ---
		if (!Base::$aRequest['search_archive']) $sWhere.=" and c.is_archive='0'";
		if (Base::$aRequest['search_id']) $sWhere.=" and c.id like '%".Base::$aRequest['search_id']."%'";
		if (Base::$aRequest['search_name']) $sWhere.=" and c.name_translate like '%".Base::$aRequest['search_name']."%'";
		//if (Base::$aRequest['search_id_user']) $sWhere.=" and c.id_user like '%".Base::$aRequest['search_id_user']."%'";
		if (Base::$aRequest['search_login']) {
		    $sWhere.=" and (u.login like '%".Base::$aRequest['search_login']."%'";
		    $sWhere.=" || uc.name like '%".Base::$aRequest['search_login']."%'";
		    $sWhere.=" || uc.phone like '%".Base::$aRequest['search_login']."%')";
		}
		//if (Base::$aRequest['search_login']) $sWhere.=" and u.login ='".Base::$aRequest['search_login']."'";
		//if (Base::$aRequest['search_cart_status']) $sWhere.=" and c.cart_status = '".Base::$aRequest['search_cart_status']."'";

		switch(Base::$aRequest['search_changes']){
			case  '': break;
			case '1':
			    $sWhere.=" and c.post_date>='".date('Y-m-d H:i:s',strtotime('-1 DAY'))."'
				and c.post_date<='".DateFormat::FormatSearchNow()."'";
			    break;
			case '7':
			    $sWhere.=" and c.post_date>='".date('Y-m-d H:i:s',strtotime('-7 DAY'))."'
				and c.post_date<='".DateFormat::FormatSearchNow()."'";
			    break;
			case '30':
			    $sWhere.=" and c.post_date>='".date('Y-m-d H:i:s',strtotime('-30 DAY'))."'
				and c.post_date<='".DateFormat::FormatSearchNow()."'";
			    break;
			}
		
		if (Base::$aRequest['search']['date']) {
			$sWhere.=" and (c.post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
				and c.post_date<='".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."')";
		}
		
		// --------------

		$oTable=new Table();
		$oTable->sSql="select uc.*, cg.*,u.*,uc.*,c.*,u.login, uc.name as customer_name
					, m.login as manager_login, u.post_date as user_post_date
				from cart c
					inner join user u on c.id_user=u.id
				 	inner join user_customer uc on uc.id_user=u.id
				 	inner join user_account ua on ua.id_user=u.id
					inner join customer_group cg on uc.id_customer_group=cg.id
					inner join user m on uc.id_manager=m.id
				where 1=1 and c.type_='cart'
					and c.id_user in (".$this->sCustomerSql.")
					".$sWhere;

		$oTable->aColumn=array(
// 		'id'=>array('sTitle'=>'cart #','sWidth'=>'10%'),
		'id_user'=>array('sTitle'=>'User','sWidth'=>'10%'),
		'code'=>array('sTitle'=>'Code','sWidth'=>'10%'),
// 		'name'=>array('sTitle'=>'Name','sWidth'=>'30%'),
// 		'term'=>array('sTitle'=>'Term','sWidth'=>'1%'),
		'number'=>array('sTitle'=>'Number','sWidth'=>'1%'),
		'price'=>array('sTitle'=>'Price','sWidth'=>'5%'),
// 		'total'=>array('sTitle'=>'Total','sWidth'=>'5%'),
		'post'=>array('sTitle'=>'Date','sWidth'=>'5%'),
		'action'=>array('sWidth'=>'30%'),
		);
		//$oTable->iRowPerPage=5;
		$oTable->sDataTemplate='manager_cart/row_cart.tpl';
		$oTable->aCallback=array($this,'CallParseCart');
		//		$oTable->sAddButton="Add cart for Customer";
		//		$oTable->sAddAction="manager_cart_add";
		$oTable->aOrdered="order by c.post_date desc";
		$oTable->iRowPerPage=20;

		$aData['sCartItem']=$oTable->getTable("Customer Cart Items by Manager");
		Base::$tpl->assign('aData',$aData);

		Base::$sText.=Base::$tpl->fetch('manager_cart/list.tpl');;
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseCart(&$aItem)
	{
		if ($aItem) {
			foreach($aItem as $key => $value) {
				$acartId[]=$value['id'];

				$aItem[$key]['name']="<b>".(trim($value['name']) != '' ? $value['name'] : $value['name_translate']).
				"</b><br>".String::FirstNwords($value['customer_comment'],5);
				$aItem[$key]['total']=$value['number']*Currency::PrintPrice($value['price']);
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function Archive()
	{
		if (Base::$aRequest['is_archive']) $iIsArchive=1;
		$sQuery="update cart set
				is_archive='".$iIsArchive."'
			where id='".Base::$aRequest['id']."'
				and type_='cart'
				and id_user in (".$this->sCustomerSql.")
				";
		Base::$db->Execute($sQuery);
		$this->Index();
	}
	//-----------------------------------------------------------------------------------------------
	public function Store()
	{
		Base::$aTopPageTemplate=array('panel/tab_manager_cart.tpl'=>'cart_store');

		User::AssignPartnerRegion();
		Base::$tpl->assign('aUserManager',$aUserManager=array(""=>"")+Base::$db->GetAssoc("select id, login as name
			from user where type_='manager' and visible=1"));

		$aUserAccountAmount=array(
		    ''=>Language::GetMessage('All'),
		    '1'=>Language::GetMessage('Positive'),
		    '-1'=>Language::GetMessage('Negative'),
		);
		
		$aField['amount']=array('tytle'=>'User Account Amount','type'=>'select','options'=>$aUserAccountAmount,'name'=>'search[amount]','selected'=>Base::$aRequest['search']['amount']);
		$aField['id_partner_region']=array('title'=>'Partner Region','type'=>'select','options'=>$aPartnerRegion,'selected'=>Base::$aRequest['search']['id_partner_region']);
	    $aField['id_user_manager']=array('title'=>'User Manager','type'=>'select','options'=>$aUserManager,'name'=>'search[id_user_manager]','selected'=>Base::$aRequest['search']['id_user_manager']);
	    
		$aData=array(
		'sHeader'=>"method=get",
		//'sTitle'=>"Customer Store Items",
		'sContent'=>Base::$tpl->fetch('manager_cart/form_cart_store_search.tpl'),
// 		'aField'=>$aField,
// 		'bType'=>'generate',
// 		 'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'manager_cart_store',
		'sReturnButton'=>'Clear',
		'sReturnAction'=>'manager_cart_store',
		'bIsPost'=>0,
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		Base::$sText.=$oForm->getForm();

		// --- search ---
		if (Base::$aRequest['search']['amount']>0) $sWhere.=" and ua.amount>0 ";
		if (Base::$aRequest['search']['amount']<0) $sWhere.=" and ua.amount<0 ";

		if (Base::$aRequest['search']['id_user_manager'])
		$sWhere.=" and uc.id_manager='".Base::$aRequest['search']['id_user_manager']."'";
		// --------------

		$oTable=new Table();
		$oTable->sSql="select u.*, ua.*, c.*
						, count(c.id) as cart_number
						, uum.login as manager_login
				from cart c
					inner join user as u on c.id_user=u.id
					inner join user_customer uc on u.id=uc.id_user
					inner join user_account as ua on c.id_user=ua.id_user
					inner join cart_log as cl on (c.id=cl.id_cart and cl.order_status=c.order_status and c.order_status='store')
					inner join user_manager um on uc.id_manager=um.id_user
					inner join user uum on um.id_user=uum.id
				where 1=1 and c.type_='order'
					".$sWhere
		.($this->sCustomerSql ? " and c.id_user in (".$this->sCustomerSql.")":"")."
				group by c.id_user";
		$oTable->aColumn=array(
		'login'=>array('sTitle'=>'User'),
		'amount'=>array('sTitle'=>'Amount'),
		'cart_number'=>array('sTitle'=>'Number'),
		'region'=>array('sTitle'=>'Region'),
		'action'=>array(),
		);
		$oTable->iRowPerPage=100;
		$oTable->sDataTemplate='manager_cart/row_cart_store.tpl';
		$oTable->aOrdered="order by u.login";
		$oTable->bFormAvailable=false;

		Base::$sText.=$oTable->getTable("Store Carts");
	}
	//-----------------------------------------------------------------------------------------------
	public function Payment()
	{
		Base::$aTopPageTemplate=array('panel/tab_manager_cart.tpl'=>'cart_payment');

		if (Base::$aRequest['is_post'])
		{
			$aCart=Db::GetRow(Base::GetSql('Cart',array('id'=>Base::$aRequest['data']['id_cart'])));

			if (!Base::$aRequest['data']['id_cart'] || !$aCart['id']) {
				Form::ShowError("Please, fill the required fields");
				Base::$aRequest['action']='manager_cart_payment_add';
				Base::$tpl->assign('aData',$aData=Base::$aRequest['data']);
			}
			else {
				$aCartPayment=String::FilterRequestData(Base::$aRequest['data'],
				array('id_cart','number','weight_payment','volume_payment'));

				if (!Base::$aRequest['id']) {
					Db::Autoexecute('cart_payment',$aCartPayment);
				} else {
					$sWhere="id='".Base::$aRequest['id']."'";
					Db::Autoexecute('cart_payment',$aCartPayment,'UPDATE',$sWhere);
				}
				Form::RedirectAuto("&aMessage[MI_NOTICE]=cart payment updated");
			}
		}

		if (Base::$aRequest['action']=='manager_cart_payment_add' || Base::$aRequest['action']=='manager_cart_payment_edit') {
			if (Base::$aRequest['action']=='manager_cart_payment_edit') {
				$aCartPayment=Db::GetRow(Base::GetSql('CartPayment',array(
				'id'=>Base::$aRequest['id']
				)));
				if ($aCartPayment['is_payed']) Base::Redirect('/?action=manager_cart_payment&aMessage[MI_NOTICE]=error_payed');

				Base::$tpl->assign('aData',$aData=$aCartPayment);
			}

			$aField['id_cart']=array('title'=>'Id Cart','type'=>'input','value'=>$aData['id_cart'],'name'=>'data[id_cart]','szir'=>1);
			$aField['number']=array('title'=>'number','type'=>'input','value'=>!$aData['number']?'1':$aData['number'],'name'=>'data[number]');
			$aField['weight_payment']=array('title'=>'weight_payment','type'=>'input','value'=>$aData['weight_payment'],'name'=>'data[weight_payment]');
			$aField['volume_payment']=array('title'=>'volume_payment','type'=>'input','value'=>$aData['volume_payment'],'name'=>'data[volume_payment]');
			
			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=>"Cart Payment",
			//'sContent'=>Base::$tpl->fetch('manager_cart/form_cart_payment.tpl'),
			'aField'=>$aField,
			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>'manager_cart_payment',
			'sReturnButton'=>'<< Return',
			'sReturnAction'=>'manager_cart_payment',
			'sError'=>$sError,
			);
			$oForm=new Form($aData);


			Base::$sText.=$oForm->getForm();
			return;
		}

		if (Base::$aRequest['action']=='manager_cart_payment_delete') {
			Base::$db->Execute("delete from cart_payment where id='".Base::$aRequest['id']."' and is_payed='0'");
			Form::RedirectAuto("&aMessage[MI_NOTICE]=cart payment deleted");
		}

		if (Base::$aRequest['action']=='manager_cart_payment_pay')
		{
			$aCartPayment=Db::GetRow(Base::GetSql('CartPayment',array(
			'id'=>Base::$aRequest['id']
			)));
			if ($aCartPayment['is_payed']) Base::Redirect('/?action=manager_cart_payment&aMessage[MI_NOTICE]=error_payed');

			if ($aCartPayment['weight_payment']) {
				//				Finance::Deposit($aCartPackage['id_user'],-$dCartPrice
				//				,Language::getMessage($sPaymentCode).$aValue['id'],$aValue['id'],'internal','cart','',9);
				//
				//				InvoiceAccountLog::AddItem($aValue['id'],$dCartPrice,Language::GetMessage('ii_cart'));
				//				Base::$db->Execute("update cart set full_payment_discount='{$aValue['full_payment_discount']}'
				//where id='$sKey'");
				//
				//				$aCartUpdate['weight_delivery_cost']='';
				//				$aCartUpdate['weight_delivery_cost_post']='Now()';
				$bPayed=true;
			}

			if ($aCartPayment['volume_payment']) {
				//				Finance::Deposit($aCartPackage['id_user'],-$dCartPrice
				//				,Language::getMessage($sPaymentCode).$aValue['id'],$aValue['id'],'internal','cart','',9);
				//
				//				InvoiceAccountLog::AddItem($aValue['id'],$dCartPrice,Language::GetMessage('ii_cart'));
				//				Base::$db->Execute("update cart set full_payment_discount='{$aValue['full_payment_discount']}'
				//where id='$sKey'");
				$bPayed=true;
			}

			if ($bPayed) {
				$aCartPaymentUpdate=array();
				$aCartPaymentUpdate['is_payed']='1';
				$aCartPaymentUpdate['payed_date']='Now()';
				Db::AutoExecute('cart_payment',$aCartPaymentUpdate,'UPDATE',"id='".$aCartPayment['id']."'");
				Form::RedirectAuto("&aMessage[MI_NOTICE]=cart payment deleted");
			}
		}


        $aField['login']=array('title'=>'Login','type'=>'input','value'=>Base::$aRequest['search']['login'],'name'=>'search[login]');
		$aData=array(
		'sHeader'=>"method=get",
		//'sContent'=>Base::$tpl->fetch('manager_cart/form_cart_payment_search.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'manager_cart_payment',
		'sReturnButton'=>'Clear',
		'sReturnAction'=>'manager_cart_payment',
		'bIsPost'=>0,
		'sError'=>$sError,
		);
		$oForm=new Form($aData);
		Base::$sText.=$oForm->getForm();

		// --- search ---
		if (Base::$aRequest['search']['login']) $sWhere.=" and u.login='".Base::$aRequest['search']['login']."'";
		// --------------

		$oTable=new Table();
		$oTable->sSql=Base::GetSql('CartPayment',array('where'=>$sWhere));
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'ID'),
		'cart'=>array('sTitle'=>'Cart'),
		'post_date'=>array('sTitle'=>'PostDate'),
		'number'=>array('sTitle'=>'number'),
		'weight_payment'=>array('sTitle'=>'weight_payment'),
		'volume_payment'=>array('sTitle'=>'volume_payment'),
		'is_payed'=>array('sTitle'=>'is_payed'),
		'action'=>array(),
		);
		$oTable->iRowPerPage=20;
		$oTable->sButtonTemplate='manager_cart/button_cart_payment.tpl';
		$oTable->sDataTemplate='manager_cart/row_cart_payment.tpl';

		Base::$sText.=$oTable->getTable("Cart Payments");

	}
	//-----------------------------------------------------------------------------------------------

}
?>