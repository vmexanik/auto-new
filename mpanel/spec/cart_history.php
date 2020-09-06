<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class ACartHistory extends Admin {

	//-----------------------------------------------------------------------------------------------
	function ACartHistory() {
		$this->sTableName='cart_history';
		$this->sTablePrefix='ch';
		$this->sAction='cart_history';
		$this->sWinHead=Language::getDMessage('Cart Historys');
		$this->sPath = Language::GetDMessage('>>Logs >');
		//$this->aCheckField=array('name','code');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'ch.id'),
		'code'=>array('sTitle'=>'Code','sOrder'=>'ch.code'),
		'make'=>array('sTitle'=>'Make','sOrder'=>'ch.make'),
		'up.name'=>array('sTitle'=>'ProviderName','sOrder'=>'up.name'),
		'post_date'=>array('sTitle'=>'post_date','sOrder'=>'ch.post_date'),
		'order_status'=>array('sTitle'=>'order_status','sOrder'=>'ch.order_status'),
		'comment'=>array('sTitle'=>'comment','sOrder'=>'ch.comment'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeApply() {
		require_once(SERVER_PATH.'/class/module/Catalog.php');
		Base::$aRequest['data']['code']=Catalog::StripCode(Base::$aRequest['data']['code']);
	}
	//-----------------------------------------------------------------------------------------------
}
?>