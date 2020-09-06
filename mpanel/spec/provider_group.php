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