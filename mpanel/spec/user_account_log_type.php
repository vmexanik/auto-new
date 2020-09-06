<?

class AUserAccountLogType extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName='user_account_log_type';
		$this->sTablePrefix='ualt';
		$this->sAction='user_account_log_type';
		$this->sWinHead=Language::getDMessage('User Account Log Types');
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
		'id'=>array('sTitle'=>'Id','sOrder'=>'ualt.id'),
		'name'=>array('sTitle'=>'Name','sOrder'=>'ualt.name'),
		'description'=>array('sTitle'=>'Description','sOrder'=>'ualt.description'),
		'post_date'=>array('sTitle' => 'Date','sOrder'=>'ualt.post_date'),
		'language'=>array('sTitle' => 'Lang'),
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