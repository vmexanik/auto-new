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

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong',0)) {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and cm.id = '".$this->aSearch['id']."'";
                if ($this->aSearch['brand'])	$this->sSearchSQL .= " and cm.brand = '".$this->aSearch['brand']."'";
                if ($this->aSearch['name'])	$this->sSearchSQL .= " and cm.name = '".$this->aSearch['name']."'";
                if ($this->aSearch['description'])	$this->sSearchSQL .= " and cm.description = '".$this->aSearch['description']."'";
                if ($this->aSearch['id_mfa'])	$this->sSearchSQL .= " and cm.id_mfa = '".$this->aSearch['id_mfa']."'";
                if ($this->aSearch['visible'])	$this->sSearchSQL .= " and cm.visible = '".$this->aSearch['visible']."'";
            }
            else {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and cm.id like '%".$this->aSearch['id']."%'";
                if ($this->aSearch['brand'])	$this->sSearchSQL .= " and cm.brand like '%".$this->aSearch['brand']."%'";
                if ($this->aSearch['name'])	$this->sSearchSQL .= " and cm.name like '%".$this->aSearch['name']."%'";
                if ($this->aSearch['description'])	$this->sSearchSQL .= " and cm.description like '%".$this->aSearch['description']."%'";
                if ($this->aSearch['id_mfa'])	$this->sSearchSQL .= " and cm.id_mfa like '%".$this->aSearch['id_mfa']."%'";
                if ($this->aSearch['visible'])	$this->sSearchSQL .= " and cm.visible like '%".$this->aSearch['visible']."%'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and cm.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and cm.visible = '0'";
            //with else "ignore" will not be found
            switch($this->aSearch['visible']){
                case '1':
                    $this->sSearchSQL.=" and c.visible>='1'";
                    break;
                case '0':
                    $this->sSearchSQL.=" and c.visible>='0'";
                    break;
                case  '':
                    break;
            }
        }

		$this->initLocaleGlobal();
		$oTable=new Table();
		$oTable->aColumn ['id']=array('sTitle'=>'Id','sOrder'=>$this->sTablePrefix.'.id');
		//$oTable->aColumn ['code']=array('sTitle'=>'Code','sOrder'=>$this->sTablePrefix.'.code');
		$oTable->aColumn ['brand']=array('sTitle'=>'Brand','sOrder'=>$this->sTablePrefix.'.brand');
		$oTable->aColumn ['name']=array('sTitle'=>'Name','sOrder'=>$this->sTablePrefix.'.name');
		$oTable->aColumn ['image']=array('sTitle'=>'Image');
		$oTable->aColumn ['description']=array('sTitle'=>'Description');
		$oTable->aColumn ['id_mfa']=array('sTitle'=>'id_mfa','sOrder'=>$this->sTablePrefix.'.id_mfa');
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