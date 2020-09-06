<?

require_once(SERVER_PATH.'/class/core/Admin.php');
class AFormValue extends Admin {

	//-----------------------------------------------------------------------------------------------
	function AFormValue() {
		$this->sSqlPath = 'Form/Value';
		$this->sTableName='form_value';
		$this->sTablePrefix='fv';
		$this->sAction='form_value';
		$this->sWinHead=Language::getDMessage('Form Value');
		$this->sPath=Language::GetDMessage('>>Configuration >');
		$this->aCheckField=array('caption','num');
		$this->sBeforeAddMethod = "BeforeAdd";
		$this->Admin();
	}

	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();
		$this->initLocaleGlobal ();
		require_once(SERVER_PATH.'/class/core/Table.php');
		$oTable=new Table();
		$oTable->aColumn=array(
			'id'=>array('sTitle'=>'Id','sOrder'=>'fv.id'),
			'caption'=>array('sTitle'=>'Caption','sOrder'=>'fv.caption'),
			'num'=>array('sTitle'=>'Num','sOrder'=>'fv.num'),
			'lang'=>array('sTitle'=>'Lang'),
			'action'=>array(),
		);

		$aData['where'] = "and fv.id_form = '".Base::$aRequest['id_form']."' and fv.id_item = '".Base::$aRequest['id_item']."'";
		$this->SetDefaultTable($oTable, $aData);
		Base::$sText.=$oTable->getTable();
		$this->AfterIndex();
	}

	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign(&$aData) {
		$aParam = $this->getAssocFromReturn();
		$aData['id_form'] = !$aData['id_form'] ? $aParam['id_form'] : $aData['id_form'];
		$aData['id_item'] = !$aData['id_item'] ? $aParam['id_item'] : $aData['id_item'];

		$res = Base::$db->getOne("select max(num) from form_value where id_form='".$aData['id_form']."' and id_item='".$aData['id_item']."'");
		$aData['num'] = !$aData['num']  ? $res+1 : $aData['num'];
	}

	//-----------------------------------------------------------------------------------------------
	public function getAssocFromReturn(){
		$res = array();
		$arr = explode("form_value",Base::$aRequest['return']);
		if ($arr && count($arr) > 1){
			$param = explode("&", $arr[1]);
			if ($param){
				for($i=0; $i<count($param); $i++){
					$id = explode("=", $param[$i]);
					if ($id && count($id)>1)
						$res[$id[0]] = $id[1];
				}
			}
		}
		return  $res;
	}
}

?>