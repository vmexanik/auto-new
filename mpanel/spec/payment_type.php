<?

/**
 * @author Mikhail Starovoyt
 *
 */

class APaymentType extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName='payment_type';
		$this->sTablePrefix='dt';
		$this->sAction = 'payment_type';
		$this->sWinHead = Language::GetDMessage('payment Type');
		$this->sPath = Language::GetDMessage('>>Content >');
		$this->aCheckField=array('name');
		//$this->aFCKEditors=array('description','end_description');
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong', 0)) {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and pt.id = '" . $this->aSearch['id'] . "'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and pt.name = '" . $this->aSearch['name'] . "'";
                if ($this->aSearch['url'])
                    $this->sSearchSQL .= " and pt.url = '" . $this->aSearch['url'] . "'";
                if ($this->aSearch['description'])
                    $this->sSearchSQL .= " and pt.description = '" . $this->aSearch['description'] . "'";
                if ($this->aSearch['num'])
                    $this->sSearchSQL .= " and pt.num = '" . $this->aSearch['num'] . "'";
            } else {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and pt.id like '%" . $this->aSearch['id'] . "%'";
                if ($this->aSearch['url'])
                    $this->sSearchSQL .= " and pt.url like '%" . $this->aSearch['url'] . "%'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and pt.name like '%" . $this->aSearch['name'] . "%'";
                if ($this->aSearch['description'])
                    $this->sSearchSQL .= " and pt.description like '%" . $this->aSearch['description'] . "%'";
                if ($this->aSearch['num'])
                    $this->sSearchSQL .= " and pt.num like '%" . $this->aSearch['num'] . "%'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and pt.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and pt.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and pt.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and pt.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }

		$this->initLocaleGlobal();
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=> array('sTitle'=>'Id', 'sOrder'=>'pt.id'),
		'name' => array('sTitle'=>'Name', 'sOrder'=>'pt.name'),
		'url' => array('sTitle'=>'Url', 'sOrder'=>'pt.url'),
		'description' => array('sTitle'=>'Description' , 'sOrder'=>'pt.description'),
		'visible' => array('sTitle'=>'Visible', 'sOrder'=>'pt.visible'),
		'num' => array('sTitle'=>'Num' ,'sOrder'=>'pt.num'),
		'lang' => array ('sTitle' => 'Lang'),
		'action' => array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>