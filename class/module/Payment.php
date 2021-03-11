<?

/**
 * @author Mikhail Starovoyt
 *
 */

class Payment extends Base
{

	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		$this->aLiqpayCurrency=array(
		'UAH'=>'UAH',
		'EUR'=>'EUR',
		'USD'=>'USD',
		);
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		Auth::NeedAuth();

		switch (Base::$aRequest['action']) {
			case 'payment_liqpay':
				$sMethod='liqpay';
				Base::$tpl->assign('aLiqpayCurrency',$this->aLiqpayCurrency);
				Base::$tpl->assign('sUniqid',uniqid() );

				$aField['amount']=array('title'=>'liqpay amount','type'=>'input','value'=>Base::$aRequest['amount']?Base::$aRequest['amount']:Language::GetConstant('payment:default_amount','0.6'),'name'=>'amount');
				$aField['currency']=array('type'=>'select','options'=>$this->aLiqpayCurrency,'name'=>'currency');
				$aField['version']=array('type'=>'hidden','name'=>'version','value'=>'1.1');
				$aField['merchant_id']=array('type'=>'hidden','name'=>'merchant_id','value'=>Language::GetConstant('payment:liqpay_merchant_id','i48066539671'));
			    $aField['description']=array('type'=>'hidden','name'=>'description','value'=>Language::GetConstant('payment:liqpay_description','Liqpay description').' '.Auth::$aUser['login'].':'.Auth::$aUser['id']);
				$aField['order_id']=array('type'=>'hidden','name'=>'order_id','value'=>Auth::$aUser['id'].'_'.uniqid());
				$aField['result_url']=array('type'=>'hidden','name'=>'result_url','value'=>'http://'.SERVER_NAME.'/?action=payment_liqpay_success');
				$aField['server_url']=array('type'=>'hidden','name'=>'server_url','value'=>'http://'.SERVER_NAME.'/?action=payment_liqpay_result');
				
				$aData=array(
				'sHeader'=>" action='".Base::GetConstant('payment:liqpay_url','https://liqpay.com/?do=clickNbuy')."'
					method='POST' accept-charset='utf-8' ",
				//'sContent'=>Base::$tpl->fetch('payment/liqpay.tpl'),
				'aField'=>$aField,
				'bType'=>'generate',
				'sSubmitButton'=>'Payment Process',
				'sReturnButton'=>'Return',
				'sReturnAction'=>'finance_payforaccount',
				);
				$oForm=new Form($aData);

				$sForm=$oForm->getForm();
				break;
		}

		Base::$sText.=Language::GetText('top_'.$sMethod.'_payment');
		Base::$sText.=$sForm;
	}
	//-----------------------------------------------------------------------------------------------
	function Log($sMethod='webmoney', $sMessage='')
	{
		$sDescription='remote_addr: '.$_SERVER["REMOTE_ADDR"];

		$aLogPayment=array(
		'method'=>$sMethod,
		'message'=>mysql_real_escape_string($sMessage),
		'description'=>$sDescription,
		);
		Db::AutoExecute('log_payment',$aLogPayment);
	}
	//-----------------------------------------------------------------------------------------------
	public function LiqpayResult()
	{
		$this->LiqpayPayment();
		Base::$sText.=Language::GetText("Liqpay Result");
	}
	//-----------------------------------------------------------------------------------------------
	public function LiqpaySuccess()
	{
		Base::$sText.=Language::GetText("Liqpay Success");
	}
	//-----------------------------------------------------------------------------------------------
	public function LiqpayFail()
	{
		Base::$sText.=Language::GetText("Liqpay Fail");
	}
	//-----------------------------------------------------------------------------------------------
	private function LiqpayPayment()
	{
	    $aStatusOk = array('success','wait_accept');
	    $isPayOrder = 0;
	    $iOrderId = 0;
	    
		$this->Log("Liqpay", print_r(Base::$aRequest,true));
		
		if (Base::$aRequest['status']=='payment_liqpay_result' && 
		    Base::$aRequest['signature'] && Base::$aRequest['data']){
		    
		    $a = json_decode(base64_decode( Base::$aRequest['data'] ),true);
		    if ($a['order_id']) {
		        $iOrderId = explode("_",$a['order_id']); // шаблон order_id_xxx
		        $iOrderId = $iOrderId[2];		        
		        // выбрать id транзакции и использовать часть старого алгоритма для нее
		        $sTransactionId=Db::GetOne("select id from liqpay_txn where id='".$a['transaction_id']."'");
		        if($sTransactionId)
		        {
		            $this->Log("liqpay", "Old txn id '".$a['transaction_id']."'");
		        } else {
		            Db::Execute("INSERT INTO `liqpay_txn` (`id`, `date`) VALUES('".$a['transaction_id']."', NOW())");
		            if(!in_array($a['currency'],array_keys($this->aLiqpayCurrency) ))
		            {
		                $this->Log("liqpay", "Bad currency (".$a['currency']." not in th list)");
		            } else {
            		    $private_key = Base::GetConstant('liqpay:private_key','plKvI40SGyr8Opr8FxSucf6M8SG5DwiAqpUXVDkQ');
            		    $public_key = Base::GetConstant('liqpay:public_key','i48066539671');
            		    
            		    $sSignatureCheck = base64_encode( sha1( $private_key . Base::$aRequest['data'] . $private_key , 1 ));
            		    if (Base::$aRequest['signature'] != $sSignatureCheck) {
            		        //echo 'Bad signature'; перепроверить статус самостоятельно
            		        $liqpay = new LiqPay($public_key, $private_key);
            		        $res = $liqpay->api("request", array(
            		            'action'        => 'status',
            		            'version'       => '3',
            		            'order_id'      => $a['order_id']
            		        ));
            		        if ($res->result == 'ok' && in_array($res->status,$aStatusOk)) {
            		            $isPayOrder=1;
            		        }
            		    }
            		    else {
            		        //echo 'ok'; - сверить номер заказа, сумму, статус (при каких статусах ставить оплачено)
            		        $isPayOrder = 1;            		        
            		    }
		            }
		        }
		    }
		}
		
		if ($isPayOrder) {
		    $aCartPackage=Base::$db->getRow("select cp.*, u.login, uc.name as name_user from cart_package cp
		        inner join user u on u.id=cp.id_user
		        inner join user_customer uc on uc.id_user = cp.id_user
		        where cp.id='$iOrderId'");
		    if (!$aCartPackage) return false;
		
		    // !!! Если на проэкте кроме простой смены поля еще что-то используется, учесть!
		    if (!$aCartPackage['is_payed']) 
		        Db::Execute("Update cart_package set is_payed=1 where id=".$aCartPackage['id']);
		    
    		$aSmartyTemplate=String::GetSmartyTemplate('liqpay_success', array(
    		    'order_id' => $iOrderId,
    		    'user' => $aCartPackage['name_user'].' ('.$aCartPackage['login'].') ',
    		));
    		Mail::AddDelayed(Base::GetConstant('global:to_email')
    		,$aSmartyTemplate['name'].' №'.$iOrderId,
    		$aSmartyTemplate['parsed_text']);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function LiqPayGetForm($iIdOrder,$dAmount) {
	    $liqpay = new LiqPay(Base::GetConstant('liqpay:public_key','i48066539671'), Base::GetConstant('liqpay:private_key','plKvI40SGyr8Opr8FxSucf6M8SG5DwiAqpUXVDkQ'));
	    $html = $liqpay->cnb_form(array(
	        'action'         => 'pay',
	        'amount'         => $dAmount,
	        'currency'       => 'UAH',
	        'description'    => Language::GetMessage('liqpay_site_name').' оплата за заказ №'.$iIdOrder,
	        'order_id'       => 'order_id_'.$iIdOrder,
	        'version'        => '3',
			'result_url'	 => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/pages/payment_liqpay_success',
			'server_url'	 => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/pages/payment_liqpay_result'
	    ));
	    
	    return $html;
	}
	//-----------------------------------------------------------------------------------------------
	public function LiqPayGetFormOrder($iIdOrder,$dAmount) {
	    $liqpay = new LiqPay(Base::GetConstant('liqpay:public_key','i48066539671'), Base::GetConstant('liqpay:private_key','plKvI40SGyr8Opr8FxSucf6M8SG5DwiAqpUXVDkQ'));
	    $html = $liqpay->cnb_form(array(
	        'action'         => 'pay',
	        'amount'         => $dAmount,
	        'currency'       => 'UAH',
	        'description'    => Language::GetMessage('liqpay_site_name').' оплата за заказ №'.$iIdOrder,
	        'order_id'       => 'order_id_'.$iIdOrder,
	        'version'        => '3',
	        'result_url'	 => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/pages/cart_package_list',
	        'server_url'	 => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/pages/payment_liqpay_result'
	    ), true);
	     
	    return $html;
	}
	//-----------------------------------------------------------------------------------------------
	
}

?>