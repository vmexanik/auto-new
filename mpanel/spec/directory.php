<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class ADirectory extends Admin {

	//-----------------------------------------------------------------------------------------------
	function ADirectory() {
		$this->sTableName='directory';
		$this->sTablePrefix='d';
		$this->sAction='directory';
		$this->sWinHead=Language::getDMessage('Directories');
		$this->sPath=Language::GetDMessage('>>Directory >');
		$this->aCheckField=array('name','description');
		$this->sBeforeAddMethod='BeforeAdd';
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
		'id'=>array('sTitle'=>'Id','sOrder'=>'d.id'),
		'directory_category_name'=>array('sTitle'=>'DirectoryCategoryName','sOrder'=>'directory_category_name'),
		'name'=>array('sTitle'=>'Name','sOrder'=>'d.name'),
		'description'=>array('sTitle'=>'Description','sOrder'=>'d.description'),
		'visible'=>array('sTitle'=>'Visible','sOrder'=>'visible'),
		'action'=>array(),
		);
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAdd() {

		$aRecordSet=Base::$db->getAll("select * from directory_category as dc where 1=1");
		foreach ($aRecordSet as $aItem) {$aDirectoryCategory[$aItem["id"]]=$aItem["name"];};

		Base::$tpl->assign('aDirectoryCategory',$aDirectoryCategory);

		$aType=array(
			'default'=>'default',
			'admin'=>'admin',
		);
		Base::$tpl->assign('aType',$aType);
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckField() {

		if (!strtotime(Base::$aRequest['data']['creation_time'])) {
			Base::$aRequest['data']['creation_time']=time();
		} else {
				Base::$aRequest['data']['creation_time']=strtotime(Base::$aRequest['data']['creation_time']);
		        };

		return Admin::CheckField();
	}
	//-----------------------------------------------------------------------------------------------
}
?>