<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class AFaq extends Admin {

	//-----------------------------------------------------------------------------------------------
	function AFaq() {
		$this->sTableName='faq';
		$this->sTablePrefix='f';
		$this->sAction='faq';
		$this->sWinHead=Language::getDMessage('FAQ');
		$this->sPath=Language::GetDMessage('>>Customer support >');
		$this->aCheckField=array('question','answer');
		$this->sBeforeAddMethod='BeforeAdd';
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn = array ();
		$oTable->aColumn['id']=array('sTitle'=>'Id','sOrder'=>'f.id');
		$oTable->aColumn['faq_category_name']=array('sTitle'=>'FaqCategoryName','sOrder'=>'faq_category_name');
		$oTable->aColumn['question']=array('sTitle'=>'Question','sOrder'=>'f.question');
		$oTable->aColumn['answer']=array('sTitle'=>'Answer','sOrder'=>'f.answer');
		$oTable->aColumn['visible']=array('sTitle'=>'Visible','sOrder'=>'visible');
		$oTable->aColumn['num']=array('sTitle'=>'Num','sOrder'=>'f.num');
		$this->initLocaleGlobal ();
		$oTable->aColumn ['language'] = array ('sTitle' => 'Lang' );
		$oTable->aColumn['action']=array();
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAdd() {
		$aRecordSet=Base::$db->getAll("select * from faq_category as fc where 1=1");
		foreach ($aRecordSet as $aItem) {$aFaqCategory[$aItem["id"]]=$aItem["name"];};

		Base::$tpl->assign('aFaqCategory',$aFaqCategory);
	}
}
?>