<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class ADirectoryConfig extends Admin {

	//-----------------------------------------------------------------------------------------------
	function ADirectoryConfig() {
		$this->sTableName='directory_config';
		$this->sAction='directory_config';
		$this->sWinHead=Language::getDMessage('DirectoryConfig');
		$this->sPath=Language::GetDMessage('>>Directory >');
		//$this->aCheckField=array('name','code');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		$aData=Base::$db->getRow("select * from directory_config where 1=1");
		Base::$tpl->assign('aData',$aData);
		$aDisplaySelect=array(
			'5'=>'5',
			'10'=>'10',
			'20'=>'20',
			'50'=>'50',
			'100'=>'100',
		);
		Base::$tpl->assign('aDisplaySelect',$aDisplaySelect);
		$aOrderWay=array(
			'asc'=>Language::getDMessage('Asc'),
			'desc'=>Language::getDMessage('Desc'),
		);
		Base::$tpl->assign('aOrderWay',$aOrderWay);
		Base::$sText.=Base::$tpl->fetch('mpanel/'.$this->sAction.'/form_add.tpl');

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
}
?>