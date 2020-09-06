<?
require_once (SERVER_PATH . '/class/core/Admin.php');
class ADiscount extends Admin {

	//-----------------------------------------------------------------------------------------------
	function ADiscount() {
		$this->sTableName = 'discount';
		$this->sTablePrefix = 'd';
		$this->sAction = 'discount';
		$this->sWinHead = Language::getDMessage ( 'Discount' );
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField = array ('amount', 'discount');
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
		'amount' => array('sTitle'=>'Amount', 'sOrder'=>'d.amount'),
		'discount' => array('sTitle'=>'Discount', 'sOrder'=>'d.discount'),
		'visible' => array('sTitle'=>'Visible', 'sOrder'=>'d.visible'),
		'action' => array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
}

?>