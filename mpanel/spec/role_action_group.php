<?php
/**
 * @author 
 *
 */

require_once(SERVER_PATH.'/mpanel/spec/user.php');
class ARoleActionGroup extends Admin
{
	//-----------------------------------------------------------------------------------------------
	function __construct()
	{
		$this->sTableName='role_action_group';
		$this->sTablePrefix='rag';
		$this->sAction = 'role_action_group';
		$this->sWinHead = Language::getDMessage ( 'role_action_group' );
		$this->sPath = Language::GetDMessage('>>roles >');
		$this->Admin ();
	}
	
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		$this->PreIndex();
		
		if(Base::$aRequest['action']=='role_action_group' && Base::$aRequest['id']){
			Db::Execute('delete from role_action_group where id='.Base::$aRequest['id']);
			Db::Execute("Update role_action set id_role_group=1 where id_role_group=".Base::$aRequest['id']);
		}
		$oTable=new Table();
		$oTable->aColumn['id']=array('sTitle'=>'Id');
		$oTable->aColumn['name']=array('sTitle'=>'Name');
		$oTable->aColumn['action']=array();
		//$oTable->aCallback=array($this,'CallParse');
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	
}