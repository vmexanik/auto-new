<?

class AOfficeCity extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName='office_city';
		$this->sTablePrefix='oc';
		$this->sAction='office_city';
		$this->sWinHead=Language::GetDMessage('Office city');
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField=array('id_office_country','id_office_region','name');		
		Base::$tpl->assign('aCountryList', Base::$db->getAssoc("select id, name from office_country order by id"));
		Base::$tpl->assign('aRegionList', array(""=>"")+Base::$db->getAssoc("select id, name from office_region order by id"));
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		$this->initLocaleGlobal();
		$oTable=new Table();
		$oTable->aColumn ['id']=array('sTitle'=>'Id','sOrder'=>'oc.id');
		$oTable->aColumn ['country']=array('sTitle'=>'Country','sOrder'=>'ofc.name');
		$oTable->aColumn ['region']=array('sTitle'=>'Region','sOrder'=>'ofr.name');
		$oTable->aColumn ['name']=array('sTitle'=>'Name','sOrder'=>'oc.name');
		$oTable->aColumn ['code']=array('sTitle'=>'Code','sOrder'=>'oc.code');
		$oTable->aColumn ['term_delivery']=array('sTitle'=>'Term delivery','sOrder'=>'oc.term_delivery');
		$oTable->aColumn ['markup']=array('sTitle'=>'Markup','sOrder'=>'oc.markup');
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