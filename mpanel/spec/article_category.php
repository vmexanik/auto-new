<?
require_once(SERVER_PATH.'/class/core/Category.php');
class AArticleCategory extends Category {
	//-----------------------------------------------------------------------------------------------
	function AArticleCategory() {
		$this->sTableName='article_category';
		$this->sTablePrefix='ac';
		$this->sAction='article_category';
		$this->sWinHead=Language::getDMessage('Article Categories');
		$this->sPath = Language::GetDMessage('>>Customer support >');
		$this->aCheckField=array(code, name);
		$this->Admin();
		
		parent::__construct();
	}
}
?>