<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class AArticle extends Admin {

	//-----------------------------------------------------------------------------------------------
	function AArticle() {
		$this->sTableName='article';
		$this->sTablePrefix='a';
		$this->sAction='article';
		$this->sWinHead=Language::getDMessage('Articles');
		$this->sPath=Language::GetDMessage('>>Customer support >');
		$this->aCheckField=array('name');
		$this->sBeforeAddMethod='BeforeAdd';
		//$this->aFCKEditors = array ('content' );
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn = array ();
		$oTable->aColumn['id']=array('sTitle'=>'Id','sOrder'=>'a.id');
		$oTable->aColumn['article_category_name']=array('sTitle'=>'ArticleCategoryName','sOrder'=>'ac.name');
		$oTable->aColumn['name']=array('sTitle'=>'Name','sOrder'=>'a.name');
		$oTable->aColumn['content']=array('sTitle'=>'Content','sOrder'=>'a.content');
		$oTable->aColumn['visible']=array('sTitle'=>'Visible','sOrder'=>'a.visible');
		$oTable->aColumn['num']=array('sTitle'=>'Num','sOrder'=>'a.num');
		$this->initLocaleGlobal();
		$oTable->aColumn['language']=array('sTitle' => 'Lang');
		$oTable->aColumn['action']=array();
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAdd() {

		$aArticleCategory=Base::$db->getAll("select * from article_category as ac where 1=1");
		foreach ($aArticleCategory as $aValue) $aArticleCategoryHash[$aValue['id']]=$aValue['name'];

		Base::$tpl->assign('aArticleCategoryHash',$aArticleCategoryHash);
	}
	//-----------------------------------------------------------------------------------------------
}
?>