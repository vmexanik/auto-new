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

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong', 0)) {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and pr.id = '" . $this->aSearch['id'] . "'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and pr.name = '" . $this->aSearch['name'] . "'";
                if ($this->aSearch['code'])
                    $this->sSearchSQL .= " and pr.code = '" . $this->aSearch['code'] . "'";
                if ($this->aSearch['description'])
                    $this->sSearchSQL .= " and pr.description = '" . $this->aSearch['description'] . "'";
            } else {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and pr.id like '%" . $this->aSearch['id'] . "%'";
                if ($this->aSearch['code'])
                    $this->sSearchSQL .= " and pr.code like '%" . $this->aSearch['code'] . "%'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and pr.name like '%" . $this->aSearch['name'] . "%'";
                if ($this->aSearch['description'])
                    $this->sSearchSQL .= " and pr.description like '%" . $this->aSearch['description'] . "%'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and pr.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and pr.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and pr.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and pr.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }

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