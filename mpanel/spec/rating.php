<?
/**
 * @author Mikhail Starovoyt
 *
 */
class ARating extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName = 'rating';
		$this->sTablePrefix = 'r';
		$this->sAction = 'rating';
		$this->sWinHead = Language::getDMessage('Rating');
		$this->sPath = Language::GetDMessage('>>Content >');
		$this->aCheckField = array ('section', 'num' , 'name');
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();
		$oTable=new Table();
		$this->initLocaleGlobal ();
		$oTable->aColumn=array(
		'id'=> array('sTitle'=>'Id', 'sOrder'=>'r.id'),
		'num' => array('sTitle'=>'Num', 'sOrder'=>'r.num'),
		'section' => array('sTitle'=>'Section', 'sOrder'=>'r.section'),
		'name' => array('sTitle'=>'Name', 'sOrder'=>'r.name'),
		'language' => array ('sTitle' => 'Lang' ),
		'action' => array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>