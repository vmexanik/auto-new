<?

/**
 * @author Mikhail Starovoyt
 *
 */

class ADropDownAdditional extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName = 'drop_down_additional';
		$this->sTablePrefix = 'dda';
		$this->sAction = 'drop_down_additional';
		$this->sWinHead = Language::getDMessage('drop down additional');
		$this->sPath = Language::GetDMessage('>>Content >');
		$this->aCheckField = array('url');
		//$this->aFCKEditors = array('description');
		//$this->sAddonPath='addon/';
		$this->sSqlPath='CoreDropDownAdditional';
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex ();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong',0)) {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and dda.id = '".$this->aSearch['id']."'";
                if ($this->aSearch['url'])	$this->sSearchSQL .= " and dda.url = '".$this->aSearch['url']."'";
                if ($this->aSearch['title'])	$this->sSearchSQL .= " and dda.title = '".$this->aSearch['title']."'";
            }
            else {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and dda.id like '%".$this->aSearch['id']."%'";
                if ($this->aSearch['url'])	$this->sSearchSQL .= " and dda.url like '%".$this->aSearch['url']."%'";
                if ($this->aSearch['link'])	$this->sSearchSQL .= " and dda.link like '%".$this->aSearch['link']."%'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and dda.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and dda.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and dda.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and dda.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }

		$oTable=new Table();
		//$this->initLocaleGlobal ();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'dda.id'),
		'url'=>array('sTitle'=>'Url','sOrder'=>'dda.url'),
		'title'=>array('sTitle'=>'Title','sOrder'=>'dda.title'),
		'visible'=>array('sTitle'=>'Visible','sOrder'=>'dda.visible'),
		//'language' => array ('sTitle'=>'Lang'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>