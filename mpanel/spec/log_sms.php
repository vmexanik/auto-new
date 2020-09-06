<?

require_once (SERVER_PATH . '/class/core/Admin.php');
class ALogSms extends Admin {

	//-----------------------------------------------------------------------------------------------
	function ALogSms() {
		$this->sTableName = 'sms_delayed';
		$this->sTablePrefix = 'ls';
		$this->sAction = 'log_sms';
		$this->sWinHead = Language::getDMessage('Sms Queue');
		$this->sPath = Language::GetDMessage('>>Logs >');
		$this->aCheckField = array ('number', 'message' );
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex ();

		require_once (SERVER_PATH . '/class/core/Table.php');
		$oTable = new Table ( );
		$oTable->aColumn = array ('id' => array ('sTitle' => 'Id', 'sOrder' => 'ls.id' ), 'number' => array ('sTitle' => 'Number', 'sOrder' => 'ls.number' ), 'message' => array ('sTitle' => 'Message', 'sOrder' => 'ls.message' ), 'post' => array ('sTitle' => 'Post', 'sOrder' => 'ls.post' ), 'sent_time' => array ('sTitle' => 'Sent', 'sOrder' => 'ls.sent_time' ) );
		$this->SetDefaultTable ( $oTable );
		Base::$sText .= $oTable->getTable ();

		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeApply() {
		require_once SERVER_PATH . '/class/core/Sms.php';
		Base::$aRequest ['data'] ['post'] = time ();
		Base::$aRequest ['data'] ['message'] = mb_substr ( Base::$aRequest ['data'] ['message'], 0, 70 );
		Base::$aRequest ['data'] ['number'] = Sms::FormatNumber ( Base::$aRequest ['data'] ['number'] );
	}
	//-----------------------------------------------------------------------------------------------


}

?>