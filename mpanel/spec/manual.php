<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class AManual extends Admin {

	//-----------------------------------------------------------------------------------------------
	function AManual() {
		$this->sTableName='manual';
		$this->sTablePrefix='m';
		$this->sAction='manual';
		$this->sWinHead=Language::getDMessage('CManual');
		$this->sPath=Language::getDMessage('>>Content >');
		$this->aCheckField=array('name');
		//$this->aFCKEditors=array ('content');
		$this->sBeforeAddMethod='BeforeAdd';
		$this->sNumSql="select max(num) from ".$this->sTableName."";
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn = array ();
		$oTable->aColumn['id']=array('sTitle'=>'Id','sOrder'=>'m.id');
		$oTable->aColumn['user_type']=array('sTitle'=>'user type','sOrder'=>'m.user_type');
		$oTable->aColumn['code']=array('sTitle'=>'Code','sOrder'=>'m.code');
		$oTable->aColumn['name']=array('sTitle'=>'Name','sOrder'=>'m.name');
		$oTable->aColumn['short_content']=array('sTitle'=>'Short content','sOrder'=>'m.short_content');
		$oTable->aColumn['content']=array('sTitle'=>'Content','sOrder'=>'m.content');
		$oTable->aColumn['visible']=array('sTitle'=>'Visible','sOrder'=>'m.visible');
		$this->initLocaleGlobal();
		$oTable->aColumn['language']=array('sTitle' => 'Lang');
		$oTable->aColumn['action']=array();
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAdd() {

		$aManualCategory=Base::$db->getAll("select * from manual_category as mc where 1=1");
		foreach ($aManualCategory as $aValue) $aManualCategoryHash[$aValue['code']]=$aValue['name'];

		Base::$tpl->assign('aManualCategoryHash',$aManualCategoryHash);
	}
}
?>