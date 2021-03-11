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

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong', 0)) {
                if ($this->aSearch['amount'])
                    $this->sSearchSQL .= " and d.amount = '" . $this->aSearch['amount'] . "'";
                if ($this->aSearch['discount'])
                    $this->sSearchSQL .= " and d.discount = '" . $this->aSearch['discount'] . "'";
            } else {
                if ($this->aSearch['amount'])
                    $this->sSearchSQL .= " and d.amount like '%" . $this->aSearch['amount'] . "%'";
                if ($this->aSearch['discount'])
                    $this->sSearchSQL .= " and d.discount like '%" . $this->aSearch['discount'] . "%'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and d.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and d.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and d.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and d.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
		'amount' => array('sTitle'=>'Amount', 'sOrder'=>'d.amount'),
		'discount' => array('sTitle'=>'Discount', 'sOrder'=>'d.discount'),
		'visible' => array('sTitle'=>'Visible', 'sOrder'=>'d.visible'),
		'action' => array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText .=$oTable->getTable();

		$this->AfterIndex();
	}
}

?>