<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class ACartPackageSign extends Admin {

	//-----------------------------------------------------------------------------------------------
	function ACartPackageSign() {
		$this->sTableName='cart_package_sign';
		$this->sTablePrefix='cps';
		$this->sAction='cart_package_sign';
		$this->sWinHead=Language::getDMessage('CartPackageSign');
		$this->sPath=Language::GetDMessage('>>Content >');
		$this->aCheckField=array('name','code');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn = array ();
		$oTable->aColumn['id']=array('sTitle'=>'Id','sOrder'=>'cps.id');
		$oTable->aColumn['code']=array('sTitle'=>'Code','sOrder'=>'code');
		$oTable->aColumn['name']=array('sTitle'=>'Name','sOrder'=>'cps.name');
		$oTable->aColumn['description']=array('sTitle'=>'Description','sOrder'=>'cps.description');
		$oTable->aColumn['visible']=array('sTitle'=>'Visible','sOrder'=>'cps.visible');
		$oTable->aColumn['num']=array('sTitle'=>'Num','sOrder'=>'cps.num');
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