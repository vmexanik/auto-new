<?

class AOffice extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName='office';
		$this->sTablePrefix='o';
		$this->sAction='office';
		$this->sWinHead=Language::getDMessage('Office');
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField=array('name');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();

		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'o.id'),
		'name'=>array('sTitle'=>'name','sOrder'=>'o.name'),
		'city_name'=>array('sTitle'=>'city_name','sOrder'=>'oc.name'),
		'visible'=>array('sTitle'=>'Visible','sOrder'=>'o.visible'),
		'post_date'=>array('sTitle'=>'Date','sOrder'=>'o.post_date'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData)
	{
		$aOfficeCity=Db::GetAssoc("Assoc/OfficeCity",array(
		'all'=> '1',
		'order'=> ' order by oc.num, oc.name',
		));
		Base::$tpl->assign('aOfficeCity',$aOfficeCity);
	}
	//-----------------------------------------------------------------------------------------------


}
?>