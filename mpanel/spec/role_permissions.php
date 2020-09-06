<?php
/**
 * @author Roman Dehtyarov
 *
 */

require_once(SERVER_PATH.'/mpanel/spec/user.php');
class ARolePermissions extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sAction = 'role_permission';
		$this->sWinHead = Language::getDMessage ( 'Roles permissions' );
		$this->sPath = Language::GetDMessage('>>role >');
		$sNotLinkGroup = Db::GetOne('select name from role_action_group where id=1');
		Base::$tpl->assign('aGroupList', array('1' => $sNotLinkGroup) + Base::$db->getAssoc("select id, name from role_action_group where id!=1 order by name"));
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();
		
		if(Base::$aRequest['mod']=='edit') {
			$aData=DB::GetRow("select * from role_action where id=".Base::$aRequest['id_role_action']);
			Base::$tpl->assign('aData', $aData);
			Base::$sText.=Base::$tpl->fetch('mpanel/role_permission/form_edit.tpl');
		}
		else {
			if(Base::$aRequest['mod']=='save') {
				Db::Execute("update role_action set action_name='".Base::$aRequest['data']['action_name']."', 
					action_description='".Base::$aRequest['data']['action_description']."',
					id_role_group=".Base::$aRequest['data']['id_role_group']." 
					where id=".Base::$aRequest['data']['id']);
			}
			
			if(Base::$aRequest['id_permission']){
				Db::Execute('delete from role_permissions where id='.Base::$aRequest['id_permission']);
			}
			
			if(Base::$aRequest['id_exeption']){
				$aPermissions = Db::GetRow("Select * from role_action where id=".Base::$aRequest['id_exeption']);
				if ($aPermissions) {
					Db::Execute('delete from role_action where id='.Base::$aRequest['id_exeption']);
					Db::Execute("insert into role_action_exeption (action_name,is_exeption) VALUES
						('".$aPermissions['action_name']."',1) on duplicate key update 
						is_exeption=values(is_exeption)");
				}
			}
			
			if(Base::$aRequest['id_role']){
				Db::Execute("INSERT INTO role_permissions (`id_role` ,`id_action`)
							VALUES ('".Base::$aRequest['id_role']."', '".Base::$aRequest['id_action']."')");
			}		

			$aRoleActionGroup = Db::GetAll('select * from role_action_group where id!=1 order by name');

			if($aRoleActionGroup) foreach($aRoleActionGroup as $aGroup){
				$oTable=new Table();
				$oTable->aColumn['name']=array('sTitleNT'=>$aGroup['name']);
				$oTable->aColumn['roles']=array('sTitle'=>'Roles');
				$oTable->aColumn['action']=array();
				$oTable->sTemplateName = 'admin_new.tpl';
				$oTable->bStepperVisible = 0;
				$oTable->iRowPerPage = 100;
				$oTable->sSql = Base::GetSql("RoleAction",array(
				'id_role_group'=>$aGroup['id']
				));
				$oTable->aCallback=array($this,'CallParseRoles');
				$oTable->sDataTemplate = 'mpanel/role_permission/row_role_permission.tpl';

				Base::$sText.=$oTable->getTable();
			}
			// not link roles
			$sNotLinkGroup = Db::GetOne('select name from role_action_group where id=1');
			$oTable=new Table();
			$oTable->aColumn['name']=array('sTitleNT'=>$sNotLinkGroup);
			$oTable->aColumn['roles']=array('sTitle'=>'Roles');
			$oTable->aColumn['action']=array();
			$oTable->sTemplateName = 'admin_new.tpl'; 
			$oTable->bStepperVisible = 0;
			$oTable->iRowPerPage = 100;
			$oTable->sSql = Base::GetSql("RoleAction",array(
					'id_role_group'=>1,
					'order' => 'ra.action_name'
			));
			$oTable->sDataTemplate = 'mpanel/role_permission/row_not_link.tpl';
			$oTable->aCallback=array($this,'CallParseRoles');
			
			Base::$sText.=$oTable->getTable();
				
		}

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseRoles(&$aItem)
	{
		foreach ($aItem as $sKey => $aValue) {
			$aAssignedRoles = Db::GetAll('select rn.name, rn.id, rp.id as id_permission from role_name rn
			left join role_permissions rp on rp.id_role = rn.id
			left join role_action ra on rp.id_action = ra.id
			where ra.id ='.$aValue['id']);
			$aItem[$sKey]['assigned_roles'] = $aAssignedRoles;
			$sNotIn = '';
			if($aAssignedRoles){
				$sNotIn = 'where rn.id not in(';
					foreach($aAssignedRoles as $role){
						$sNotIn.=$role['id'].',' ;
					}
				$sNotIn = substr($sNotIn, 0, mb_strlen($sNotIn)-1);
				$sNotIn.=')';
			}	
			$aNotAssignedRoles = Db::GetAll('select rn.id , rn.name from role_name rn '.$sNotIn);
			$aItem[$sKey]['not_assigned_roles'] = $aNotAssignedRoles;
		}
	}
}
?>