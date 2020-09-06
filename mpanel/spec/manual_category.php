<?
require_once(SERVER_PATH.'/class/core/Category.php');
class AManualCategory extends Category {
	//-----------------------------------------------------------------------------------------------
	function AManualCategory() {
		$this->sTableName='manual_category';
		$this->sTablePrefix='mc';
		$this->sAction='manual_category';
		$this->sWinHead=Language::getDMessage('Manual Categories');
		$this->sPath = Language::GetDMessage('>>Customer support >');
		$this->aCheckField=array(code, name);
		$this->Admin();
		
		parent::__construct();
	}
}

?>