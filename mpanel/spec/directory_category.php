<?
require_once(SERVER_PATH.'/class/core/Category.php');
class ADirectoryCategory extends Category {
	//-----------------------------------------------------------------------------------------------
	function ADirectoryCategory() {
		$this->sTableName='directory_category';
		$this->sTablePrefix='dc';
		$this->sAction='directory_category';
		$this->sWinHead=Language::getDMessage('Directory Categories');
		$this->sPath = Language::GetDMessage('>>Directory >');
		$this->aCheckField=array(code, name);
		$this->Admin();
		
		parent::__construct();
	}
}
?>