<?

class AOfficeRegion extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName='office_region';
		$this->sTablePrefix='ofr';
		$this->sAction='office_region';
		$this->sWinHead=Language::GetDMessage('Office region');
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField=array('id_office_country','name');		
		Base::$tpl->assign('aCountryList', Base::$db->getAssoc("select id, name from office_country order by id"));
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		$this->initLocaleGlobal();
		$oTable=new Table();
		$oTable->aColumn ['id']=array('sTitle'=>'Id','sOrder'=>'ofr.id');
		$oTable->aColumn ['name']=array('sTitle'=>'Name','sOrder'=>'ofr.name');
		$oTable->aColumn ['code']=array('sTitle'=>'Code','sOrder'=>'ofr.code');
		$oTable->aColumn ['visible']=array('sTitle'=>'Visible','sOrder'=>'ofr.visible');
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