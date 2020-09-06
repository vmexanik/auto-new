<?
/**
 * @author 
 *
 */
class ABanner extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName = 'banner';
		$this->sTablePrefix = 'b';
		$this->sAction = 'banner';
		$this->sWinHead = Language::getDMessage('Caorusel');
		$this->sPath = Language::GetDMessage('>>Content >');
		$this->aCheckField = array ('name','link','image');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=> array('sTitle'=>'Id', 'sOrder'=>'b.id'),
		'name' => array('sTitle'=>'name', 'sOrder'=>'b.name'),
		'link' => array('sTitle'=>'link', 'sOrder'=>'b.link'),
		'image'=>array('sTitle'=>'Image','sOrder'=>'b.image'),
		'visible' => array('sTitle'=>'visible', 'sOrder'=>'b.visible'),
		'action' => array(),
		);
				
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>