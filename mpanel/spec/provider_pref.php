<?
require_once (SERVER_PATH . '/class/core/Admin.php');
class AProviderPref extends Admin {
	//-----------------------------------------------------------------------------------------------
	function AProviderPref() {
		$this->sTableName = 'provider_pref';
		$this->sTablePrefix = 'pp';
		$this->sAction = 'provider_pref';
		$this->sWinHead = Language::getDMessage ( 'Provider Pref' );
		$this->sPath = Language::GetDMessage('>>Users >');
		$this->aCheckField = array ("id_user_provider","pref");
		$this->Admin ();

		$this->sBeforeAddMethod='BeforeAdd';
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex ();
		require_once (SERVER_PATH . '/class/core/Table.php');
		$oTable = new Table ( );
		$oTable->aColumn = array ();
		$oTable->aColumn ['id'] = array ('sTitle' => 'Id', 'sOrder' => 'pp.id' );
		$oTable->aColumn ['name'] = array ('sTitle' => 'Provider', 'sOrder' => 'up.name' );
		$oTable->aColumn ['pref'] = array ('sTitle' => 'Pref', 'sOrder' => 'pp.pref' );
		$oTable->aColumn ['name_to'] = array ('sTitle' => 'Name', 'sOrder' => 'pp.name_to' );
		//$oTable->aColumn ['mail_to'] = array ('sTitle' => 'Mail', 'sOrder' => 'pp.mail_to' );
		$oTable->aColumn ['subject'] = array ('sTitle' => 'Subject', 'sOrder' => 'pp.subject' );
		$this->initLocaleGlobal ();
		$oTable->aColumn ['action'] = array ();
		$this->SetDefaultTable ( $oTable );
		Base::$sText .= $oTable->getTable ();
		$this->AfterIndex ();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAdd() {
		
		Base::$tpl->assign('aProvider',array("")+Base::$db->getAssoc("
		select up.id_user, concat(up.name,'::',up.id_user) name 
	 	from user_provider as up where is_auction=1
	 	order by up.name
		"));

		Base::$tpl->assign('aPref',array(""=>"","neoriginal_all"=>"neoriginal_all")+Base::$db->getAssoc("
		select c.pref, concat(c.title,'::',c.pref) name 
		from cat as c
		order by c.title
		"));
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeApply() {
		if (Base::$aRequest ['data']['pref']=="neoriginal_all") 
		{
			Db::Execute("
			insert into provider_pref (id_user_provider, pref, mail_to, name_to, subject, neoriginal_all)
			select '".Base::$aRequest ['data']['id_user_provider']."'
			, pref
			, '".Base::$aRequest ['data']['mail_to']."'
			, '".Base::$aRequest ['data']['name_to']."'
			, '".Base::$aRequest ['data']['subject']."'			
			, 1
			from cat where is_brand=0
			on duplicate key update id_user_provider=values(id_user_provider)
			, pref=values(pref), mail_to=values(mail_to), subject=values(subject),  neoriginal_all=1
			");
			
			Db::Execute("delete from provider_pref where (pref='neo' or pref is null)");
					
		}
	}
}
?>