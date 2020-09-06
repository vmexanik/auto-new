<?

/**
 * @author Mikhail Starovoyt
 *
 */

class APaymentType extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName='payment_type';
		$this->sTablePrefix='dt';
		$this->sAction = 'payment_type';
		$this->sWinHead = Language::GetDMessage('payment Type');
		$this->sPath = Language::GetDMessage('>>Content >');
		$this->aCheckField=array('name');
		//$this->aFCKEditors=array('description','end_description');
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();

		$this->initLocaleGlobal();
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=> array('sTitle'=>'Id', 'sOrder'=>'pt.id'),
		'name' => array('sTitle'=>'Name', 'sOrder'=>'pt.name'),
		'url' => array('sTitle'=>'Url', 'sOrder'=>'pt.url'),
		'description' => array('sTitle'=>'Description' , 'sOrder'=>'pt.description'),
		'visible' => array('sTitle'=>'Visible', 'sOrder'=>'pt.visible'),
		'num' => array('sTitle'=>'Num' ,'sOrder'=>'pt.num'),
		'lang' => array ('sTitle' => 'Lang'),
		'action' => array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>