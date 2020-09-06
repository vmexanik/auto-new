<?
require_once (SERVER_PATH . '/class/core/Admin.php');
class AProviderVirtual extends Admin {
	//-----------------------------------------------------------------------------------------------
	function AProviderVirtual() {
		$this->sTableName = 'provider_virtual';
		$this->sTablePrefix = 'pv';
		$this->sAction = 'provider_virtual';
		$this->sWinHead = Language::getDMessage ( 'Provider Virtual' );
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField = array ();
		$this->Admin ();

		$this->sBeforeAddMethod='BeforeAdd';
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex ();
		require_once (SERVER_PATH . '/class/core/Table.php');
		$oTable = new Table ( );
		$oTable->aColumn = array ();
		$oTable->aColumn ['id'] = array ('sTitle' => 'Id', 'sOrder' => 'pv.id' );
		$oTable->aColumn ['name'] = array ('sTitle' => 'Provider', 'sOrder' => 'up.name' );
		$oTable->aColumn ['name_virtual'] = array ('sTitle' => 'Provider Virtual', 'sOrder' => 'up1.name' );
		$this->initLocaleGlobal ();
		$oTable->aColumn ['action'] = array ();
		$this->SetDefaultTable ( $oTable );
		Base::$sText .= $oTable->getTable ();
		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAdd() {
		Base::$tpl->assign('aProvider',Base::$db->getAssoc(
		"    select up.id_user, concat(up.id_user,' ',up.name) name "
		."\n from user_provider as up ")
		);
	}
}
?>