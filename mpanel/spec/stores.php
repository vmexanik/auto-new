<?
/**
 * @author 
 *
 */
class AStores extends Admin {
	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sSqlPath = 'Stores';
		$this->sTableName='store';
		$this->sTablePrefix='s';
		$this->sTableId='id';
		$this->sAction='stores';
		$this->sWinHead=Language::getDMessage ('Stores');
		$this->sPath=Language::getDMessage ('>>store module >');
		$this->aCheckField=array('code','name');

		$this->sBeforeAddMethod='BeforeAdd';
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();
		
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id', 'sOrder'=>'s.id', 'sWidth'=>1),
		'code'=>array('sTitle'=>'code', 'sOrder'=>'s.code', 'sWidth'=>'10%'),
		'name'=>array('sTitle'=>'Name', 'sOrder'=>'s.name', 'sWidth'=>'30%'),
		'is_virtual'=>array('sTitle'=>'is_virtual', 'sOrder'=>'s.is_virtual'),
		'provider'=>array('sTitle'=>'provider', 'sOrder'=>'up.name'),
		'is_return'=>array('sTitle'=>'is_return', 'sOrder'=>'s.is_return'),
		'is_sale'=>array('sTitle'=>'is_sale', 'sOrder'=>'s.is_sale'),
		'visible'=>array('sTitle'=>'visible', 'sOrder'=>'s.visible'),
		'action' => array ()
		);
		$this->SetDefaultTable ( $oTable);
		
		Base::$sText.=$oTable->getTable();
		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAdd() {
		Base::$tpl->assign('aProviders',Base::$db->getAssoc("select id_user, name from user_provider order by name"));
	}
	//-----------------------------------------------------------------------------------------------
	public function AfterApply($aBeforeRow,$aAfterRow) {
		
	}
	//-----------------------------------------------------------------------------------------------
}
?>
