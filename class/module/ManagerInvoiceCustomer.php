<?

/**
 * @author Mikhail Starovoyt
 * version 4.5.2
 */

class ManagerInvoiceCustomer extends Base
{
	var $aCartScan=array();

	const LOG_PRICE_PLACE = 1;
	const LOG_ADDITIONAL_PAYMENT = 2;

	const CUSTOMER_TYPE_UKRAINE = 1;
	const CUSTOMER_TYPE_NOT_UKRAINE = -1;
	const CUSTOMER_TYPE_NOT_SET = -2;

	private $sPrefix='manager_invoice_customer';

	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		Auth::NeedAuth('manager');
		//		if (! (Auth::$aUser ['is_super_manager'] || Auth::$aUser ['is_sub_manager'])) {
		//			Base::Redirect ( '/?action=auth_type_error' );
		//		}
		Base::$tpl->assign('bHideRightColumn',true);
		//User::AssignPartnerRegion();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		Base::$bXajaxPresent=true;
		Base::$aTopPageTemplate=array('panel/tab_manager.tpl'=>'invoice_customer');

		Base::$tpl->assign('aIsTravelSheetAssoc',array(
		'0'=>Language::GetMessage('Only without travelsheets'),
		'1'=>Language::GetMessage('With travelsheets'),
		));

		$aData=array(
			'table'=>'rating',
			'where'=>" and section='store_customer' ",
			'order'=>" order by t.num",
		);
		$aTmp=Language::GetLocalizedAll($aData);
		foreach ($aTmp as $aValue) {
			$aRating[$aValue['num']]=$aValue['content']?$aValue['content']:$aValue['name'];
		}
		Base::$tpl->assign('aRatingAssoc',$aRating);
		Base::$sText.=Base::$tpl->fetch('manager_invoice_customer/link_customer_search.tpl');;

		Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(ifnull(uc.name,''),' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
						Language::getMessage('tel.')." ',uc.phone))) name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where u.visible=1 /*and uc.name is not null and trim(uc.name)!=''*/
		".$sWhereManager."
		order by uc.name"));
		
		Resource::Get()->Add('/js/select_search.js');
		
		$aField['login']=array('title'=>'Login','type'=>'select','options'=>$aNameUser,'value'=>Base::$aRequest['search']['login'],'name'=>'search[login]','selected'=>Base::$aRequest['search_login'],'class'=>'select_name_user');
		$aField['fio']=array('title'=>'Fio','type'=>'input','value'=>Base::$aRequest['search']['fio'],'name'=>'search[fio]');
		
		$aData=array(
		'sHeader'=>"method=get",
		//'sContent'=>Base::$tpl->fetch('manager_invoice_customer/form_invoice_search.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'manager_invoice_customer',
		'sReturnButton'=>'Clear',
		'bIsPost'=>0,
		'sError'=>$sError,
		'sWidth'=>'750px',
		);
		$oForm=new Form($aData);
		Base::$sText.=$oForm->getForm();

		// --- search ---
		if (Base::$aRequest['search']['login']) $sWhere.=" and u.login like '%".Base::$aRequest['search']['login']."%'";
		if (Base::$aRequest['search']['fio']) $sWhere.=" and uc.name like '%".Base::$aRequest['search']['fio']."%'";
		// --------------
		$aSearchData=array(
 			'status_array'=>array("'store'","'office_sent'","'office_received'"),
 			'id_invoice_customer'=>0,
 			'where'=>$sWhere,
 			'having'=>$sHaving,
 			'join_user_account_log'=>1,
		);
		if (Base::$aRequest['search']['num_rating']) $aSearchData['num_rating']=Base::$aRequest['search']['num_rating'];

		$oTable=new Table();
		//if (count(Base::$aRequest)>1) {
			$oTable->sSql=Base::GetSql('InvoiceCustomer/Customer',$aSearchData);

			$oTable->aOrdered="order by u.login";
			//$oTable->aCallback=array($this,'CallParseCustomer');
		//}
		//else $oTable->sSql="select 1 from cart where 1!=1";

		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'CustID'),
		'login'=>array('sTitle'=>'Login'),
		'cart_count'=>array('sTitle'=>'CartCount store'),
		'cart_count_work'=>array('sTitle'=>'CartCount work'),
		'action'=>array(),
		);

		$oTable->iRowPerPage=50;
		$oTable->bCheckVisible=true;
		$oTable->sDataTemplate='manager_invoice_customer/row_customer.tpl';

		$oTable->sButtonTemplate='manager_invoice_customer/button_customer.tpl';

		Base::$sText.=$oTable->getTable("Customer Invoice Carts");
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseCustomer(&$aItem)
	{
		$aCustomerDebt=Base::$db->GetAll(Base::GetSql('CustomerDebt'));
		$aCustomerDebtHash=Language::Array2Hash($aCustomerDebt,'id_user');

		if ($aItem) {
			foreach($aItem as $key => $aValue) {
				$aItem[$key]['current_debt_amount']=$aCustomerDebtHash[$aValue['id_user']]['amount'];
				$aIdUser[]=$aValue['id_user'];
				$aAdditionalAccountLog=Db::GetAll(Base::GetSql('Finance/AdditionalUserAccountLog',array(
				'id_user'=>$aValue['id_user'],
				'order'=>'post_date desc',
				'where'=>' and ual.amount<0'
				)));
				$aItem[$key]['aAdditionalAccountLog']=$aAdditionalAccountLog;
			}

			$aCustomerCartAssoc=Db::GetAssoc('Assoc/CustomerCart',array(
			'where'=> " and c.id_user in (".implode(',',$aIdUser).")
				and c.order_status in ('new','work','confirmed','road') and c.type_='order'"
			));

			foreach($aItem as $key => $aValue) {
				$aItem[$key]['current_cart_amount']=$aCustomerCartAssoc[$aValue['id_user']]['cart_sum_price'];
				$aItem[$key]['cart_count_work']=$aCustomerCartAssoc[$aValue['id_user']]['cart_count_work'];
				$aDeliveryCost=Db::GetAll("select dc.ser,dc.delivery_customer_cost,dc.delivery_customer_tax
				from delivery_cost_link dcl
				join delivery_cost dc on dc.id=dcl.id_delivery_cost
				where dcl.id_cart in (".$aValue['cart_id'].")
				group by dc.id");
				foreach ($aDeliveryCost as $aValueDC) {
					$aSer=unserialize($aValueDC['ser']);
					$aDataSer=$aSer['data'];
					$aTmp=array();
					foreach ($aDataSer as $aValueSer) {
						$aTmp['name']=$aValueSer['provider_region_name'];
						$aTmp['cost']=$aValueSer['correct_weight_delivery_cost'];
						$aTmp['tax']=$aValueSer['delivery_cost_tax'];
						$aTmp['total']=$aValueSer['total'];
						$aItem[$key]['delivery_cost'][]=$aTmp;
					}
					$aTmp=array();
					$aTmp['name']='customer';
					$aTmp['cost']=$aValueDC['delivery_customer_cost'];
					$aTmp['tax']=$aValueDC['delivery_customer_tax'];
					$aTmp['total']=$aValueDC['delivery_customer_cost']+$aValueDC['delivery_customer_tax'];
					$aItem[$key]['delivery_cost'][]=$aTmp;
				}
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function Create($bPrint=false)
	{
		Base::$aTopPageTemplate=array('panel/tab_manager.tpl'=>'invoice_customer');

		/* --[-- scan */
		if (!Base::$aRequest['id_cart_invoice']) {
			Db::AutoExecute("cart_invoice",array('id_user_customer'=>Base::$aRequest['id_user'],'id_user'=>Auth::$aUser['id']));
			$iCartInvoice=Db::InsertId();
			Base::Redirect('/?action=manager_invoice_customer_create&id_user='.Base::$aRequest['id_user']
			."&id_cart_invoice=".$iCartInvoice);
		}

		if (Base::$aRequest['search']['id_cart']) {

			$aData["id_user"]=Auth::$aUser["id"];
			$aData["id_cart_invoice"]=Base::$aRequest["id_cart_invoice"];
			$aIdCart=explode("/",Base::$aRequest["search"]["id_cart"]);

			if ($aIdCart[1]) {
				$aData["number"]=$aIdCart[1];
			} else {
				$aData["number"]=1;
			}
			$aData["id_cart"]=$aIdCart[0];

			Base::$db->AutoExecute("cart_invoice_log", $aData);

		} else {
			//Base::Message(array('MF_ERROR'=>"error scan"),false);
		}

		$this->aCartScan=Db::GetAll("
		select cil.* , sum(cil.number) as numbersum,ifnull(c.number,0) as cart_number
		from cart_invoice_log as cil
		left join cart as c on (
			c.id=cil.id_cart
			and c.id_invoice_customer=0
			and c.id_user = '".Base::$aRequest["id_user"]."'
			and c.order_status in ('store','end','office_sent','office_received'))
		where id_cart_invoice=".Base::$aRequest["id_cart_invoice"]."
		group by cil.id_cart order by cil.id desc");
		Base::$tpl->assign('aCartScan',$this->aCartScan);
		/* --]-- scan */

		if (Base::$aRequest['is_post']) {

			if (Base::$aRequest['row_check']) {
				$aUserCart=Base::$db->getAll("select * from cart c left join cart_package cp on cp.id=c.id_cart_package
				where c.id in (".implode(',',Base::$aRequest['row_check']).")");
				if (!$aUserCart) Base::Redirect('/?action=manager_invoice_customer_invoice&table_error=cart_not_checked');
				else {
					$aUserCartId=array();
					$aDeliveryRegion=array();
					foreach ($aUserCart as $aValue) {
						$dPriceTotal+=(Currency::PrintPrice($aValue['price'])*$aValue['number'] - $aValue['full_payment_discount']);
						$aUserCartId[]=$aValue['id'];
						if(!in_array($aValue['id_delivery_type_region'], $aDeliveryRegion))$aDeliveryRegion[]=$aValue['id_delivery_type_region'];
					}
				}
				$aInvoiceCustomer=Base::$aRequest['invoice_customer'];

				if (Base::$aRequest['data']['additional_account_log']) {
					$aUserAccountLog=Base::$db->getAll("select ual.* from user_account_log as ual
						where id in (".implode(',',Base::$aRequest['data']['additional_account_log']).")");
					if ($aUserAccountLog) foreach ($aUserAccountLog as $aValue) {
						$aUserAccountLogId[]=$aValue['id'];
						$dPriceTotal-=$aValue['amount'];
					}
				}

				$aInvoiceCustomer['total']=$dPriceTotal;
				$aInvoiceCustomer['previous_total']=$dPriceTotal;
				$aInvoiceCustomer['id_cart_list']=implode(', ',Base::$aRequest['row_check']);
				$aInvoiceCustomer['post_manager']=Auth::$aUser['login'];

				Db::Autoexecute('invoice_customer',$aInvoiceCustomer,'INSERT');
				$iInvoiceCustomer=Db::InsertId();
				if ($iInvoiceCustomer) {
					Base::$db->Execute("update cart set id_invoice_customer='$iInvoiceCustomer'
						where id in (".implode(',',Base::$aRequest['row_check']).")");

					if ($aUserAccountLogId) {
						$aInvoiceCustomerAdditional['id_invoice_customer']=$iInvoiceCustomer;
						foreach ($aUserAccountLogId as $iValue) {
							$aInvoiceCustomerAdditional['id_user_account_log']=$iValue;
							Db::AutoExecute('invoice_customer_additional',$aInvoiceCustomerAdditional);
						}
					}
					if ($bPrint) Base::Redirect('/?action=manager_invoice_customer_print&id='.$iInvoiceCustomer);

					if(count($aDeliveryRegion)>1)Base::Redirect('/?action=manager_invoice_customer&table_error=cart_different_stores');
					Base::Redirect('/?action=manager_invoice_customer&table_error=invoice_customer_created');
				}
			}
		}

		$aField['id_provider_invoice']=array('title'=>'Id Provider Invoice','type'=>'input','value'=>Base::$aRequest['search']['id_provider_invoice'],'name'=>'search[id_provider_invoice]');
		$aField['id_cart']=array('title'=>'Id cart (example 12354/5)','type'=>'input','value'=>'','name'=>'search[id_cart]','id'=>'search_scan','class'=>'search_scan','add_to_td'=>array(
		    'search_id_cart'=>array('type'=>'text','value'=>Base::$aRequest['search']['id_cart'])
		));
        $aField['id_user']=array('type'=>'hidden','name'=>'id_user','value'=>Base::$aRequest['id_user']);
        $aField['id_cart_invoice']=array('type'=>'hidden','name'=>'id_cart_invoice','value'=>Base::$aRequest['id_cart_invoice']);
		
        //righr form
        $aField['title']=array('type'=>'text','value'=>Language::GetMessage('Cart - Number / All in cart'));
        foreach ($this->aCartScan as $aValue){
            $aField[$aValue['id']]=array('type'=>'text','value'=>$aValue['id_cart'].' - '.$aValue['numbersum'].' / '.$aValue['cart_number']);
        }
        
		$aData=array(
		'sHeader'=>"method=get",
		//'sContent'=>Base::$tpl->fetch('manager_invoice_customer/form_invoice_create_search.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
// 		'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'manager_invoice_customer_create',
		'sReturnButton'=>'Clear',
		'sReturnAction'=>'manager_invoice_customer_create&id_user='.Base::$aRequest['id_user'],
		'bIsPost'=>0,
		'sError'=>$sError,
		);
		$oForm=new Form($aData);
		//$oForm->sRightTemplate='manager_invoice_customer/right_form_invoice_create_search.tpl';

		Base::$sText.=$oForm->getForm();


		$aCustomer=Base::$db->GetRow( Base::GetSql('Customer',array('id'=>Base::$aRequest['id_user'])) );
		//		$aAdditionalAccountLog=Db::GetAll(Base::GetSql('Finance/AdditionalUserAccountLog',array(
		//		'id_user'=>Base::$aRequest['id_user'],
		//		)));
		//		Base::$tpl->assign('aAdditionalAccountLog',$aAdditionalAccountLog);
		Base::$tpl->assign('aAccount',Db::GetAssoc('Assoc/Account'));

		//if ($aCustomer['id_language']==1) $sAccountWhere=" and a.is_ukraine='1'";
		//else $sAccountWhere=" and a.is_ukraine='0'";
		Base::$tpl->assign('aActiveAccount',Db::GetRow(Base::GetSql('Account',array(
		'is_active'=>1,
		'visible'=>1,
		'where'=> $sAccountWhere,
		))));

		$aSmartyTemplate=String::GetSmartyTemplate('manager_invoice_customer_create', $aData);
		Base::$sText.=$aSmartyTemplate['parsed_text'];

		if (Base::$aRequest['search']['id_provider_invoice'])
		$sWhere.=" and c.id_provider_invoice like '%".Base::$aRequest['search']['id_provider_invoice']."%'";

		/* 'error','ok' or 'too many','duplicate' checking only when id_cart set !!!*/
		$aIncomeCart=explode("/",Base::$aRequest["search"]["id_cart"]);
		if(!$aIncomeCart[1])$aIncomeCart[1]=1;
		if($aIncomeCart[0]){

			$aIdCountCartInvoiceLog=Base::$db->GetAssoc(
			"select cil.id_cart ,sum(cil.number)as number
			from cart_invoice_log as cil
			where cil.id_cart_invoice='"
			.Base::$aRequest["id_cart_invoice"]
			."' group by cil.id_cart ");

			/* get id-number pairs for carts*/
			$aIdCountCart=Base::$db->GetAssoc(
			"select c.id, c.number from cart as c where
			c.order_status in ('store','end','office_sent','office_received')
			and	id_invoice_customer='0'
			and id_user = '"
			.Base::$aRequest['id_user']
			."' "
			.$sWhere);

			$sScanResult='ok';
			if(in_array($aIncomeCart[0],array_keys($aIdCountCart)))
			{
				if($aIdCountCartInvoiceLog[$aIncomeCart[0]]>$aIdCountCart[$aIncomeCart[0]])
				$sScanResult='many';
			}else{
				$sScanResult='error'; //if not cart id
			}

			require_once("Sound.php");
			/*insert Status showing template (it will fadeOut)*/
			if($sScanResult=='ok')
			Base::$tpl->assign('iCurrentScannedCart',$aIncomeCart[0]);

			Base::$tpl->assign('sRes',$sScanResult);
			switch ($sScanResult){
				case "ok":
					$sSoundContent=Sound::InsertIntoPage("manager_invoice_customer:scan_ok","scan_ok.wav");
					break;
				case "error":
					$sSoundContent=Sound::InsertIntoPage("manager_invoice_customer:scan_error","scan_error.wav");
					break;
				case "many":
					$sSoundContent=Sound::InsertIntoPage("manager_invoice_customer:scan_many","scan_many.wav");
					break;
			}
			Base::$tpl->assign('sSound',$sSoundContent);
			Base::$tpl->assign('iTimeShow',Base::GetConstant("scaner:time_show_status",5000));
			Base::$sText.=Base::$tpl->fetch("manager_invoice_customer/scan_status_viewer.tpl");
		}


		$oTable=new Table();
		$oTable->sSql=Base::GetSql('Cart',array(
		'status_array'=>array("'store'","'end'","'office_sent'","'office_received'"),
		'id_invoice_customer'=>0,
		'id_user'=>Base::$aRequest['id_user'],
		'where'=>$sWhere,
		//'order'=>'order by cp.id',
		'join_delivery'=>1
		));

		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'man_Order #'),
		'id_cart_package'=>array('sTitle'=>'man_CP'),
		'code'=>array('sTitle'=>'CartCode'),
		'order_status'=>array('sTitle'=>'man_Order Status'),
		'name'=>array('sTitle'=>'Name'),
		'term'=>array('sTitle'=>'Term'),
		'weight'=>array('sTitle'=>'Weight'),
		'price'=>array('sTitle'=>'Price'),
		);
		$oTable->iRowPerPage=500;
		$oTable->sDataTemplate='manager_invoice_customer/row_cart.tpl';
		$oTable->sButtonTemplate='manager_invoice_customer/button_cart.tpl';
		$oTable->bCheckVisible=true;
		$oTable->bDefaultChecked=false;
		$oTable->bStepperVisible=false;
		$oTable->sFormHeader='method=post';
		$oTable->aCallback=array($this,'CallParseCreate');



		Base::$sText.=$oTable->getTable("Create Customer Invoice",'',' - '.$aCustomer['login']);
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseCreate(&$aItem)
	{
		foreach ($this->aCartScan as $sKey => $aValue) {
			$aIdCart[$aValue['id_cart']]=$aValue;
		}

		if ($aItem && $aIdCart) foreach ($aItem as $sKey => $aValue) {
			if (array_key_exists($aValue['id'],$aIdCart) && $aValue['number']<=$aIdCart[$aValue['id']]['numbersum'])
			{
				$aItem[$sKey]['bCheckTr']=true;
			}
		}

		foreach ($aItem as $aValue) {
			if($aValue['id_cart_package']!=$sIdCartPackage){
				$iCount=count($aTmp);
				$aTmp[]=array(
				'separator'=>1,
				'separator_header'=>Language::GetMessage("Group by order")." ".$aValue['id_cart_package'],
				'id'=>0,
				'row'=>$iCount,
				);
				$sIdCartPackage=$aValue['id_cart_package'];
				if(isset($sGroupId)) {
					$aTmp[$sGroupId]['checked_id']=$aCheckedId;
					$aCheckedId=array();
				}
				$sGroupId=count($aTmp)-1;
			}
			$aTmp[]=$aValue;
			$aCheckedId[]='row_check_'.(count($aTmp)-1);
			$aTmp[count($aTmp)-1]['GID']=$sGroupId;
		}
		$aTmp[$sGroupId]['checked_id']=$aCheckedId;
		$aItem = $aTmp;//Debug::PrintPre($aItem);
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangeRating()
	{
		if (Base::$aRequest['id_user'] && Base::$aRequest['num_rating']) {
			Rating::Change('store_customer',Base::$aRequest['id_user'],Base::$aRequest['num_rating']);
		}
		//Base::$oResponse->AddScript("window.location.reload();");
	}
	//-----------------------------------------------------------------------------------------------
	public function Invoice()
	{
		Base::$aTopPageTemplate=array('panel/tab_manager.tpl'=>'invoice_customer_invoice');

		if (Base::$aRequest['is_post']) {
			if (!Base::$aRequest['data']['id_account']) {
				$sError=Language::GetMessage("Please, fill id_account");
				Base::$aRequest['action']='manager_invoice_customer_invoice_edit';
				Base::$tpl->assign('aData',Base::$aRequest['data']);
			}
			else {
				//[----- UPDATE -----------------------------------------------------]
				$aInvoiceCusstomer=String::FilterRequestData(Base::$aRequest['data'],array('id_account'));
				Db::AutoExecute('invoice_customer',$aInvoiceCusstomer,'UPDATE',"id='".Base::$aRequest['id']."'");

				//[----- END UPDATE -------------------------------------------------]
				Form::RedirectAuto("&action=manager_invoice_customer_invoice&aMessage[MI_NOTICE]=invoice updated");
			}
		}

		if (Base::$aRequest['action']=='manager_invoice_customer_invoice_edit') {
			$aInvoiceCustomer=Db::GetRow(Base::GetSql('InvoiceCustomer',array('id'=>Base::$aRequest['id'])));
			Base::$tpl->assign('aData',$aData=$aInvoiceCustomer);

			Base::$tpl->assign('aAccount',$aAccount=Db::GetAssoc('Assoc/Account'));

			$aField['id_account']=array('title'=>'Account','type'=>'select','options'=>$aAccount,'selected'=>$aData['id_account'],'name'=>'data[id_account]');
			
			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=>"Invoice Customer",
			//'sContent'=>Base::$tpl->fetch('manager_invoice_customer/form_invoice.tpl'),
			'aField'=>$aField,
			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>'manager_invoice_customer_invoice',
			'sReturnButton'=>'<< Return',
			'sReturnAction'=>'manager_invoice_customer_invoice',
			'sError'=>$sError,
			);
			$oForm=new Form($aData);

			Base::$sText.=$oForm->getForm();
			return;
		}


		if (Base::$aRequest['subaction']=='make_visible') {
			$aInvoiceCustomer['visible']=Base::$aRequest['visible'];
			Base::$db->AutoExecute('invoice_customer',$aInvoiceCustomer,'UPDATE',"id='".Base::$aRequest['id']."'");
		}

		if (Base::$aRequest['subaction']=='send') {
			$this->Send(Base::$aRequest['id']);
			Form::RedirectAuto();
		}

		if (Base::$aRequest['subaction']=='end') {
			$this->End(Base::$aRequest['id']);
			Form::RedirectAuto();
		}

		if (Base::$aRequest['subaction']=='send_end') {
			$this->Send(Base::$aRequest['id']);
			$this->End(Base::$aRequest['id']);
			Form::RedirectAuto();
		}

		if (Base::$aRequest['subaction']=='preview') {
			//$aInvoiceCustomer['visible']=Base::$aRequest['visible'];
			//Base::$db->AutoExecute('invoice_customer',$aInvoiceCustomer,'UPDATE',"id='".Base::$aRequest['id']."'");

			$aInvoiceCustomer=Base::$db->GetRow(Base::GetSql('InvoiceCustomer',array(
			'id'=>Base::$aRequest['id'],
			)));

			Base::$tpl->assign('aInvoiceCustomer',$aInvoiceCustomer);
			Base::$tpl->assign('aCart',$aCart);


			Base::$sText.=Base::$tpl->fetch('manager_invoice_customer/preview.tpl');
			return;
		}


		if (Base::$aRequest['subaction']=='cancel') {
			$aInvoiceCustomer=Db::GetRow(Base::GetSql('InvoiceCustomer',array('id'=>Base::$aRequest['id'])));
			if (!$aInvoiceCustomerUpdate['is_sent'] && !$aInvoiceCustomerUpdate['is_end'] && Base::$aRequest['id']) {
				Db::Execute("update cart set id_invoice_customer='0' where id_invoice_customer='".$aInvoiceCustomer['id']."'");
				Db::Execute("delete from invoice_customer_additional where id_invoice_customer='".$aInvoiceCustomer['id']."'");
				Db::Execute("delete from invoice_customer where id='".$aInvoiceCustomer['id']."'");
			}
			Form::RedirectAuto();
		}

		if (Base::$aRequest['subaction']=='return') {
			$aInvoiceCustomer=Db::GetRow(Base::GetSql('InvoiceCustomer',array('id'=>Base::$aRequest['id'])));
			if (!$aInvoiceCustomerUpdate['is_sent'] && !$aInvoiceCustomerUpdate['is_end'] && Base::$aRequest['id']) {
				Db::Execute("update invoice_customer set id_travel_sheet=0,is_travel_sheet=1 where id='".$aInvoiceCustomer['id']."'");
				Db::Execute("delete from travel_sheet_notification where id_travel_sheet='".$aInvoiceCustomer['id_travel_sheet']."'"
				." and id_user='".$aInvoiceCustomer['id_user']."'");
			}
			Form::RedirectAuto();
		}

		Base::$tpl->assign('aIsEnd',$aIsEnd=array(
		''=>Language::GetMessage('All'),
		'0'=>Language::GetMessage('Not IsEnd'),
		'1'=>Language::GetMessage('IsEnd'),
		));
		$aPostManager=array(''=>Language::GetMessage('All'))+Db::GetAssoc("select ic.post_manager as id, ic.post_manager as value
			from invoice_customer as ic
			group by ic.post_manager
			");
		Base::$tpl->assign('aPostManager',$aPostManager);
		//		Base::$tpl->assign('aIdLanguage',array(
		//		''=>Language::GetMessage('All'),
		//		ManagerInvoiceCustomer::CUSTOMER_TYPE_UKRAINE=>Language::GetMessage('Ukraine customers'),
		//		ManagerInvoiceCustomer::CUSTOMER_TYPE_NOT_UKRAINE=>Language::GetMessage('Not ukraine customers'),
		//		ManagerInvoiceCustomer::CUSTOMER_TYPE_NOT_SET=>Language::GetMessage('Not set customer'),
		//		));

		
		
		
		Base::$tpl->assign('aNameUser',$aNameUser=array(0 =>'')+Db::GetAssoc("select u.login, concat(ifnull(uc.name,''),' ( ',u.login,' )',
				IF(uc.phone is null or uc.phone='','',concat(' ".
						Language::getMessage('tel.')." ',uc.phone))) name
		from user as u
		inner join user_customer as uc on u.id=uc.id_user
		where u.visible=1 /*and uc.name is not null and trim(uc.name)!=''*/
		/*and uc.id_manager='".Auth::$aUser['id_user']."'*/
		order by uc.name"));
		Resource::Get()->Add('/js/select_search.js');
		
		$aField['search_login']=array('title'=>'Login','type'=>'select','options'=>$aNameUser,'name'=>'search_login','selected'=>Base::$aRequest['search_login'],'class'=>'select_name_user');
// 		$aField['login']=array('title'=>'Login','type'=>'input','value'=>Base::$aRequest['search']['login'],'name'=>'search[login]');
		$aField['fio']=array('title'=>'fio','type'=>'input','value'=>Base::$aRequest['search']['fio'],'name'=>'search[fio]');
		$aField['is_end']=array('title'=>'IsEnd','type'=>'select','options'=>$aIsEnd,'name'=>'search[is_end]','selected'=>Base::$aRequest['search']['is_end']);
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		$aField['id']=array('title'=>'Id','type'=>'input','value'=>Base::$aRequest['search']['id'],'name'=>'search[id]');
		$aField['post_manager']=array('title'=>'post manager','type'=>'select','options'=>$aPostManager,'name'=>'search[post_manager]','selected'=>Base::$aRequest['search']['post_manager']);
		
		$aData=array(
		'sHeader'=>"method=get",
		//'sContent'=>Base::$tpl->fetch('manager_invoice_customer/form_invoice_customer_search.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sGenerateTpl'=>'form/index_search.tpl',
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>'manager_invoice_customer_invoice',
		'sReturnButton'=>'Clear',
		'bIsPost'=>0,
		'sError'=>$sError,
		);
		$oForm=new Form($aData);

		Base::$tpl->assign('sForm',$oForm->getForm());

		Base::$tpl->assign('iIsTravelSheetCount',Db::GetOne("select count(*) from invoice_customer where is_travel_sheet='1'"));
		Base::$sText.=$oForm->getForm();
// 		Base::$sText.=Base::$tpl->fetch('manager_invoice_customer/outer_invoice.tpl');

		// --- search ---
// 		if (!Base::$aRequest['search']) {
// 			Base::$aRequest['search']['date'] = 1;
// 			Base::$aRequest['search']['date_from'] = date("d-m-Y H:i:s", time()-30*86400);
// 			Base::$aRequest['search']['date_to'] = date("d-m-Y H:i:s", time()+86400);
// 		}
		
		if (Base::$aRequest['search']['login']) $sWhere.=" and u.login like '%".Base::$aRequest['search']['login']."%'";
		if (Base::$aRequest['search']['fio']) $sWhere.=" and uc.name like '%".Base::$aRequest['search']['fio']."%'";
		
		if (Base::$aRequest['search']['date']) {
			$sWhere.=" and ic.post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
				and ic.post_date<='".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."'";
		}
		if (Base::$aRequest['search']['is_end']!='') $sWhere.=" and ic.is_end='".Base::$aRequest['search']['is_end']."'";
		if (Base::$aRequest['search']['id']) $sWhere.=" and ic.id='".Base::$aRequest['search']['id']."'";
		if (Base::$aRequest['search']['is_travel_sheet']) $sWhere.=" and ic.is_travel_sheet='1'";
		if (Base::$aRequest['search']['id_travel_sheet'])
		$sWhere.=" and ic.id_travel_sheet='".Base::$aRequest['search']['id_travel_sheet']."'";
		if (Base::$aRequest['search']['id_partner_region'])
		$sWhere.=" and uc.id_partner_region='".Base::$aRequest['search']['id_partner_region']."'";
		if (Base::$aRequest['search']['post_manager'])
		$sWhere.=" and ic.post_manager='".Base::$aRequest['search']['post_manager']."'";

		// --------------

		$oTable=new Table();
		$oTable->sSql=Base::GetSql('InvoiceCustomer',array('where'=>$sWhere));
		$_SESSION['manager_invoice_customer']['current_sql']=$oTable->sSql;

		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'CustID'),
		'login'=>array('sTitle'=>'Login'),
		'post_date'=>array('sTitle'=>'PostDate'),
		'total'=>array('sTitle'=>'Invoice Total'),
		'is_end'=>array('sTitle'=>'IsEnd'),
		'action'=>array(),
		);
		$oTable->iRowPerPage=20;
		$oTable->sDataTemplate='manager_invoice_customer/row_invoice.tpl';
		$oTable->sButtonTemplate='manager_invoice_customer/button_invoice.tpl';
		$oTable->aOrdered="order by ic.id desc ";
		$oTable->bCheckVisible=true;
		Base::$sText.=$oTable->getTable("Customer Invoice List");
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * @param $iIdUser for customers printing of this invoice
	 */
	public function PrintInvoice($iIdUser='',$bReturnContent=false)
	{
		if ($iIdUser) $iIsSent=1;

		$aCustomerInvoice=Base::$db->GetRow(Base::GetSql('InvoiceCustomer',array(
		'id'=>Base::$aRequest['id'],
		'id_user'=>$iIdUser,
		'is_sent'=>$iIsSent,
		)));
		if (!$aCustomerInvoice) Base::Redirect('/?action=auth_type_error');

		Base::$tpl->assign('aCustomerInvoice',$aCustomerInvoice);
		$aInvoiceAccount=Db::GetRow(Base::GetSql('Account',array('id'=>$aCustomerInvoice['id_account'])));
		Base::$tpl->assign('aInvoiceAccount',$aInvoiceAccount);

		//		$aAdditionalItem=Db::GetAll(Base::GetSql('UserAccountLog'
		//		,array('join_additional'=>'1','id_invoice_customer'=>$aCustomerInvoice['id'],
		//		'join_calculator_item'=>'1')));
		//		Base::$tpl->assign('aAdditionalItem',$aAdditionalItem);

		$aUserInvoice=Db::GetAll(Base::GetSql('InvoiceCustomer/UserInvoice',array(
		'id_invoice_customer'=>Base::$aRequest['id'],
		'id_user'=>$aCustomerInvoice['id_user'],
		)));
		// recacl total price with round
		foreach ($aUserInvoice as $iKey => $aValue) 
			$aUserInvoice[$iKey]['total_price'] = ($aUserInvoice[$iKey]['number'] * 
				Currency::PrintPrice($aUserInvoice[$iKey]['price'])) - $aUserInvoice[$iKey]['full_payment_discount'];
		
		Base::$tpl->assign('aUserInvoice',$aUserInvoice);
		$aCustomer = Db::GetRow(Base::GetSql('Customer', array('id'=>$aCustomerInvoice['id_user'])));
		if ($aUserInvoice && $aCustomer['id_language']!=1) {
			$aFactureRight=ManagerInvoiceCustomer::GetFactureRight($aUserInvoice,$aAdditionalItem);
			Base::$tpl->assign('aFactureRight',$aFactureRight);
		}


		$aInvoice = Db::GetRow(Base::GetSql('InvoiceCustomer', array('id'=>Base::$aRequest['id'])));
		$aCustomer = Db::GetRow(Base::GetSql('Customer', array('id'=>$aInvoice['id_user'])));

		// get Currency
		$aCurrency = Base::$db->getAssoc("select id,code from currency where code='USD' or code='UAH' order by num");
		$iCurrencyDefault = Base::$aRequest['id_currency'] ? Base::$aRequest['id_currency'] :
		Base::$db->getOne("select id from currency where code='UAH' order by num");
		Base::$tpl->assign('aCurrency', $aCurrency);
		Base::$tpl->assign('iCurrencyDefault', $iCurrencyDefault);
		Base::$tpl->assign('sCurrencyDefault', $aCurrency[$iCurrencyDefault]);

		$aInvoice=array(
		'sName'=>$aCustomer['name'].' (login: '.$aCustomer['login'].')',
		'sPhone'=>$aCustomer['phone'],
		'sAmount'=>$aCustomer['amount'],
		'date'=>DateFormat::GetPostDate($aCustomerInvoice['post_date']),
		'fTotalSum'=> Base::$oCurrency->PrintPrice($aCustomerInvoice['total'],$iCurrencyDefault),
		'sNum'=>date('ymd', strtotime($aCustomerInvoice['post_date'])).'-'.$aCustomerInvoice['id'],
		'sTotalSumText'=>Currency::CurrecyConvert(Base::$oCurrency->PrintPrice($aCustomerInvoice['total']
		,$iCurrencyDefault),
		$aCurrency[$iCurrencyDefault]),
		);
		Base::$tpl->assign('aInvoice',$aInvoice);

		/* Ukrainian invoice */
		if (Base::$aRequest['nodata']=="yes") Base::$tpl->assign('bNoFopData',true);

		$sContent=Base::$tpl->fetch('manager_invoice_customer/print/print_action_button.tpl').
		Base::$tpl->fetch('manager_invoice_customer/print/customer_invoice.tpl');

		if ($bReturnContent) return $sContent;

		PrintContent::Append($sContent);
		Base::Redirect('?action=print_content&custom_button[custom_action]=manager_invoice_customer_invoice'.
		'&custom_button[translate_code]=return_to_invoices');
	}
	//-----------------------------------------------------------------------------------------------
	public function GetFactureRight(&$aUserInvoice,&$aAdditionalItem,$aDeliveryCost=array(),$dTarifTax=0,$sCurrency='USD')
	{
		foreach ($aUserInvoice as $sKey => $aValue) {
			$aUserInvoice[$sKey]['price_unit']=$aValue['price']-$aValue['price_tax']-$aValue['price_fee']
			-$aValue['weight_delivery_cost'];
			$aFactureRight['subtotal']+=$aValue['number']*$aValue['price'];
			if($aValue['calculate_mode']==1){
				$aFactureRight['tax_total']+=$aValue['number']*($aValue['price_tax']);
				$aFactureRight['total_add_tax']+=$aValue['number']*($aValue['price_tax']);
			}else
			$aFactureRight['tax_total']+=$aValue['number']*($aValue['price_tax']+$aValue['price_fee']);
			$aFactureRight['weight_delivery_cost_total']+=$aValue['number']*$aValue['weight_delivery_cost'];
		}
		$aFactureRight['total_price_place']=0;
		$aFactureRight['total_additional_payment']=0;
		if ($aAdditionalItem) foreach ($aAdditionalItem as $aValue){
			if ($aValue['id_subconto2']==ManagerInvoiceCustomer::LOG_PRICE_PLACE){
				$aFactureRight['total_price_place']-=$aValue['amount'];
			}
			if ($aValue['id_subconto2']==ManagerInvoiceCustomer::LOG_ADDITIONAL_PAYMENT){
				$aFactureRight['total_additional_payment']-=$aValue['amount'];
			}
		}
		if($dTarifTax>0){
			$aFactureRight['total_price_place']=round($aFactureRight['total_price_place']/(1+$dTarifTax),2);
			$aFactureRight['tax_total']+=round($aFactureRight['total_price_place']*$dTarifTax,2);
			$aFactureRight['total_add_tax']+=round($aFactureRight['total_price_place']*$dTarifTax,2);
			$aFactureRight['total_additional_payment']=round($aFactureRight['total_additional_payment']/(1+$dTarifTax),2);
			$aFactureRight['tax_total']+=round($aFactureRight['total_additional_payment']*$dTarifTax,2);
			$aFactureRight['total_add_tax']+=round($aFactureRight['total_additional_payment']*$dTarifTax,2);
		}elseif($dTarifTax<0){
			$aFactureRight['total_price_place']=round($aFactureRight['total_price_place']/(1-$dTarifTax),2);
			$aFactureRight['total_additional_payment']=round($aFactureRight['total_additional_payment']/(1-$dTarifTax),2);
			$aFactureRight['tax_total']=0;
			$aFactureRight['total_add_tax']=0;
		}
		$aFactureRight['total']=$aFactureRight['subtotal']+$aFactureRight['total_price_place']
		+$aFactureRight['total_additional_payment'];
		if($aDeliveryCost){
			$aFactureRight['total_price_place']+=$aDeliveryCost['dc_total2'];
			$aFactureRight['weight_delivery_cost_total']+=$aDeliveryCost['dc_total1'];
			$aFactureRight['tax_total']+=$aDeliveryCost['dc_tax'];
			$aFactureRight['total']+=$aDeliveryCost['dc_total'];
		}
		$aFactureRight['total']+=$aFactureRight['total_add_tax'];
		$aFactureRight['total_text']=Currency::CurrecyConvert(Currency::Price($aFactureRight['total'],$sCurrency),$sCurrency);

		return $aFactureRight;
	}
	//-----------------------------------------------------------------------------------------------
	public function Recalculate($iIdInvoiceCustomer)
	{
		$aUserCart=Base::$db->getAll("select * from cart where id in (".implode(',',Base::$aRequest['row_check']).")");
		if (!$aUserCart) return;

		$aUserCartId=array();
		foreach ($aUserCart as $aValue) {
			$dPriceTotal+=($aValue['price']*$aValue['number'] - $aValue['full_payment_discount']);
			$aUserCartId[]=$aValue['id'];
		}

		$aInvoiceCustomer=Base::$aRequest['invoice_customer'];

		if (Base::$aRequest['data']['additional_account_log']) {
			$aUserAccountLog=Base::$db->getAll("select ual.* from user_account_log as ual
						where id in (".implode(',',Base::$aRequest['data']['additional_account_log']).")");
			if ($aUserAccountLog) foreach ($aUserAccountLog as $aValue) {
				$aUserAccountLogId[]=$aValue['id'];
				$dPriceTotal-=$aValue['amount'];
			}
		}

		$aInvoiceCustomer['total']=$dPriceTotal;
	}
	//-----------------------------------------------------------------------------------------------
	public function CreateOfficeTravelSheet()
	{
		if (Base::$aRequest['row_check'])
		{
			$sWhere.=" and c.id_user in (".implode(',',Base::$aRequest['row_check']).")
				 and c.order_status in ('store','end','office_received','office_sent')
				 and c.id_invoice_customer='0' and c.id_travel_sheet='0'";
			$aCart=Db::GetAll(Base::GetSql('Part/Search',array(
			'where'=>$sWhere,
			)));
			if ($aCart) {
				$aPartnerRegion=Db::GetRow(Base::GetSql('PartnerRegion',array('id'=>$aCart[0]['id_partner_region'])));

				$aTravelSheet=array(
				'type_'=>'office',
				'additional_info'=>$aPartnerRegion['name'],
				'id_office'=>Base::$aRequest['id_office'],
				);
				Db::AutoExecute('travel_sheet',$aTravelSheet);
				$iIdTravelSheet=Db::InsertId();

				foreach ($aCart as $aValue) $aCartId[]=$aValue['id'];
				Db::Execute("update cart set id_travel_sheet='".$iIdTravelSheet."' where id in (".implode(',',$aCartId).")");
			}

			Base::Redirect("/?action=travel_sheet");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function IsTravelSheetAdd()
	{
		if (Base::$aRequest['id']) $aCustomerInvoice=Base::$db->GetRow(Base::GetSql('InvoiceCustomer',array(
		'id'=>Base::$aRequest['id'],
		)));
		if ($aCustomerInvoice && !$aCustomerInvoice['id_travel_sheet'] && !$aCustomerInvoice['is_travel_sheet']) {
			Db::Execute("update invoice_customer set is_travel_sheet='1' where id='".$aCustomerInvoice['id']."'");
		}
		Form::RedirectAuto();
	}
	//-----------------------------------------------------------------------------------------------
	public function IsTravelSheetClear()
	{
		Db::Execute("update invoice_customer set is_travel_sheet='0'");
		Base::Redirect("/?action=manager_invoice_customer_invoice");
	}
	//-----------------------------------------------------------------------------------------------
	public function IsTravelSheetBrowse()
	{
		Form::RedirectAuto();
	}
	//-----------------------------------------------------------------------------------------------
	public function CreateInvoiceTravelSheet()
	{
		if(Base::$aRequest['row_check'])
		$sInvoiceCustomerSelector=" and ic.id in(".implode(',', Base::$aRequest['row_check']).") ";

		$aTravelInvoiceCustomer=Db::GetAll(Base::GetSql('InvoiceCustomer',
		array('where'=>" and ic.is_travel_sheet='1'".$sInvoiceCustomerSelector)));
		if ($aTravelInvoiceCustomer) {
			foreach ($aTravelInvoiceCustomer as $aValue) $aInvoiceCustomerId[]=$aValue['id'];

			$aTravelSheet=array('type_'=>'invoice',);
			Db::AutoExecute('travel_sheet',$aTravelSheet);
			$iTravelSheetId=Db::InsertId();

			Db::Execute("update invoice_customer set is_travel_sheet='0', id_travel_sheet='".$iTravelSheetId."'
				where id in (".implode(',',$aInvoiceCustomerId).")");
		}
		Form::Redirect('/?action=travel_sheet');
	}
	//-----------------------------------------------------------------------------------------------
	public function GetInvoiceExcel()
	{
		$aCustomerInvoice=Db::GetRow(Base::GetSql('InvoiceCustomer',array(
		'id'=>Base::$aRequest['id'],
		)));
		if ($aCustomerInvoice) InvoiceAccountLog::SendInvoiceExcel(array($aCustomerInvoice['id']));
	}
	//-----------------------------------------------------------------------------------------------
	public function GetInvoiceExcelFitlered()
	{
		if (Base::$aRequest['row_check']) {
			InvoiceAccountLog::SendInvoiceExcel(Base::$aRequest['row_check']);
		} else Form::Redirect('/?action=manager_invoice_customer_invoice');
	}
	//-----------------------------------------------------------------------------------------------
	public function GetInvoiceExcelAll()
	{
		$aCustomerInvoiceAll=Db::GetAll($_SESSION['manager_invoice_customer']['current_sql']);
		if ($aCustomerInvoiceAll) {
			foreach ($aCustomerInvoiceAll as $aValue) $aCustomerInvoiceId[]=$aValue['id'];
			InvoiceAccountLog::SendInvoiceExcel($aCustomerInvoiceId);
		} else Form::Redirect('/?action=manager_invoice_customer_invoice');
	}
	//-----------------------------------------------------------------------------------------------
	public function Send($iInvoiceCustomer)
	{
		$aInvoiceCustomer=Db::GetRow(Base::GetSql('InvoiceCustomer',array('id'=>$iInvoiceCustomer)));

		if (!$aInvoiceCustomer['is_sent']) {
			$aInvoiceCustomerUpdate['is_sent']=1;
			$aInvoiceCustomerUpdate['post_date']=date('Y-m-d H:i:s');
			Db::AutoExecute('invoice_customer',$aInvoiceCustomerUpdate,'UPDATE',"id='".$iInvoiceCustomer."'");
			InvoiceAccountLog::Add($aInvoiceCustomer['id_user'],$aInvoiceCustomer['id'],'invoice_customer'
			,-$aInvoiceCustomer['total']);

			$aCartAssoc=Db::GetAssoc('Assoc/Cart',array('where'=>" and c.id_invoice_customer='".$iInvoiceCustomer."'"));
			if ($aCartAssoc) Db::Execute("update cart set is_endable='1'
					where id in (".implode(',',array_keys($aCartAssoc)).")");

			Message::CreateDelayedNotification($aInvoiceCustomer['id_user'],'invoice_is_sent'
			,array('aInvoiceCustomer'=>$aInvoiceCustomer),true);

			if ($aInvoiceCustomer['id_language']!=1) {
				Base::$aRequest['id']=$aInvoiceCustomer['id'];
				$sInvoiceFacture=$this->PrintInvoice('',true);

				$iMailId=Mail::AddDelayed($aInvoiceCustomer['email'],Language::GetMessage('invoice facture sent'),$sInvoiceFacture);
				Db::AutoExecute('mail_delayed',array('id_language'=>$aInvoiceCustomer['id_language']),'UPDATE',"id='".$iMailId."'");
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function End($iInvoiceCustomer)
	{
		$aInvoiceCustomerUpdate['is_end']=1;
		$aInvoiceCustomerUpdate['post_date']=date('Y-m-d H:i:s');
		$aInvoiceCustomerUpdate['end_manager']=Auth::$aUser['login'];
		Db::AutoExecute('invoice_customer',$aInvoiceCustomerUpdate,'UPDATE',"id='".$iInvoiceCustomer."'");
		$aCart=Db::GetAll(Base::GetSql('Cart',array('id_invoice_customer'=>$iInvoiceCustomer)));
		if ($aCart) {
			$oManager=new Manager();
			foreach ($aCart as $aValue) {
				if ($aValue['order_status']!='end')
				$oManager->ProcessOrderStatus($aValue['id'],'end','','','','','','','',true);
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function GetInvoiceFactureExcel()
	{
		InvoiceAccountLog::CreateExcelInvoiceFacture(Base::$aRequest['id'],true);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetInvoiceFactureExcelFitlered()
	{
		if (Base::$aRequest['row_check']) {
			$this->SendInvoiceFactureExcel(Base::$aRequest['row_check']);
		} else Form::Redirect('/?action=manager_invoice_customer_invoice');
	}
	//-----------------------------------------------------------------------------------------------
	public function GetInvoiceFactureExcelAll()
	{
		$aCustomerInvoiceAll=Db::GetAll($_SESSION['manager_invoice_customer']['current_sql']);
		if ($aCustomerInvoiceAll) {
			foreach ($aCustomerInvoiceAll as $aValue) $aCustomerInvoiceId[]=$aValue['id'];
			$this->SendInvoiceFactureExcel($aCustomerInvoiceId);
		} else Form::Redirect('/?action=manager_invoice_customer_invoice');
	}
	//-----------------------------------------------------------------------------------------------
	public function SendInvoiceFactureExcel($aInvoiceId)
	{
		if ($aInvoiceId) {
			foreach($aInvoiceId as $iValue) {
				$aFullFileName[]=InvoiceAccountLog::CreateExcelInvoiceFacture($iValue);
			}
			$oZipArchive = new ZipArchive();
			$sFileName='InvoiceFactureArchive_'.DateFormat::GetFileDateTime(time(),'',false)."_".uniqid().'.zip';
			$sFullFileName=SERVER_PATH.'/imgbank/temp_upload/'.$sFileName;
			if ($oZipArchive->open($sFullFileName, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE)) {
				foreach ($aFullFileName as $aValue) {
					$oZipArchive->AddFile($aValue['full_file_name'],$aValue['file_name']);
				}
			}
			$oZipArchive->close();

			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=\"".$sFileName."\"");
			die(file_get_contents($sFullFileName));
		}

	}
	//-----------------------------------------------------------------------------------------------
	public function GetInvoiceListExcel()
	{
		$aInvoice=Db::GetAll($_SESSION['manager_invoice_customer']['current_sql']);
		//Debug::PrintPre($aInvoice);

		$oExcel = new Excel();
		$aHeader=array(
		'A'=>array("value"=>"invoice ID", "autosize"=>true),
		'B'=>array("value"=>"date", "autosize"=>true),
		'C'=>array("value"=>"client", "autosize"=>true),
		'D'=>array("value"=>"currency", "autosize"=>true),
		'E'=>array("value"=>"total cart", "autosize"=>true),
		'F'=>array("value"=>"total delivery", "autosize"=>true),
		'G'=>array("value"=>"total", "autosize"=>true),
		'H'=>array("value"=>"vat", "autosize"=>true),
		'I'=>array("value"=>"total with vat", "autosize"=>true),
		'J'=>array("value"=>"address", "autosize"=>true),
		'K'=>array("value"=>"country", "autosize"=>true),
		);

		$oExcel->SetHeaderValue($aHeader,1,false);
		$oExcel->SetAutoSize($aHeader);
		$oExcel->DuplicateStyleArray("A1:K1");
		$oExcel->SetTitle('List Invoice Excel');

		$i=$j=2;
		foreach ($aInvoice as $aValue)
		{
			$bFactureRus=($aValue['id_language']==1);
			$dTarifTax=Manager::GetVat($aValue['post_date']);
			if($aValue['id_partner_region_group']==3 && $aValue['vat_number'] && $aValue['vat_is_confirmed']){
				//EU with VAT
				$aValue['type_invoice']=1;
			}elseif($aValue['id_partner_region_group']==3){
				//EU without VAT
				$aValue['type_invoice']=2;
			}else{
				//WORLD
				$aValue['type_invoice']=3;
				$dTarifTax=0;
			}
			$aUserInvoice=Db::GetAll(Base::GetSql('InvoiceCustomer/UserInvoice',array(
			'id_invoice_customer'=>$aValue['id'],//Base::$aRequest['id'],
			'id_user'=>$aValue['id_user'],
			'join_delivery_cost'=>1,
			)));
			$aDeliveryCostId=array();
			$aTotal=array();
			foreach ($aUserInvoice as $sKeyUserInvoice=>$aValueUserInvoice) {
				if($aValueUserInvoice['delivery_cost_id'] && !in_array($aValueUserInvoice['delivery_cost_id'], $aDeliveryCostId)){
					$aDeliveryCostId[]=$aValueUserInvoice['delivery_cost_id'];
					$aTotal['dc_total1']+=$aValueUserInvoice['delivery_cost_cost'];
					$aTotal['dc_total2']+=$aValueUserInvoice['delivery_customer_cost'];
					$aTotal['dc_total']+=$aValueUserInvoice['delivery_cost_total']
					+$aValueUserInvoice['delivery_customer_cost']+$aValueUserInvoice['delivery_customer_tax'];
					$aTotal['dc_tax']+=$aValueUserInvoice['delivery_cost_tax']+$aValueUserInvoice['delivery_customer_tax'];
				}
				if(!$bFactureRus && !$aValueUserInvoice['delivery_cost_id']){
					$aUserInvoice[$sKeyUserInvoice]['calculate_mode']=1;
					if($dTarifTax){
						$dPrice=$aValueUserInvoice['price'];
						$aUserInvoice[$sKeyUserInvoice]['price']=round($aValueUserInvoice['price']/(1+$dTarifTax),2);
						$aUserInvoice[$sKeyUserInvoice]['price_tax']=$dPrice-$aUserInvoice[$sKeyUserInvoice]['price'];
					}else{
						$aUserInvoice[$sKeyUserInvoice]['price_tax']=0;
					}
				}elseif(!$bFactureRus && $aValueUserInvoice['delivery_cost_id']){
					if($dTarifTax==0){
						$aUserInvoice[$sKeyUserInvoice]['price_tax']=0;
					}
				}

			}
			$aAdditionalItem=Db::GetAll(Base::GetSql('UserAccountLog'
			,array('join_additional'=>'1','id_invoice_customer'=>$aValue['id'],
			'join_calculator_item'=>'1')));
			if($aValue['type_invoice']==1)
			$aFuctureRight=ManagerInvoiceCustomer::GetFactureRight($aUserInvoice,$aAdditionalItem,$aTotal,$dTarifTax*(-1),$aValue['code_currency']);
			else
			$aFuctureRight=ManagerInvoiceCustomer::GetFactureRight($aUserInvoice,$aAdditionalItem,$aTotal,$dTarifTax,$aValue['code_currency']);

			$oExcel->SetCellValue('A'.$i, date('ymd', strtotime($aValue['post_date'])).'-'.$aValue['id']);
			$oExcel->SetCellValue('B'.$i, $aValue['post_date']);
			$oExcel->SetCellValue('C'.$i, $aValue['login']." (".$aValue['name'].")");
			$oExcel->SetCellValue('D'.$i, $bFactureRus?'UAH':'USD');
			$oExcel->SetCellValue('E'.$i, $aFuctureRight['subtotal']);
			$oExcel->SetCellValue('F'.$i, $aFuctureRight['total_price_place']);//+$aFuctureRight['weight_delivery_cost_total']
			$oExcel->SetCellValue('G'.$i, $aFuctureRight['total_price_place']+$aFuctureRight['subtotal']);
			$oExcel->SetCellValue('H'.$i, $aFuctureRight['tax_total']);
			$oExcel->SetCellValue('I'.$i, $aValue['total']);
			$oExcel->SetCellValue('J'.$i, $aValue['region_name'].', '.$aValue['city'].', '.$aValue['address']);
			$oExcel->SetCellValue('K'.$i, $aValue['region_name']);

			$i++;
		}

		$sFileName='ListInvoice_'.DateFormat::GetFileDateTime(time(),'',false).".xls";
		$oExcel->WriterExcel5($sFileName,true);

	}
	//---------------------------------------------------------------------------------------------------------
	function ChangeCustomerType()
	{
		$_SESSION[$this->sPrefix]['customer_type']=Base::$aRequest['id'];
		Base::$oResponse->AddScript("window.location.reload();");
	}
	//-----------------------------------------------------------------------------------------------
	public function DeliveryCalculator()
	{
		Base::$bXajaxPresent=true;
		if(Base::$aRequest['return']){
			Base::$tpl->assign('sReturn',Base::$aRequest['return']);
			$_SESSION['return']=Base::$aRequest['return'];
		}

		$oCalculatorZone=new CalculatorZone();

		if(Base::$aRequest['id_user']){
			$_SESSION['current_delivery_cost']['id_user']=Base::$aRequest['id_user'];
			$aUser=Db::GetRow(Base::GetSql("Customer",array('id'=>$_SESSION['current_delivery_cost']['id_user'])));
			if($aUser) $sNameCountry=$aUser['region_name'];
			if($sNameCountry){
				Base::$tpl->assign('sNameCountry',$sNameCountry);
				$iCountry=Db::GetOne("select id from calculator_country where name like '%".$sNameCountry."%'");
				Base::$aRequest['search']['id_provider_region_to']=$iCountry;
				$_REQUEST['search']['id_provider_region_to']=$iCountry;
			}
		}

		if(!Base::$aRequest['search']['weight']){
			$sError="You need to fill all the inputs";

			$aCart=Db::GetAll(Base::GetSql('Cart',array(
			'status_array'=>array("'store'","'end'","'office_sent'","'office_received'"),
			'id_invoice_customer'=>0,
			'id_user'=>$_SESSION['current_delivery_cost']['id_user'],
			'where'=>' and dc.delivery_customer_cost is null and dc.cost is not null and weight_delivery_cost=0 ',
			'order'=>'order by cp.id',
			'join_delivery'=>1,
			'join_delivery_cost'=>1
			)));
			switch ($aCart[0]['id_delivery_type']) {
				case '20':
					$sDeliveryType='dhl_PARCEL';
					break;

				case '24':
					$sDeliveryType='dhl_EXPRES';
					break;

				case '25':
					$sDeliveryType='royal';
					break;

			}
			$aDeliveryCostId=array();
			foreach ($aCart as $aValue) {
				if(!in_array($aValue['delivery_cost_id'], $aDeliveryCostId)){
					$dWeight+=$aValue['delivery_cost_weight'];
					$dDeliveryCost+=$aValue['delivery_cost_cost'];
					$dDeliveryTax+=$aValue['delivery_cost_tax'];
					$aDeliveryCostId[]=$aValue['delivery_cost_id'];
					$aDeliveryCostWeight[]=$aValue['delivery_cost_weight'];
				}
			}
			$_SESSION['current_delivery_cost']['delivery_cost_id']=$aDeliveryCostId;
			$_SESSION['current_delivery_cost']['delivery_cost_weight']=$aDeliveryCostWeight;
			$_SESSION['current_delivery_cost']['delivery_cost']=$dDeliveryCost;
			$_SESSION['current_delivery_cost']['delivery_tax']=$dDeliveryTax;
			$_SESSION['current_delivery_cost']['tarif_tax']=$aCart[0]['tarif_tax'];
			Base::$aRequest['search']['weight']=$dWeight;
			$_REQUEST['search']['weight']=$dWeight;
			Base::$aRequest['search']['code_type']=$sDeliveryType;
			$_REQUEST['search']['code_type']=$sDeliveryType;
		}
		if(!Base::$aRequest['search']['weight']){
			Base::$sText.=Language::GetMessage('Not found delivery cost');
			if($_SESSION['return']) Base::Redirect('./?'.$_SESSION['return']);
			return;
		}

		$dWeight=String::GetDecimal(Base::$aRequest['search']['weight']);
		$dWidth=String::GetDecimal(Base::$aRequest['search']['width']);
		$dLength=String::GetDecimal(Base::$aRequest['search']['length']);
		$dHeight=String::GetDecimal(Base::$aRequest['search']['height']);

		if ((Base::$aRequest['is_post']) || (Base::$aRequest['action']=='manager_invoice_customer_delivery_calculator_apply')) {
			if (!Base::$aRequest['search']['weight'] || !Base::$aRequest['search']['width']
			|| !Base::$aRequest['search']['height'] || !Base::$aRequest['search']['length']
			//|| !Base::$aRequest['search']['id_provider_region']
			|| !Base::$aRequest['search']['id_provider_region_to']
			//|| !Base::$aRequest['search']['id_provider_region_way']
			) {
				$sError="You need to fill all the inputs";
			} else {
				$aResult=$oCalculatorZone->GetDeliveryCost($dWeight,$dWidth/100,$dLength/100,$dHeight/100,
				Base::$aRequest['search']['id_provider_region'],
				Base::$aRequest['search']['id_provider_region_to'],
				Base::$aRequest['search']['id_payment_mode'],
				Base::$aRequest['search']['id_provider_region_way'],
				0,
				Base::$aRequest['search']['code_type']
				);

				if(Base::$aRequest['search']['manual']) $aResult['delivery_cost']=str_replace(',', '.', Base::$aRequest['search']['manual']);
				$dCost=round($aResult['delivery_cost'],2);
				$dTax=round($aResult['delivery_cost']*$_SESSION['current_delivery_cost']['tarif_tax'],2);
				$dAmount=$dCost+$dTax
				+$_SESSION['current_delivery_cost']['delivery_cost']
				+$_SESSION['current_delivery_cost']['delivery_tax'];
				if ($aResult['delivery_cost']==-1) {
					$this->Message(array("MF_ERROR"=>$aResult['message']),false);
				}elseif($_SESSION['current_delivery_cost']['id_user'] && $dAmount>0 &&
				Base::$aRequest['action']=='manager_invoice_customer_delivery_calculator_apply' && !$sError){
					$sDescr=Language::GetUserTranslateMessage('payment for euro delivery','',$_SESSION['current_delivery_cost']['id_user'])
					.' '.Language::GetUserTranslateMessage('weight','',$_SESSION['current_delivery_cost']['id_user']).':'.$dWeight
					.' '.Language::GetUserTranslateMessage('size','',$_SESSION['current_delivery_cost']['id_user']).':'.
					$dWidth.'*'.$dLength.'*'.$dHeight
					;
					$aUser=Db::GetRow(Base::GetSql("Customer",array('id'=>$_SESSION['current_delivery_cost']['id_user'])));
					if($aUser){
						Finance::Deposit($aUser['id'],(-1)*$dAmount,$sDescr
						,$iCustomId,'internal','',3338,361);
						$aData=array(
						'user'=>array('id'=>$aUser['id']),
						'data'=>array(
						'amount'=>$dAmount,
						'weight'=>$dWeight,
						'size'=>$dWidth.'*'.$dLength.'*'.$dHeight,
						),
						);
						$aSmartyTemplate=Language::GetUserTemplate('delivery_calculator_details', $aData);
						$iMailId=Mail::AddDelayed($aUser['email'],$aSmartyTemplate['name'],$aSmartyTemplate['parsed_text']);
						Db::AutoExecute('mail_delayed',array('id_language'=>$aUser['id_language']),'UPDATE',"id='".$iMailId."'");
						/*if($aUser['phone'])
						Sms::AddDelayed($aUser['phone'],strip_tags($aSmartyTemplate['parsed_text']));*/
						foreach ($_SESSION['current_delivery_cost']['delivery_cost_id'] as $sKey=>$sValue) {
							$dWeightPart=$_SESSION['current_delivery_cost']['delivery_cost_weight'][$sKey];
							$dPersent=$dWeightPart/$dWeight;
							Db::AutoExecute('delivery_cost',array(
							'delivery_customer_cost'=>round($dPersent*$aResult['delivery_cost'],2),
							'delivery_customer_tax'=>round($dPersent*$aResult['delivery_cost']*$_SESSION['current_delivery_cost']['tarif_tax'],2),
							),'UPDATE',"id='".$sValue."'");
						}
					}
					if($_SESSION['return']) Base::Redirect('./?'.$_SESSION['return']);
				}
			}
		}

		$aResult['volume']=round($dWidth/100 * $dLength/100 * $dHeight/100,3);
		$aResult['volume_weight']=round($dWidth * $dLength * $dHeight /6000,2);
		if($aResult['delivery_cost']!=-1){
			$aResult['cost']=$_SESSION['current_delivery_cost']['delivery_cost'];
			$aResult['tax']=$_SESSION['current_delivery_cost']['delivery_tax']+$dTax;
			$aResult['delivery_cost']=$dCost+$dTax
			+$_SESSION['current_delivery_cost']['delivery_cost']+$_SESSION['current_delivery_cost']['delivery_tax'];
			$aResult['cost1']=$_SESSION['current_delivery_cost']['delivery_cost'];
			$aResult['tax1']=$_SESSION['current_delivery_cost']['delivery_tax'];
			$aResult['cost2']=$dCost;
			$aResult['tax2']=$dTax;
		}
		Base::$tpl->assign('aResult',$aResult);

		/*Base::$tpl->assign('aProviderRegion',Db::GetRow(Base::GetSql('CalculatorTarif',array(
		'id_provider_region'=>Base::$aRequest['search']['id_provider_region'] ,
		'id_provider_region_to'=>Base::$aRequest['search']['id_provider_region_to'],
		'id_provider_region_way'=>Base::$aRequest['search']['id_provider_region_way'],
		'id_payment_mode'=>Base::$aRequest['search']['id_payment_mode']
		))));*/

		$aProviderRegion['description']=$oCalculatorZone->GetInfo(Base::$aRequest['search']['id_provider_region_to'],Base::$aRequest['search']['code_type']);

		if (!$aProviderRegion) $aProviderRegion['description']='not tarif';
		Base::$tpl->assign('aProviderRegion',$aProviderRegion);

		//Base::$tpl->assign('aTypeDelivery',Db::GetAssoc("Assoc/ProviderRegionWay"));
		//Base::$tpl->assign('aPaymentMode',Db::GetAssoc("Assoc/PackagePaymentMode"));

		$aData=array(
		'sContent'=>Base::$tpl->fetch($this->sPrefix.'/form_calculator_search.tpl'),
		'sWidth'=>'740px',
		'sSubmitButton'=>'Calculate',
		'sSubmitAction'=>'manager_invoice_customer_delivery_calculator',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);
		$oForm->sHeader="method=get";

		if (Auth::$aUser['id']) $oForm->sAdditionalButtonTemplate=$this->sPrefix.'/button_form_calculator.tpl';
		$oForm->bIsPost=true;
		Base::$sText.=$oForm->getForm();

	}
	//-----------------------------------------------------------------------------------------------
	public function Delivery()
	{
		Base::$bXajaxPresent=true;
		if(Base::$aRequest['return']){
			Base::$tpl->assign('sReturn',Base::$aRequest['return']);
			$_SESSION['return']=Base::$aRequest['return'];
		}
		if(Base::$aRequest['id_user']){
			if($_SESSION['current_delivery_cost']['id_user']!=Base::$aRequest['id_user'])
			unset($_SESSION['current_delivery_cost']);
			$_SESSION['current_delivery_cost']['id_user']=Base::$aRequest['id_user'];
		}

		$aCustomer=Base::$db->GetRow( Base::GetSql('Customer',array('id'=>Base::$aRequest['id_user'])) );
		$_SESSION['current_delivery_cost']['id_user_region']=$aCustomer['id_partner_region'];

		$oTable=new Table();
		$oTable->sSql=Base::GetSql('Cart',array(
		'status_array'=>array("'store'","'end'","'office_sent'","'office_received'"),
		'id_invoice_customer'=>0,
		'id_user'=>$_SESSION['current_delivery_cost']['id_user'],
		'where'=>' and dcl.id is null and weight_delivery_cost=0 ',
		'order'=>'order by cp.id',
		'join_delivery'=>1,
		'join_delivery_cost'=>1
		));

		if(Base::$aRequest['correct_weight']){
			Base::$aRequest['correct_weight']=str_replace(',', '.', trim(Base::$aRequest['correct_weight']));
			$_SESSION['current_delivery_cost']['data'][Base::$aRequest['id']]['correct_weight']=Base::$aRequest['correct_weight'];
			//Debug::PrintPre($_SESSION);
		}
		if(Base::$aRequest['is_post']){
			$aCart=Db::GetAll($oTable->sSql);
			if($aCart){
				$aCost=$_SESSION['current_delivery_cost'];
				foreach ($aCost['data'] as $aValue) {
					$aTotal['weight']+=$aValue['correct_weight'];
					$aTotal['cost']+=$aValue['correct_weight_delivery_cost'];
					$aTotal['delivery_tax']+=$aValue['delivery_cost_tax'];
					$aTotal['total']+=$aValue['total'];
				}
				$aTotal['tarif_tax']=$aCost['data'][0]['tarif_tax'];
				$aTotal['ser']=serialize($aCost);

				Db::AutoExecute('delivery_cost',$aTotal);
				$iInsertId=Db::InsertId();
				foreach ($aCart as $aValue) {
					Db::AutoExecute('delivery_cost_link',array(
					'id_delivery_cost'=>$iInsertId,
					'id_cart'=>$aValue['id_cart']
					));
				}
				Base::Redirect("/?action=manager_invoice_customer_delivery_calculator&id_user=".
				$_SESSION['current_delivery_cost']['id_user']);
			}
		}
		//Debug::PrintPre(Base::$aRequest);
		//Debug::PrintPre(Db::GetAll($oTable->sSql));
		$oTable->aColumn=array(
		'provider_region_name'=>array('sTitle'=>'provider region'),
		'number'=>array('sTitle'=>'qty'),
		'weight'=>array('sTitle'=>'Weight'),
		'weight_delivery_cost'=>array('sTitle'=>'weight delivery cost'),
		//		'price_total'=>array('sTitle'=>'part cost'),
		'price_tax'=>array('sTitle'=>'vat'),
		'total'=>array('sTitle'=>'total'),
		);
		$oTable->iRowPerPage=500;
		$oTable->sDataTemplate='manager_invoice_customer/row_delivery.tpl';
		$oTable->sButtonTemplate='manager_invoice_customer/button_delivery.tpl';
		$oTable->sSubtotalTemplate='manager_invoice_customer/subtotal_delivery.tpl';
		$oTable->bCheckVisible=false;
		$oTable->bDefaultChecked=false;
		$oTable->bStepperVisible=false;
		$oTable->sFormHeader='method=post';
		$oTable->aCallback=array($this,'CallParseDelivery');



		Base::$sText.=$oTable->getTable("Create Customer Invoice",'',' - '.$aCustomer['login']);
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseDelivery(&$aItem)
	{
		foreach ($aItem as $aValue) {
			if(!$aProviderRegion || ($iKey=array_search($aValue['provider_region_name'], $aProviderRegion))===FALSE) {
				$aProviderRegion[]=$aValue['provider_region_name'];
				$aTmp[]=Array();
				$iKey=count($aTmp)-1;
			}
			$aTmp[$iKey]['id']=$iKey;
			$aTmp[$iKey]['provider_id_parent_region']=$aValue['provider_id_parent_region'];
			$aTmp[$iKey]['provider_region_name']=$aValue['provider_region_name'];
			$aTmp[$iKey]['weight_tarif']=$aValue['weight_tarif'];
			$aTmp[$iKey]['number']+=$aValue['number'];
			$aTmp[$iKey]['count']+=1;
			$aTmp[$iKey]['weight']+=$aValue['weight']*$aValue['number'];
			$aTmp[$iKey]['weight_delivery_cost']+=$aValue['weight_delivery_cost']*$aValue['number'];
			$aTmp[$iKey]['price_total']+=$aValue['price']*$aValue['number'];
			$aTmp[$iKey]['price_tax']+=$aValue['price_tax'];
		}
		if($aTmp)
		foreach ($aTmp as $sKey=>$aValue) {
			$aTarif=Db::GetRow($sql="select margin from provider_tarif where
			id_partner_region_provider='".$aValue['provider_id_parent_region']."' and
			id_partner_region_customer='".$_SESSION['current_delivery_cost']['id_user_region']."'");//Debug::PrintPre($sql);
			$aTmp[$sKey]['tarif_tax']=$aTarif['margin']/100;

			$aTmp[$sKey]['correct_weight']=$_SESSION['current_delivery_cost']['data'][$sKey]['correct_weight']?
			$_SESSION['current_delivery_cost']['data'][$sKey]['correct_weight']:$aValue['weight'];

			$aTmp[$sKey]['correct_weight_delivery_cost']=round($aTmp[$sKey]['correct_weight']*$aValue['weight_tarif'],2);
			$aTmp[$sKey]['delivery_cost_tax']=round($aTmp[$sKey]['correct_weight_delivery_cost']*$aTmp[$sKey]['tarif_tax'],2);
			//$aTmp[$sKey]['part_cost']=$aValue['price_total']-$aTmp[$sKey]['price_tax']-$aTmp[$sKey]['weight_delivery_cost'];
			//$aTmp[$sKey]['part_tax']=$aTmp[$sKey]['part_cost']*$aTmp[$sKey]['tarif_tax'];
			//$aTmp[$sKey]['total_tax']=$aTmp[$sKey]['delivery_cost_tax']+$aTmp[$sKey]['price_tax'];
			$aTmp[$sKey]['total']=$aTmp[$sKey]['correct_weight_delivery_cost']+$aTmp[$sKey]['delivery_cost_tax'];
			$aSubtotal['correct_weight']+=$aTmp[$sKey]['correct_weight'];
			$aSubtotal['correct_weight_delivery_cost']+=$aTmp[$sKey]['correct_weight_delivery_cost'];
			$aSubtotal['delivery_cost_tax']+=$aTmp[$sKey]['delivery_cost_tax'];
			$aSubtotal['total']+=$aTmp[$sKey]['total'];
			$aSubtotal['number']+=$aTmp[$sKey]['number'];
			$aSubtotal['count']+=$aTmp[$sKey]['count'];
		}
		$_SESSION['current_delivery_cost']['data']=$aTmp;
		//Debug::PrintPre($aTmp);
		$aItem=$aTmp;
		Base::$tpl->assign('aSubtotal',$aSubtotal);
	}
	//-----------------------------------------------------------------------------------------------
	public function DeliveryEdit()
	{
		$aInvoiceCustomer=Db::GetRow(Base::GetSql('InvoiceCustomer',array('id'=>Base::$aRequest['id'])));
		$iIdLanguege=$aInvoiceCustomer['id_language']?$aInvoiceCustomer['id_language']:Language::$iLocale;
		if(Base::$aRequest['is_post']){
			$_SESSION['current_cart']['delivery_recipient']=Base::$aRequest['delivery_recipient'];
			if($iIdLanguege!=1)
			$sComment=$_SESSION['current_cart']['delivery_address']?$_SESSION['current_cart']['delivery_address']:'';
			else
			$sComment=$_SESSION['current_cart']['id_delivery_region']?Delivery::GetDeliveryRegionInfo():'';
			$aCartpackageInsert=array(
			'delivery_type'=>$_SESSION['current_cart']['id_delivery_type'],
			'id_delivery_type_region'=>$_SESSION['current_cart']['id_delivery_region'],
			'customer_comment'=>$sComment,
			'delivery_recipient'=>$_SESSION['current_cart']['delivery_recipient'],
			);
			Db::AutoExecute('cart_package',Db::Escape($aCartpackageInsert),'UPDATE',"id='".$aInvoiceCustomer['id_cart_package']."'");
			Base::Redirect('./?'.Base::$aRequest['return']);
		}

		if(!isset($_SESSION['current_cart']['delivery_recipient']))
		$_SESSION['current_cart']['delivery_recipient']=$aInvoiceCustomer['delivery_recipient'];
		Base::$bXajaxPresent=true;
		$aDeliveryType=Db::GetAll(Base::GetSql('DeliveryType',array('visible'=>1,'id_language'=>$iIdLanguege)));
		Base::$tpl->assign('aDeliveryType',$aDeliveryType);

		if (!$_SESSION['current_cart']['id_delivery_type']){
			$_SESSION['current_cart']['id_delivery_type']=$aInvoiceCustomer['id_delivery_type']?
			$aInvoiceCustomer['id_delivery_type']:$aDeliveryType[0]['id'];

			if($iIdLanguege==1){
				$_SESSION['current_cart']['id_delivery_region']=$aInvoiceCustomer['id_delivery_type_region']?
				$aInvoiceCustomer['id_delivery_type_region']:NULL;
				if($_SESSION['current_cart']['id_delivery_region']){
					$aDeliveryTypeRegion=Db::GetRow(Base::GetSql("DeliveryTypeRegion",array(
					"id"=>$_SESSION['current_cart']['id_delivery_region'],
					)));
					$_SESSION['current_cart']['delivery_city']=$aDeliveryTypeRegion['city'];
				}
			}
		}
		//if($_SESSION['current_cart']['id_delivery_type']==18)$_SESSION['current_cart']['id_delivery_region']=NULL;
		$aDeliveryTypeRow=Db::GetRow(Base::GetSql('DeliveryType',array(
		'id'=>$_SESSION['current_cart']['id_delivery_type'],
		'visible'=>1,
		)));
		Base::$tpl->assign('aDeliveryTypeRow',$aDeliveryTypeRow);
		if($iIdLanguege==1)
		Base::$tpl->assign('sDeliveryRegionSelector',Delivery::GetDeliveryRegionSelector());

		Base::$aRequest['id_delivery_type']=$_SESSION['current_cart']['id_delivery_type'];

		//Delivery::Set(true);
		Base::$sText.=Base::$tpl->fetch($this->sPrefix.'/delivery_edit.tpl');
	}
	//-----------------------------------------------------------------------------------------------

}
?>