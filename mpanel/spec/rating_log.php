<?
class ARatingLog extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName = 'rating_log';
		$this->sTablePrefix = 'rl';
		$this->sAction = 'rating_log';
		$this->sWinHead = Language::getDMessage('Rating Log');
		$this->sPath = Language::GetDMessage('>>Logs >');
		$this->aCheckField = array();
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex ();
		//--------------------
		$sQuery = "select distinct(section) from rating_log";
		$aSection = Db::GetAll($sQuery);
		Base::$tpl->assign_by_ref('aSection', $aSection);
		Base::$sText .= $this->SearchForm();
		if ($this->aSearch) {
			if ($this->aSearch['section'])
			$this->sSearchSQL .= " and rl.section='".$this->aSearch['section']."' ";

			if ($this->aSearch['date_from'])
			$this->sSearchSQL.=" and rl.post_date>='".DateFormat::FormatSearch($this->aSearch['date_from'])."' ";
			if ($this->aSearch['date_to'])
			$this->sSearchSQL.=" and rl.post_date<='".DateFormat::FormatSearch($this->aSearch['date_to'])."'";
			
			if (Language::getConstant('mpanel_search_strong',0)) {
				if ($this->aSearch['user_login'])
				$this->sSearchSQL .= " and u.login = '".$this->aSearch['user_login'] . "' ";
			}
			else {
				if ($this->aSearch['user_login'])
					$this->sSearchSQL .= " and u.login like '%".$this->aSearch['user_login'] . "%' ";
			}
		}

		$oTable = new Table();
		$oTable->aColumn=array(
		'user_login'=>array('sTitle'=>'user login','sOrder'=>'user_login'),
		'rating_name'=>array('sTitle'=>'rating_name','sOrder'=>'rating_name'),
		'post_date'=>array('sTitle'=>'post date','sOrder'=>'rl.post_date'),
		'section'=>array('sTitle'=>'section','sOrder'=>'rl.section'),
		'num_rating'=>array('sTitle'=>'num_rating','sOrder'=>'rl.num_rating'),
		'manager_login'=>array('sTitle'=>'manager login','sOrder'=>'manager_login'),
		);
		$this->SetDefaultTable ( $oTable );
		Base::$sText .= $oTable->getTable ();
		//--------------------
		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
}

?>