<?php
/**
 * @author Roman Dehtyarov
 *
 */

require_once(SERVER_PATH.'/mpanel/spec/user.php');
class ARoleManager extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName='role_name';
		$this->sTablePrefix='rm';
		$this->sAction = 'role_manager';
		$this->sWinHead = Language::getDMessage ( 'Managers roles' );
		$this->sPath = Language::GetDMessage('>>role >');
		$this->Admin ();
	}
	
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();
		
		if(Base::$aRequest['action']=='role_manager' && Base::$aRequest['id']){
			$iExistPermissions=Db::getOne("Select count(*) from role_permissions where id_role=".Base::$aRequest['id']);
			if (!$iExistPermissions) {
				Db::Execute('delete from role_name where id='.Base::$aRequest['id']);
			}
			else {
				$sError = Language::GetDMessage('This role exist set permissions');
			}
		}
	
		$oTable=new Table();
		$oTable->aColumn['id']=array('sTitle'=>'Id');
		$oTable->aColumn['name']=array('sTitle'=>'Name');
		$oTable->aColumn['description']=array('sTitle'=>'Description');
		$oTable->aColumn['action']=array();
		if ($sError) {
			$oTable->sTableMessage = $sError;
			$oTable->sTableMessageClass="warning_p";
		}
		$oTable->aCallback = array($this,'CallParse');
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
		
		/*if ($sError)
			Admin::Message('MT_WARNING',$sError);*/
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParse(&$aItem)
	{
		foreach ($aItem as $sKey => $aValue) {
			$iAssignedPermissions = Db::GetOne('select count(*) from role_permissions rp
			where rp.id_role ='.$aValue['id']);
			if ($iAssignedPermissions)
				$aItem[$sKey]['iassigned_permissions'] = $iAssignedPermissions;
		}
	}
	
}