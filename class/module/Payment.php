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
				$aField['merchant_id']=array('type'=>'hidden','name'=>'description','value'=>Language::GetConstant('payment:liqpay_description','Liqpay description').' '.Auth::$aUser['login'].':'.Auth::$aUser['id']);
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
		$this->Log("Liqpay", print_r(Base::$aRequest,true));

		//i9477382803
		//wvuDB6rYgZVp1N1zBYd4nhljaXAIstlRbR

		if (Base::$aRequest['status']=='success'){
			$sTransactionId=Db::GetOne("select id from liqpay_txn where id='".Base::$aRequest['transaction_id']."'");
			if($sTransactionId)
			{
				$this->Log("liqpay", "Old txn id '".Base::$aRequest['transaction_id']."'");
			} else {
				Db::Execute("INSERT INTO `liqpay_txn` (`id`, `date`) VALUES('".Base::$aRequest['transaction_id']."', NOW())");
				if(!in_array(Base::$aRequest['currency'],array_keys($this->aLiqpayCurrency) ))
				{
					$this->Log("liqpay", "Bad currency (".Base::$aRequest['currency']." not in th list)");
				} else {
					$sSignatureSource ="|".
					Base::$aRequest['version']."|".
					Base::GetConstant('payment:liqpay_merchant_password','wvuDB6rYgZVp1N1zBYd4nhljaXAIstlRbR')."|".
					Base::$aRequest['action_name']."|".
					Base::$aRequest['sender_phone']."|".
					Base::$aRequest['merchant_id']."|".
					Base::$aRequest['amount']."|".
					Base::$aRequest['currency']."|".
					Base::$aRequest['order_id']."|".
					Base::$aRequest['transaction_id']."|".
					Base::$aRequest['status']."|".
					Base::$aRequest['code']."|";
					$sCheckSignature=base64_encode(sha1($sSignatureSource,1));

					if ($sCheckSignature==Base::$aRequest['signature']) {
						$aOrderId=explode('_',Base::$aRequest['order_id']);
						$iIdUser=$aOrderId[0];

						$this->Log("liqpay", "Accepted summ ".Base::$aRequest['amount'].' '
						.Base::$aRequest['currency']." user_id:".$iIdUser);

						$dAmount=Currency::BasePrice(Base::$aRequest['amount'], Base::$aRequest['currency']);

						//						$iInsertedId=Finance::Deposit($iIdUser,$dAmount
						//						,Language::GetMessage('liqpay auto payment').': '.Base::$aRequest['amount'].' '.
						//						Base::$aRequest['currency']
						//						,'','liqpay','internal','',1);
						//
						//						//Invoice Account log add
						//						if ($iInsertedId) {
						//							InvoiceAccountLog::Add($iIdUser,$iInsertedId,'user_account_log',$dAmount);
						//						}
						$aSmartyTemplate=String::GetSmartyTemplate('liqpay_success', array(
						));
						Mail::AddDelayed(Base::GetConstant('global:to_email')
						,$aSmartyTemplate['name'].$aCartPackage['id'],
						$aSmartyTemplate['parsed_text']);

					}
					else {
						$this->Log("liqpay", "Bad signature ".Base::$aRequest['signature']." != ".$sCheckSignature."");
					}
				}
			}
		}
	}
	//-----------------------------------------------------------------------------------------------

}

?>