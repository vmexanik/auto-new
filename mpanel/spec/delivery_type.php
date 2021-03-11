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

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong', 0)) {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and dt.id = '" . $this->aSearch['id'] . "'";
                if ($this->aSearch['code'])
                    $this->sSearchSQL .= " and dt.code = '" . $this->aSearch['code'] . "'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and dt.name = '" . $this->aSearch['name'] . "'";
                if ($this->aSearch['price'])
                    $this->sSearchSQL .= " and dt.price = '" . $this->aSearch['price'] . "'";
            } else {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and dt.id like '%" . $this->aSearch['id'] . "%'";
                if ($this->aSearch['code'])
                    $this->sSearchSQL .= " and dt.code like '%" . $this->aSearch['code'] . "%'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and dt.name like '%" . $this->aSearch['name'] . "%'";
                if ($this->aSearch['price'])
                    $this->sSearchSQL .= " and dt.price like '%" . $this->aSearch['price'] . "%'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and dt.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and dt.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and dt.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and dt.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }

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
		Base::$sText.=$oTable->getTable();
		$this->AfterIndex();
	}
}
?>