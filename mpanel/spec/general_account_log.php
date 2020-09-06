<?
class AGeneralAccountLog extends Admin{

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'general_account_log';
		$this->sTablePrefix = 'gal';
		$this->sAction = 'general_account_log';
		$this->sWinHead = Language::getDMessage('General Account Log');
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex ();

		// search form
		Base::$sText .= $this->SearchForm ();
		if ($this->aSearch) {
			if ($this->aSearch['date_from'])
			$this->sSearchSQL.=" and gal.post_date>='".DateFormat::FormatSearch($this->aSearch['date_from'])."' ";
			if ($this->aSearch['date_to'])
			$this->sSearchSQL.=" and gal.post_date<='".DateFormat::FormatSearch($this->aSearch['date_to'])."'";
		}

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable = new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'gal.id'),
		'post_date'=>array('sTitle'=>'PostDate','sOrder'=>'gal.post_date'),
		'account_amount'=>array('sTitle'=>'AccountAmount','sOrder'=>'gal.account_amount'),
		'debt_amount'=>array('sTitle'=>'DebtAmount','sOrder'=>'gal.debt_amount'),
		'customer_sum_amount'=>array('sTitle'=>'CustomerSumAmount','sOrder'=>'gal.customer_sum_amount'),
		'provider_sum_amount'=>array('sTitle'=>'ProviderSumAmount','sOrder'=>'gal.provider_sum_amount'),
		'id_user_account_log'=>array('sTitle'=>'IdUserAccountLog','sOrder'=>'gal.id_user_account_log'),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();
		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>