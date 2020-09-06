<?

require_once (SERVER_PATH . '/class/core/Admin.php');
class AComment extends Admin {

	//-----------------------------------------------------------------------------------------------
	function AComment() {
		$this->sTableName = 'comment';
		$this->sTablePrefix = 'c';
		$this->sAction = 'comment';
		$this->sWinHead = Language::getDMessage('Comments');
		$this->sPath = Language::GetDMessage('>>Content >' );
		$this->aCheckField = array('section', 'name', 'content');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex ();

		require_once (SERVER_PATH . '/class/core/Table.php');
		$oTable = new Table ( );
		$oTable->aColumn = array (
		'id' => array ('sTitle' => 'Id', 'sOrder' => 'c.id' ),
		'section' => array ('sTitle' => 'Section', 'sOrder' => 'c.section' ),
		'ref_id' => array ('sTitle' => 'RefId', 'sOrder' => 'c.ref_id' ),
		'name' => array ('sTitle' => 'CommentName', 'sOrder' => 'c.name' ),
		'visible' => array ('sTitle' => 'Visible', 'sOrder' => 'c.visible' ),
		'content' => array ('sTitle' => 'Content' , 'sOrder' => 'c.content'),
		'action' => array () );
		$this->SetDefaultTable ( $oTable );
		Base::$sText .= $oTable->getTable ();

		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
}
?>