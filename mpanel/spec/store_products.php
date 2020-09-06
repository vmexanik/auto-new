<?
/**
 * @author 
 *
 */
class AStoreProducts extends Admin {
	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName='store_products';
		$this->sTablePrefix='sp';
		$this->sTableId='id';
		$this->sAction='store_products';
		$this->sWinHead=Language::getDMessage ('Store products');
		$this->sPath=Language::getDMessage ('>>store module >');
		$this->aCheckField=array('code','name','pref');

		$this->sBeforeAddMethod='BeforeAdd';
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();
		
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id', 'sOrder'=>'sp.id', 'sWidth'=>'5%'),
		'cat'=>array('sTitle'=>'cat'),
		'code'=>array('sTitle'=>'code', 'sOrder'=>'sp.code', 'sWidth'=>'10%'),
		'name'=>array('sTitle'=>'Name', 'sOrder'=>'sp.name', 'sWidth'=>'30%'),
		'item_code'=>array('sTitle'=>'item_code'),
		'action' => array ()
		);
		$this->SetDefaultTable ( $oTable);
		
		Base::$sText.=$oTable->getTable();
		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAdd() {
		Base::$tpl->assign('aPref',Base::$db->getAssoc("select pref, concat(title,' [',pref,']') as name from cat order by name"));
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeApply() {
		Base::$aRequest['data']['item_code']=Base::$aRequest['data']['pref']."_".Base::$aRequest['data']['code'];
	}
	//-----------------------------------------------------------------------------------------------
	public function AfterApply($aBeforeRow,$aAfterRow) {
		
	}
	//-----------------------------------------------------------------------------------------------
}
?>
