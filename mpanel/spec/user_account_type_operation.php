<?

class AUserAccountTypeOperation extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName='user_account_type_operation';
		$this->sTablePrefix='uato';
		$this->sAction='user_account_type_operation';
		$this->sWinHead=Language::getDMessage('User Account Type Operation');
		$this->sPath=Language::GetDMessage('>>Customers >');
		$this->aCheckField=array('name');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'uato.id'),
		'code'=>array('sTitle'=>'Code','sOrder'=>'uato.code'),
		'name'=>array('sTitle'=>'Name','sOrder'=>'uato.name'),
		'description'=>array('sTitle'=>'Description','sOrder'=>'uato.description'),
		'formula_balance'=>array('sTitle'=>'formula_balance','sOrder'=>'uato.formula_balance'),
		'action'=>array(),
		);
		$this->initLocaleGlobal();
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function Apply()
	{
		if (Base::$aRequest ['data']['add']) {
			$sMode = 'INSERT';
			$sWhere = false;

		} else {
			$sMode='UPDATE';
			$sWhere="id='".Base::$aRequest['data']['id']."'";
		}
		Db::AutoExecute($this->sTableName,Base::$aRequest['data'],$sMode,$sWhere);
		if (Base::$aGeneralConf['LogAdmin']) Log::AdminAdd(Base::$aRequest['xajaxargs'][0],$this->sTableName);
		$this->AdminRedirect($this->sAction);
	}
	//-----------------------------------------------------------------------------------------------

}
?>