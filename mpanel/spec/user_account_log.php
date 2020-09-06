<?

/**
 * @author Mikhail Starovoyt
 */
require_once(SERVER_PATH.'/mpanel/spec/account_log.php');
class AUserAccountLog extends AAccountLog
{
	public $sPathToFile='/imgbank/temp_upload/';

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName='user_account_log';
		$this->sTablePrefix='ual';
		$this->sAction='user_account_log';
		$this->sWinHead=Language::getDMessage('User account log');
		$this->sPath = Language::GetDMessage('>>Users >');
		//$this->aCheckField=array('login','name','passwd');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();

		//		Base::$tpl->assign('aPayType', BaseTemp::EnumToArray("user_account_log","pay_type"));
		Base::$tpl->assign('aSection', BaseTemp::EnumToArray("user_account_log","section"));
		Base::$tpl->assign('iSearchIdAccountSize',Base::GetConstant("account_log_month:subcontal_search_field_size",5));
		Base::$tpl->assign('iSearchIdAccountWidth',Base::GetConstant("account_log_month:subcontal_search_field_width","200px"));

		// type
		$aType = array( '' 	 => 'All',
		'debet' => 'Debet',
		'credit' => 'Credit',
		);
		Base::$tpl->assign('aType', $aType);
		$aUserType = array(
		'customer'=>'Customer',
		'provider'=>'Provider',
		'vip'=>'VipRep',
		);
		Base::$tpl->assign('aUserType', $aUserType);

		Base::$tpl->assign('sForDate', strtotime('+1 day'));
		Base::$tpl->assign('sFromDate', mktime(0, 0, 0, date("m")-6, date("d"), date("Y")));

		Base::$tpl->assign('aUserAccountLogType',Base::$db->GetAssoc(Base::GetSql('Finance/UserAccountLogTypeAssoc')));
		// search form
		//User::AssignPartnerRegion();

		Base::$tpl->assign('aAccount', array(''=>'All')+Db::GetAssoc("Assoc/Account"));

		$this->SearchForm();

		if ($this->aSearch) {
			$this->aSearch['login'] = trim($this->aSearch [login]);
			if ($this->aSearch['login'] && $this->aSearch['user_type']!='vip') {
				$this->sSearchSQL.= " and u.login = '".$this->aSearch['login']."'";
			}
			if ($this->aSearch['id_partner_region'])
			$this->sSearchSQL.= " and uc.id_partner_region='".$this->aSearch['id_partner_region']."' ";

			if ($this->aSearch['date_from'])
			$this->sSearchSQL.= " and ual.post_date>='".DateFormat::FormatSearch($this->aSearch['date_from'])."' ";
			if ($this->aSearch['date_to'])
			$this->sSearchSQL.= " and ual.post_date<='".DateFormat::FormatSearch($this->aSearch['date_to'])."'";
			if ($this->aSearch['amount'])
			$this->sSearchSQL.= " and ual.amount ='".trim($this->aSearch['amount'])."'";
			if ($this->aSearch['description'])
			$this->sSearchSQL.= " and ual.description like '%".trim($this->aSearch['description'])."%'";
			if ($this->aSearch['data'])
			$this->sSearchSQL.= " and ual.data like '%".trim($this->aSearch['data'])."%'";

			if ($this->aSearch['section'])
			$this->sSearchSQL .= " and ual.section='".$this->aSearch['section']."' ";
			if ($this->aSearch['custom_id'])
			$this->sSearchSQL.= " and custom_id='".$this->aSearch['custom_id']."' ";


			if ($this->aSearch[type_]){
				switch ($this->aSearch['type_']){
					case "debet":
						$this->sSearchSQL.=" and ual.amount>=0 ";
						break;
					case "credit":
						$this->sSearchSQL.=" and ual.amount<0 ";
						break;
				}
			}


			if ($this->aSearch['id_provider_invoice']){
				$aWhereData = array();
				$aWhereData['join1'] = "inner join cart as c on (ual.custom_id=c.id and ual.section in ('cart','debt')
					    and c.id_provider_invoice ='".$this->aSearch['id_provider_invoice']."')";
				$aWhereData['join2'] ="inner join cart_package as cp on (cp.id=ual.custom_id and ual.section in ('cart_package'))
						inner join cart as c on (cp.id=c.id_cart_package
					    and c.id_provider_invoice ='".$this->aSearch['id_provider_invoice']."')";
			}

			if ($this->aSearch['id_user_account_log_type'])
			$this->sSearchSQL .= " and ual.id_user_account_log_type_debit='".$this->aSearch['id_user_account_log_type']."'";

			switch ($this->aSearch['user_type']) {
				case 'provider':
					$this->sSqlPath="Finance/UserAccountLog";
					$this->sSearchSQL.= " and u.type_='provider'";
					break;
				case 'vip':
					if ($this->aSearch['login']) $aVipRepresentative=Base::$db->getRow(Base::GetSql('Customer',
					array('login'=>$this->aSearch['login'])));
					if ($aVipRepresentative) {
						$aIdVipUser=array_keys(Db::GetAssoc('Assoc/UserCustomer'
						,array('where'=>" and uc.id_parent='".$aVipRepresentative['id']."'")));
						$aIdVipUser[]=$aVipRepresentative['id'];
						$this->sSearchSQL.=" and ual.id_user in(".implode(',',$aIdVipUser).")";
					}
					else $this->sSearchSQL.=" and 1!=1";
					break;
			}

			if ($this->aSearch['id_account'])
			$this->sSearchSQL .= " and ual.id_account='".$this->aSearch['id_account']."'";
		}

		if ($this->aSearch){
			$aData = $aWhereData;
			if ($this->sSearchSQL) {
				$aDataDebet['where'] = $aData['where'].$this->sSearchSQL.' and ual.amount>0';
				$aDataCredit['where'] = $aData['where'].$this->sSearchSQL.' and ual.amount<0';
			}
			$aDataDebet['sum'] = 'ual.amount';
			$aDataCredit['sum'] = 'ual.amount';

			$dTotalAmountDebet=Base::$db->GetOne(Base::GetSql('UserAccountLog',$aDataDebet));
			$dTotalAmountCredit=Base::$db->GetOne(Base::GetSql('UserAccountLog',$aDataCredit));
			$dTotalAmount=$dTotalAmountDebet+$dTotalAmountCredit;
			$aDataBalance['login'] = $this->aSearch['login']; 
			$dBalance=Base::$db->GetOne(Base::GetSql('UserBalance',$aDataBalance));
			/*
			$dAmountDebt=Db::GetOne("select sum(if(ld.is_payed=0,ld.amount,0)) amount_debt 
				from log_debt as ld where 1=1 ".$sWhereDebt);
			$dAmountDebtEnd=Db::GetOne("select sum(if(ld.is_payed=0,ld.amount,0)) amount_debt_end
				from log_debt as ld inner join cart c on c.id=ld.custom_id and c.order_status='end' where 1=1 ".$sWhereDebt);
			$dAmountStore=Db::GetOne("select sum(price*number) 
				from cart where order_status='store' ".$sWhereCart);
			*/
			Base::$tpl->assign('dTotalAmountDebet', $dTotalAmountDebet);
			Base::$tpl->assign('dTotalAmountCredit', $dTotalAmountCredit);
			Base::$tpl->assign('dTotalAmount', $dTotalAmount);
			Base::$tpl->assign('dAmountDebt', $dAmountDebt);
			Base::$tpl->assign('dBalance', $dBalance);
			Base::$tpl->assign('dAmountDebtEnd', $dAmountDebtEnd?$dAmountDebtEnd:'0');
			Base::$tpl->assign('dAmountStore', $dAmountStore?$dAmountStore:'0');
		}

		Base::$sText .= $this->SearchForm();

		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'ual.id'),
		'login'=>array('sTitle'=>'Login','sOrder'=>'u.login'),
		'account_amount'=>array('sTitle'=>'Account Amount/DebtAmount','sOrder'=>'account_amount'),
		'debet'=>array('sTitle'=>'admin debet','sOrder'=>'ual.amount'),
		'credit'=>array('sTitle'=>'admin credit','sOrder'=>'ual.amount'),
		'post_date'=>array('sTitle'=>'Post date','sOrder'=>'post_date'),
		'section_custom_id'=>array('sTitle'=>'Section/ID','sOrder'=>'section'),
		'description'=>array('sTitle'=>'Description','sOrder'=>'description'),
		'action'=>array(),
		);

		// show search result form if find only 1 user by like criteria
		$aData = $aWhereData;
		$this->GetResultByFindLogin('login', $aData);

		$aWhereData['join_account']=1;
		// debt
		$aCustomerDebt=Base::$db->GetAll(Base::GetSql('CustomerDebt'));
		$aCustomerDebtHash=Language::Array2Hash($aCustomerDebt,'id_user');
		Base::$tpl->assign('aCustomerDebtHash', $aCustomerDebtHash);

		$this->SetDefaultTable($oTable, $aWhereData);
		$oTable->aCallback=array($this,'CallParseUserAccountLog');

		$_SESSION['mpanel']['user_account_log']['sql']=$oTable->sSql;

		if ($this->aSearch['is_yestarday_report']) {
			Base::$aRequest['order']='-1';
			$oTable->sDefaultOrder=" order by ual.id_subconto1, ual.id desc";
		}

		//$oTable->bCountStepper=true;
		Base::$sText.=$oTable->getTable();
		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseUserAccountLog(&$aItem)
	{
		if ($aItem){
			foreach($aItem as $sKey => $aValue) {
				if ($aValue['section']=='firstdata_transaction') $aFirstdataCustomId[]=$aValue['custom_id'];
			}
			if ($aFirstdataCustomId) {
				$aFirstdataAssoc=Db::GetAssoc("select ft.id, ft.trans_id from firstdata_transaction as ft
					where id in (".implode(',',$aFirstdataCustomId).")");

				foreach($aItem as $sKey => $aValue) {
					if ($aValue['section']=='firstdata_transaction')
					$aItem[$sKey]['trans_id']=$aFirstdataAssoc[$aValue['custom_id']];
				}
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function Export()
	{
		$aUserAccountLog=Db::GetAll($_SESSION['mpanel']['user_account_log']['sql']);

		if ($aUserAccountLog)
		{
			$sFileName=DateFormat::GetFileDateTime(time(),'',false)."_ual.xls";

			$oExcel = new Excel();

			$aHeader=array(
			'A'=>array("value"=>"id"),
			'B'=>array("value"=>"login", "autosize"=>true),
			'C'=>array("value"=>"current_account_amount", "autosize"=>true),
			'D'=>array("value"=>"account_amount", "autosize"=>true),
			'E'=>array("value"=>"amount_debet", "autosize"=>true),
			'F'=>array("value"=>"amount_credit", "autosize"=>true),
			'G'=>array("value"=>"post_date", "autosize"=>true),
			'H'=>array("value"=>"user_account_log_type_name", "autosize"=>true),
			'I'=>array("value"=>"description", "autosize"=>true),
			'J'=>array("value"=>"data", "autosize"=>true),
			'K'=>array("value"=>"account title", "autosize"=>true),
			'L'=>array("value"=>"data_cur", "autosize"=>true),
			'M'=>array("value"=>"data_amount", "autosize"=>true),
			'N'=>array("value"=>"data_user", "autosize"=>true),
			);

			$oExcel->SetHeaderValue($aHeader,1,false);
			$oExcel->SetAutoSize($aHeader);
			$oExcel->DuplicateStyleArray("A1:N1");

			$i=$j=2;
			foreach ($aUserAccountLog as $aValue)
			{
				$oExcel->SetCellValue('A'.$i, $aValue['id']);
				$oExcel->SetCellValue('B'.$i, $aValue['login']);
				$oExcel->SetCellValue('C'.$i, $aValue['current_account_amount']);
				$oExcel->SetCellValue('D'.$i, $aValue['account_amount']);
				$oExcel->SetCellValue('E'.$i, ($aValue['amount']>0 ? $aValue['amount']:'') );
				$oExcel->SetCellValue('F'.$i, ($aValue['amount']<0 ? $aValue['amount']:''));
				$oExcel->SetCellValue('G'.$i, $aValue['post_date']);
				$oExcel->SetCellValue('H'.$i, $aValue['user_account_log_type_name']);
				$oExcel->SetCellValue('I'.$i, $aValue['description']);
				$oExcel->SetCellValue('J'.$i, $aValue['data']);
				$oExcel->SetCellValue('K'.$i, $aValue['account_title']);

				$aDataSplit=explode(" ",$aValue['data']);
				$oExcel->SetCellValue('L'.$i, $aDataSplit[0]);
				$oExcel->SetCellValue('M'.$i, $aDataSplit[1]);
				$oExcel->SetCellValue('N'.$i, $aDataSplit[2]);
				$i++;
			}

			$oExcel->WriterExcel5(SERVER_PATH.$this->sPathToFile.$sFileName);
		}
		else {
			Base::$oResponse->addAlert(Language::GetMessage("No data to export"));
			return;
		}

		Base::$tpl->assign('sFileName',$sFileName);
		Base::$tpl->assign('sFilePath',$this->sPathToFile.$sFileName);

		$this->sFileContent=Base::$tpl->fetch('mpanel/user_account_log/export_file.tpl');
		//$this->Index();
		Base::$oResponse->addAssign('export_file_id','innerHTML',$this->sFileContent);
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign()
	{
		Base::$tpl->assign('aPayTypeId', BaseTemp::EnumToArray("user_account_log","pay_type"));
		Base::$tpl->assign('aPayTypeValue', array('Internal', 'Nal', 'Beznal', 'Webmoney', 'Moneybookers','Paypal','Liqpay'));

		Base::$tpl->assign('aSectionId', BaseTemp::EnumToArray("user_account_log","section"));
		Base::$tpl->assign('aSectionValue',  array('Internal', 'Cart package', 'Cart', 'Debt', 'Delivery', 'Other'));

		Base::$tpl->assign('aUserAccountLogType',Base::$db->GetAssoc(Base::GetSql('Finance/UserAccountLogTypeAssoc',array(
		'where'=>" and ualt.id in (1,8)"
		))));
		Base::$tpl->assign('aAccount',array('0'=>"Other")+Db::GetAssoc(Base::GetSql('Assoc/Account')));
	}
	//-----------------------------------------------------------------------------------------------
	public function AfterApply($aBeforeRow,$aAfterRow)
	{
		// Create new
		if (!$aBeforeRow && $aAfterRow){
			//
		}else{
			// Edit current
			Log::FinanceAdd(array(),'user_account_log',$aAfterRow['id_user']
			,$aAfterRow['id'].':<b>old:</b> '
			.$aBeforeRow['id_account'].' '
			.$aBeforeRow['pay_type'].' '
			.$aBeforeRow['section'].' '
			.$aBeforeRow['custom_id'].' '
			.$aBeforeRow['description'].' '
			.'<br><b>new:</b> '
			.$aAfterRow['id_account'].' '
			.$aAfterRow['pay_type'].' '
			.$aAfterRow['section'].' '
			.$aAfterRow['custom_id'].' '
			.$aAfterRow['description'].' '
			,$_SESSION['admin']['login']);

		}
	}
	//-----------------------------------------------------------------------------------------------
	public function Reverse()
	{
		$aUserAccountLog=Db::GetRow(Base::GetSql('UserAccountLog',array('id'=>Base::$aRequest['id'])));
		Base::$tpl->assign('aData',$aUserAccountLog);
		Base::$tpl->assign('aCustomer',Db::GetRow(Base::GetSql('Customer',array('id'=>$aUserAccountLog['id_user']))));
		Base::$tpl->assign('sReturn', stripslashes(Base::$aRequest['return']));

		$this->sAction = "user_account_log/reverse";
		Admin::ProcessTemplateForm('>>Users > Reverse');
	}
	//-----------------------------------------------------------------------------------------------
	public function ReverseApply()
	{
		if (!Base::$aRequest['data']['amount'] || Base::$aRequest['data']['amount']<0) {
			$this->Message('MT_ERROR',Language::GetDMessage('Please fill out all fields'));
			return;
		}
		if (!Base::$aRequest['data']['id']) {
			$this->Message('MT_ERROR',Language::GetDMessage('Undefined transaction'));
			return;
		}

		$aUserAccountLog=Db::GetRow(Base::GetSql('UserAccountLog',array('id'=>Base::$aRequest['data']['id'])));
		$aFirstdataTransaction=Db::GetRow(Base::GetSql('FirstdataTransaction',array('id'=>$aUserAccountLog['custom_id'])));
		if ($aFirstdataTransaction['reversal_amount']) {
			$this->Message('MT_ERROR',Language::GetDMessage('Already reversed transaction for amount').' : '
			.($aFirstdataTransaction['reversal_amount']/100));
			return;
		}
		if (!$aFirstdataTransaction || !$aUserAccountLog['custom_id']) {
			$this->Message('MT_ERROR',Language::GetDMessage('Undefined transaction'));
			return;
		}

		$oPaymentFirstdata=new PaymentFirstdata();
		$oMerchant = new Merchant( $oPaymentFirstdata->aConfig['ecomm_server_url'],$oPaymentFirstdata->aConfig['cert_url']
		,$oPaymentFirstdata->aConfig['cert_pass'],1);
		$iAmount= Base::$aRequest['data']['amount']*100;
		$sResponse = $oMerchant->reverse(urlencode($aFirstdataTransaction['trans_id']),$iAmount);

		if (substr($sResponse,8,2)=="OK") {
			Db::AutoExecute('firstdata_transaction',array('reversal_amount'=>$iAmount),'UPDATE'
			,"id='".$aFirstdataTransaction['id']."'");

			$iInsertedId=Finance::Deposit($aUserAccountLog['id_user'],-Base::$aRequest['data']['amount']
			,Language::GetMessage('firstdata reverse').': '.-Base::$aRequest['data']['amount'].' USD'
			,'','',
			'USD '.-Base::$aRequest['data']['amount'].' '.$_SESSION['admin']['login']
			,361,$aUserAccountLog['id_user_account_log_type_credit'],$aUserAccountLog['id_subconto1']);

			//Invoice Account log add
			if ($iInsertedId) {
				InvoiceAccountLog::Add($aUserAccountLog['id_user'],$iInsertedId,'user_account_log'
				,Base::$aRequest['data']['amount']);
			}
		}
		else {
			$oPaymentFirstdata->Log("firstdata", "Bad reversal result ".$sResponse);
			$this->Message('MT_ERROR', "Bad reversal result ".$sResponse);
			return;
		}

		$this->AdminRedirect($this->sAction);
	}
	//-----------------------------------------------------------------------------------------------
}
?>