<?
require_once (SERVER_PATH . '/class/core/Admin.php');
class ABill extends Admin {

	//-----------------------------------------------------------------------------------------------
	function ABill() {
		$this->sTableName = 'bill';
		$this->sTablePrefix = 'b';
		$this->sAction = 'bill';
		$this->sWinHead = Language::getDMessage ( 'Customer Bills' );
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField = array ('amount');
		$this->Admin ();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
		'id' => array('sTitle'=>'Id', 'sOrder'=>'b.id'),
		'login' => array('sTitle'=>'User', 'sOrder'=>'u.login'),
		'amount' => array('sTitle'=>'Amount', 'sOrder'=>'b.amount'),
		'id_invoice' => array('sTitle'=>'Invoice', 'sOrder'=>'b.id_invoice'),
		'code_template' => array('sTitle'=>'Code Template', 'sOrder'=>'b.code_template'),
		'post' => array('sTitle'=>'Post', 'sOrder'=>'b.post'),
		'action' => array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}

	public function BeforeAddAssign($aData) {
		$aUserList = Base::$db->getAssoc("select id, login from user where visible='1' and type_='customer' order by login");
		if($aUserList) {
			Base::$tpl->assign ( 'aUserList', $aUserList );
			Base::$tpl->assign ( 'sUserSelected', $aData['id_user'] );
		}
			//Base::$tpl->assign ( 'sUserSelected', 4326 );
		$aTemplateList = Base::$db->getAssoc("select code, concat(code, ' ', name) as name from template where type_='bill'");
		if($aTemplateList) {
			Base::$tpl->assign ( 'aTemplateList', $aTemplateList );
			Base::$tpl->assign ( 'sTemplateSelected', $aData['code_template'] );
		}
	}

}

?>