<?
require_once (SERVER_PATH . '/class/core/Admin.php');
class ADeliveryType extends Admin {

	//-----------------------------------------------------------------------------------------------
	function ADeliveryType() {
		$this->sTableName = 'delivery_type';
		$this->sTablePrefix = 'dt';
		$this->sAction = 'delivery_type';
		$this->sWinHead = Language::getDMessage('Delivery Type');
		$this->sPath = Language::GetDMessage('>>Content >');
		$this->aCheckField = array ('code', 'name');
		//$this->aFCKEditors = array ('description' );
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$this->initLocaleGlobal();
		$oTable->aColumn=array(
		'id'=> array('sTitle'=>'Id', 'sOrder'=>'dt.id'),
		'code' => array('sTitle'=>'Code', 'sOrder'=>'dt.code'),
		'name' => array('sTitle'=>'Name', 'sOrder'=>'dt.name'),
		'image' => array('sTitle'=>'Image', 'sOrder'=>'dt.url'),
		'price' => array('sTitle'=>'Price' , 'sOrder'=>'dt.price'),
		'visible' => array('sTitle'=>'Visible', 'sOrder'=>'dt.visible'),
		'num' => array('sTitle'=>'Num' ,'sOrder'=>'dt.num'),
		'lang' => array ('sTitle' => 'Lang'),
		'action' => array(),
		);
		$this->SetDefaultTable($oTable);
		$oTable->sSql=Base::GetSql("DeliveryType",array('code'=>'without'));
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
}
?>