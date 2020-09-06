<?
/**
 * @author Oleksandr Starovoit
 */

class Buh extends Base
{
	var $sPrefix="buh";
	var $sCurrentPeriod;
	var $sError;
	var $sDateTo;
	var $sDateFrom;
	var $aActionException=array('finance_payforaccount', 'cron_minutely', 'cron_hourly');
	var $sCurrentPeriodLastDate;

	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		if (!in_array(Base::$aRequest['action'], $this->aActionException)) Auth::NeedAuth();

		//Base::$aTopPageTemplate=array('panel/tab_manager_package.tpl'=>'buh_changeling');
		$this->sCurrentPeriod=Base::GetConstant("buh:current_period");
		$this->sDateFrom=Db::GetOne("select date_format('".$this->sCurrentPeriod."','".Base::GetConstant("date_format")."')");
		$this->sDateTo=Db::GetOne("select date_format('".$this->GetLastDate($this->sCurrentPeriod)."','".Base::GetConstant("date_format")."')");
		$this->sCurrentPeriodLastDate=$this->GetLastDate($this->sCurrentPeriod);
		Base::$tpl->assign('sDateFrom', $this->sDateFrom);
		Base::$tpl->assign('sDateTo', $this->sDateTo);
	}
	//-----------------------------------------------------------------------------------------------
	public function Index(){

		Base::$tpl->assign('sReturn',Base::RemoveMessageFromUrl($_SERVER ['QUERY_STRING']));
		Base::$sText.=Base::$tpl->fetch($this->sPrefix.'/index.tpl');

	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Set next CurrentPeriod
	 *
	 * @return srtring current period
	 */
	public function SetNextPeriod()
	{
		$this->sCurrentPeriod=DateFormat::GetNextMonth($this->sCurrentPeriod);
		Base::UpdateConstant('buh:current_period',$this->sCurrentPeriod);
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Get last date of month
	 *
	 * @param string $sDate (yyyy-mm-dd)
	 * @return string
	 */
	public function GetLastDate($sDate)
	{
		return Db::GetOne("select last_day('".$sDate."')");
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Entry ( privat method for mooving money for buh accounts )
	 *
	 * @param array $aDoc array of parameters of document
	 * @param integer $iAccountD id account from buh debet
	 * @param integer $iAccountC id account from buh credet
 	 * @param double $dAmount summa
	 * @param string $sDescription Description
	 * @param integer $iAccountDSub1
	 * @param integer $iAccountDSub2
	 * @param integer $iAccountDSub3
	 * @param integer $iAccountCSub1
	 * @param integer $iAccountCSub2
	 * @param integer $iAccountCSub3
	 * @param integer $iCurrency
	 * @param double $dCurrencySum
	 * @param double $dNumber number if buh account has flag number
	 * @return boolean
	 */
	private function Entry($aDoc, $iAccountD, $iAccountC, $dAmount, $sDescription=""
	, $iAccountDSub1=0, $iAccountDSub2=0, $iAccountDSub3=0, $iAccountCSub1=0, $iAccountCSub2=0, $iAccountCSub3=0
	, $iCurrency=1, $dCurrencySum=0, $dNumber="", $id=0, $sPostDate=0)
	{
		$aData['id_buh_section']=$aDoc['id_buh_section'];
		$aData['buh_section_id']=$aDoc['buh_section_id'];
		//$aData['post_date']=
		$aData['amount']=str_replace(",",".",$dAmount);
		$aData['id_buh_debit']=$iAccountD;
		$aData['id_buh_debit_subconto1']=(int)$iAccountDSub1;
		$aData['id_buh_debit_subconto2']=(int)$iAccountDSub2;
		$aData['id_buh_debit_subconto3']=(int)$iAccountDSub3;
		$aData['id_buh_credit']=$iAccountC;
		$aData['id_buh_credit_subconto1']=(int)$iAccountCSub1;
		$aData['id_buh_credit_subconto2']=(int)$iAccountCSub2;
		$aData['id_buh_credit_subconto3']=(int)$iAccountCSub3;
		$aData['id_currency']=(int)$iCurrency;
		$aData['currency_sum']=str_replace(",",".",$dCurrencySum);
		$aData['number']=$dNumber;
		$aData['description']=$sDescription;

		if ($this->CheckParametr($aData)) {
			if ($id) {
				$aData['post_date']=$sPostDate;
				Db::AutoExecute("buh_entry",$aData,"UPDATE","id=".$id);
			} else {
				Db::AutoExecute("buh_entry",$aData);
			}

			Db::Execute(
			"insert ignore into buh_entry_month (date_month, id_buh, id_buh_subconto1, id_buh_subconto2, id_buh_subconto3
			, amount_debit_start, amount_credit_start, amount_debit, amount_credit, amount_debit_end, amount_credit_end)
			values ('".$this->sCurrentPeriod."',".$aData['id_buh_debit'].",".(int)$aData['id_buh_debit_subconto1']
			.",".(int)$aData['id_buh_debit_subconto2'].",".(int)$aData['id_buh_debit_subconto3'].",0,0,0,0,0,0)
			,('".$this->sCurrentPeriod."',".$aData['id_buh_credit'].",".(int)$aData['id_buh_credit_subconto1']
			.",".(int)$aData['id_buh_credit_subconto2'].",".(int)$aData['id_buh_credit_subconto3'].",0,0,0,0,0,0)
			");

			if ($aData['id_buh_debit']==361) {
				$aRow=Db::GetRow(Base::GetSql("Buh/Changeling", array(
				"date_from"=>Db::GetStrToDate($this->sDateFrom,true),
				"date_to"=>Db::GetStrToDate($this->sDateTo,true),
				"id_buh"=>$aData['id_buh_debit'],
				"id_subconto1"=>$aData['id_buh_debit_subconto1']
				)));

				Db::Execute("
				insert into buh_entry_month (date_month, id_buh, id_buh_subconto1, id_buh_subconto2, id_buh_subconto3
				, amount_debit_start, amount_credit_start, amount_debit, amount_credit, amount_debit_end, amount_credit_end)
				values ('".$this->sCurrentPeriod."',".$aData['id_buh_debit'].",".(int)$aData['id_buh_debit_subconto1']
				.",".(int)$aData['id_buh_debit_subconto2'].",".(int)$aData['id_buh_debit_subconto3']
				.",".($aRow['amount_debit_start']?$aRow['amount_debit_start']:0).",".($aRow['amount_credit_start']?$aRow['amount_credit_start']:0)
				.",".($aRow['amount_debit']?$aRow['amount_debit']:0).",".($aRow['amount_credit']?$aRow['amount_credit']:0)
				.",".($aRow['amount_debit_end']?$aRow['amount_debit_end']:0).",".($aRow['amount_credit_end']?$aRow['amount_credit_end']:0).")
				on duplicate key update amount_debit=values(amount_debit), amount_credit=values(amount_credit), 
				amount_debit_end=values(amount_debit_end), amount_credit_end=values(amount_credit_end)
				");
			}

			if ($aData['id_buh_credit']==361) {
				$aRow=Db::GetRow(Base::GetSql("Buh/Changeling", array(
				"date_from"=>Db::GetStrToDate($this->sDateFrom,true),
				"date_to"=>Db::GetStrToDate($this->sDateTo,true),
				"id_buh"=>$aData['id_buh_credit'],
				"id_subconto1"=>$aData['id_buh_credit_subconto1']
				)));

				Db::Execute("
				insert into buh_entry_month (date_month, id_buh, id_buh_subconto1, id_buh_subconto2, id_buh_subconto3
				, amount_debit_start, amount_credit_start, amount_debit, amount_credit, amount_debit_end, amount_credit_end)
				values ('".$this->sCurrentPeriod."',".$aData['id_buh_credit'].",".(int)$aData['id_buh_credit_subconto1']
				.",".(int)$aData['id_buh_credit_subconto2'].",".(int)$aData['id_buh_credit_subconto3']
				.",".$aRow['amount_debit_start'].",".$aRow['amount_credit_start']
				.",".$aRow['amount_debit'].",".$aRow['amount_credit']
				.",".$aRow['amount_debit_end'].",".$aRow['amount_credit_end'].")
				on duplicate key update amount_debit=values(amount_debit), amount_credit=values(amount_credit), 
				amount_debit_end=values(amount_debit_end), amount_credit_end=values(amount_credit_end)
				");
			}

		} else return false;

		//$iInsertId=Db::InsertId();
		//TODO aditional operation

		//return $iInsertId;
		return true;
	}
	//-----------------------------------------------------------------------------------------------

	/**
	 * EntrySingle ( method for mooving money for buh accounts single ) transaction
	 *
	 * @param array $aDoc array of parameters of document
	 * @param integer $iAccountD id account from buh debet
	 * @param integer $iAccountC id account from buh credet
 	 * @param double $dAmount summa
	 * @param string $sDescription Description
	 * @param double $dNumber number if buh account has flag number
	 * @param integer $iAccountDSub1
	 * @param integer $iAccountDSub2
	 * @param integer $iAccountDSub3
	 * @param integer $iAccountCSub1
	 * @param integer $iAccountCSub2
	 * @param integer $iAccountCSub3
	 * @param integer $iCurrency
	 * @param double $dCurrencySum
	 * @return boolean
	 */
	public function EntrySingle($aDoc, $iAccountD, $iAccountC, $dAmount, $sDescription=""
	, $iAccountDSub1=0, $iAccountDSub2=0, $iAccountDSub3=0, $iAccountCSub1=0, $iAccountCSub2=0, $iAccountCSub3=0
	, $iCurrency=1, $dCurrencySum=0, $dNumber="", $id=0, $sPostDate=0)
	{
		Db::StartTrans();

		$iId=$this->Entry($aDoc, $iAccountD, $iAccountC, $dAmount, $sDescription
		, $iAccountDSub1, $iAccountDSub2, $iAccountDSub3, $iAccountCSub1, $iAccountCSub2, $iAccountCSub3
		, $iCurrency, $dCurrencySum, $dNumber, $id, $sPostDate);

		if (!$iId) return false;

		Db::CompleteTrans();

		return $iId;
	}
	//-----------------------------------------------------------------------------------------------

	/**
	 * Entry Many
	 *
	 * @param array $aEntry array(0=>array(key of parametr of entry => value of entry))
	 */
	public function EntryMany($aEntry)
	{
		if ($aEntry)
		{
			Db::StartTrans();
			foreach ($aEntry as $sKey => $aValue) {
				$aDoc['id_buh_section']=$aValue['id_buh_section'];
				$aDoc['buh_section_id']=$aValue['buh_section_id'];

				$this->Entry($aDoc, $aValue['id_buh_debit'], $aValue['id_buh_credit'], $aValue['amount'], $aValue['description']
				, $aValue['id_buh_debit_subconto1'], $aValue['id_buh_debit_subconto2'], $aValue['id_buh_debit_subconto3']
				, $aValue['id_buh_credit_subconto1'], $aValue['id_buh_credit_subconto2'], $aValue['id_buh_credit_subconto3']
				, $aValue['id_currency'], $aValue['currency_sum'], $aValue['number']);

			}

			Db::CompleteTrans();
			return true;
		}
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Check parametrs of entry
	 *
	 * @param array $aData
	 * @return string
	 */
	public function  CheckParametr($aData)
	{
		if (!$aData['id_buh_debit'] || !$aData['id_buh_credit'] )
		{
			Db::FailTrans();
			$this->sError="&aMessage[MI_ERROR]=Check Buh Account";
			return false;
		}

		//TODO check subconto
		return true;
	}

	//-----------------------------------------------------------------------------------------------
	/**
	 * Entry_month ( privat method for mooving money for buh_entry_month )
	 *
 	 * @param date $sDateMonth date work month
	 * @param integer $iAccount id account from buh
	 * @param integer $iAccountSub1
	 * @param integer $iAccountSub2
	 * @param integer $iAccountSub3
 	 * @param double $dAmountD summa debit
 	 * @param double $dAmountC summa credit
 	 * @param double $dAmountDEnd summa debit from end
 	 * @param double $dAmountCEnd summa credit from end
	 * @return boolean
	 */
	private function EntryMonth($sDateMonth, $iAccount, $iAccountSub1=0, $iAccountSub2=0, $iAccountSub3=0
	, $dAmountDStart, $dAmountCStart, $dAmountD, $dAmountC, $dAmountDEnd, $dAmountCEnd)
	{
		$dAmountDStart=str_replace(",",".",$dAmountDStart);
		$dAmountCStart=str_replace(",",".",$dAmountCStart);
		$dAmountD=str_replace(",",".",$dAmountD);
		$dAmountC=str_replace(",",".",$dAmountC);
		$dAmountDEnd=str_replace(",",".",$dAmountDEnd);
		$dAmountCEnd=str_replace(",",".",$dAmountCEnd);

		Db::Execute("insert into buh_entry_month
		(date_month, id_buh, id_buh_subconto1, id_buh_subconto2, id_buh_subconto3
		, amount_debit_start, amount_credit_start, amount_debit, amount_credit, amount_debit_end, amount_credit_end)
		values ('".$sDateMonth."',".$iAccount.",".(int)$iAccountSub1.",".(int)$iAccountSub2.",".(int)$iAccountSub3
		.",".(double)$dAmountDStart.",".(double)$dAmountCStart
		.",".(double)$dAmountD.",".(double)$dAmountC.",".(double)$dAmountDEnd.",".(double)$dAmountCEnd.")
		on duplicate key update
		amount_debit_start=values(amount_debit_start), amount_credit_start=values(amount_credit_start)
		, amount_debit=values(amount_debit), amount_credit=values(amount_credit)
		, amount_debit_end=values(amount_debit_end), amount_credit_end=values(amount_credit_end)
		");


		/*
		if ($aData['id_buh_subconto1']) {
		$sWhere=" and id_buh_subconto1=".$aData['id_buh_subconto1'];
		}

		$iId=Db::GetOne("select id from buh_entry_month where id_buh='".$aData['id_buh'].
		"' and id_buh_subconto2='".$aData['id_buh_subconto2']."' and date_month='".$aData['date_month']."'".$sWhere);

		if (!$iId) Db::AutoExecute("buh_entry_month",$aData);
		else Db::AutoExecute("buh_entry_month",$aData,"UPDATE","id=".$iId);

		return $iId;
		*/
		return true;
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * EntryMonthSingle ( method for mooving money for buh_entry_month single ) transaction
	 *
 	 * @param date $dDateMonth date work month
	 * @param integer $iAccount id account from buh
	 * @param integer $iAccountSub1
	 * @param integer $iAccountSub2
	 * @param integer $iAccountSub3
 	 * @param double $dAmountDStart summa debit from start
 	 * @param double $dAmountCStart summa credit from start
 	 * @param double $dAmountD summa debit
 	 * @param double $dAmountC summa credit
 	 * @param double $dAmountDEnd summa debit from end
 	 * @param double $dAmountCEnd summa credit from end
	 * @return boolean
	 */
	public function EntryMonthSingle($dDateMonth, $iAccount, $iAccountSub1, $iAccountSub2=0, $iAccountSub3=0
	, $dAmountD, $dAmountC, $dAmountDEnd, $dAmountCEnd)
	{
		Db::StartTrans();

		$iId=$this->EntryMonth($dDateMonth, $iAccount, $iAccountSub1, $iAccountSub2=0, $iAccountSub3=0
		, $dAmountD, $dAmountC, $dAmountDEnd, $dAmountCEnd);

		if (!$iId) return false;

		Db::CompleteTrans();

		return $iId;
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Entry Month Many
	 *
	 * @param array $aEntry array(0=>array(key of parametr of entry => value of entry))
	 */
	public function EntryMonthMany($aEntry)
	{
		if ($aEntry)
		{
			//Debug::PrintPre($aEntry);
			Db::StartTrans();
			foreach ($aEntry as $sKey => $aValue) {

				$this->EntryMonth($this->sCurrentPeriod, $aValue['id_buh']
				, $aValue['id_buh_subconto1'], $aValue['id_buh_subconto2'], $aValue['id_buh_subconto3']
				, $aValue['amount_debit_start'], $aValue['amount_credit_start']
				, $aValue['amount_debit'], $aValue['amount_credit']
				, $aValue['amount_debit_end'], $aValue['amount_credit_end']
				);
			}
			Db::CompleteTrans();
			return true;
		}
	}

	//-----------------------------------------------------------------------------------------------
	/**
	 * EntryMonthStartSingle ( method for mooving money for start buh_entry_month single ) transaction
	 *
 	 * @param date $dDateMonth date work month
	 * @param integer $iAccount id account from buh
	 * @param integer $iAccountSub1
	 * @param integer $iAccountSub2
	 * @param integer $iAccountSub3
 	 * @param double $dAmountDStart summa debit from start
 	 * @param double $dAmountCStart summa credit from start
  	 * @param double $dAmountDEnd summa debit from end
 	 * @param double $dAmountCEnd summa credit from end
 	 * @return boolean
	 */
	public function EntryMonthStartSingle($dDateMonth, $iAccount, $iAccountSub1, $iAccountSub2=0, $iAccountSub3=0
	, $dAmountDStart, $dAmountCStart)
	{
		$aData['date_month']=$dDateMonth;
		$aData['id_buh']=$iAccount;
		$aData['id_buh_subconto1']=$iAccountSub1;
		$aData['id_buh_subconto2']=$iAccountSub2;
		$aData['id_buh_subconto3']=$iAccountSub3;
		$aData['id_currency']=$iCurrency;
		$aData['amount_debit_start']=$dAmountDStart;
		$aData['amount_credit_start']=$dAmountCStart;
		$aData['amount_debit_end']=$dAmountDStart;
		$aData['amount_credit_end']=$dAmountCStart;

		Db::StartTrans();

		if (($aData['amount_debit_start']>0)or($aData['amount_credit_start']>0)) {
			Db::AutoExecute("buh_entry_month",$aData);
		}

		Db::CompleteTrans();

		return true;
	}
	//-----------------------------------------------------------------------------------------------
	public function Changeling ()
	{
		//TODO Oborot saldo vedomost
		Base::$bXajaxPresent=true;
		if (Auth::$aUser['type_']<>'manager'){
			Base::$tpl->assign('aBuhAccount', $aBuhAccount=Db::GetAssoc("select id as id, concat(id,' - ', name) as name
					from buh as b
					where 1=1 and id=361"));
			Base::$tpl->assign('aSubconto', $aSubconto=Db::GetAssoc("select u.id , u.login as name
					from user as u
					where 1=1 and id=".Auth::$aUser['id'].""));
			Base::$aRequest['search']['id_buh']=361;
			Base::$aRequest['search']['id_subconto1']=Auth::$aUser['id'];
		} else {
			Base::$tpl->assign('aBuhAccount', $aBuhAccount=array("")+Db::GetAssoc("Assoc/Buh"));
			if (Base::$aRequest['search']['id_buh'] && Base::$aRequest['search']['id_subconto1']) {
				$sSubconto1=Db::GetOne("select subconto1 from buh where id=".Base::$aRequest['search']['id_buh']);
				if ($sSubconto1=='account') {
					Base::$tpl->assign('aSubconto', $aSubconto=Db::GetRow("select id as id, name
						from account as a
						where 1=1 and id=".Base::$aRequest['search']['id_subconto1']));
				}
				if ($sSubconto1=='user') {
					Base::$tpl->assign('aSubconto', $aSubconto=Db::GetRow(Base::GetSql("Assoc/User",array(
					'id_user'=>Base::$aRequest['search']['id_subconto1']))));
				}
			}
		}

		Base::$tpl->assign('date_from', $this->sDateFrom);
		Base::$tpl->assign('date_to', $this->sDateTo);

		Resource::Get()->Add('/libp/jquery/jquery.ajaxQueue.js');
		Resource::Get()->Add('/libp/jquery/jquery.autocomplete.js');
		Resource::Get()->Add('/css/jquery.autocomplete.css');
		Resource::Get()->Add('/js/form.js',3284);
		
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:$this->sDateFrom,'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:$this->sDateTo,'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		$aField['id_buh']=array('title'=>'Buh account','type'=>'select','options'=>$aBuhAccount,'selected'=>Base::$aRequest['search']['id_buh'],'name'=>'search[id_buh]','id'=>'id_buh');
		$aField['name']=array('title'=>'Subconto1','type'=>'input','value'=>$aSubconto['name'],'id'=>'subconto','onfocus'=>'ready','nowrap'=>1,'add_to_td'=>array(
		    'id_subconto1'=>array('type'=>'input','value'=>Base::$aRequest['search']['id_subconto1'],'name'=>'search[id_subconto1]','id'=>'id_subconto','readonly'=>1),
		    'cancel_link'=>array('type'=>'link','href'=>'#','onclick'=>"javascript: $('#id_subconto').val(''); $('#subconto').val(''); return false;",'caption'=>'<img src="/image/design/cancel.png">')
		));
	
		//right form
		if (Base::$aRequest['search']['id_buh']=='361' && Base::$aRequest['search']['id_subconto1']) {
 			$aCustomer=Db::GetRow(Base::GetSql('Customer',array('id'=>Base::$aRequest['search']['id_subconto1'])));
 			Base::$tpl->assign('aCustomer',$aCustomer);
 			if($aCustomer){
 			   $aField['customer_name']=array('title'=>'Customer Name','type'=>'text','value'=>$aCustomer['name']);
 			   $aField['customer_group_name']=array('title'=>'Customer group name','type'=>'text','value'=>$aCustomer['customer_group_name']);
 			   $aField['hr']=array('type'=>'hr');
 			   $aField['buh_balance']=array('title'=>'cash account','type'=>'text','value'=>Currency::PrintPrice($aCustomer['buh_balance']));
 			   $aField['debt_order']=array('title'=>'debt on orders','type'=>'text','value'=>Currency::PrintPrice($aCustomer['debt_order']));
 			   $aField['fund_balance']=array('title'=>'fund balance','type'=>'text','value'=>Currency::PrintPrice($aCustomer['fund_balance'])); 
 			}
 		}    
		$oForm = new Form();
		$oForm->sHeader="method=get";
		$oForm->sTitle="Buh Changeling";
		//$oForm->sContent=Base::$tpl->fetch($this->sPrefix.'/form_changeling.tpl');
		$oForm->aField=$aField;
		$oForm->bType='generate';
// 		$oForm->sGenerateTpl='form/index_search.tpl';
		$oForm->sSubmitButton="Search";
		$oForm->sSubmitAction="buh_changeling";
		if (Base::$aRequest['return']) {
			$oForm->sReturnButton='Return';
			$oForm->bAutoReturn=true;
		}
		$oForm->bIsPost=0;
		$oForm->sWidth="400px";
		
// 		if (Base::$aRequest['search']['id_buh']=='361' && Base::$aRequest['search']['id_subconto1']) {
// 			$aCustomer=Db::GetRow(Base::GetSql('Customer',array('id'=>Base::$aRequest['search']['id_subconto1'])));
// 			Base::$tpl->assign('aCustomer',$aCustomer);
// 			$oForm->sRightTemplate=$this->sPrefix.'/right_form_changeling.tpl';
// 		}
		Base::$sText.=$oForm->getForm();

		if (!Base::$aRequest['search']['id_buh']) return ;

		$oTable=new Table();

		if (Base::$aRequest['search']['id_buh']) {

			$oTable->sSql=Base::GetSql("Buh/Changeling",array(
			"date_from"=>Base::$aRequest['search']['date_from']?DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])
			:DateFormat::FormatSearch($this->sDateFrom),
			"date_to"=>Base::$aRequest['search']['date_to']?DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")
			:DateFormat::FormatSearch($this->sDateTo),
			"id_buh"=>Base::$aRequest['search']['id_buh'],
			"id_subconto1"=>Base::$aRequest['search']['id_subconto1']
			));
		}

		if (Auth::$aUser['type_']=='manager'){
			$oTable->aColumn['name_subconto1']=array('sTitle'=>'Subconto1','sWidth'=>'10%');
		}
		$oTable->aColumn['amount_debit_start']=array('sTitle'=>'Start Debit','sWidth'=>'10%');
		$oTable->aColumn['amount_credit_start']=array('sTitle'=>'Start Kredit','sWidth'=>'10%');
		$oTable->aColumn['amount_debit']=array('sTitle'=>'Movement Debit','sWidth'=>'10%');
		$oTable->aColumn['amount_credit']=array('sTitle'=>'Movement Kredit','sWidth'=>'10%');
		$oTable->aColumn['amount_debit_end']=array('sTitle'=>'End Debit','sWidth'=>'10%');
		$oTable->aColumn['amount_credit_end']=array('sTitle'=>'End Kredit','sWidth'=>'10%');

		$oTable->iRowPerPage=500;
		$oTable->sDataTemplate=$this->sPrefix.'/row_changeling.tpl';
		$oTable->aCallback=array($this,'CallParseSum');
		$oTable->aOrdered=" order by name_subconto1 ";
		$oTable->sSubtotalTemplate=$this->sPrefix.'/subtotal_changeling.tpl';
		Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseSum(&$aItem)
	{
		if ($aItem) {
			foreach($aItem as $key => $aValue) {

				if ($aItem[$key]['amount_debit_start']!=0 || $aItem[$key]['amount_credit_start']!=0
				|| $aItem[$key]['amount_debit']!=0        || $aItem[$key]['amount_credit']!=0
				|| $aItem[$key]['amount_debit_end']!=0    || $aItem[$key]['amount_credit_end']!=0)
				{
					$dSubtotal['sum_amount_debit_start']+=$aItem[$key]['amount_debit_start'];
					$dSubtotal['sum_amount_credit_start']+=$aItem[$key]['amount_credit_start'];
					$dSubtotal['sum_amount_debit']+=$aItem[$key]['amount_debit'];
					$dSubtotal['sum_amount_credit']+=$aItem[$key]['amount_credit'];
					$dSubtotal['sum_amount_debit_end']+=$aItem[$key]['amount_debit_end'];
					$dSubtotal['sum_amount_credit_end']+=$aItem[$key]['amount_credit_end'];
					$aTmp[]=$aItem[$key];
				}
			}
			$aItem=$aTmp;
		}

		//Base::$tpl->assign('dSubtotal',$dSubtotal);
		return array('dSubtotal'=>$dSubtotal);
	}
	//-----------------------------------------------------------------------------------------------
	public function CloseMonth()
	{
		$aData=Db::GetAll(Base::GetSql("Buh/Changeling",array(
		"date_from"=>$this->sCurrentPeriod,
		"date_to"=>$this->GetLastDate($this->sCurrentPeriod),
		)));

		if ($aData)
		{
			$this->EntryMonthMany($aData);
			$this->SetNextPeriod();
			foreach ($aData as $sKey => $aValue) {
				$aEntry[]=array(
				'id_buh'=>$aValue['id_buh'],
				'id_buh_subconto1'=>$aValue['id_buh_subconto1'],
				'amount_debit_start'=>$aValue['amount_debit_end'],
				'amount_credit_start'=>$aValue['amount_credit_end'],
				'amount_debit'=>0,
				'amount_credit'=>0,
				'amount_debit_end'=>0,
				'amount_credit_end'=>0
				);
			}
			$this->EntryMonthMany($aEntry);
			
			$aData=Db::GetAll(Base::GetSql("Buh/Changeling",array(
			"date_from"=>$this->sCurrentPeriod,
			"date_to"=>$this->GetLastDate($this->sCurrentPeriod),
			)));
			if ($aData) {
				$this->EntryMonthMany($aData);
			}

			//TODO quit from close month;
			$sMessage.="&aMessage[MF_NOTICE]=Month close";
			Form::RedirectAuto($sMessage);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function AddAmount()
	{
		$this->sPrefixAction=Base::$aRequest['action'];

		if (Base::$aRequest['is_post'])	{
			if (Base::$aRequest['aData']['amount']==''){
				Base::Message(array('MF_ERROR'=>'Required amount'));
				Base::$aRequest['action']=$this->sPrefixAction;
				Base::$tpl->assign('aData',Base::$aRequest['aData']);
			} else {
				$aData=String::FilterRequestData(Base::$aRequest['aData']);
				$aData['amount']=String::GetDecimal($aData['amount']);
				if (!$aData['id_buh_section']) $aData['id_buh_section']=1;

				if ($this->sPrefixAction==$this->sPrefix.'_edit_amount' && Base::$aRequest['id']) {
					$aDoc['id_buh_section']=$aData['id_buh_section'];
					$aDoc['buh_section_id']=$aData['buh_section_id'];

					if ($this->EntrySingle($aDoc, $aData['id_buh_debit'], $aData['id_buh_credit'], $aData['amount'], $aData['description']
					, $aData['id_buh_debit_subconto1'], $aData['id_buh_debit_subconto2'], $aData['id_buh_debit_subconto3']
					, $aData['id_buh_credit_subconto1'], $aData['id_buh_credit_subconto2'], $aData['id_buh_credit_subconto3']
					, $aData['id_currency'], $aData['currency_sum'], $aData['number'], Base::$aRequest['id'], $aData['post_date'])) {
						$sMessage="&aMessage[MT_NOTICE]=Successfull";
					} else {
						$sMessage=$oBuh->sError;
					}
				} elseif ($this->sPrefixAction==$this->sPrefix.'_add_amount') {
					$aEntry[]=$aData;
					$oBuh = new Buh();
					if ($oBuh->EntryMany($aEntry)) {
						$sMessage="&aMessage[MT_NOTICE]=Successfull";
					} else {
						$sMessage=$oBuh->sError;
					}
				}

				Manager::PayCartPackage($aData['buh_section_id'],$aData);
				Form::RedirectAuto($sMessage);
			}
		}

		Base::Message(Base::$aRequest['aMessage'],false);

		if ($this->sPrefixAction==$this->sPrefix."_edit_amount") {
			$aData=Db::GetRow(Base::GetSql("Buh/Entry",array("id"=>Base::$aRequest['id'])));
			Base::$aRequest['search']=$aData;
		}

		Base::$tpl->assign('aBuh', $aBuh=Db::GetAssoc("Assoc/Buh"));
		Base::$tpl->assign('aBuhSection', $aBuhSection=Db::GetAssoc("select id as id, code from buh_section as a where 1=1"));
		Base::$tpl->assign('aCurrency', Db::GetAssoc("Assoc/Currency",array("type_"=>"id")));

		$aData['id_buh_debit']=Base::$aRequest['search']['id_buh_debit'];
		$aData['id_buh_debit_subconto1']=Base::$aRequest['search']['id_buh_debit_subconto1'];
		$aSubcontoD=Db::GetRow(Base::GetSql("Buh/Subconto",array(
		"id_buh"=>$aData['id_buh_debit'],
		"id_buh_subconto1"=>$aData['id_buh_debit_subconto1'],
		)));
		Base::$tpl->assign('aSubcontoD',$aSubcontoD);

		$aData['id_buh_credit']=Base::$aRequest['search']['id_buh_credit'];
		$aData['id_buh_credit_subconto1']=Base::$aRequest['search']['id_buh_credit_subconto1'];
		$aSubcontoC=Db::GetRow(Base::GetSql("Buh/Subconto",array(
		"id_buh"=>$aData['id_buh_credit'],
		"id_buh_subconto1"=>$aData['id_buh_credit_subconto1'],
		)));
		Base::$tpl->assign('aSubcontoC',$aSubcontoC);

		$aData['id_buh_section']=Base::$aRequest['search']['id_buh_section'];
		$aData['buh_section_id']=Base::$aRequest['search']['buh_section_id'];
		$aData['amount']=Base::$aRequest['search']['amount'];

		Base::$tpl->assign('aData',$aData);

		Resource::Get()->Add('/libp/jquery/jquery.ajaxQueue.js');
		Resource::Get()->Add('/libp/jquery/jquery.autocomplete.js');
		Resource::Get()->Add('/css/jquery.autocomplete.css');
		Resource::Get()->Add('/js/form.js');
		
		$aField['id_buh_debit']=array('title'=>'Buh account from','type'=>'select','options'=>Language::GetMessageArray($aBuh),'selected'=>$aData['id_buh_debit'],'name'=>'aData[id_buh_debit]','id'=>'id_buh_debit','szir'=>1);
		$aField['debit_subconto1']=array('title'=>'Buh subconto from','type'=>'input','value'=>$aSubcontoD['name'],'id'=>'debit_subconto1','szir'=>1,'autocomplete'=>'off','add_to_td'=>array(
		    'buh_debit_subconto1'=>array('type'=>'input','value'=>$aSubcontoD['id'],'name'=>'aData[id_buh_debit_subconto1]','id'=>'id_buh_debit_subconto1','readonly'=>1),
		    'cancel_link'=>array('type'=>'link','href'=>'#','onclick'=>"javascript: $('#debit_subconto1').val(''); $('#id_buh_debit_subconto1').val(''); return false;",'caption'=>'<img src="/image/design/cancel.png">')
		));
		$aField['id_buh_credit']=array('title'=>'Buh account to','type'=>'select','options'=>$aBuh,'selected'=>$aData['id_buh_credit'],'name'=>'aData[id_buh_credit]','id'=>'id_buh_credit','szir'=>1);
		$aField['credit_subconto1']=array('title'=>'Buh subconto to','type'=>'input','value'=>$aSubcontoC['name'],'id'=>'credit_subconto1','szir'=>1,'autocomplete'=>'off','add_to_td'=>array(
		    'id_buh_credit_subconto1'=>array('type'=>'input','value'=>$aData['id_buh_credit_subconto1'],'name'=>'aData[id_buh_credit_subconto1]','id'=>'id_buh_credit_subconto1','readonly'=>1),
		    'cancel_link2'=>array('type'=>'link','href'=>'#','onclick'=>"javascript: $('#credit_subconto1').val(''); $('#id_buh_credit_subconto1').val(''); return false;",'caption'=>'<img src="/image/design/cancel.png">')
		));		
		$aField['id_buh_section']=array('title'=>'Buh section','type'=>'select','options'=>$aBuhSection,'selected'=>$aData['id_buh_section'],'name'=>'aData[id_buh_section]','id'=>'id_buh_section');
		$aField['buh_section_id']=array('title'=>'Buh number section','type'=>'input','value'=>$aData['buh_section_id'],'name'=>'aData[buh_section_id]');
		$aField['amount']=array('title'=>'Buh amount','type'=>'input','value'=>$aData['amount'],'name'=>'aData[amount]','szir'=>1);
		$aField['description']=array('title'=>'Buh description','type'=>'textarea','name'=>'aData[description]','value'=>$aData['description']);
		$aField['post_date']=array('title'=>'Date','type'=>'date','id'=>'post_date','name'=>'aData[post_date]','readonly'=>1,'value'=>$aData['post_date'],'onclick'=>"popUpCalendar(this, this, 'yyyy-mm-dd')");
		
		$oForm = new Form();
		$oForm->sHeader="method=post";
		$oForm->sTitle="Add payment";
		//$oForm->sContent=Base::$tpl->fetch($this->sPrefix.'/form_add_amount.tpl');
		$oForm->aField=$aField;
		$oForm->bType='generate';
		$oForm->sSubmitButton='Apply';
		$oForm->sSubmitAction=$this->sPrefixAction;
		$oForm->sReturnButton='<< Return';
		$oForm->bAutoReturn=true;
		$oForm->bIsPost=true;
		$oForm->sWidth="480px";

		Base::$sText.=$oForm->getForm();

		Base::$aRequest['search']['id_buh']=Base::$aRequest['search']['id_buh_credit'];
		Base::$aRequest['search']['id_subconto1']=Base::$aRequest['search']['id_buh_debit_subconto1'];
		//$this->ChangelingPreview(false);

	}
	//-----------------------------------------------------------------------------------------------
	public function ChangeForm()
	{
		if (Base::$aRequest['id_buh']) {
			$sSubconto1=Db::GetOne("select subconto1 from buh where id=".Base::$aRequest['id_buh']);
			if ($sSubconto1=='account') {
				$aSubconto=Db::GetAssoc("select id as id, name
					from account as a
					where 1=1");
			}
			if ($sSubconto1=='user') {
				$aSubconto=Db::GetAssoc("Assoc/User");
			}
		}
		$sOptions="<option value=0></option>";
		if ($aSubconto) {
			foreach ($aSubconto as $sKey => $aValue){
				$sOptions.="<option value=".$sKey.">".$aValue."</option>";
			}
		}
		Base::$oResponse->addAssign('id_subconto1','innerHTML', $sOptions);
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangelingPreview($bVisibleForm=true)
	{
		if (Auth::$aUser['type_']<>'manager'){
			Base::$tpl->assign('aBuhAccount', $aBuhAccount=Db::GetAssoc("select id as id, concat(id,' - ', name) as name
					from buh as b
					where 1=1 and id=361"));
			Base::$tpl->assign('aSubconto', $aSubconto=Db::GetAssoc("select u.id , u.login as name
					from user as u
					where 1=1 and id=".Auth::$aUser['id'].""));
			Base::$aRequest['search']['id_buh']=361;
			Base::$aRequest['search']['id_subconto1']=Auth::$aUser['id'];
		} else {
			Base::$tpl->assign('aBuhAccount', $aBuhAccount=array("")+Db::GetAssoc("Assoc/Buh"));
			if (Base::$aRequest['search']['id_buh'] && Base::$aRequest['search']['id_subconto1']) {
				$sSubconto1=Db::GetOne("select subconto1 from buh where id=".Base::$aRequest['search']['id_buh']);
				if ($sSubconto1=='account') {
					Base::$tpl->assign('aSubconto', $aSubconto=Db::GetRow("select id as id, name
						from account as a
						where 1=1 and id=".Base::$aRequest['search']['id_subconto1']));
				}
				if ($sSubconto1=='user') {
					Base::$tpl->assign('aSubconto', $aSubconto=Db::GetRow(Base::GetSql("Assoc/User",array(
					'id_user'=>Base::$aRequest['search']['id_subconto1']))));
				}
			}
		}

		//$sNameSubconto1=Db::GetOne("select login from user where id=".Base::$aRequest['id_subconto1']);
		//Base::$tpl->assign('sNameSubconto1',$sNameSubconto1);

		$aSaldo=Db::GetRow(Base::GetSql("Buh/Changeling",array(
		"date_from"=>Base::$aRequest['search']['date_from']?DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])
		:DateFormat::FormatSearch($this->sDateFrom),
		"date_to"=>Base::$aRequest['search']['date_to']?DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")
		:DateFormat::FormatSearch($this->sDateTo),
		"id_buh"=>Base::$aRequest['search']['id_buh'],
		"id_subconto1"=>Base::$aRequest['search']['id_subconto1']
		)));

		Base::$tpl->assign('aSaldo',$aSaldo);

		if ($bVisibleForm) {
		    Resource::Get()->Add('/libp/jquery/jquery.ajaxQueue.js');
		    Resource::Get()->Add('/libp/jquery/jquery.autocomplete.js');
		    Resource::Get()->Add('/css/jquery.autocomplete.css');
		    Resource::Get()->Add('/js/form.js');
		    
		    $aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from'],'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		    $aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to'],'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		    $aField['id_buh']=array('title'=>'Buh account','type'=>'select','options'=>Language::GetMessageArray($aBuhAccount),'selected'=>Base::$aRequest['search']['id_buh'],'name'=>'search[id_buh]','id'=>'id_buh');
		    $aField['subconto']=array('title'=>'Buh subconto','type'=>'input','value'=>$aSubconto['name'],'id'=>'subconto','szir'=>1,'add_to_td'=>array(
		        'id_subconto1'=>array('type'=>'input','value'=>Base::$aRequest['search']['id_subconto1'],'name'=>'search[id_subconto1]','readonly'=>1,'id'=>'id_subconto'),
		        'link_clear'=>array('type'=>'link','href'=>'#','caption'=>'q','onclick'=>"$(#subconto).val('')")
		    ));
 		 	$oForm = new Form();
			$oForm->sHeader="method=get";
			$oForm->sTitle="Buh changeling preview";
			//$oForm->sContent=Base::$tpl->fetch($this->sPrefix.'/form_changeling_preview.tpl');
			$oForm->aField=$aField;
			$oForm->bType='generate';
		//	$oForm->sGenerateTpl='form/index_search.tpl';
			$oForm->sSubmitAction="buh_changeling_preview";
			$oForm->sReturnButton='Return';
			$oForm->bAutoReturn=true;
			$oForm->bIsPost=0;
			$oForm->sWidth="400px";
			Base::$sText.=$oForm->getForm();
		}

		$oTable=new Table();
		//$oTable->sWidth="60%";

		$dateFrom=Base::$aRequest['search']['date_from']?DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])
		:DateFormat::FormatSearch($this->sDateFrom);

		$dateTo=Base::$aRequest['search']['date_to']?DateFormat::FormatSearch(Base::$aRequest['search']['date_to'],"Y-m-d 23:59:59")
		:DateFormat::FormatSearch($this->sDateTo);

		$oTable->sSql=Base::GetSql("Buh/ChangelingDetail",array(
		"date_from"=>$dateFrom,
		"date_to"=>$dateTo,
		"id_buh"=>Base::$aRequest['search']['id_buh'],
		"id_subconto1"=>Base::$aRequest['search']['id_subconto1']
		));

		Base::$tpl->assign('saldo_date_from',DateFormat::FormatSearch($dateFrom,Base::GetConstant('date_format:post_date')));
		Base::$tpl->assign('saldo_date_to',DateFormat::FormatSearch($dateTo,Base::GetConstant('date_format:post_date')));

		$oTable->aColumn['post_date']=array('sTitle'=>'Date','sWidth'=>'15%');
		//$oTable->aColumn['id_buh_debit']=array('sTitle'=>'Start Kredit','sWidth'=>'10%');
		$oTable->aColumn['amount_debit']=array('sTitle'=>'Movement Debit','sWidth'=>'10%');
		//$oTable->aColumn['id_buh_credit']=array('sTitle'=>'Movement Kredit','sWidth'=>'10%');
		$oTable->aColumn['amount_credit']=array('sTitle'=>'Movement Kredit','sWidth'=>'10%');
		$oTable->aColumn['document']=array('sTitle'=>'Document','sWidth'=>'20%');
		$oTable->aColumn['description']=array('sTitle'=>'Description','sWidth'=>'35%');
		if (Auth::$aUser['type_']=='manager') $oTable->aColumn['action']=array('sTitle'=>'action','sWidth'=>'5%');

		$oTable->iRowPerPage=500;
		$oTable->sDataTemplate=$this->sPrefix.'/row_changeling_preview.tpl';
		$oTable->aCallback=array($this,'CallParseSum');
		$oTable->aOrdered=" order by post_date_order ";
		$oTable->bStepperVisible=false;

		$oTable->sSubtotalTemplateTop=$this->sPrefix.'/subtotal_changeling_preview_top.tpl';
		$oTable->sSubtotalTemplate=$this->sPrefix.'/subtotal_changeling_preview.tpl';
		Base::$sText.=$oTable->getTable();
	}
	//-------------------------------------------------------------------------------------------------
	public function GetSubconto()
	{
		if (Base::$aRequest['id_buh']) {
			$sSubconto1=Db::GetOne("select subconto1 from buh where id=".Base::$aRequest['id_buh']);

			if ($sSubconto1=='user') {
				$aSubconto=Db::GetAll("select id, login as name from user where login like '%".str_replace("*","%",Base::$aRequest['q'])."%'");
			} elseif ($sSubconto1) {
				$aSubconto=Db::GetAll("select id, name from ".$sSubconto1);
			}

		}
		if ($aSubconto) {
			foreach ($aSubconto as $sKey => $aValue) {
				echo $aValue['name']."|".$aValue['id']."\n";
			}
		}
		die();
	}
	//-------------------------------------------------------------------------------------------------
	public function GetAmount($sBuhSection,$iBuhSection,$idBuh,$sBuhType='credit') {
		return Db::GetAll("
		select be.*, ".DateFormat::GetSqlDate()." as date_payment
		from buh_entry as be
		inner join buh_section as bs on be.id_buh_section=bs.id
		where be.id_buh_".$sBuhType."=".$idBuh." and bs.code='".$sBuhSection."' and be.buh_section_id=".$iBuhSection
		);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetSaldoLast($iSubconto1,$iBuh='361') {
		$aRow=Db::GetRow(Base::GetSql("Buh/Changeling",array(
		"date_from"=>$this->sCurrentPeriod,
		"date_to"=>$this->sCurrentPeriodLastDate,
		"id_buh"=>$iBuh,
		"id_subconto1"=>$iSubconto1
		)));

		if ($aRow) $aRow['saldo_end']=$aRow['amount_credit_end']-$aRow['amount_debit_end'];
		else $aRow['saldo_end']=0;

		return $aRow;
	}
}
?>