<?
/**
 * @author 
 *
 */
class ABanner extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName = 'banner';
		$this->sTablePrefix = 'b';
		$this->sAction = 'banner';
		$this->sWinHead = Language::getDMessage('Caorusel');
		$this->sPath = Language::GetDMessage('>>Content >');
		$this->aCheckField = array ('name','link','image');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong',0)) {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and b.id = '".$this->aSearch['id']."'";
                if ($this->aSearch['name'])	$this->sSearchSQL .= " and b.name = '".$this->aSearch['name']."'";
                if ($this->aSearch['link'])	$this->sSearchSQL .= " and b.link = '".$this->aSearch['link']."'";
            }
            else {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and b.id like '%".$this->aSearch['id']."%'";
                if ($this->aSearch['name'])	$this->sSearchSQL .= " and b.name like '%".$this->aSearch['name']."%'";
                if ($this->aSearch['link'])	$this->sSearchSQL .= " and b.link like '%".$this->aSearch['link']."%'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and b.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and b.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and b.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and b.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }

		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=> array('sTitle'=>'Id', 'sOrder'=>'b.id'),
		'name' => array('sTitle'=>'name', 'sOrder'=>'b.name'),
		'link' => array('sTitle'=>'link', 'sOrder'=>'b.link'),
		'image'=>array('sTitle'=>'Image','sOrder'=>'b.image'),
		'visible' => array('sTitle'=>'visible', 'sOrder'=>'b.visible'),
		'action' => array(),
		);
				
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>