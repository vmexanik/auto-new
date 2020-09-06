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