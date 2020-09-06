<?
/**
 * @author Vladimir Fedorov
 * 
 */

class PaymentReportManager extends Base
{
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		Auth::NeedAuth('manager');
		Base::$bXajaxPresent = true;
		Base::$aData['template']['bWidthLimit']=true;
		Base::Message();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$aCurrency = Db::getAssoc("Select code,name from currency where visible=1 order by num");
		Base::$tpl->assign('aCurrency',$aCurrency);
		Base::$tpl->assign('aMethod',$aMethod=array(
		    'другое'=>Language::GetMessage('method:other'),
		    'карточный счет'=>Language::GetMessage('method:card account'),
		    'расчетный счет'=>Language::GetMessage('method:current account'),
		));
		
		$aField['id_cart_package']=array('title'=>'cartpackage #','type'=>'input','value'=>Base::$aRequest['search']['id_cart_package'],'name'=>'search[id_cart_package]');
		$aField['method']=array('title'=>'method','type'=>'select','options'=>$aMethod,'name'=>'search[method]','selected'=>Base::$aRequest['search']['method'],'checkbox'=>1);
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		$aField['amount_from']=array('title'=>'amFrom','type'=>'input','value'=>Base::$aRequest['search']['amount_from'],'name'=>'search[amount_from]','checkbox'=>1);
		$aField['amount_to']=array('title'=>'amTo','type'=>'input','value'=>Base::$aRequest['search']['amount_to'],'name'=>'search[amount_to]');
		$aField['code_currency']=array('title'=>'Currency payment report','type'=>'select','options'=>$aCurrency,'name'=>'search[code_currency]','selected'=>Base::$aRequest['search']['code_currency']);
		
	    $aData=array(
	        'sHeader'=>"method=get",
	        //'sContent'=>Base::$tpl->fetch('payment_report/form_search_payment_report_manager.tpl'),
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sGenerateTpl'=>'form/index_search.tpl',
	        'sSubmitButton'=>'Search',
	        'sSubmitAction'=>'payment_report_manager',
	        'sReturnButton'=>'Clear',
	        'bIsPost'=>0,
	        'sWidth'=>'60%',
	        'sError'=>$sError,
	    );
	    $oForm=new Form($aData);
	    
	    Base::$sText .= $oForm->getForm();
	    
	   // --- search ---
	    if (Base::$aRequest['search']['id_cart_package']) $sWhere.=" and pr.id_cart_package = '".Base::$aRequest['search']['id_cart_package']."'";
	    if (Base::$aRequest['search']['date']) {
	        $sWhere.=" and (pr.payment_date >= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
	            and pr.payment_date <= '".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")."') ";
	    }
	    if (Base::$aRequest['search']['amount']) {
	    	if (Base::$aRequest['search']['amount_from'] && !Base::$aRequest['search']['amount_to'])
	    		$sWhere.=" and pr.price >= '".Base::$aRequest['search']['amount_from']."'";
	    	elseif (!Base::$aRequest['search']['amount_from'] && Base::$aRequest['search']['amount_to'])
	    	$sWhere.= " and pr.price <= '".Base::$aRequest['search']['amount_to']."'";
	    	else
	    		$sWhere.=" and (pr.price >= '".Base::$aRequest['search']['amount_from']."'
	            	and pr.price <= '".Base::$aRequest['search']['amount_to']."') ";
	    }
	    if (Base::$aRequest['search']['method_is']){
	        if (Base::$aRequest['search']['method'])
	            $sWhere.=" and pr.method = '".Base::$aRequest['search']['method']."'";
	    }
	    if (Base::$aRequest['search']['code_currency']){
	    	$sWhere.=" and c.code = '".Base::$aRequest['search']['code_currency']."'";
	    }
	    // --- search ---
	    
		$oTable=new Table();
		$oTable->sSql="select pr.*, u.email, u.login, uc.name, c.id as id_currency from payment_report pr 
				left join user as u ON u.id = pr.id_user
				left join user_customer as uc ON uc.id_user = pr.id_user
				left join currency c on c.code = pr.code_currency 
		        where 1=1
		        ".$sWhere;
		$oTable->aOrdered="order by payment_date desc";
		$oTable->aColumn=array(
		    'id_cart_package'=> array('sTitle'=>'cartpackage #'),
			'payment_date'=>array('sTitle'=>'Date payment'),
			'user' =>array('sTitle' => 'Customer'), 
			'method'=>array('sTitle'=>'Method'),
			'price'=>array('sTitle'=>'Price payment report'),
			'comment'=>array('sTitle'=>'Comment'),
		    'read'=>array(''),
		);
		$oTable->sDataTemplate='payment_report/row_payment_report_manager.tpl';
		$oTable->bStepperVisible=true;
		$oTable->bHeaderVisible=true;
		$oTable->iRowPerPage=50;
		Base::$sText.=$oTable->getTable();
		
		if (Base::$aRequest['id']) {
		    Base::$db->Execute("update payment_report set is_read='1' 
		    where id='".Base::$aRequest['id']."'");
		    
		    Base::Redirect("/pages/payment_report_manager/");
		}
		
		//
	}
	//-----------------------------------------------------------------------------------------------
	
}
?>