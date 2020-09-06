<?

/**
 * @author Mikhail Starovoyt
 *
 * @version 4.5.1
 * - fixed:AT-138 customer creation with password was incorrect
 *
 * @version 4.5.0
 * Initial admin module from base auto box: AT-114
 */

require_once(SERVER_PATH.'/mpanel/spec/user.php');
class ACustomer extends AUser
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName='user';
		$this->sAdditionalLink='_customer';
		$this->sSqlPath = "Customer";
		$this->sTablePrefix='uc';
		$this->sAction='customer';
		$this->sWinHead=Language::getDMessage('Customer');
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField=array('login');
		$this->sBeforeAddMethod = "BeforeAdd";
		$this->aChildTable = array(
		    array('sTableName'=>'user_customer', 'sTablePrefix'=>'uc', 'sTableId'=>'id_user'),
		);
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();
		
		//--------------------
		Base::$sText .= $this->SearchForm ();
		if ($this->aSearch) {
		    if (Language::getConstant('mpanel_search_strong',0)) {
		        if ($this->aSearch['login'])$this->sSearchSQL .= " and u.login = '".$this->aSearch['login']."'";
		        if ($this->aSearch['id'])	$this->sSearchSQL .= " and u.id = '".$this->aSearch['id']."'";
		        if ($this->aSearch['customer_group_name'])	$this->sSearchSQL .= " and u.customer_group_name = '".$this->aSearch['customer_group_name']."'";
		        if ($this->aSearch['visible'])	$this->sSearchSQL .= " and u.visible = '".$this->aSearch['visible']."'";
		        if ($this->aSearch['confirmed'])	$this->sSearchSQL .= " and u.confirmed = '".$this->aSearch['confirmed']."'";
		    }
		    else {
		        if ($this->aSearch['login'])$this->sSearchSQL .= " and u.login like '%".$this->aSearch['login']."%'";
		        if ($this->aSearch['id'])	$this->sSearchSQL .= " and u.id like '%".$this->aSearch['id']."%'";
		        if ($this->aSearch['customer_group_name'])	$this->sSearchSQL .= " and u.customer_group_name like '%".$this->aSearch['customer_group_name']."%'";
		        if ($this->aSearch['visible'])	$this->sSearchSQL .= " and u.visible like '%".$this->aSearch['visible']."%'";
		        if ($this->aSearch['confirmed'])	$this->sSearchSQL .= " and u.confirmed like '%".$this->aSearch['confirmed']."%'";
		    }
		    if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and u.visible='1'";
		    if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and u.visible='0'";
		    switch($this->aSearch['visible']){
		        case '1':
		            $this->sSearchSQL.=" and u.visible>='1'";
		            break;
		        case '0':
		            $this->sSearchSQL.=" and u.visible>='0'";
		            break;
		        case  '':
		            break;
		    }
		    if ($this->aSearch['approved']=='1')	$this->sSearchSQL .= " and u.approved='1'";
		    if ($this->aSearch['approved']=='0')	$this->sSearchSQL .= " and u.approved='0'";
		    switch($this->aSearch['approved']){
		        case '1':
		            $this->sSearchSQL.=" and u.approved>='1'";
		            break;
		        case '0':
		            $this->sSearchSQL.=" and u.approved>='0'";
		            break;
		        case  '':
		            break;
		    }
		   
		}
		//--------------------
		
		$oTable=new Table();
		$oTable->aColumn=array(
		'login'=> array('sTitle'=>'Login','sOrder'=>'u.login'),
		'id_user'=>	array('sTitle'=>'Id','sOrder'=>'uc.id_user'),
		'customer_name'=> array('sTitle'=>'Fname','sOrder'=>'uc.name'),
		'phone'=> array('sTitle'=>'Phone','sOrder'=>'uc.phone'),
		'customer_group_name'=>	array('sTitle'=>'Gustomer group','sOrder'=>'cg.name'),
		'email'=> array('sTitle'=>'E-mail','sOrder'=>'u.email'),
		'visible'=> array('sTitle'=>'Visible','sOrder'=>'u.visible'),
		'confirmed'=> array('sTitle'=>'confirmed','sOrder'=>'u.comfirmed'),
		'action'=> array(),
		);

		$this->SetDefaultTable($oTable);
// 		$oTable->bCacheStepper=true;
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAdd()
	{
		Base::$tpl->assign('aCustomerGroupAssoc', DB::GetAssoc('Assoc/CustomerGroup',array(
		'where' => ' and cg.visible=1'
		)));
		$aFinanceType = array(
		'fiz' 	=> 'fiz',
		'nds'	=> 'nds',
		'beznds'	=> 'beznds',
		);
		$aOfficeCountry=Db::GetAssoc("Assoc/OfficeCountry");

		Base::$tpl->assign('aFinanceType', $aFinanceType);
		Base::$tpl->assign('aManagerAssoc', Db::GetAssoc('Assoc/UserManager'));
		Base::$tpl->assign('aCustomerGroup', $aCustomerGroup);
		Base::$tpl->assign('aOfficeCountry', $aOfficeCountry);
	}
	//-----------------------------------------------------------------------------------------------
	public function AfterApply($aBeforeRow,$aAfterRow)
	{
		// Create new
		if (!$aBeforeRow && $aAfterRow){
			$aUserCustomer  = Base::$aRequest['data'];
			unset($aUserCustomer['id']);
			$aUserCustomer['id_user'] = $aAfterRow['id'];
			$aUserAccount['id_user'] = $aAfterRow['id'];
			$aUserAccount['amount'] = Base::$aRequest['amount'] ?  Base::$aRequest['amount'] : 0;
			Base::$db->AutoExecute('user_customer', $aUserCustomer , 'INSERT', false, true, true );
			Base::$db->AutoExecute('user_account', $aUserAccount , 'INSERT', false, true, true );
		}else{
			Db::AutoExecute('user_customer',Base::$aRequest['data'],'UPDATE',"id_user='".Base::$aRequest['data']['id']."'"
			,true,true);

			// Edit current
			Base::$aRequest['data']['discount_static']  =
			Base::$aRequest['data']['discount_static']>=99 ? 0 : Base::$aRequest['data']['discount_static'];
			Base::$aRequest['data']['discount_dynamic'] =
			Base::$aRequest['data']['discount_dynamic']>=99 ? 0 : Base::$aRequest['data']['discount_dynamic'];


			$aCustomer=Base::$db->GetRow(Base::GetSql('Customer',array('id'=>Base::$aRequest['data']['id']) ));
			if ($aCustomer['user_debt']!=Base::$aRequest['data']['user_debt']) {
				Log::FinanceAdd(array(),'debt',$aAfterRow['id'],'New user debt: $'.
				Base::$aRequest['data']['user_debt'],$_SESSION['admin']['login']);
			}
			if ($aCustomer['discount_static']!=Base::$aRequest['data']['discount_static']) {
				Log::FinanceAdd(array(),'discount',$aAfterRow['id'],"New static discount: ".
				Base::$aRequest['data']['discount_static']." %",$_SESSION['admin']['login']);
			}
			if ($aCustomer['discount_dynamic']!=Base::$aRequest['data']['discount_dynamic']) {
				Log::FinanceAdd(array(),'discount',$aAfterRow['id'],"New dynamic discount: ".
				Base::$aRequest['data']['discount_dynamic'] ."%",$_SESSION['admin']['login']);
			}

		}
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Test global test data for customers and providers if is_test flag is set for user
	 */
	public function ClearTestData($bShowAlert=true)
	{
		$iAffectedRow = 0;
		$aTestUser = DB::GetAssoc("select u.id as id, u.id as value from user as u where u.is_test='1'");
		$aTestUserProvider = DB::GetAssoc("select u.id as id, u.id as value from user as u where u.is_test='1' and u.type_='provider'");		
		if ($aTestUser){
			$sWhere=" and id_user in (".implode(',',$aTestUser).")";
			$inCustomer="'".implode("','", $aTestUser)."'";
			$sWhereProvider=" and id_user in (".implode(',',$aTestUserProvider).")";
			$inProvider="'".implode("','", $aTestUserProvider)."'";
			
			DB::Execute("delete from cart_package where 1=1" . $sWhere);
			$iAffectedRow+=DB::AffectedRow();

			$aCart=DB::getAll("select * from cart where type_='order' ".$sWhere);
			$aCartInvoice=Db::GetAssoc("select id as i,id  from cart_invoice where id_user_customer in (".$inCustomer.")");
			if ($aCart) foreach ($aCart as $value) $aCartId[]=$value['id'];
			if ($aCartId) {
				DB::Execute("delete from cart_history where id_cart in (".implode(',',$aCartId).")");
				$iAffectedRow+=DB::AffectedRow();
				DB::Execute("delete from cart_log where id_cart in (".implode(',',$aCartId).")");
				$iAffectedRow+=DB::AffectedRow();
			}
			DB::Execute("delete from cart where type_='order'".$sWhere);
			$iAffectedRow+=DB::AffectedRow();
			DB::Execute("delete from user_account_log where 1=1".$sWhere);
			$iAffectedRow+=DB::AffectedRow();
			//DB::Execute("update user_account set amount=0  where 1=1".$sWhere);
			DB::Execute("delete from user_account where 1=1".$sWhere);
			$iAffectedRow+=DB::AffectedRow();
			DB::Execute("delete from log_finance where 1=1".$sWhere);
			$iAffectedRow+=DB::AffectedRow();
			DB::Execute("delete from log_debt where 1=1".$sWhere);
			$iAffectedRow+=DB::AffectedRow();
			/*
			DB::Execute("delete from invoice_account_log where 1=1".$sWhere);
			$iAffectedRow+=DB::AffectedRow();
			*/
			DB::Execute("delete from invoice_customer where 1=1".$sWhere);
			$iAffectedRow+=DB::AffectedRow();
			DB::Execute("delete from vin_request where 1=1".$sWhere);
			$iAffectedRow+=DB::AffectedRow();
			
			$aMessage=Db::GetAssoc("select id as i,id  from message where 1=1".$sWhere);
			DB::Execute("delete from message_attachment where id_message in ('".implode("','", $aMessage)."')");
			$iAffectedRow+=DB::AffectedRow();
			
			DB::Execute("delete from message where 1=1".$sWhere);
			$iAffectedRow+=DB::AffectedRow();
			
			DB::Execute("delete from cart_invoice_log where id_cart_invoice in ('".implode("','", $aCartInvoice)."')");
			$iAffectedRow+=DB::AffectedRow();
				
			DB::Execute("delete from cart_invoice where id_user_customer in (".$inCustomer.")");
			$iAffectedRow+=DB::AffectedRow();

			DB::Execute("delete from user where id in (".$inCustomer.")");
			$iAffectedRow+=DB::AffectedRow();
			
			DB::Execute("delete from user_customer where 1=1".$sWhere);
			$iAffectedRow+=DB::AffectedRow();

			if ($aTestUserProvider) {
    			DB::Execute("delete from user_provider where 1=1".$sWhereProvider);
    			$iAffectedRow+=DB::AffectedRow();
    			
    			DB::Execute("delete from cart where type_!='order' and id_provider in (".$inProvider.")");
    			$iAffectedRow+=DB::AffectedRow();
    			
    			Db::Execute("delete from price where id_provider in (".$inProvider.")");
    			$iAffectedRow+=DB::AffectedRow();
    
    			Db::Execute("delete from price_import where id_provider in (".$inProvider.")");
    			$iAffectedRow+=DB::AffectedRow();
			}
		}
		if ($bShowAlert)
		Base::$oResponse->addAlert(Language::GetDMessage("All test data cleared. Deleted and updated rows:").$iAffectedRow);
		
		Base::$oResponse->addScript("javascript:xajax_process_browse_url('?action=customer'); return false;");
	
	}
	//-----------------------------------------------------------------------------------------------
	public function Deposit($action = 'deposit')
	{
		Base::$tpl->assign('aCurrency', Base::$db->GetAll(Base::GetSql('Currency',array("order"=>"num"))));
		$aUser=Base::$db->GetRow(Base::GetSql('User',array(
		"id"=>Base::$aRequest['user_id'] ? Base::$aRequest['user_id']:-1,
		)));
		if ($aUser)	Base::$tpl->assign('aData', Base::$db->GetRow(Base::GetSql(ucfirst($aUser['type_'])
		,array("id"=>$aUser['id']))));

		Base::$tpl->assign('sFormType', $action);

		Base::$tpl->assign('sReturn', stripslashes(Base::$aRequest['return']));

		if ($aUser){
			if($aUser['type_']=='provider') $sLogTypeWhere=" and ualt.id in (361,3338,631,632)";
			else $sLogTypeWhere=" and ualt.id in (361,3338)";
		}
		else $sLogTypeWhere=" and ualt.is_customer_visible='0'";

		Base::$tpl->assign('aUserAccountLogType',Db::GetAssoc(Base::GetSql('Finance/UserAccountLogTypeAssoc',array(
		'where'=>$sLogTypeWhere,
		))));

		Base::$tpl->assign('aAccount',array('0'=>"Other")+Db::GetAssoc(Base::GetSql('Assoc/Account')));

		$this->sAction = "customer/deposit";
		Admin::ProcessTemplateForm('>>Users > '.ucwords($action));
	}
	//-----------------------------------------------------------------------------------------------
	public function DepositApply($bXajaxRedirect=true)
	{
		// check amount
		if ( round(Base::$aRequest['data']['amount'],2) == 0){
			$this->Message('MT_ERROR', Language::GetDMessage('Amount must be integer and not 0'));
			return;
		}
		if ( Base::$aRequest['data']['id_user_account_log_type_debit'] == 361 &&
		!Base::$aRequest['data']['id_subconto1']){
			$this->Message('MT_ERROR', Language::GetDMessage('You need to set account for money deposit'));
			return;
		}
		//if 361 and !currency {} else if !3338 and !currency {}
		if ($bXajaxRedirect && Base::$aRequest['data']['id_user_account_log_type_debit'] == 361
		&& !$this->CheckAccountCurrency(Base::$aRequest['data']['id_subconto1']
		,Base::$aRequest['data']['code_currency']) ){
			$this->Message('MT_ERROR', Language::GetDMessage('Check account currency'));
			return;
		}else if(Base::$aRequest['data']['id_user_account_log_type_debit'] != 3338
		&& !$this->CheckAccountCurrency(Base::$aRequest['data']['id_subconto1']
		,Base::$aRequest['data']['code_currency'])){
			$this->Message('MT_ERROR', Language::GetDMessage('Check account currency'));
			return;
		}

		$sDescription = Base::$aRequest['data']['description'];
		if (!Base::$aRequest['data']['description']){
			if (Base::$aRequest['data']['amount'] > 0) $sDescription = Language::GetDMessage('admin_debet');
			else $sDescription = Language::GetDMessage('admin_credit');
		}

		$aAccount=Db::GetRow(Base::GetSql('Account',array(
		'id'=>(Base::$aRequest['data']['id_subconto1'] ? Base::$aRequest['data']['id_subconto1']:'-1'),
		)));
		if ($aAccount) $iIdUserAccountLogCredit=$aAccount['id_user_account_log_type'];
		else $iIdUserAccountLogCredit=361;

		$dCurrencyAmount=trim(Base::$aRequest['data']['currency_'.Base::$aRequest['data']['code_currency']]);
		if (Base::$aRequest['data']['zero_currency_amount']) $dCurrencyAmount=0;

		$iInsertedId=Finance::Deposit(Base::$aRequest['data']['id'],
		Base::$aRequest['data']['amount'],
		$sDescription,
		Base::$aRequest['data']['custom_id'],
		Base::$aRequest['data']['section'],
		Base::$aRequest['data']['code_currency']
		.' '.$dCurrencyAmount.' '
		.($_SESSION['admin']['login']?$_SESSION['admin']['login']:Auth::$aUser['login'])
		,Base::$aRequest['data']['id_user_account_log_type_debit']
		,$iIdUserAccountLogCredit
		,Base::$aRequest['data']['id_subconto1']);

		if (!$iInsertedId){
			Admin::Message ( 'MT_ERROR', Language::GetDMessage ( 'Can\'t execute this operation' ) );
			return;
		}

		//Invoice Account log add
		if ($iInsertedId && in_array(Base::$aRequest['data']['id_user_account_log_type_debit'],array(361))) {
			InvoiceAccountLog::Add(Base::$aRequest['data']['id'],$iInsertedId,'user_account_log',Base::$aRequest['data']['amount']);
		}

		//Referer percentage
		//		if (Base::$aRequest['data']['id_user_referer'] && "customer" == Base::$aRequest['data']['type_']
		//		&& Base::$aRequest['data']['id_user_account_log_type']=='1') {
		//			$dRefererAmount=round(Base::$aRequest['data']['amount']*Base::$aGeneralConf['RefererPercentage']/100,2);
		//			$sMessage = Base::$aRequest['data']['amount'] > 0 ? 'referer_deposit' : 'referer_withdraw';
		//
		//			$iInsertedId=Finance::Deposit(Base::$aRequest['data']['id_user_referer'],
		//			$dRefererAmount, Language::GetDMessage($sMessage), $iInsertedId,
		//			Base::$aRequest['data']['pay_type'], Base::$aRequest['data']['section'],'',7);
		//
		//	InvoiceAccountLog::Add(Base::$aRequest['data']['id_user_referer'],$iInsertedId,'user_account_log',$dRefererAmount);
		//		}

		if ($bXajaxRedirect) parent::AdminRedirect($this->sAction);
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckAccountCurrency($iIdAccount,$sCurrencyCode)
	{
		if (!$iIdAccount || !$sCurrencyCode) return false;

		$aAccount=Db::GetRow(Base::GetSql('Account',array('id'=>$iIdAccount)));

		if ($aAccount['currency_code']==$sCurrencyCode) return true;
		return false;
	}
	//-----------------------------------------------------------------------------------------------
	public function SetBalance($action = 'set_balance')
	{
		Base::$tpl->assign('aCurrency', Base::$db->GetAll(Base::GetSql('Currency',array("order"=>"num"))));
		$aUser=Base::$db->GetRow(Base::GetSql('User',array(
				"id"=>Base::$aRequest['user_id'] ? Base::$aRequest['user_id']:-1,
		)));
		if ($aUser)	Base::$tpl->assign('aData', Base::$db->GetRow(Base::GetSql(ucfirst($aUser['type_'])
				,array("id"=>$aUser['id']))));
	
		Base::$tpl->assign('sFormType', $action);
	
		Base::$tpl->assign('sReturn', stripslashes(Base::$aRequest['return']));
	
		if ($aUser){
			if($aUser['type_']=='provider') $sLogTypeWhere=" and ualt.id in (361,3338,631,632)";
			else $sLogTypeWhere=" and ualt.id in (361,3338)";
		}
		else $sLogTypeWhere=" and ualt.is_customer_visible='0'";
	
		Base::$tpl->assign('aUserAccountLogType',Db::GetAssoc(Base::GetSql('Finance/UserAccountLogTypeAssoc',array(
		'where'=>$sLogTypeWhere,
		))));
	
		Base::$tpl->assign('aAccount',array('0'=>"Other")+Db::GetAssoc(Base::GetSql('Assoc/Account')));
	
		$this->sAction = "customer/set_balance";
		Admin::ProcessTemplateForm('>>Users > '.ucwords($action));
	}
	//-----------------------------------------------------------------------------------------------
	public function SetBalanceApply($bXajaxRedirect=true)
	{
		// check amount
		if ( round(Base::$aRequest['data']['amount'],2) == 0){
			$this->Message('MT_ERROR', Language::GetDMessage('Amount must be integer and not 0'));
			return;
		}
		Db::StartTrans();
		
		$sDescription = Language::getMessage('set balance');
		$iIdUserAccountLogCredit=361;
		$oResult = Db::Execute("Update user_account set amount=0".
				" where id_user=".Base::$aRequest['data']['id']);
		$sType = Base::$db->GetOne("select type_ from user where id=".Base::$aRequest['data']['id']);
		if ($sType == 'provider')
			Base::$db->Execute("Delete from user_account_log where id_user = ".Base::$aRequest['data']['id']);
		
		$iInsertedId=Finance::Deposit(Base::$aRequest['data']['id'],
				Base::$aRequest['data']['amount'],
				$sDescription,
				Base::$aRequest['data']['custom_id'],
				'internal',
				Base::$aRequest['data']['code_currency']
				.' '.$dCurrencyAmount.' '
				.($_SESSION['admin']['login']?$_SESSION['admin']['login']:Auth::$aUser['login'])
				,361
				,$iIdUserAccountLogCredit
				,Base::$aRequest['data']['id_subconto1']);
		
		if (!$iInsertedId){
			Admin::Message ( 'MT_ERROR', Language::GetDMessage ( 'Can\'t execute this operation' ) );
			return;
		}
		Db::CompleteTrans();
		if ($bXajaxRedirect) parent::AdminRedirect($this->sAction);
	}
}
?>