<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class AAttachment extends Admin {
    private $sSessionKey = 'attachment';
    
	//-----------------------------------------------------------------------------------------------
	public function __construct() {
		$this->sTableName='attachment';
		$this->sTablePrefix='at';
		$this->sAction='attachment';
		$this->sWinHead=Language::getDMessage('Attachment');
		$this->sPath=Language::GetDMessage('>>Content > '.$sWinHead);
		
		if (isset(Base::$aRequest['click_from_menu']))
		if (isset(Base::$aRequest['owner_name']) && isset(Base::$aRequest['id'])){
			$_SESSION[$this->sSessionKey]['owner_name'] = Base::$aRequest['owner_name'];
			$_SESSION[$this->sSessionKey]['owner_code'] = self::GetOwnerCode(Base::$aRequest['owner_name'], Base::$aRequest['id']);
		}
		parent::__construct();
	}
	//-----------------------------------------------------------------------------------------------
	public static function GetOwnerCode($sName,$sId) {
		switch (strtolower($sName)) {
			case 'distribution': 
				$sCode = 'distr_'.$sId;
			  	break;
			default:
				$sCode = $sName.'_'.$sId;
		}
		return $sCode;
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
	
		if (isset($_SESSION[$this->sSessionKey]['owner_code'])) {
			$aWhereData['where'].=" and at.owner_code='".$_SESSION[$this->sSessionKey]['owner_code']."'";
		}

		$this->sWinHead.=' > '.$_SESSION[$this->sSessionKey]['owner_name'];
		Base::$tpl->assign('sOwner_Name', $_SESSION[$this->sSessionKey]['owner_name']);
		
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn = array ();
		$oTable->aColumn['id']=array('sTitle'=>'Id','sOrder'=>'at.id');
		$oTable->aColumn['owner_code']=array('sTitle'=>'Owner Code','sOrder'=>'at.owner_code');
		$oTable->aColumn['attach_file']=array('sTitle'=>'Attach File','sOrder'=>'at.attach_file');
		$oTable->aColumn['action']=array();
		$this->SetDefaultTable($oTable,$aWhereData);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign ( &$aData ) {
		if (!isset($aData['owner_code'])) { 
			$aData['owner_code'] = $_SESSION[$this->sSessionKey]['owner_code']; 
		}
	}
	//-----------------------------------------------------------------------------------------------
}
?>
