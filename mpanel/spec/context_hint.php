<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class AContextHint extends Admin {

	//-----------------------------------------------------------------------------------------------
	function AContextHint() {
		$this->sTableName='context_hint';
		$this->sTablePrefix='ch';
		$this->sAction='context_hint';
		$this->sWinHead=Language::getDMessage('Context Hint');
		$this->sPath=Language::GetDMessage('>>Customer support >');
		$this->aCheckField=array('key_');
		//$this->aFCKEditors = array ('content' );
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn = array ();
		$oTable->aColumn['id']=array('sTitle'=>'Id','sOrder'=>'ch.id');
		$oTable->aColumn['key_']=array('sTitle'=>'Key','sOrder'=>'ch.key_');
		$oTable->aColumn['content']=array('sTitle'=>'Content','sOrder'=>'ch.content');
		$oTable->aColumn['visible']=array('sTitle'=>'Visible','sOrder'=>'ch.visible');
		$oTable->aColumn['num']=array('sTitle'=>'Num','sOrder'=>'ch.num');
		$this->initLocaleGlobal ();
		$oTable->aColumn ['language'] = array ('sTitle' => 'Lang' );
		$oTable->aColumn['action']=array();
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>