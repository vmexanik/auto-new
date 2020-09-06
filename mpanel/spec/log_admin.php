<?

/**
 * @author Mikhail Starovoyt
 */

require_once (SERVER_PATH . '/class/core/Admin.php');
class ALogAdmin extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'log_admin';
		$this->sTablePrefix = 'la';
		$this->sAction = 'log_admin';
		$this->sWinHead = Language::getDMessage('Log Admin');
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
				if ($this->aSearch['action']) $this->sSearchSQL.=" and la.action = '".$this->aSearch['action']."'";
			}
			else { 
				if ($this->aSearch['action']) $this->sSearchSQL.=" and la.action like '%".$this->aSearch['action']."%'";
			}
			
			if ($this->aSearch['login']) $this->sSearchSQL.=" and la.login = '".$this->aSearch['login']."'";
			if ($this->aSearch['date_from']) $this->sSearchSQL.=" and la.post_date>=
				'".date('Y-m-d',strtotime($this->aSearch['date_from']))."' ";
			if ($this->aSearch['date_to']) $this->sSearchSQL.=" and la.post_date<=
				'".date('Y-m-d',strtotime($this->aSearch['date_to']))."'";
			if ($this->aSearch['table_name']) $this->sSearchSQL.=" and la.table_name = '".$this->aSearch['table_name']."'";
			if ($this->aSearch['ip']) $this->sSearchSQL.=" and la.ip = '".$this->aSearch['ip']."'";
		}
		//--------------------
		require_once (SERVER_PATH.'/class/core/Table.php');
		$oTable = new Table();
		$oTable->aColumn =array(
		'id'=> array('sTitle'=> 'Id','sOrder'=>'la.id'),
		'login'=> array ('sTitle' => 'Admin','sOrder'=>'la.login'),
		'post_date'=>array ('sTitle' => 'Post Date','sOrder'=>'la.post_date'),
		'action'=> array ('sTitle' => 'Action','sOrder'=>'la.action'),
		'table_name'=> array ('sTitle' => 'TableName','sOrder'=>'la.table_name'),
		'ip'=>array ('sTitle' => 'IP','sOrder'=>'la.ip'),
		);
		$this->SetDefaultTable ($oTable);
		Base::$sText .= $oTable->getTable ();

		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
}

?>