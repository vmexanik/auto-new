<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class APermission extends Admin {

	//-----------------------------------------------------------------------------------------------
	function APermission() {
		$this->sTableName='permission_action';
		$this->sTablePrefix='pa';
		$this->sAction='permission';
		$this->sWinHead=Language::getDMessage('Permission');
		$this->sPath=Language::GetDMessage('>>Configuration > ');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
			'id'=>array('sTitle'=>'Id','sOrder'=>'id'),
			'pa_action'=>array('sTitle'=>'Action','sOrder'=>'pa_action'),
			'deny'=>array('sTitle'=>'Allowed', 'sOrder'=>'deny'),
			'action'=>array(),
		);

		$aData['id_user'] = Base::$aRequest['id'];
		Base::$tpl->assign('id_user', Base::$aRequest['id']);
		$this->SetDefaultTable($oTable, $aData);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}

	/*public function AfterIndex() {
		$aRow['id_user'] = Base::$aRequest['id'];
		parent::AfterIndex();
	}*/
	//-----------------------------------------------------------------------------------------------
	public function Deny() {
		if (!Base::$aRequest['deny']){
			$aRes['id_user'] = Base::$aRequest['id_user'];
			$aRes['action'] = Base::$aRequest['s_action'];
			Base::$db->AutoExecute ( 'permission_deny', $aRes, 'INSERT', false, true, true );
		}else{
			Base::$db->Execute("delete from permission_deny where
									id_user='".Base::$aRequest['id_user']."' and
									action='".Base::$aRequest['s_action']."'");
		}
		parent::AdminRedirect (Base::$aRequest['return']);
	}
}
?>