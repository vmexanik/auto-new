<?
require_once(SERVER_PATH.'/class/core/Category.php');
class AFaqCategory extends Category {
	//-----------------------------------------------------------------------------------------------
	function AFaqCategory() {
		$this->sTableName='faq_category';
		$this->sTablePrefix='fc';
		$this->sAction='faq_category';
		$this->sWinHead=Language::getDMessage('FAQ Categories');
		$this->sPath = Language::GetDMessage('>>Customer support >');
		$this->aCheckField=array(code, name);
		$this->Admin();
		
		parent::__construct();
	}
}
?>