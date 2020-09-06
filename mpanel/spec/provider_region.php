<?

/**
 * @author Mihail Starovoyt
 *
 */

class AProviderRegion extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName='provider_region';
		$this->sTablePrefix='pr';
		$this->sAction='provider_region';
		$this->sWinHead=Language::getDMessage('Provider Regions');
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField=array('code_delivery','name');
		//$this->aFCKEditors = array ('full');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		$this->initLocaleGlobal();
		$oTable=new Table();
		$oTable->aColumn ['id']=array('sTitle'=>'Id','sOrder'=>'pr.id');
		$oTable->aColumn ['code']=array('sTitle'=>'Code','sOrder'=>'pr.code');
		//$oTable->aColumn ['additional_delivery']=array('sTitle'=>'AdditionalDelivery','sOrder'=>'pr.additional_delivery');
		//$oTable->aColumn ['way']=array('sTitle'=>'Way','sOrder'=>'prw.name');
		$oTable->aColumn ['name']=array('sTitle'=>'Name_reg','sOrder'=>'pr.name');
		$oTable->aColumn ['description']=array('sTitle'=>'Description','sOrder'=>'pr.description');
		//$oTable->aColumn ['delivery_cost']=array('sTitle'=>'Delivery Cost','sOrder'=>'pr.delivery_cost');
		$oTable->aColumn ['visible']=array('sTitle'=>'Visible','sOrder'=>'pr.visible');
		$oTable->aColumn ['language'] = array ('sTitle' => 'Lang' );
		$oTable->aColumn ['action']=array();
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign($aData) {
		Base::$tpl->assign('aProviderRegionWayList', Base::$db->getAssoc("select id, name from provider_region_way order by id") );
	}
	//-----------------------------------------------------------------------------------------------
}
?>