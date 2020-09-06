<?

/**
 * @author Mikhail Starovoyt
 *
 */

class AAccount extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName='account';
		$this->sTablePrefix='a';
		$this->sAction='account';
		$this->sWinHead=Language::getDMessage('Account');
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField=array('id_buh','name','account_id','holder_name','bank_name','holder_code','bank_mfo');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();

		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'a.id'),
		'id_buh'=>array('sTitle'=>'IdBuh','sOrder'=>'a.id_buh'),
		'name'=>array('sTitle'=>'Ofice / name','sOrder'=>'a.name'),
		'account_id'=>array('sTitle'=>'account_id','sOrder'=>'a.account_id'),
		'holder_name'=>array('sTitle'=>'holder_name','sOrder'=>'a.holder_name'),
		'bank_name'=>array('sTitle'=>'bank_name','sOrder'=>'a.bank_name'),
		'bank_code'=>array('sTitle'=>'bank_code','sOrder'=>'a.bank_code'),
		'correspondent_account'=>array('sTitle'=>'correspondent_account','sOrder'=>'a.correspondent_account'),
		'holder_code'=>array('sTitle'=>'holder_code','sOrder'=>'a.holder_code'),
		'bank_mfo'=>array('sTitle'=>'bank_mfo','sOrder'=>'a.bank_mfo'),
		'is_active'=>array('sTitle'=>'Is Active','sOrder'=>'a.is_active'),
		'visible'=>array('sTitle'=>'Visible','sOrder'=>'a.visible'),
		'post_date'=>array('sTitle'=>'Date','sOrder'=>'a.post_date'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function Activate()
	{
		Db::Execute("update account set is_active='0'");
		Db::Execute("update account set is_active='1' where id='".Base::$aRequest['id']."'");
		$this->Index();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData)
	{
		Base::$tpl->assign('aOffice',Db::GetAssoc('Assoc/Office'));
		Base::$tpl->assign('aCurrencyAssoc', Db::GetAssoc("Assoc/Currency",array(
		'key_field'=>'id',
		)));
	}
	//-----------------------------------------------------------------------------------------------


}
?>