<?
require_once (SERVER_PATH . '/class/core/Admin.php');
require_once(SERVER_PATH.'/mpanel/spec/account_log.php');
class ALogFinance extends AAccountLog {

	//-----------------------------------------------------------------------------------------------
	function ALogFinance() {
		$this->sTableName = 'log_finance';
		$this->sTablePrefix = 't';
		$this->sAction = 'log_finance';
		$this->sWinHead = Language::getDMessage('Log Finance');
		$this->sPath = Language::GetDMessage('>>Logs >');
		$this->aCheckField = array ();
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex ();
		//--------------------
		$sQuery = "select distinct(section) from log_finance";
		$aSection = Base::$db->getArray ( $sQuery );
		Base::$tpl->assign_by_ref ( 'aSection', $aSection );
		Base::$sText .= $this->SearchForm ();
		if ($this->aSearch) {
			if (Language::getConstant('mpanel_search_strong',0)) {
				if ($this->aSearch['customer_login'])
					$this->sSearchSQL .= " and u.login = '".$this->aSearch['customer_login'] . "' ";
				if ($this->aSearch['description'])
					$this->sSearchSQL .= " and t.description = '".$this->aSearch['description']."' ";
				if ($this->aSearch['currency_code'])
					$this->sSearchSQL .= " and t.description = '".$this->aSearch['currency_code']."' ";
			}
			else {
				if ($this->aSearch['customer_login'])
					$this->sSearchSQL .= " and u.login like '%".$this->aSearch['customer_login'] . "%' ";
				if ($this->aSearch['description'])
					$this->sSearchSQL .= " and t.description like '%".$this->aSearch['description']."%' ";
				if ($this->aSearch['currency_code'])
					$this->sSearchSQL .= " and t.description like '%".$this->aSearch['currency_code']."%' ";
			}
			if ($this->aSearch['section'])
			$this->sSearchSQL .= " and t.section='".$this->aSearch['section']."' ";
			if ($this->aSearch['date_from'])
			$this->sSearchSQL .= " and t.post>='".strtotime($this->aSearch['date_from'] )."' ";
			if ($this->aSearch['date_to'])
			$this->sSearchSQL .= " and t.post<='".strtotime($this->aSearch['date_to'])."'";
		}
		/*//--------------------
		if ($this->aSearch ['customer_login']) {
			$aCustomer = Base::$db->GetRow (
			Base::GetSql ( 'Customer',
			array ('login' => $this->aSearch ['customer_login'] ) ) );
			Base::$tpl->assign_by_ref ( 'aCustomer', $aCustomer );
			Base::$sText .= Base::$tpl->fetch ( 'mpanel/' . $this->sAction . '/customer.tpl' );
		}*/
		$this->GetResultByFindLogin('customer_login');
		//--------------------
		require_once (SERVER_PATH . '/class/core/Table.php');
		$oTable = new Table ( );
		$oTable->aColumn = array ();
		$oTable->aColumn ['id'] = array ('sTitle' => 'Id', 'sOrder' => 't.id' );
		$oTable->aColumn ['user'] = array ('sTitle' => 'User', 'sOrder' => 'u.login' );
		$oTable->aColumn ['post_date'] = array ('sTitle' => 'Post Date', 'sOrder' => 't.post_date' );
		$oTable->aColumn ['section'] = array ('sTitle' => 'Section', 'sOrder' => 't.section' );
		$oTable->aColumn ['description'] = array ('sTitle' => 'Description',
		'sOrder' => 't.description' );
		$oTable->aColumn ['created_by'] = array ('sTitle' => 'Created By',
		'sOrder' => 't.created_by' );
		$this->SetDefaultTable ( $oTable );
		Base::$sText .= $oTable->getTable ();
		//--------------------
		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
}

?>