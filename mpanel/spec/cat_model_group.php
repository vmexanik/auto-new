<?

class ACatModelGroup extends Admin {

	//-----------------------------------------------------------------------------------------------
	function __construct() {
		$this->sTableName='cat_model_group';
		$this->sTablePrefix='cmg';
		$this->sAction='cat_model_group';
		$this->sSqlPath='Cat/ModelGroup';
		$this->sWinHead=Language::GetDMessage('Cat Model group');
		$this->sPath = Language::GetDMessage('>>Catalog >');
		$this->aCheckField=array('name','code','id_make');
		$this->Admin();
	}
	//-----------------------------------------------------------------------------------------------
	public function Index() {
		$this->PreIndex();

        Base::$sText .= $this->SearchForm ();
        if ($this->aSearch) {
            if (Language::getConstant('mpanel_search_strong',0)) {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and cmg.id = '".$this->aSearch['id']."'";
                if ($this->aSearch['brand'])	$this->sSearchSQL .= " and c.title = '".$this->aSearch['brand']."'";
                if ($this->aSearch['name'])	$this->sSearchSQL .= " and cmg.name = '".$this->aSearch['name']."'";
                if ($this->aSearch['code'])	$this->sSearchSQL .= " and cmg.code = '".$this->aSearch['code']."'";
                if ($this->aSearch['id_models'])	$this->sSearchSQL .= " and cmg.id_models = '".$this->aSearch['id_models']."'";
                if ($this->aSearch['visible'])	$this->sSearchSQL .= " and cmg.visible = '".$this->aSearch['visible']."'";
            }
            else {
                if ($this->aSearch['id'])$this->sSearchSQL .= " and cmg.id like '%".$this->aSearch['id']."%'";
                if ($this->aSearch['brand'])	$this->sSearchSQL .= " and c.title like '%".$this->aSearch['brand']."%'";
                if ($this->aSearch['name'])	$this->sSearchSQL .= " and cmg.name like '%".$this->aSearch['name']."%'";
                if ($this->aSearch['code'])	$this->sSearchSQL .= " and cmg.code like '%".$this->aSearch['code']."%'";
                if ($this->aSearch['id_models'])	$this->sSearchSQL .= " and cmg.id_models like '%".$this->aSearch['id_models']."%'";
                if ($this->aSearch['visible'])	$this->sSearchSQL .= " and cmg.visible like '%".$this->aSearch['visible']."%'";
            }
            if ($this->aSearch['visible']=='1')	$this->sSearchSQL .= " and cmg.visible = '1'";
            if ($this->aSearch['visible']=='0')	$this->sSearchSQL .= " and cmg.visible = '0'";
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
		$oTable->aColumn ['brand']=array('sTitle'=>'Brand','sOrder'=>'c.title');
		$oTable->aColumn ['name']=array('sTitle'=>'Name','sOrder'=>$this->sTablePrefix.'.name');
		$oTable->aColumn ['code']=array('sTitle'=>'Code','sOrder'=>$this->sTablePrefix.'.code');
		$oTable->aColumn ['id_models']=array('sTitle'=>'Id models','sOrder'=>$this->sTablePrefix.'.id_models');
		$oTable->aColumn ['visible']=array('sTitle'=>'Visible','sOrder'=>$this->sTablePrefix.'.visible');
		$oTable->aColumn ['action']=array();
		$this->SetDefaultTable($oTable);
		Base::$sText.=$oTable->getTable();

		$this->AfterIndex();
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeAddAssign($aData) {
		$aCat = Base::$db->getAssoc("select id, title from cat where is_brand=1 and visible=1 order by name");
		Base::$tpl->assign ( 'aCat', $aCat );
		Base::$tpl->assign ( 'sCatSelected', $aData['id_make'] );
		
		$aDataAll=TecdocDb::GetModels(array('id_make'=>$aData['id_make']));
		if ($aDataAll) {
			$aModels=array();
			foreach ($aDataAll as $aValue) {
				$aModels[$aValue['mod_id']]=trim($aValue['name']);
			}
			Base::$tpl->assign('aModels',$aModels);
		
			$aModels=explode(",", $aData['id_models']);
			if ($aModels){
				$aModelsPreview=array();
				foreach($aModels as $sValue) $aModelsPreview[$sValue]=$sValue;
			}
			Base::$tpl->assign('aModelsPreview',$aModelsPreview);
		}
		
		$this->sScriptForAdd="$('#select_model').select2();";
	}
	//-----------------------------------------------------------------------------------------------
	public function BeforeApply() {
		$aModels=Base::$aRequest['data']['id_models_selected'];
		if ($aModels){
			Base::$aRequest['data']['id_models']=implode(',', $aModels);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function GenerateGroups()
	{
		if(!$iMake) Db::Execute("truncate table cat_model_group");
		if($iMake) $sWhere=" and id='".$iMake."'";
		
		$aCat=Db::GetAll("select name,id from cat where is_brand=1 and visible=1".$sWhere." order by id");

        $aCatFotInsert=array_combine(array_column($aCat,'name'),array_column($aCat,'id'));

		foreach($aCatFotInsert as $iIdMake) {
			$aDataAll=TecdocDb::GetModels(array('id_make'=>$iIdMake,'where' => " and (m.DateEnd is null or m.DateEnd='' or substr(m.DateEnd,4,4) >= ".Language::getConstant('start_year_model',1980).')'));
			//$aDataAll=TecdocDb::GetModels(array('id_make'=>$iIdMake));
			if ($aDataAll) {
				$aModel=array();
				$aModelAll=array();
				foreach ($aDataAll as $aValue) {
					$sNameModel=trim($aValue['name']);
					$sName=substr($sNameModel,0,strpos($sNameModel,' '));
					if(!$sName) $sName=Content::Translit($sNameModel);
					$aModelAll[$sName]['id'][$aValue['mod_id']]=$aValue['mod_id'];
				}
				$aModel=array();
				foreach ($aModelAll as $sKey=>$aValue) {
					$aModel[$sKey]=implode(",", $aValue['id']);
				}
				foreach ($aModel as $sKey => $sModelsId){
					$sCode=mb_strtolower(
					    str_replace('/', '',
					    str_replace(',', '',
					    str_replace('&', '', 
						str_replace(' ', '', 
						str_replace('-', '_', 
						str_replace('+', '_plus',$sKey)))))),"UTF-8");
					
					$sCode=Content::Translit($sCode);
					
					if(preg_replace("/\D/","",$sCode)==$sCode) $sCode=$sCode."_";

					// скрыть из списка модели без модификаций
					$iCnt = TecdocDb::GetOne("SELECT count(*) FROM ".DB_OCAT."`cat_alt_models` m 
					    inner join ".DB_OCAT."cat_alt_types t on t.ID_mod = m.ID_mod 
					    WHERE m.`ID_src` in (".$sModelsId.")");
					$iVisible = 0;
					if ($iCnt)
					    $iVisible=1;
					
					$aData=array(
					    "id_make"=>$iIdMake,
					    "id_models"=>$sModelsId,
					    "name"=>$sKey,
					    "code"=>$sCode,
					    "visible"=>$iVisible
					);
					Db::AutoExecute("cat_model_group",$aData);
				}
			}
		}
		$this->AdminRedirect ( $this->sAction );
	}
	//-----------------------------------------------------------------------------------------------
}
?>