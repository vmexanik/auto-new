<?

class AOfficeCountry extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName='office_country';
		$this->sTablePrefix='oc';
		$this->sAction='office_country';
		$this->sWinHead=Language::GetDMessage('Office country');
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField=array('name','num');
		
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		$this->initLocaleGlobal();
		$oTable=new Table();
		$oTable->aColumn ['id']=array('sTitle'=>'Id','sOrder'=>'oc.id');
		$oTable->aColumn ['name']=array('sTitle'=>'Name','sOrder'=>'oc.name');
		$oTable->aColumn ['num']=array('sTitle'=>'Num','sOrder'=>'oc.num');
		$oTable->aColumn ['visible']=array('sTitle'=>'Visible','sOrder'=>'oc.visible');
		$oTable->aColumn ['action']=array();
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign($aData) {
	}
	//-----------------------------------------------------------------------------------------------
}
?>