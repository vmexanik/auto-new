<?
/**
 * @author 
 *
 */
class APopularProducts extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName = 'popular_products';
		$this->sTablePrefix = 'p';
		$this->sAction = 'popular_products';
		$this->sWinHead = Language::getDMessage('PopularProducts');
		$this->sPath = Language::GetDMessage('>>Content >');
		$this->aCheckField = array ('name','zzz_code');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=> array('sTitle'=>'Id', 'sOrder'=>'p.id'),
		'name' => array('sTitle'=>'name', 'sOrder'=>'p.name'),
		'zzz_code' => array('sTitle'=>'zzz_code', 'sOrder'=>'p.zzz_code'),
		'old_price'=>array('sTitle'=>'old_price','sOrder'=>'p.old_price'),
		'image'=>array('sTitle'=>'Image','sOrder'=>'p.image'),
		'bage'=>array('sTitle'=>'bage','sOrder'=>'p.bage'),
		'visible' => array('sTitle'=>'visible', 'sOrder'=>'p.visible'),
		'action' => array(),
		);
				
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData)
	{	
		Base::$tpl->assign('aBage',array(
			"0"=>Language::GetMessage("not selected"),
			"recommend"=>Language::GetMessage("badge recommend"),
			"new"=>Language::GetMessage("badge new"),
			"action"=>Language::GetMessage("badge action"),
		));
	}
	//-----------------------------------------------------------------------------------------------
}
?>