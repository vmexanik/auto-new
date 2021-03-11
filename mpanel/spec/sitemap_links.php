<?
class ASitemapLinks extends Admin {
	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'sitemap_links';
		$this->sTablePrefix = 'sl';
		$this->sAction = 'sitemap_links';
		$this->sWinHead = Language::getDMessage ('sitemap_links');
		$this->sPath = Language::GetDMessage('>>Content >');
		$this->aCheckField = array("url");
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex ();
		require_once (SERVER_PATH . '/class/core/Table.php');

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong', 0)) {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and sl.id = '" . $this->aSearch['id'] . "'";
                if ($this->aSearch['url'])
                    $this->sSearchSQL .= " and sl.url = '" . $this->aSearch['url'] . "'";
            } else {
                if ($this->aSearch['id'])
                    $this->sSearchSQL .= " and sl.id like '%" . $this->aSearch['id'] . "%'";
                if ($this->aSearch['url'])
                    $this->sSearchSQL .= " and sl.url like '%" . $this->aSearch['url'] . "%'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and sl.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and sl.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and sl.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and sl.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }
		
		$oTable = new Table ( );
		$oTable->aColumn = array ();
		$oTable->aColumn ['id'] = array ('sTitle' => 'Id', 'sOrder' => $this->sTablePrefix.'.id' );
		$oTable->aColumn ['url'] = array ('sTitle' => 'Url', 'sOrder' => $this->sTablePrefix.'.url');
		$oTable->aColumn ['visible'] = array ('sTitle' => 'Visible', 'sOrder' => $this->sTablePrefix.'.visible');
		$oTable->aColumn ['action'] = array ();
		
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();
		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>