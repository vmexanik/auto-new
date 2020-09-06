<?

/**
 * @author Mikhail Starovoyt
 *
 */


class ALogVisit extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'log_visit';
		$this->sTablePrefix = 'lv';
		$this->sAction = 'log_visit';
		$this->sWinHead = Language::getDMessage('Log Visit');
		$this->sPath = Language::GetDMessage('>>Logs >');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex ();
		//--------------------
		Base::$sText .= $this->SearchForm ();
		if ($this->aSearch) {
			if (Language::getConstant('mpanel_search_strong',0)) {
				if ($this->aSearch [customer_login])
					$this->sSearchSQL .= " and u.login = '" . $this->aSearch [customer_login] . "' ";
			}
			else {
				if ($this->aSearch [customer_login])
					$this->sSearchSQL .= " and u.login like '%" . $this->aSearch [customer_login] . "%' ";
			}
			
			if ($this->aSearch [date_from])
			$this->sSearchSQL .= " and lv.post>='".strtotime($this->aSearch [date_from] )."' ";
			if ($this->aSearch [date_to])
			$this->sSearchSQL .= " and lv.post<='".strtotime($this->aSearch[date_to])."'";
		}
		//--------------------

		$oTable = new Table ( );
		$oTable->aColumn =array ();
		$oTable->aColumn ['id']= array('sTitle'=> 'Id','sOrder'=>'lv.id');
		$oTable->aColumn ['user_login']= array ('sTitle' => 'User','sOrder'=>'u.login');
		$oTable->aColumn ['post_date']=array ('sTitle' => 'Post Date','sOrder'=>'lv.post_date');
		$oTable->aColumn ['url']=array ('sTitle' => 'Url','sOrder'=>'lv.url');
		$oTable->aColumn ['ip']=array ('sTitle' => 'IP','sOrder'=>'lv.url');

		$this->SetDefaultTable ($oTable);
		Base::$sText .= $oTable->getTable ();

		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
}

?>