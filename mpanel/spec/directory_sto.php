<?
require_once (SERVER_PATH . '/class/core/Admin.php');
class ADirectorySto extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'directory_sto';
		$this->sTablePrefix = 'ds';
		$this->sAction = 'directory_sto';
		$this->sWinHead = Language::getDMessage('Directory Sto');
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
		'id'=> array('sTitle'=>'Id', 'sOrder'=>'ds.id'),
		'name' => array('sTitle'=>'Name', 'sOrder'=>'ds.name'),
		'city_name' => array('sTitle'=>'City', 'sOrder'=>'ds.city_name'),
		'visible' => array('sTitle'=>'Visible', 'sOrder'=>'ds.visible'),
		'is_featured' => array('sTitle'=>'Featured', 'sOrder'=>'ds.is_featured'),
		'action' => array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign($aData){
		Base::$tpl->assign('aCity', Base::$db->GetAssoc("select dc.id, dc.name from directory_city as dc order by name"));
		Base::$tpl->assign('aDirectoryTag', Base::$db->GetAll(Base::GetSql('DirectoryTag')));

		$aDirectoryStoTag = array_keys(Base::$db->GetAssoc("select dst.id_directory_tag, dst.* from directory_sto_tag as dst
			where dst.id_directory_sto='{$aData['id']}'"));
		Base::$tpl->assign('aDirectoryStoTag', $aDirectoryStoTag);
	}
	//-----------------------------------------------------------------------------------------------
	public function AfterApply($aBeforeRow,$aAfterRow) {
		Base::$db->Execute("delete from directory_sto_tag where id_directory_sto='{$aAfterRow['id']}'");
		$aDirectoryStoTag['id_directory_sto']=$aAfterRow['id'];
		if (Base::$aRequest['data']['directory_sto_tag']) foreach (Base::$aRequest['data']['directory_sto_tag'] as $value) {
			$aDirectoryStoTag['id_directory_tag']=$value;
			Base::$db->AutoExecute('directory_sto_tag',$aDirectoryStoTag,'INSERT');
		}
	}
	//-----------------------------------------------------------------------------------------------
}
?>