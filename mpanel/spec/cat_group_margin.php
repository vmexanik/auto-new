<?
class ACatGroupMargin extends Admin {
	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'cat_group_margin';
		$this->sTablePrefix = 'cgm';
		$this->sAction = 'cat_group_margin';
		$this->sWinHead = Language::getDMessage ( 'Cat Group Margin' );
		$this->sPath = Language::GetDMessage('>> Auto catalog >');
		$this->aCheckField = array ("id","name");
		$this->Admin ();
	
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex ();
		require_once (SERVER_PATH . '/class/core/Table.php');
		$oTable = new Table ( );
		$oTable->aColumn = array ();
		$oTable->aColumn ['id'] = array ('sTitle' => 'Id', 'sOrder' => $this->sTablePrefix.'.id' );
		$oTable->aColumn ['name'] = array ('sTitle' => 'Name', 'sOrder' => $this->sTablePrefix.'.name', 'sWidth'=>'80%' );
		$oTable->aColumn ['margin'] = array ('sTitle' => 'Margin', 'sOrder' => $this->sTablePrefix.'.margin' );
		$this->initLocaleGlobal ();
		
		$oTable->aColumn ['action'] = array ();
		$this->SetDefaultTable ( $oTable );
		Base::$sText .= $oTable->getTable ();
		$this->AfterIndex ();
	}
}
?>