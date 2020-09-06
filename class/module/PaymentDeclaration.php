<?
/**
 * @author Vladimir Fedorov
 * 
 */

class PaymentDeclaration extends Base
{
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		Base::$bXajaxPresent = true;
		Base::$aData['template']['bWidthLimit']=true;
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		if (!Auth::$aUser['id'])
			Base::Redirect('/');
		
		if (Auth::$aUser['type_'] == 'manager')
			Base::Redirect('/pages/payment_declaration_manager');
		
		$aField['search_id_cart_package']=array('title'=>'cartpackage #','type'=>'input','value'=>Base::$aRequest['search_id_cart_package'],'name'=>'search_id_cart_package');
		$aField['number_declaration']=array('title'=>'Number declaration','type'=>'input','value'=>Base::$aRequest['search_number_declaration'],'name'=>'search_number_declaration');
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		
		$aData=array(
		    'sHeader'=>"method=get",
		    //'sContent'=>Base::$tpl->fetch('payment_declaration/form_search_payment_declaration.tpl'),
		    'aField'=>$aField,
		    'bType'=>'generate',
		    'sGenerateTpl'=>'form/index_search.tpl',
		    'sSubmitButton'=>'Search',
		    'sSubmitAction'=>'payment_declaration',
		    'sReturnButton'=>'Clear',
		    'sReturnAction'=>'payment_declaration',
		    'bIsPost'=>0,
		    'sWidth'=>'70%',
		    'sError'=>$sError,
		);
		$oForm=new Form($aData);
		Base::$sText.=$oForm->getForm();
		
		// --- search ---
		if (Base::$aRequest['search_id_cart_package']) $sWhere.=" and pd.id_cart_package ='".Base::$aRequest['search_id_cart_package']."'";
		if (Base::$aRequest['search_number_declaration']) $sWhere.=" and pd.number_declaration ='".Base::$aRequest['search_number_declaration']."'";
		if (Base::$aRequest['search']['date']) {
		    $sWhere.="and pd.date_send>='".DateFormat::FormatSearch(Base::$aRequest['date_from'])."'
		    and pd.date_send<='".DateFormat::FormatSearch(Base::$aRequest['date_to'])."' ";
		}
		    
		$sWhere.=" and pd.id_user ='".Auth::$aUser[id]."'";
		
		$oTable=new Table();
		$oTable->sSql="Select * from payment_declaration as pd where id_user = '".Auth::$aUser['id']."' ".$sWhere;
		$oTable->aOrdered="order by date_send desc";
		$oTable->aColumn=array(
		    'id_cart_package'=>array('sTitle'=>'cartpackage #'),
			'date_send'=>array('sTitle'=>'Date send'),
			'recipient'=>array('sTitle'=>'Recipient'),
			'carrier'=>array('sTitle'=>'Carrier'),
			'number_declaration'=>array('sTitle'=>'Number declaration'),
			'number_places'=>array('sTitle' => 'Number places' ),
		    'read'=>array(),
		);
		$oTable->sDataTemplate='payment_declaration/row_payment_declaration.tpl';
		$oTable->bStepperVisible=true;
		$oTable->bHeaderVisible=false;
		$oTable->iRowPerPage=10;
		$oTable->bCountStepper=true;
		Base::$sText.=$oTable->getTable();
		
		if (Base::$aRequest['id']) {
		    Base::$db->Execute("update payment_declaration set is_read='1'
		    where id='".Base::$aRequest['id']."' and id_user='".Auth::$aUser[id]."'");
		
		    Base::Redirect("/pages/payment_declaration/");
		}
	
		
	}
	//-----------------------------------------------------------------------------------------------

}
?>