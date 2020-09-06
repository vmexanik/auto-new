<?
require_once (SERVER_PATH . '/class/core/Admin.php');
class ADirectoryCity extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'directory_city';
		$this->sTablePrefix = 'dc';
		$this->sAction = 'directory_city';
		$this->sWinHead = Language::getDMessage('Directory City');
		$this->sPath = Language::GetDMessage('>>Directory >');
		$this->aCheckField = array('name');
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=> array('sTitle'=>'Id', 'sOrder'=>'dc.id'),
		'name' => array('sTitle'=>'Name', 'sOrder'=>'dc.name'),
		'visible' => array('sTitle'=>'Visible', 'sOrder'=>'dc.visible'),
		'action' => array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>