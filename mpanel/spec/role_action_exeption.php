<?php
/**
 * @author Vladimir Fedorov
 *
 */

require_once(SERVER_PATH.'/mpanel/spec/user.php');
class ARoleActionExeption extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName='role_action_exeption';
		$this->sTablePrefix='re';
		$this->sAction = 'role_action_exeption';
		$this->sWinHead = Language::getDMessage ( 'Exeption action' );
		$this->sPath = Language::GetDMessage('>>role >');
		$this->Admin ();
	}
	
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();
		
		if(Base::$aRequest['id'])
			Db::Execute('delete from role_action_exeption where id='.Base::$aRequest['id']);
	
		$oTable=new Table();
		$oTable->sSql = Base::GetSql("RoleActionExeption").' order by re.action_name';
		$oTable->aColumn['id']=array('sTitle'=>'Id');
		$oTable->aColumn['action_name']=array('sTitle'=>'Action','sOrder'=>'re.action_name');
		$oTable->aColumn['is_exeption']=array('sTitle'=>'is_exeption','sOrder'=>'re.is_exeption');
		$oTable->aColumn['action']=array();
		//$oTable->aCallback = array($this,'CallParse');
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
		
	}
	//-----------------------------------------------------------------------------------------------
	public function SetExeption()
	{
		if (Base::$aRequest['row_check']) {
			Db::Execute("Update role_action_exeption set is_exeption=1 where id in (".implode(',',Base::$aRequest['row_check']).")");
			$aActions = Db::getAssoc("Select action_name, id from role_action_exeption where id in (".implode(',',Base::$aRequest['row_check']).")");
			if ($aActions) {
				$aIdActionPermissions = Db::getAssoc("Select id as key_,id from role_action where action_name in ('".implode("','",array_keys($aActions))."')");
				if ($aIdActionPermissions)
					Db::Execute("Delete from role_permissions where id_action in ('".implode("','",array_keys($aIdActionPermissions))."')");
				Db::Execute("Delete from role_action where action_name in ('".implode("','",array_keys($aActions))."')");
			}
		}
		$this->AdminRedirect ( $this->sAction );
	}
	//-----------------------------------------------------------------------------------------------
	public function UnSetExeption()
	{
		if (Base::$aRequest['row_check']) {
			Db::Execute("Update role_action_exeption set is_exeption=0 where id in (".implode(',',Base::$aRequest['row_check']).")");
		}
		$this->AdminRedirect ( $this->sAction );
	}
	//-----------------------------------------------------------------------------------------------
	public function Rebuild()
	{
		$directory=SERVER_PATH."/spec/";
		if ($dh = opendir($directory)) {
			while (($file = readdir($dh)) !== false) {
				if ($file != "." && $file != ".." && strpos($file,'.php')!==false) {
					if (filetype($directory . $file)=="file") {
						unset($sPrefix);unset($aMatches);
						if ($file=='catalog_manager.php') {
							$i=1;
						}
						$sFileData = file_get_contents(SERVER_PATH."/spec/".$file);
						$sPattern="!sPrefix(.*?)\'(.*?)\'!si";
						preg_match_all($sPattern,$sFileData,$aMatches);
						if ($aMatches[2][0])
							$sPrefix=$aMatches[2][0];
						else {
							$sPattern="!sPreffix(.*?)\'(.*?)\'!si";
							preg_match_all($sPattern,$sFileData,$aMatches);
						}
						if ($aMatches[2][0])
							$sPrefix=$aMatches[2][0];
						
						if (!$sPrefix || $sPrefix[strlen($sPrefix)-1]!='_') {
							$file_name_array=preg_split("/\.php/",$file);
							$file_name=$file_name_array[0];
							$sPrefix=$file_name."_";
						}
						$sPattern="!case(.*?)\"(.*?)\"!si";
						preg_match_all($sPattern,$sFileData,$aMatches);
						if (!$aMatches[2][0]) {
							$sPattern="!case(.*?)\'(.*?)\'!si";
							preg_match_all($sPattern,$sFileData,$aMatches);
						}
						// main action
						$sMainAction = $sPrefix; 
						if ($sMainAction[strlen($sMainAction)-1]=='_')
							$sMainAction = substr($sMainAction, 0, -1);

						$iExist = Db::getOne("Select action_name from role_action_exeption where action_name='".$sMainAction."'");
						if (!$iExist)
							Db::Execute("insert into role_action_exeption (action_name) VALUES 
								('".$sMainAction."')");
						if ($aMatches[2][0]) {
							foreach ($aMatches[2] as $sValue) {
								$iExist = Db::getOne("Select action_name from role_action_exeption where action_name='".$sPrefix.$sValue."'");
								if (!$iExist)
									Db::Execute("insert ignore into role_action_exeption (action_name) VALUES
									('".$sPrefix.$sValue."') on duplicate key update action_name=values(action_name)");
							}
						}
					}
				}
			}
			closedir($dh);
		}
		
		$this->AdminRedirect ( $this->sAction );
	}
	//-----------------------------------------------------------------------------------------------
	public function MovePermissionsList()
	{
		if (Base::$aRequest['row_check']) {
			//Db::Execute("Update role_action_exeption set is_exeption=1 where id in (".implode(',',Base::$aRequest['row_check']).")");
			$aActions = Db::getAssoc("Select action_name, id from role_action_exeption where id in (".implode(',',Base::$aRequest['row_check']).")");
			if ($aActions) {
				foreach ($aActions as $sActionName => $iId)
					Db::Execute("Insert into role_action set action_name='".$sActionName.
						"' on duplicate key update action_name=values(action_name)");
				
				Db::Execute("Delete from role_action_exeption where id in (".implode(',',Base::$aRequest['row_check']).")"); 
						
			}
		}
		$this->AdminRedirect ( $this->sAction );
	}
}