<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class ADirectory extends Admin {

	//-----------------------------------------------------------------------------------------------
	function ADirectoy() {
		$this->sTableName='directory';
		$this->sTablePrefix='d';
		$this->sAction='directory';
		$this->sWinHead=Language::getDMessage('Directories');
		$this->sPath=Language::GetDMessage('>>Configuration > ');
		$this->aCheckField=array('code','name','value');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'c.id'),
		'code'=>array('sTitle'=>'Code','sOrder'=>'c.code'),
		'name'=>array('sTitle'=>'Name','sOrder'=>'c.name'),
		'symbol'=>array('sTitle'=>'Symbol','sOrder'=>'c.symbol'),
		'image'=>array('sTitle'=>'Image','sOrder'=>'c.image'),
		'value'=>array('sTitle'=>'Value','sOrder'=>'c.value'),
		'visible'=>array('sTitle'=>'Visible','sOrder'=>'c.visible'),
		'num'=>array('sTitle'=>'Num','sOrder'=>'c.num'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>