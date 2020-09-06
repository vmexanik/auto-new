<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class AFormItem extends Admin {

	//-----------------------------------------------------------------------------------------------
	function AFormItem() {
		$this->sSqlPath = 'Form/Item';
		$this->sTableName='form_item';
		$this->sTablePrefix='fi';
		$this->sAction='form_item';
		$this->sWinHead=Language::getDMessage('Form Item');
		$this->sPath=Language::GetDMessage('>>Configuration >');
		$this->aCheckField=array('caption','type','num');
		//$this->sBeforeAddMethod='BeforeAdd';
		$this->Admin();
	}


	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();
		$this->initLocaleGlobal ();
		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
			'id'=>array('sTitle'=>'Id','sOrder'=>'fi.id'),
			'caption'=>array('sTitle'=>'Caption','sOrder'=>'fi.caption'),
			'type'=>array('sTitle'=>'Type','sOrder'=>'fi.type'),
			'num'=>array('sTitle'=>'Num','sOrder'=>'fi.num'),
			'values'=>array('sTitle'=>'Values'),
			'lang'=>array('sTitle'=>'Lang'),
			'action'=>array(),
		);

		$aData['where'] = "and fi.id_form = '".Base::$aRequest['id_form']."'";
		$this->SetDefaultTable($oTable, $aData);
		Base::$sText.=$oTable->getTable();
		$this->AfterIndex();
	}

	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData) {
		$typeInput = array(
		   'input' 				=> 'Text Field',
		   'textarea'			=> 'Text Area',
		   'checkbox' 			=> 'Check Box',
		   'multiple_checkbox'	=> 'Multiple Check Box',
		   'select' 			=> 'Drop Down List',
		   'email_select' 		=> 'Email Drop Down List',
		   'separator' 			=> 'Separator',
		   );
		Base::$tpl->assign('typeInput', $typeInput);

		// Prepare data for form
		$aData['type']  = !$aData['type'] ? "select" : $aData['type'];
		$formId = explode("&id_form=", Base::$aRequest['return']);
		$fId = $formId && count($formId)>1 ? $formId[1] : "";

		$res = Base::$db->getOne("select max(num) from form_item where id_form='".$fId."'");
		$aData['num'] = !$aData['num']  ? $res+1 : $aData['num'];

		$aData['id_form'] = !$aData['id_form'] ? $fId : $aData['id_form'];
	}
	//-----------------------------------------------------------------------------------------------
	// we need delete the items and value for this form
	public function Delete() {
		$sql = "delete from form_value where id_form='" . Base::$aRequest ['id_form'] ."' and id_item='".Base::$aRequest ['id']."'";
		Base::$db->Execute ($sql);
		parent::Delete();
	}
}
?>