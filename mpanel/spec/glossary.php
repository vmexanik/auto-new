<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class AGlossary extends Admin {

	//-----------------------------------------------------------------------------------------------
	function AGlossary() {
		$this->sTableName='glossary';
		$this->sTablePrefix='g';
		$this->sAction='glossary';
		$this->sWinHead=Language::getDMessage('Glossary');
		$this->sPath=Language::GetDMessage('>>Content >');
		$this->aCheckField=array('name','first_letter', 'title', 'status', 'description');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'g.id'),
		'name'=>array('sTitle'=>'Name','sOrder'=>'g.name'),
		'first_letter'=>array('sTitle'=>'First letter', 'sOrder'=>'g.first_letter'),
		//'term_action'=>array('sTitle'=>'Term action','sOrder'=>'g.term_action'),
		'title'=>array('sTitle'=>'Title','sOrder'=>'g.title'),
		'status'=>array('sTitle'=>'Status','sOrder'=>'g.status'),
		'description'=>array('sTitle'=>'Description','sOrder'=>'g.description'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function Apply() {
		Base::$aRequest['data']['description']="";
		$descr =  strip_tags(Base::$aRequest['data_description']);
		if (strlen($descr) > 0){
			Base::$aRequest['data']['description'] = Base::$aRequest['data_description'];
		}
		parent::Apply ();
	}
}
?>