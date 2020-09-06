<?

class ACatModel extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName='cat_model';
		$this->sTablePrefix='cm';
		$this->sAction='cat_model';
		$this->sSqlPath='Cat/Model';
		$this->sWinHead=Language::GetDMessage('Cat Model');
		$this->sPath = Language::GetDMessage('>>Catalog >');
		//$this->aCheckField=array('name');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

		$this->initLocaleGlobal();
		$oTable=new Table();
		$oTable->aColumn ['id']=array('sTitle'=>'Id','sOrder'=>$this->sTablePrefix.'.id');
		//$oTable->aColumn ['code']=array('sTitle'=>'Code','sOrder'=>$this->sTablePrefix.'.code');
		$oTable->aColumn ['brand']=array('sTitle'=>'Brand','sOrder'=>$this->sTablePrefix.'.brand');
		$oTable->aColumn ['name']=array('sTitle'=>'Name','sOrder'=>$this->sTablePrefix.'.name');
		$oTable->aColumn ['image']=array('sTitle'=>'Image');
		$oTable->aColumn ['description']=array('sTitle'=>'Description');
		$oTable->aColumn ['visible']=array('sTitle'=>'Visible','sOrder'=>$this->sTablePrefix.'.visible');
		$oTable->aColumn ['action']=array();
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function Apply() {
		/*$sUploadDir = '/imgbank/temp_upload/mpanel/';
		$sFile = SERVER_PATH.$sUploadDir.Base::$aRequest['data']['upload_img'];
		if (Base::$aRequest['data']['upload_img'] && file_exists($sFile) &&	Base::$aRequest['data']['id_model']) {
			$aCar = TecdocDb::GetModelDetail(Base::$aRequest['data']);
			if ($aCar['mod_mfa_id']) {
				$sFileName=$aCar['mod_mfa_id'].'_'.Base::$aRequest['data']['id_model'].'.jpg';			
				rename ( $sFile, SERVER_PATH.'/imgbank/Image/model/'.$sFileName);
				Base::$aRequest['data']['image']=$sFileName;				
			}
		}*/
	
		parent::Apply ();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign($aData) {
	}
	//-----------------------------------------------------------------------------------------------
}
?>