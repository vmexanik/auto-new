<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class AForm extends Admin {

	//-----------------------------------------------------------------------------------------------
	function AForm() {
		$this->sSqlPath = 'Form/Form';
		$this->sTableName='form';
		$this->sTablePrefix='f';
		$this->sAction='form';
		$this->sWinHead=Language::getDMessage('Contact Form');
		$this->sPath=Language::GetDMessage('>>Configuration >');
		$this->aCheckField=array('name','code','caption', 'active');
//		$this->aChildTable = array(
//			array('sTableName'=>'form_item', 'sTablePrefix'=>'fi', 'sTableId'=>'id_form'),
//			array('sTableName'=>'form_value', 'sTablePrefix'=>'fv', 'sTableId'=>'id_form'),
//		);
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();
		$this->initLocaleGlobal ();
		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
			'id'=>array('sTitle'=>'Id','sOrder'=>'f.id'),
			'name'=>array('sTitle'=>'Name','sOrder'=>'f.name'),
			'code'=>array('sTitle'=>'Code','sOrder'=>'f.code'),
			'caption'=>array('sTitle'=>'Caption','sOrder'=>'f.caption'),
			'active'=>array('sTitle'=>'Active','sOrder'=>'f.active'),
			'to_email'=>array('sTitle'=>'To Email','sOrder'=>'f.to_email'),
			'item'=>array('sTitle'=>'Items Number', 'sOrder'=>'item_count'),
			'lang'=>array('sTitle'=>'Lang'),
			'action'=>array(),
		);

		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}

	//-----------------------------------------------------------------------------------------------
	// we need delete the items and value for this form
	public function Delete() {
		$aChildTable = array(
			array('sTableName'=>'form_item', 'sTablePrefix'=>'fi', 'sTableId'=>'id_form'),
			array('sTableName'=>'form_value', 'sTablePrefix'=>'fv', 'sTableId'=>'id_form'),
		);

		foreach ($aChildTable as $aTable){
			$sql = "delete from " . $aTable['sTableName'] . " where " . $aTable['sTableId'] . "='" . Base::$aRequest ['id'] ."'";
			Base::$db->Execute ($sql);
		}
		parent::Delete();
	}
}
?>