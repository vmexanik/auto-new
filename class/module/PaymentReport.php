<?
/**
 * @author Vladimir Fedorov
 * 
 */

class PaymentReport extends Base
{
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
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
		    'асчетный счет'=>Language::GetMessage('method:current account'),
		));
		
		$aField['id_cart_package']=array('title'=>'cartpackage #','type'=>'input','value'=>Base::$aRequest['search']['id_cart_package'],'name'=>'search[id_cart_package]');
		$aField['method']=array('title'=>'method','type'=>'select','options'=>$aMethod,'name'=>'search[method]','selected'=>Base::$aRequest['search']['method'],'checkbox'=>array('name'=>'search[method_is]','value'=>1,'checked'=>Base::$aRequest['search']['method_is']));
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("1.m.Y",time()),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		$aField['amount_from']=array('title'=>'amFrom','type'=>'input','value'=>Base::$aRequest['search']['amount_from'],'name'=>'search[amount_from]','checkbox'=>array('name'=>'search[amount]','value'=>1,'checked'=>Base::$aRequest['search']['amount']));
		$aField['amount_to']=array('title'=>'amTo','type'=>'input','value'=>Base::$aRequest['search']['amount_to'],'name'=>'search[amount_to]');
		$aField['code_currency']=array('title'=>'Currency payment report','type'=>'select','options'=>$aCurrency,'name'=>'search[code_currency]','selected'=>Base::$aRequest['search']['code_currency']);
		
	    $aData=array(
	        'sHeader'=>"method=get",
	        //'sContent'=>Base::$tpl->fetch('payment_report/form_search_payment_report.tpl'),
	        'aField'=>$aField,
	        'bType'=>'generate',
	        'sGenerateTpl'=>'form/index_search.tpl',
	        'sSubmitButton'=>'Search',
	        'sSubmitAction'=>'payment_report',
	        'sReturnButton'=>'Clear',
	        'bIsPost'=>0,
	        'sWidth'=>'53%',
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
		$oTable->sSql="select pr.*, c.id as id_currency from payment_report as pr
			left join currency c on c.code = pr.code_currency   
			where id_user = '".Auth::$aUser['id']."'".$sWhere;

		$oTable->aOrdered="order by payment_date desc";
		$oTable->aColumn=array(
		    'id_cart_package'=>array('sTitle'=>'cartpackage #'),
			'payment_date'=>array('sTitle'=>'Date payment'),
			'method'=>array('sTitle'=>'Method'),
			'price'=>array('sTitle'=>'Price payment report'),
			'comment'=>array('sTitle'=>'Comment'),
			'action' =>array(), 
		);
		$oTable->sDataTemplate='payment_report/row_payment_report.tpl';
		$oTable->bStepperVisible=true;
		$oTable->bHeaderVisible=false;
		$oTable->iRowPerPage=25;
		$oTable->bCountStepper=false;
		$oTable->sButtonTemplate='payment_report/button_payment_report.tpl';
		Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function Add()
	{
		Base::$oContent->AddCrumb(Language::GetMessage('payment_report'),'');
		Base::$bXajaxPresent=true;
		$oCurrency = new Currency();
		$sError = '';
		$aData = array();
		if (Base::$aRequest['is_post']) {
			if (!Base::$aRequest['data']['payment_date'] || Base::$aRequest['data']['payment_date'] == '') {
				$iTime = time();
				$sTime = date("Y-m-d H:i:s", $iTime);
				$aData['payment_date'] = date("d-m-Y H:i:s", $iTime);
			}
			elseif (($sTime=strtotime(Base::$aRequest['data']['payment_date'])) === false) {
				$sError .= Language::GetMessage("Incorrect format date and time. Use format: d-m-Y H:i:s"); 
			}
			else 
				$sTime = date("Y-m-d H:i:s",$sTime);
			
			if (!$aData['payment_date'] && Base::$aRequest['data']['payment_date'])
				$aData['payment_date'] = Base::$aRequest['data']['payment_date'];
			
			if (!is_numeric(Base::$aRequest['data']['price']) || floatval(Base::$aRequest['data']['price']) <= 0) {
				if ($sError != '')
					$sError .= "<br>";
				$sError .= language::GetMessage("Incorrect value price. Need fill integer value > 0");
			}
			
			$iIdPackage=Db::GetAll("SELECT id_cart_package  FROM cart 
			    WHERE id_cart_package = '".Base::$aRequest['data']['id_cart_package']."' AND id_user='".$_SESSION['user']['id_user']."' ");
			
// 			Debug::PrintPre($iIdPackage);
			
			if (!Base::$aRequest['data']['id_cart_package'] || !$iIdPackage) {
			    $sError .= language::GetMessage("Incorrect value id_cart_package. Need fill");
			}
			
			if ($sError == '') {
				$sComment = strip_tags(Base::$aRequest['data']['comment']);
				$fPrice = floatval(Base::$aRequest['data']['price']);
				$sCart_Package = Base::$aRequest['data']['id_cart_package'];
				if (!isset(Base::$aRequest['id'])) {
					$sQuery = "Insert into payment_report (id_user, payment_date, method, price, comment, id_cart_package,code_currency) VALUES
							(".Auth::$aUser['id'].",'".$sTime."','".Base::$aRequest['data']['method']."',
							 ".$fPrice.",'".$sComment."','".$sCart_Package."','".Base::$aRequest['data']['code_currency']."')";
					$sMessage="Payment report created";
					$sSubject = Language::GetMessage('created new payment report');
				}
				else { 
					$sQuery = "Update payment_report set payment_date = '".$sTime."', method = '".Base::$aRequest['data']['method']."',
								price = ".$fPrice.", comment = '".$sComment."',id_cart_package ='".$sCart_Package."',
								code_currency = '".Base::$aRequest['data']['code_currency']."'										  
								    where id = ".Base::$aRequest['id'];
					$sMessage='Payment report updated';
					$sSubject = Language::GetMessage('updated payment report');
				}
				Base::$db->Execute($sQuery);
				
				$iIdCurrency = Db::getOne("Select id from currency where code='".Base::$aRequest['data']['code_currency']."'"); 
				
				$aData['aUser'] = Auth::$aUser;
				$aData['payment_report'] = array(
					'date' => $sTime,
					'method' => Base::$aRequest['data']['method'],
					'comment' => $sComment,
					'price' => $oCurrency->PrintPrice($fPrice,$iIdCurrency),
				    'id_cart_package' =>$sCart_Package
				);
				
				$aTemplate=String::GetSmartyTemplate('create_new_payment_report', $aData);
				$sBody=$aTemplate['parsed_text'];
				
				Mail::SendNow(
					Base::GetConstant('payment_report:to_email','mstarrr@gmail.com'),
					Language::GetMessage('User') .': '. Auth::$aUser['name'] . '(login: '.Auth::$aUser['login'] .') '. $sSubject,
					$sBody
				);
					
				Base::Redirect("/pages/payment_report/?aMessage[MT_NOTICE]=".$sMessage);
			}
		}
		$sButtonSubmit = 'Add';
		if (Base::$aRequest['id']) {
			$aInfo = Db::GetRow("Select * from payment_report where id =".Base::$aRequest['id']." and id_user=".Auth::$aUser['id']);
			if ($aInfo['id']) {
				$aData = $aInfo;
				$sButtonSubmit = 'Edit';
			}
		}
		
		Base::$tpl->assign('aData',$aData);
		
		Base::$tpl->assign('aMethods',array(
			Language::GetMessage('method:other'),
			Language::GetMessage('method:card account'),
			Language::GetMessage('method:current account')
		));

		$aMethods=array(
		    Language::GetMessage('method:other')=>Language::GetMessage('method:other'),
		    Language::GetMessage('method:card account')=>Language::GetMessage('method:card account'),
		    Language::GetMessage('method:current account')=>Language::GetMessage('method:current account')
		);
		
		$aCurrency = Db::getAssoc("Select code,name from currency where visible=1 order by num");
		Base::$tpl->assign('aCurrency',$aCurrency);
		
		$aField['is_post']=array('type'=>'hidden','name'=>'is_post','value'=>'1');
		$aField['payment_date']=array('title'=>'Date and time','type'=>'input','value'=>Base::$aRequest['data']['payment_date']?Base::$aRequest['data']['payment_date']:$aData['payment_date'],'name'=>'data[payment_date]');
		$aField['method']=array('title'=>'Method','type'=>'select','options'=>$aMethods,'selected'=>$aData['method'],'name'=>'data[method]','id'=>'method');
		$aField['price']=array('title'=>'Price payment report','type'=>'input','value'=>$aData['price'],'name'=>'data[price]','szir'=>1);
		$aField['code_currency']=array('title'=>'Currency payment report','type'=>'select','options'=>$aCurrency,'selected'=>$aData['code_currency'],'name'=>'data[code_currency]','szir'=>1);
		$aField['comment']=array('title'=>'Comment','type'=>'textarea','name'=>'data[comment]','value'=>$aData['comment']);
		$aField['id_cart_package']=array('title'=>'cartpackage #','type'=>'input','value'=>$aData['id_cart_package'],'name'=>'data[id_cart_package]','szir'=>1);
		
		if (!isset(Base::$aRequest['id'])) {
		$aData=array(
				'sHeader'=>"method=post",
				'sTitle'=>"Create payment info",
// 				'sContent'=>Base::$tpl->fetch('payment_report/form_add_payment_report.tpl'),
		        'aField'=>$aField,
		        'bType'=>'generate',
				'sSubmitButton'=>$sButtonSubmit,
				'sSubmitAction'=>'payment_report_add',
				'sErrorNT'=>$sError,
				'sReturnButton'=>'<< Return',
				'sReturnAction'=>'payment_report',
		        'sWidth'=>'70%',
				'sReturnButtonClass' => '',
				'sSubmitButtonClass' => '',
		);
		}
		else {
		    $aData=array(
		        'sHeader'=>"method=post",
		        'sTitle'=>"Edit payment info",
		        //'sContent'=>Base::$tpl->fetch('payment_report/form_add_payment_report.tpl'),
		        'aField'=>$aField,
		        'bType'=>'generate',
		        'sSubmitButton'=>$sButtonSubmit,
		        'sSubmitAction'=>'payment_report_add',
		        'sErrorNT'=>$sError,
		        'sReturnButton'=>'<< Return',
		        'sReturnAction'=>'payment_report',
		        'sWidth'=>'70%',
		        'sReturnButtonClass' => '',
		        'sSubmitButtonClass' => '',
		    );
		    }
		
		$oForm=new Form($aData);
		Base::$sText.=$oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------
	public function Delete()
	{
		if (!Base::$aRequest['id'])
			$sMessage = 'Not found payment report item for delete';
		else {
			$aInfo = Db::GetRow("Select * from payment_report where id =".Base::$aRequest['id']." and id_user=".Auth::$aUser['id']);
			if (!$aInfo['id'])
				$sMessage = 'Not found payment report item for delete';
			else {
				$oCurrency = new Currency();
				Db::Execute("Delete from payment_report where id =".Base::$aRequest['id']." and id_user=".Auth::$aUser['id']);
				
				$aData['aUser'] = Auth::$aUser;
				$aData['payment_report'] = array(
						'date' => date("d-m-Y H:i:s",strtotime($aInfo['payment_date'])),
						'method' => $aInfo['method'],
						'comment' => $aInfo['comment'],
						'price' => $oCurrency->PrintPrice($aInfo['price'])
				);
				
				$aTemplate=String::GetSmartyTemplate('create_new_payment_report', $aData);
				$sBody=$aTemplate['parsed_text'];
				
				$sToEmail = Base::GetConstant('payment_report:to_email','mstarrr@gmail.com');
				
				Mail::SendNow($sToEmail,
				Language::GetMessage('User') .': '. Auth::$aUser['name'] . '(login: '.Auth::$aUser['login'] .') '. Language::GetMessage('delete payment report'),
				$sBody
				);
				$sMessage = 'Payment report item delete';
			}
		}
		$sUrl = "/pages/payment_report/?aMessage[MT_NOTICE]=".$sMessage;
		Base::Redirect($sUrl);
	}
}
?>