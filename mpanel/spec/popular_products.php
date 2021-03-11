<?
/**
 * @author 
 *
 */
class APopularProducts extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName = 'popular_products';
		$this->sTablePrefix = 'p';
		$this->sAction = 'popular_products';
		$this->sWinHead = Language::getDMessage('PopularProducts');
		$this->sPath = Language::GetDMessage('>>Content >');
		$this->aCheckField = array ('name','zzz_code');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong', 0)) {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and p.id = '" . $this->aSearch['id'] . "'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and p.name = '" . $this->aSearch['name'] . "'";
                if ($this->aSearch['zzz_code'])
                    $this->sSearchSQL .= " and p.zzz_code = '" . $this->aSearch['zzz_code'] . "'";
                if ($this->aSearch['old_price'])
                    $this->sSearchSQL .= " and p.old_price = '" . $this->aSearch['old_price'] . "'";
                if ($this->aSearch['bage'])
                    $this->sSearchSQL .= " and p.bage = '" . $this->aSearch['bage'] . "'";
            } else {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and p.id like '%" . $this->aSearch['id'] . "%'";
                if ($this->aSearch['zzz_code'])
                    $this->sSearchSQL .= " and p.zzz_code like '%" . $this->aSearch['zzz_code'] . "%'";
                if ($this->aSearch['name'])
                    $this->sSearchSQL .= " and p.name like '%" . $this->aSearch['name'] . "%'";
                if ($this->aSearch['old_price'])
                    $this->sSearchSQL .= " and p.old_price like '%" . $this->aSearch['old_price'] . "%'";
                if ($this->aSearch['bage'])
                    $this->sSearchSQL .= " and p.bage like '%" . $this->aSearch['bage'] . "%'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and p.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and p.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and p.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and p.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }

		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=> array('sTitle'=>'Id', 'sOrder'=>'p.id'),
		'name' => array('sTitle'=>'name', 'sOrder'=>'p.name'),
		'zzz_code' => array('sTitle'=>'zzz_code', 'sOrder'=>'p.zzz_code'),
		'old_price'=>array('sTitle'=>'old_price','sOrder'=>'p.old_price'),
		'image'=>array('sTitle'=>'Image','sOrder'=>'p.image'),
		'bage'=>array('sTitle'=>'bage','sOrder'=>'p.bage'),
		'visible' => array('sTitle'=>'visible', 'sOrder'=>'p.visible'),
		'action' => array(),
		);
				
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData)
	{	
		Base::$tpl->assign('aBage',array(
			"0"=>Language::GetMessage("not selected"),
			"recommend"=>Language::GetMessage("badge recommend"),
			"new"=>Language::GetMessage("badge new"),
			"action"=>Language::GetMessage("badge action"),
		));
	}
	//-----------------------------------------------------------------------------------------------
}
?>