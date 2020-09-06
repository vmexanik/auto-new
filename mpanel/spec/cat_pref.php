<?
/**
 * @author Starovoit Alexsandr
 *
 */
class ACatPref extends Admin {
	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName='cat_pref';
		$this->sTablePrefix='cp';
		$this->sTableId='id';
		$this->sAction='cat_pref';
		$this->sWinHead=Language::getDMessage('Pref equivalent');
		$this->sPath=Language::GetDMessage('>>Auto catalog >');
		$this->aCheckField=array('cat_id','name');

		$this->sBeforeAddMethod='BeforeAdd';
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();
		
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>Language::getDMessage('Id'), 'sOrder'=>'cp.id', 'sWidth'=>1, 'sMethod'=>'exact'),
		'name'=>array('sTitle'=>'Name',Language::getDMessage('Name'), 'sOrder'=>'cp.name', 'sWidth'=>'40%'),
		'pref'=>array('sTitle'=>Language::getDMessage('Pref'), 'sOrder'=>'c.pref', 'sWidth'=>10),
		'action' => array ()
		);
		$oTable->bCheckVisible=false;
		$this->SetDefaultTable ( $oTable);
		
		Base::$sText.=$oTable->getTable();
		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAdd() {
		Base::$tpl->assign('aPref',Base::$db->getAssoc("select pref, concat(title,' ',pref) as name from cat order by name"));
		Base::$tpl->assign('aCatId',Base::$db->getAssoc("select id, concat(coalesce(title,'?'),' ',coalesce(pref,'?')) as name from cat order by name"));
	}
	//-----------------------------------------------------------------------------------------------
	public function AfterApply($aBeforeRow,$aAfterRow) {
		if($aAfterRow) Base::$db->Execute("update cat_pref set name=upper(name) where id=".$aAfterRow['id']);
	}
	//-----------------------------------------------------------------------------------------------
}
?>
