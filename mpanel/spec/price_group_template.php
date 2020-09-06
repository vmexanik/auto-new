<?
/**
  * @author Mikhail Starovoyt
 * @version 4.5.2
 */
class APriceGroupTemplate extends Admin {
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
		$this->sTableName='price_group_template';
		$this->sTablePrefix='pgt';
		$this->sAction='price_group_template';
		//$this->sSqlPath="Price/Group";
		$this->sWinHead=Language::getDMessage('Price group template');
		$this->sPath=Language::GetDMessage('>>Auto catalog >');
		$this->aCheckField=array('code');
		//$this->aFCKEditors = array ('description');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		$oTable=new Table();
		$sTablePref = 'pgt.';
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>$sTablePref.'id'),
		'code'=>array('sTitle'=>'Code','sOrder'=>$sTablePref.'code'),
		'visible'=>array('sTitle'=>'Visible','sOrder'=>$sTablePref.'visible'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------

}
?>