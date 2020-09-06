<?
require_once (SERVER_PATH . '/class/core/Admin.php');
class ADirectoryTag extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'directory_tag';
		$this->sTablePrefix = 'dt';
		$this->sAction = 'directory_tag';
		$this->sWinHead = Language::getDMessage('Directory Tag');
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
		'id'=> array('sTitle'=>'Id', 'sOrder'=>'dt.id'),
		'name' => array('sTitle'=>'Name', 'sOrder'=>'dt.name'),
		'visible' => array('sTitle'=>'Visible', 'sOrder'=>'dt.visible'),
		'action' => array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>