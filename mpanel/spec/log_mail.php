<?

/**
 * @author Mikhail Starovoyt
 *
 */

class ALogMail extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName = 'mail_delayed';
		$this->sTablePrefix = 'lm';
		$this->sAction = 'log_mail';
		$this->sWinHead = Language::getDMessage('Mail Queue');
		$this->sPath = Language::GetDMessage('>>Logs >');
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex ();

		//--------------------
		Base::$sText .= $this->SearchForm ();
		if ($this->aSearch) {
			if (Language::getConstant('mpanel_search_strong',0)) {
				if ($this->aSearch['subject'])	$this->sSearchSQL .= " and md.subject = '".$this->aSearch['subject']."'";
				if ($this->aSearch['address'])	$this->sSearchSQL .= " and md.address = '".$this->aSearch['address']."'";
			}
			else {
				if ($this->aSearch['subject'])	$this->sSearchSQL .= " and md.subject like '%".$this->aSearch['subject']."%'";
				if ($this->aSearch['address'])	$this->sSearchSQL .= " and md.address like '%".$this->aSearch['address']."%'";
			}
			if ($this->aSearch['date_from'])
			$this->sSearchSQL .= " and md.post_date>='".DateFormat::FormatSearch($this->aSearch['date_from'])."' ";
			if ($this->aSearch['date_to'])
			$this->sSearchSQL .= " and md.post_date<='".DateFormat::FormatSearch($this->aSearch['date_to'])."'";
		}
		//--------------------

		$oTable = new Table ( );
		$oTable->aColumn = array (
		'id' => array ('sTitle' => 'Id', 'sOrder' => 'md.id' )
		, 'address' => array ('sTitle' => 'Address', 'sOrder' => 'md.address' )
		, 'from' => array ('sTitle' => 'From', 'sOrder' => 'md.from' )
		, 'subject' => array ('sTitle' => 'Subject', 'sOrder' => 'md.subject' )
		, 'post' => array ('sTitle' => 'Post/Sent', 'sOrder' => 'md.post_date' )
		, 'priority' => array ('sTitle' => 'priority', 'sOrder' => 'md.priority' )
		, 'action' => array () );
		$this->SetDefaultTable ( $oTable );
		Base::$sText .= $oTable->getTable ();

		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Preview() {
		Base::$oResponse->addAssign ( 'sub_menu', 'innerHTML', '' );
		$iId = ( int ) Base::$aRequest ['id'];
		Base::$tpl->assign ( 'aData', Base::$db->GetRow ( "SELECT * FROM `mail_delayed` WHERE `id`='$iId'" ) );
		Base::$tpl->assign ( 'sReturn', stripslashes ( Base::$aRequest ['return'] ) );
		Base::$sText .= Base::$tpl->fetch ( 'mpanel/log_mail/preview.tpl' );
		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------


}

?>