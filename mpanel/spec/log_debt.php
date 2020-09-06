<?
require_once (SERVER_PATH . '/class/core/Admin.php');
require_once(SERVER_PATH.'/mpanel/spec/account_log.php');
class ALogDebt extends AAccountLog {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'log_debt';
		$this->sTablePrefix = 'ld';
		$this->sAction = 'log_debt';
		$this->sWinHead = Language::getDMessage('Log debt');
		$this->sPath = Language::GetDMessage('>>Logs >');
		$this->aCheckField = array ();
		$this->aFCKEditors = array ();
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex ();

		/*$aSearchData = Base::$aRequest['search'];
		if( !is_array($aSearchData) && (strlen($aSearchData) > 0) ) {
			parse_str($aSearchData, $aTmpData);
			if( is_array($aTmpData) && (count($aTmpData) > 0) ) {
				$aSearchData = $aTmpData;
				$aSearchFilterField = array('description', 'customer_login', 'custom_id');
				foreach($aSearchFilterField as $sCurFilterName) {
					if( isset($aSearchData[ $sCurFilterName ]) && (strlen($aSearchData[ $sCurFilterName ]) > 0) ) {
						Base::$aRequest['filter'] = $sCurFilterName;
						Base::$aRequest['filter_value'] = $aSearchData[ $sCurFilterName ];
					}
				}
				if( isset($aSearchData['customer_login']) && (strlen($aSearchData['customer_login']) > 0) ) {
					$aCustomer = Base::$db->GetRow( Base::GetSql ('Customer', array('login' => $aSearchData['customer_login']) ) );
					Base::$tpl->assign ( 'aCustomer', $aCustomer );
				}
			} else {
				$aSearchData = array ();
			}
		}
		if(!isset($aSearchData['date_from'])) {
			$aSearchData['default_date_from'] = date( "d.m.Y", mktime(0, 0, 0, date("m")-6, date("d"), date("Y")) );
		}
		if(!isset($aSearchData['date_to'])) {
			$aSearchData['default_date_to']   = date( "d.m.Y", mktime(0, 0, 0, date("m"), date("d")+1, date("Y")) );
		}
		Base::$tpl->assign ( 'aSearchData', $aSearchData );
		Base::$sText .= $this->SearchForm ();
		
		if( Base::$aRequest ['filter'] == 'customer_login' ) {
			Base::$aRequest ['filter'] = 'id_user';
		}
		$sOldIdUser = 0;
		if( isset(Base::$aRequest ['filter']) && (Base::$aRequest ['filter'] == 'id_user') &&	isset(Base::$aRequest ['filter_value']) &&
		(strlen(Base::$aRequest ['filter_value']) > 0)
		) {
			$sOldIdUser = Base::$aRequest['filter_value'];
			$iNewIdUser = Base::$db->getOne("SELECT `id` FROM `user` WHERE login = '" . Base::$aRequest ['filter_value'] . "'");
			if( $iNewIdUser ) {
				$this->sSearchSQL .= " and ld.id_user = '{$iNewIdUser}' ";
				Base::$aRequest ['filter_value'] = $iNewIdUser;

			}
		}
		*/
		
		// search form
		Base::$sText .= $this->SearchForm ();
		if ($this->aSearch) {
			$this->aSearch [login] = trim($this->aSearch [login]);
			if (Language::getConstant('mpanel_search_strong',0)) {
				if ($this->aSearch [login])
					$this->sSearchSQL .= " and ld.customer_login = '" . $this->aSearch [customer_login] . "' ";
				if ($this->aSearch [description])
					$this->sSearchSQL .= " and ld.description = '". trim($this->aSearch [description])."'";
			}
			else {
				if ($this->aSearch [login])
					$this->sSearchSQL .= " and ld.customer_login like '%" . $this->aSearch [customer_login] . "%' ";				
				if ($this->aSearch [description])
					$this->sSearchSQL .= " and ld.description like '%". trim($this->aSearch [description])."%'";
			}
			if ($this->aSearch [date_from])
			$this->sSearchSQL .= " and ld.post>='".strtotime($this->aSearch [date_from] )."' ";
			if ($this->aSearch [date_to])
			$this->sSearchSQL .= " and ld.post<='".strtotime($this->aSearch[date_to])."'";
		}
		
		$this->GetResultByFindLogin('customer_login');
		
		require_once (SERVER_PATH . '/class/core/Table.php');
		$oTable = new Table ( );
		$oTable->aColumn                 = array ();
		$oTable->aColumn ['id']          = array ( 'sTitle' => 'Id',          'sOrder' => 'ld.id' );
		$oTable->aColumn ['id_user']     = array ( 'sTitle' => 'User',        'sOrder' => 'ld.id_user',    'sMethod' => 'skip');
		$oTable->aColumn ['post_date']   = array ( 'sTitle' => 'Post Date',   'sOrder' => 'ld.post_date' );
		$oTable->aColumn ['custom_id']   = array ( 'sTitle' => 'Custom Id',   'sOrder' => 'ld.custom_id' );
		$oTable->aColumn ['amount']      = array ( 'sTitle' => 'Amount',      'sOrder' => 'ld.amount' );
		$oTable->aColumn ['is_payed']    = array ( 'sTitle' => 'Is Payed',    'sOrder' => 'ld.is_payed' );
		$oTable->aColumn ['description'] = array ( 'sTitle' => 'Description', 'sOrder' => 'ld.description' );
		$this->SetDefaultTable ( $oTable );
		Base::$aRequest ['filter_value'] = $sOldIdUser;
		Base::$sText .= $oTable->getTable ();

		$this->AfterIndex ();
	}
}
?>