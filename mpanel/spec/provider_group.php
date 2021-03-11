<?
/**
 * @author Oleksandr Starovoit
 *
 */
class AProviderGroup extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'provider_group';
		$this->sTablePrefix = 'pg';
		$this->sAction = 'provider_group';
		$this->sWinHead = Language::getDMessage ( 'Provider Groups' );
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField = array ('code', 'name');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong', 0)) {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and pg.id = '" . $this->aSearch['id'] . "'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and pg.name = '" . $this->aSearch['name'] . "'";
                if ($this->aSearch['code'])
                    $this->sSearchSQL .= " and pg.code = '" . $this->aSearch['code'] . "'";
                if ($this->aSearch['group_margin'])
                    $this->sSearchSQL .= " and pg.group_margin = '" . $this->aSearch['group_margin'] . "'";
            } else {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and pg.id like '%" . $this->aSearch['id'] . "%'";
                if ($this->aSearch['code'])
                    $this->sSearchSQL .= " and pg.code like '%" . $this->aSearch['code'] . "%'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and pg.name like '%" . $this->aSearch['name'] . "%'";
                if ($this->aSearch['group_margin'])
                    $this->sSearchSQL .= " and pg.group_margin like '%" . $this->aSearch['group_margin'] . "%'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and pg.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and pg.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and pg.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and pg.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }

		$this->initLocaleGlobal();
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=> array('sTitle'=>'Id', 'sOrder'=>'pg.id'),
		//'id_provider_group_type'=> array('sTitle'=>'Type', 'sOrder'=>'pg.id_provider_group_type'),
		'name'=> array('sTitle'=>'Name', 'sOrder'=>'pg.name'),
		'code'=> array('sTitle'=>'Code', 'sOrder'=>'pg.code'),
		'group_margin' => array('sTitle'=>'Group Margin' , 'sOrder'=>'pg.group_margin'),
		//'group_discount' => array('sTitle'=>'Group _discount' , 'sOrder'=>'pg.group_discount'),
		//'group_term' => array('sTitle'=>'Group term' , 'sOrder'=>'pg.group_term'),
		'visible'=> array('sTitle'=>'Visible', 'sOrder'=>'pg.visible'),
		'action'=> array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
}

?>