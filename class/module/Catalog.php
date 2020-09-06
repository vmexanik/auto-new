<?
/**
 * @author Aleksandr Starovoyt
 */
class Catalog extends Base
{
	var $sPrefix="catalog";
	var $sPref;
	var $aCode=array();
	var $aCodeCross=array();
	var $aItemCodeCross=array();
	var $aExt=array(1=>"bmp", 2=>'pdf', 3=>'jpg', 4=>'jpg', 5=>'png');
	var $sPathToFile="/imgbank/";
	var $bShowSeparator=true;
	
	var $aCat=array();
	var $aCats=array();
	var $aModel=array();
	var $aModelDetail=array();
	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
	    if (Base::$aData['global_class'] && Base::$aData['global_class']=='price_control')
	        return;
	    
		if (Language::$sLocale=='en') {
			Db::Execute("SET @lng_id = 4");
		} else {
			Db::Execute("SET @lng_id = 16");
		}
		Db::Execute("SET @cou_id = 187");
		Base::$tpl->assign('aMake', array(""=>Language::getMessage('choose make'))+Db::GetAssoc("Assoc/Cat",array(
		'is_brand'=>1,
		'is_main'=>1,
		)));
		Base::$bRightSectionVisible=true;
		Base::$bXajaxPresent=true;
		
// 		if (Base::$aRequest['action']!='price_group_filter') {
		    Rubricator::CheckSelectedAuto();
		    Rubricator::CheckSelectedAutoName();
		    Rubricator::ClearAutoUrl();
// 		}
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
	    MultiLanguage::Redirect("/rubricator/");
	    
// 		Base::$oContent->AddCrumb(Language::GetMessage('Catalog product'),'');

// 		Base::$tpl->assign('aCatalog', Db::GetAssoc("Assoc/Cat",array(
// 		    "multiple"=>1,
// 		    "is_brand"=>1,
// 		    "is_main"=>1,
// 		)));
// 		Base::$sText.=Base::$tpl->fetch("home/index.tpl");
	}
	//-----------------------------------------------------------------------------------------------
	public function ViewModel()
	{
		$this->aCat=Db::GetRow("select * from cat where name='".Base::$aRequest['cat']."'");
		Base::$aRequest['data']['id_tof']=$this->aCat['id_tof'];
		
		if(strpos($_SERVER['REQUEST_URI'],'/?')!==FALSE && count(Base::$aRequest)<=3 && Base::$aRequest['cat']) {
			$sUrl='/cars/'.Base::$aRequest['cat'];
			if(Base::$aRequest['step']>0)$sUrl.='='.Base::$aRequest['step'];
			Base::Redirect($sUrl);
		}
		if (Base::$aRequest['cat']) {
		    if($this->aCat['id']) Base::$aRequest['data']['id_make']=$this->aCat['id'];
			else Base::$aRequest['data']['id_make']=Db::GetOne("select id from cat where name='".Base::$aRequest['cat']."'");
			if (!Base::$aRequest['data']['id_make'])
				Base::Redirect('/missing/');
		}
		elseif (Base::$aRequest['cat_id']) {
		    Base::$aRequest['data']['id_make'] = Base::$aRequest['cat_id'];
		}
		$_REQUEST['data']['id_make']=Base::$aRequest['data']['id_make'];

		$sSelectedCatName=Db::GetOne("select title from cat where name='".Base::$aRequest['cat']."'");
		Base::$oContent->AddCrumb(Language::GetMessage('Catalog product'),'/pages/catalog/');
		Base::$oContent->AddCrumb($sSelectedCatName,'');
		
		Rubricator::GetModels();


// 		//show rubricator
// 		$aRubric=Db::GetAssoc(str_replace("select","select r.id as key_, ",Base::GetSql("Rubricator",array('visible'=>1))));
// 		//filter rubric by auto
// 		if(Base::$aRequest['data']['id_model_detail'] && Base::$aRequest['data']['id_model'] && Base::$aRequest['data']['id_make']) {
// 		    $aTree=TecdocDb::GetTree(Base::$aRequest['data']);
// 		    if ($aTree) foreach ($aTree as $sKey => $aValue) {
// 		        if($aValue['id']=='13771' || $aValue['str_id_parent']=='13771') {
// 		            unset($aTree[$sKey]);
// 		            continue;
// 		        }
// 		        $aTreeAssoc[$aValue['id']]=$aValue;
// 		    }
// 		}
// 		if($aTreeAssoc) {
// 		    $aAllowedTreeNodes=array_keys($aTreeAssoc);
		
// 		    $aMenu=array();
// 		    foreach ($aRubric as $aValue) {
// 		        if($aValue['level']==1) $aMenu[$aValue['id']]=$aValue;
// 		        else continue;
// 		    }
		
// 		    foreach ($aRubric as $aValue) {
// 		        if($aValue['level']==2) {
// 		            //filter by auto
// 		            $bAllow=false;
// 		            $aRubricTree=explode(",", $aValue['id_tree']);
// 		            if($aRubricTree) foreach ($aRubricTree as $iTreeNode) {
// 		                if(in_array($iTreeNode, $aAllowedTreeNodes)) {
// 		                    $bAllow=true;
// 		                    break;
// 		                }
// 		            }
		
// 		            if($bAllow) {
// 		                $aMenu[$aValue['id_parent']]['childs'][]=$aValue;
// 		            }
// 		        }
// 		        else continue;
// 		    }
// 		} else {
// 		    $aMenu=array();
// 		    foreach ($aRubric as $aValue) {
// 		        if($aValue['level']==1) $aMenu[$aValue['id']]=$aValue;
// 		        else continue;
// 		    }
		
// 		    foreach ($aRubric as $aValue) {
// 		        if($aValue['level']==2) {
// 		            $aMenu[$aValue['id_parent']]['childs'][]=$aValue;
// 		        }
// 		        else continue;
// 		    }
// 		}
		
// 		if($aMenu) {
// 		    //sort by num
		
// 		    usort($aMenu, function ($a, $b)
// 		    {
// 		        if ($a['sort'] == $b['sort']) {
// 		            return 0;
// 		        }
// 		        return ($a['sort'] < $b['sort']) ? -1 : 1;
// 		    });
// 		}
		
// 	    Base::$tpl->assign('sSelectedCarUrlRubricator',"c/".Base::$aRequest['cat']);
		
	    Base::$sText.="<h1>".Language::GetMessage('spareparts')." ".$sSelectedCatName."</h1>";
// 		Base::$tpl->assign('aMainRubric', $aMenu);
// 		Base::$sText.=Base::$tpl->fetch('rubricator/index.tpl');
		
		$aModels=Db::GetAll("select * from cat_model_group where visible=1 and id_make='".Base::$aRequest['data']['id_make']."' order by name");
		if ($aModels) foreach ($aModels as $sKey => $aValue){
// 		    $aModels[$sKey]['models']=TecdocDb::GetModels(array(
// 		        "id_make"=>$aValue['id_make'],
// 		        "id_models"=>$aValue['id_models'],
// 		        "is_hide"=>1
// 		    ));
		
		    $aModels[$sKey]['count']=count($aModels[$sKey]['models']);
		    $aModels[$sKey]['seourl']="/cars/".Base::$aRequest['cat']."/".$aModels[$sKey]['code']."/";
// 		    $this->CallParseModel($aModels[$sKey]['models']);
		}
		Base::$tpl->assign("aModels", $aModels);
		if(count($aModels)==0) {
		    Form::Error404();
		} else {
		    Base::$aRequest['car_select']['brand'] = Base::$aRequest['cat'];
		    Base::$sText.=Base::$tpl->fetch('catalog/model_group.tpl');
		}
		
		Content::SetMetaTagsPage('model_list:',array(
		    'brand' => $this->aCat['name'],
		));
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseModel(&$aItem, $bSeparator=false)
	{
		if ($aItem) {
			foreach($aItem as $key => $aValue) {
				$aItem[$key]['seourl']=Content::CreateSeoUrl('catalog_detail_model_view',array(
				'cat'=>$this->aCat['name'],
				'data[id_make]'=>Base::$aRequest['data']['id_make'],
				'data[id_model]'=>$aValue['mod_id'],
				'model_translit'=>Content::Translit($aValue['name'])
				));
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ViewModelGroup()
	{
	    if(strpos($_SERVER['REQUEST_URI'],'/?')!==FALSE && count(Base::$aRequest)<=3 && Base::$aRequest['cat']) {
	        $sUrl='/cars/'.Base::$aRequest['cat'];
	        if(Base::$aRequest['step']>0)$sUrl.='='.Base::$aRequest['step'];
	        Base::Redirect($sUrl);
	    }
	    if (Base::$aRequest['cat']) {
		    if($this->aCat['id']) Base::$aRequest['data']['id_make']=$this->aCat['id'];
			else Base::$aRequest['data']['id_make']=Db::GetOne("select id from cat where name='".Base::$aRequest['cat']."'");
			if (!Base::$aRequest['data']['id_make'])
				Base::Redirect('/missing/');
		}
		elseif (Base::$aRequest['cat_id']) {
		    Base::$aRequest['data']['id_make'] = Base::$aRequest['cat_id'];
		}
		$_REQUEST['data']['id_make']=Base::$aRequest['data']['id_make'];
	    if(Base::$aRequest['model_group'] && Base::$aRequest['data']['id_make']){
	        $aModelGroup = Db::GetRow("SELECT * FROM cat_model_group WHERE code like '".Base::$aRequest['model_group']."' and id_make = '".Base::$aRequest['data']['id_make']."'");
	        Base::$aRequest['data']['id_model'] = $aModelGroup['id_models'];
	    }
	    if (!Base::$aRequest['data']['id_make'] || !Base::$aRequest['data']['id_model']) {
	        Form::Error404(true);
	    }else{
	        Base::$tpl->assign('sH1Assigned',Language::GetMessage('Autodetail').' '.Db::GetOne("select title from cat where name='".Base::$aRequest['cat']."'").' '.$aModelGroup['name']);
	    }
	    //AKD-82
	    Base::$aRequest['car_select']['brand'] = Base::$aRequest['cat'];
	    Base::$aRequest['car_select']['model'] = Base::$aRequest['model_group'];
	    //JPN-267
	    $sSizeStr = strlen(Base::$aRequest['model_group']);
	    if(Base::$aRequest['model_group']{$sSizeStr-1} == '_')
	        Base::$aRequest['car_select']['model'] = substr(Base::$aRequest['model_group'],0 , $sSizeStr-1);
	    else
	        Base::$aRequest['car_select']['model'] = Base::$aRequest['model_group'];
	    
	    $aCat=Db::GetAssoc("select c.id_tof,c.id from cat as c where c.visible=1");
	    $aCatTitles=Db::GetAssoc("select c.id_tof,c.title from cat as c where c.visible=1");
	    $aSelectedModelGroup=Db::GetRow("select * from cat_model_group where code='".Base::$aRequest['model_group']."' and id_make='".Base::$aRequest['data']['id_make']."' ");
	
	    Base::$tpl->assign('model_preselected', trim(str_replace(strtoupper(Base::$aRequest['cat']), "", $aSelectedModelGroup['name'])));
	    Base::$aRequest['car_select']['name_model_group'] = $aSelectedModelGroup['name'];
	    
	    //pre selected model begin
	    $aModelNew=array(
	        $aSelectedModelGroup['id_models']=>$aSelectedModelGroup['name']
	    );
	    Base::$tpl->assign('aModel',$aModelNew);
	    $_REQUEST['data']['id_model']=$aSelectedModelGroup['id_models'];
	    Base::$aRequest['data']['id_model']=$aSelectedModelGroup['id_models'];
	    Rubricator::GetModelDetails();
	    //pre selected model end
	    
	    if ($aSelectedModelGroup) {
	        $aModels=TecdocDb::GetModels(array(
	            "id_make"=>$aSelectedModelGroup['id_make'],
	            "id_models"=>$aSelectedModelGroup['id_models'],
	            "is_hide"=>1
	        ));
	        if(empty($aModels)){
	            Base::Redirect("/cars/".Base::$aRequest['cat']."/");
	        }
	        
	        $aModelDetailsTmp=TecdocDb::GetModelDetails(array(
	            "join_visible"=>1,
	            "id_make"=>$aSelectedModelGroup['id_make'],
	            "id_model"=>$aSelectedModelGroup['id_models']
	        ),$aCat,$aCatTitles);
	        if($aModelDetailsTmp) {
	            $aModelDetails=array();
	            foreach ($aModelDetailsTmp as $aValueDetail) {
	                $aModelDetails[$aValueDetail['mod_id']][]=$aValueDetail;
	            }
	            unset($aModelDetailsTmp);
	        }
	        
	        if ($aModels) foreach($aModels as $sKey => $aModel){
	            $aModels[$sKey]['seourl']=Content::CreateSeoUrl('catalog_detail_model_view',array(
	                'data[id_make]'=>$aSelectedModelGroup['id_make'],
	                'data[id_model]'=>$aModel['mod_id'],
	                'model_translit'=>Content::Translit($aModel['name']),
	                'cat'=>Base::$aRequest['cat']
	            ));
	            //get model details
	            $aModels[$sKey]['model_details']=$aModelDetails[$aModel['mod_id']];
	            $aModels[$sKey]['count']=count($aModels[$sKey]['model_details']);
	        }
	        
	        if ($aModels) foreach($aModels as $sKey => $aModel){
	            if ($aModels[$sKey]['model_details']) foreach($aModels[$sKey]['model_details'] as $sKeyDetail => $aModelDetail) {
	                $aModels[$sKey]['model_details'][$sKeyDetail]['seourl']=Content::CreateSeoUrl('catalog_assemblage_view',array(
	                    'data[id_make]'=>$aSelectedModelGroup['id_make'],
	                    'data[id_model]'=>$aModel['mod_id'],
	                    'data[id_model_detail]'=>$aModelDetail['ID_src'],
	                    'model_translit'=>Content::Translit($aModelDetail['Name']),
	                    'cat'=>Base::$aRequest['cat'],
	                    'model'=>array($aModel['mod_id']=>$aModel),
	                    'model_detail'=>array($aModelDetail['ID_src']=>$aModels[$sKey]['model_details'][$sKeyDetail])
	                ));
	                $aOldBodys = array('Наклонная задняя часть','вездеход закрытый','Вездеход открытый','вэн','кабрио');
	                $aNewBodys = array('хэтчбек','внедорожник','внедорожник','минивэн','кабриолет');
	                $aModels[$sKey]['model_details'][$sKeyDetail]['body']=str_replace($aOldBodys, $aNewBodys, $aModelDetail['body']);
	                $aModels[$sKey]['model_details'][$sKeyDetail]['Fuel'] = strtolower($aModelDetail['Fuel']);
	                $aModels[$sKey]['model_details'][$sKeyDetail]['Fuel'] = str_replace('Дизель', 'дизель', $aModelDetail['Fuel']);
	                $aModels[$sKey]['model_details'][$sKeyDetail]['Engines'] = $aModelDetail['Engines'];
	            }
	        }
	    }
	
	    $sCatTitle=Db::GetOne("select title from cat where name='".Base::$aRequest['cat']."' ");
	    Base::$oContent->AddCrumb('Каталог', '/cars/');
	    Base::$oContent->AddCrumb($sCatTitle, '/cars/'.Base::$aRequest['cat']);
	    Base::$oContent->AddCrumb(strtoupper(Base::$aRequest['car_select']['name_model_group']), '');
	    
	    //show rubricator
	    $aRubric=Db::GetAssoc(str_replace("select","select r.id as key_, ",Base::GetSql("Rubricator",array('visible'=>1))));
	    //filter rubric by auto
	    if(Base::$aRequest['data']['id_model_detail'] && Base::$aRequest['data']['id_model'] && Base::$aRequest['data']['id_make']) {
	        $aTree=TecdocDb::GetTree(Base::$aRequest['data']);
	        if ($aTree) foreach ($aTree as $sKey => $aValue) {
	            if($aValue['id']=='13771' || $aValue['str_id_parent']=='13771') {
	                unset($aTree[$sKey]);
	                continue;
	            }
	            $aTreeAssoc[$aValue['id']]=$aValue;
	        }
	    }
	    if($aTreeAssoc) {
	        $aAllowedTreeNodes=array_keys($aTreeAssoc);
	    
	        $aMenu=array();
	        foreach ($aRubric as $aValue) {
	            if($aValue['level']==1) $aMenu[$aValue['id']]=$aValue;
	            else continue;
	        }
	    
	        foreach ($aRubric as $aValue) {
	            if($aValue['level']==2) {
	                //filter by auto
	                $bAllow=false;
	                $aRubricTree=explode(",", $aValue['id_tree']);
	                if($aRubricTree) foreach ($aRubricTree as $iTreeNode) {
	                    if(in_array($iTreeNode, $aAllowedTreeNodes)) {
	                        $bAllow=true;
	                        break;
	                    }
	                }
	    
	                if($bAllow) {
	                    $aMenu[$aValue['id_parent']]['childs'][]=$aValue;
	                }
	            }
	            else continue;
	        }
	    } else {
	        $aMenu=array();
	        foreach ($aRubric as $aValue) {
	            if($aValue['level']==1) $aMenu[$aValue['id']]=$aValue;
	            else continue;
	        }
	    
	        foreach ($aRubric as $aValue) {
	            if($aValue['level']==2) {
	                $aMenu[$aValue['id_parent']]['childs'][]=$aValue;
	            }
	            else continue;
	        }
	    }
	    
	    if($aMenu) {
	        //sort by num
	    
	        usort($aMenu, function ($a, $b)
	        {
	            if ($a['sort'] == $b['sort']) {
	                return 0;
	            }
	            return ($a['sort'] < $b['sort']) ? -1 : 1;
	        });
	    }
	    
        Base::$tpl->assign('sSelectedCarUrlRubricator',"c/".Base::$aRequest['cat']."_".$aSelectedModelGroup['code']);
	    
        Base::$sText.="<h1>".Language::GetMessage('spareparts')." ".$sCatTitle." ".$aSelectedModelGroup['name']."</h1>";
	    Base::$tpl->assign('aMainRubric', $aMenu);
	    Base::$sText.=Base::$tpl->fetch('rubricator/index.tpl');

	    Base::$tpl->assign("aModelGroups", $aModels);
	    Base::$sText.=Base::$tpl->fetch('catalog/model_group_selected.tpl');
	    
	    Content::SetMetaTagsPage('model_group_list:',array(
	        'brand' => $sCatTitle,
	        'model' => $aSelectedModelGroup['name']
	    ));
	}
	//-----------------------------------------------------------------------------------------------
	public function GetModelPic($aData)
	{
		//if(!$aData['id_make'] && !$aData['id_model']) return false;
		if(!$aData['id_model']) return false;
		unset($aData['id']);
		$sImagePath="/imgbank/Image/model/";
		if (!file_exists(SERVER_PATH."/imgbank/Image/model/")) mkdir(SERVER_PATH."/imgbank/Image/model/", 0775, 1);
		$aData['bNoTecdocName']=1;
		$aModelPic = Db::GetRow("Select * from cat_model where tof_mod_id=".$aData['id_model']);
		//$aModelPic=Db::GetRow(Base::GetSql("ModelPic",$aData));
		if(!isset($aModelPic['image'])){
			/* new format module name file and tablestructure other
			//$handle = @fopen("http://novaton.com.ua/photos_catalog/cars/".$aData['id_make']."_".$aData['id_model']."_F.jpeg", "rb");
			$handle = @fopen("http://mirdetaley.com.ua/imgbank/Image/model/".$aData['id_make']."_".$aData['id_model'].".jpg", "rb");
			if($handle===false) {
				Db::Execute("insert ignore model_pic set
					id_make='".$aData['id_make']."',
					id_model='".$aData['id_model']."',
					image='',
					name='".$aData['name']."'
						");
				return false;
			}
			$sContents = stream_get_contents($handle);
			fclose($handle);
			if($sContents){
				$sFilename = $aData['id_make']."_".$aData['id_model'].'.jpg';
				$handle = fopen(SERVER_PATH.$sImagePath.$sFilename, 'wb');
				fwrite($handle, $sContents);
				fclose($handle);
				$aModelPic['image']=$sImagePath.$sFilename;
				Db::Execute("insert ignore model_pic set
					id_make='".$aData['id_make']."',
					id_model='".$aData['id_model']."',
					image='".$sFilename."',
					name='".$aData['name']."'
						");
			}
			*/
		}else
		if(!$aModelPic['image'] || !file_exists(SERVER_PATH.$aModelPic['image'])){
			$aModelPic['image']='';
		}/*else{
			$aModelPic['image']=$sImagePath.$aModelPic['image'];
		}*/
		return $aModelPic['image'];
	}
	//-----------------------------------------------------------------------------------------------
	private function GetModelDescription($aData)
	{
		if(!$aData['id_model']) return false;
		$aModelPic = Db::GetRow("Select * from cat_model where tof_mod_id=".$aData['id_model']);
		return $aModelPic['description'];
	}
	//-----------------------------------------------------------------------------------------------
	private function GetModelName($aData)
	{
		if(!$aData['id_make'] && !$aData['id_model']) return false;
		unset($aData['id']);
	
	}
	//-----------------------------------------------------------------------------------------------
	public function ViewModelDetail($bShow=false)
	{
		$this->aCat=Db::GetRow("select * from cat where name='".Base::$aRequest['cat']."'");
		Base::$aRequest['data']['id_tof']=$this->aCat['id_tof'];
		Base::$aRequest['data']['id_make']=$this->aCat['id'];
		$this->aCats=Db::GetAssoc("select c.id_tof,c.id from cat as c where c.visible=1");
		$this->aModel=TecdocDb::GetModel(Base::$aRequest['data']);
		
		if(strpos($_SERVER['REQUEST_URI'],'/?')!==FALSE && !Base::$aRequest['data']['art_id']) {
			$sUrl=Content::CreateSeoUrl('catalog_detail_model_view',array(
				'data[id_make]'=>Base::$aRequest['data']['id_make'],
				'data[id_model]'=>Base::$aRequest['data']['id_model'],
			));
			if(Base::$aRequest['step']>0)$sUrl.='='.Base::$aRequest['step'];
			Base::Redirect($sUrl);
		}
		
		$sSelectedCatName=Db::GetOne("select title from cat where name='".Base::$aRequest['cat']."'");
		Base::$oContent->AddCrumb(Language::GetMessage('Catalog product'),'/pages/catalog/');
		Base::$oContent->AddCrumb($sSelectedCatName,'/cars/'.Base::$aRequest['cat'].'/');
		Base::$oContent->AddCrumb($this->aModel['name'],'');

		$aModelAsoc=TecdocDb::GetModelAssoc(
		     array(
		        "id_make"=>Base::$aRequest['data']['id_make'], 
		        "id_tof"=>Base::$aRequest['data']['id_tof'], 
		        "sOrder"=>" order by name"
		     )
		);
		Base::$tpl->assign('aModel', array("")+$aModelAsoc);
		$_REQUEST['data']['id_make']=Base::$aRequest['data']['id_make'];

		$oTable=new Table();
		$oTable->sClass .= " model-detail-table mobile-table";
		
		$oTable->sType='array';
		$oTable->aDataFoTable=TecdocDb::GetModelDetails(Base::$aRequest['data'],$this->aCats);
		
		$oTable->aColumn['name']=array('sTitle'=>'Make','sWidth'=>'55%', 'sClass'=>'cell-name');
		$oTable->aColumn['year']=array('sTitle'=>'Yaer','sWidth'=>'5%', 'sClass'=>'cell-years');
		$oTable->aColumn['power_kw']=array('sTitle'=>'Power<br>KW','sWidth'=>'5%', 'sClass'=>'cell-power-kw');
		$oTable->aColumn['power_hp']=array('sTitle'=>'Power<br>HP','sWidth'=>'5%', 'sClass'=>'cell-power-hp');
		$oTable->aColumn['V']=array('sTitle'=>'V','sWidth'=>'5%', 'sClass'=>'cell-volume');
		$oTable->aColumn['assemblage']=array('sTitle'=>'assemblage','sWidth'=>'5%', 'sClass'=>'cell-body');
		$oTable->aColumn['fuel']=array('sTitle'=>'fuel','sWidth'=>'10%', 'sClass'=>'cell-body');
		$oTable->aColumn['engine']=array('sTitle'=>'engine','sWidth'=>'10%', 'sClass'=>'cell-body');

		$oTable->aCallback=array($this,'CallParseModelDetail');
		$oTable->iRowPerPage=200;
		$oTable->sDataTemplate='catalog/row_modeldetail.tpl';
		Base::$aRequest['return']?$oTable->sButtonTemplate='catalog/button_index.tpl':"";

		Content::SetMetaTagsPage('modification_list:',array(
		    'model' => $this->aModel['name'],
		    'brand' => $this->aCat['name'],
		));
		
		if ($bShow) {
			if(!$this->aModel) $aModel=TecdocDb::GetModel(Base::$aRequest['data']);
			else $aModel=$this->aModel;
			
			if ($aModel) {
				$aModel['image']=Catalog::GetModelPic(Base::$aRequest['data']);
				$aModel['description']=Catalog::GetModelDescription(Base::$aRequest['data']);
				Base::$tpl->assign('aInfo',$aModel); //+$aInfo
				Base::$sText.=Base::$tpl->fetch('catalog/model_detail.tpl');
			}
			Base::$sText.=$oTable->getTable();
		} else {
			return $oTable->GetTable();
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ModelFor($bOutput=false)
	{
	    if(strpos($_SERVER['REQUEST_URI'],'/?')!==FALSE && !Base::$aRequest['data']['art_id']) {
	        $sUrl=Content::CreateSeoUrl('catalog_detail_model_view',array(
	            'data[id_make]'=>Base::$aRequest['data']['id_make'],
	            'data[id_model]'=>Base::$aRequest['data']['id_model'],
	        ));
	        if(Base::$aRequest['step']>0)$sUrl.='='.Base::$aRequest['step'];
	        Base::Redirect($sUrl);
	    }

	    $aCat=Db::GetRow("select * from cat where name='".Base::$aRequest['data']['cat']."' ");
	    Base::$aRequest['data']['pref']=$aCat['pref'];
	    
	    if($aCat['is_cat_virtual']) {
	        $aVirtualPrefs=Db::GetAssoc("select pref, pref as id from cat where id_cat_virtual='".$aCat['id']."' ");
	        if($aVirtualPrefs) {
	            $aVirtualPrefs[$aCat['pref']]=$aCat['pref'];
	            Base::$aRequest['data']['pref_arr']=$aVirtualPrefs;
	        }
	    }
	    
	    Base::$aRequest['data']['art_id']=TecdocDb::GetArt(array(
	        'code'=>Base::$aRequest['data']['code'],
	        'pref'=>Base::$aRequest['data']['pref']
	    ));
	    Base::$aRequest['data']['id_cat_part']=Db::GetOne("select id from cat_part where item_code ='".Base::$aRequest['data']['pref']."_".Base::$aRequest['data']['code']."' ");

		 if(1==1){
	        //select art_id for virtual cat
			$this->aCat=Db::GetRow("select * from cat as c where name='".Base::$aRequest['data']['cat']."' ");
	        if($this->aCat['is_cat_virtual']!=0) {
	            $aVag=Db::getAll("Select c.* from cat c	where c.visible=1 and c.id_cat_virtual=".$this->aCat['id']);
	        
	            if ($aVag) {
	                foreach ($aVag as $aValVag){
	                    $aAs=TecdocDb::GetArt(array(
	                        'code'=>Base::$aRequest['data']['code'],
	                        'id_tof'=>$aValVag['id_tof']
	                    ));
	                    if($aAs > 0) {
    	                    if(Base::$aRequest['data']['art_id']==""&&$aAs!="")
    	                        Base::$aRequest['data']['art_id'].=$aAs;
    	                    elseif ($aAs!="")
    	                        Base::$aRequest['data']['art_id'].=",".$aAs;
	                    }
	                }
	            }
	            unset($aVag);
	        }
	        //Debug::PrintPre(Base::$aRequest['art_id']);
	        if ($this->aCat['id_cat_virtual']!=0) {
	            $aVag=Db::getAll("Select c.* from cat c inner join cat c2 on c2.id = c.id_cat_virtual and c2.visible=1
	    				where c.visible=1 and c.id_cat_virtual=".$this->aCat['id_cat_virtual']);
	            if ($aVag) {
	                foreach ($aVag as $aValVag){
	                    $aAs=TecdocDb::GetArt(array(
	                        'code'=>Base::$aRequest['data']['code'],
	                        'id_tof'=>$aValVag['id_tof']
	                    ));
	                    if($aAs > 0) {
    	                    if(Base::$aRequest['data']['art_id']==""&&$aAs!="")
    	                        Base::$aRequest['data']['art_id'].=$aAs;
    	                    elseif($aAs!="")
    	                        Base::$aRequest['data']['art_id'].=",".$aAs;
	                    }
	                }
	            }
	            unset($aVag);
	        }
	        //Debug::PrintPre(Base::$aRequest);
	    }
	    if (Base::$aRequest['make']) {
	    	$aCatMake = Db::getRow("Select * from cat where name='".Base::$aRequest['make']."' and visible=1");	 
	    }
	     
		if(!$bOutput) {
			Base::$oContent->AddCrumb(Language::GetMessage('Catalog product'),'/rubricator');
			Base::$oContent->AddCrumb($aCat['title']." - ".Base::$aRequest['data']['code'],'/buy/'.Base::$aRequest['data']['cat'].'_'.Base::$aRequest['data']['code']);
			$sAdd='';
			if ($aCatMake)
				$sAdd=" (  бренд ".$aCatMake['title'].")";    
			Base::$oContent->AddCrumb('Применяемость для '.Base::$aRequest['data']['cat'].' '.Base::$aRequest['data']['code'].$sAdd,'');
		}

        $aTmpAplicability = TecdocDb::GetApplicability(Base::$aRequest['data']);
        $aAplicability = array();
        
        if ($aCatMake) {
        	$aTmp = $aTmpSort = array();
        	foreach ($aTmpAplicability as $aVal) {
        		if ($aVal['cat']==$aCatMake['name']) {
	        		$aTmp[] = $aVal;
	        		$aTmpSort[] = $aVal['name'];
        		}
        	}
        	array_multisort ($aTmpSort, SORT_ASC, SORT_STRING, $aTmp);
        	$aTmpAplicability = $aTmp;
        }
        
        if (Base::$aRequest['step']) {
            $pStepMin = Base::$aRequest['step'];
            $pStepMax = Base::$aRequest['step'] + 1;
            
            $pMinItemPosition = ($pStepMin*10);
            $pMaxItemPosition = ($pStepMax*10)-1;
            
            foreach ($aTmpAplicability as $sKey=>$sValue) {
                if ($sKey>=$pMinItemPosition && $sKey<=$pMaxItemPosition) {
                    $aAplicability[$sKey] = $sValue;
                }
                if ($sKey>$pMaxItemPosition) {
                    $aAplicability = array_values($aAplicability);
                    break;
                }
            }
        } else {
        	if (Base::$aRequest['action']=='catalog_part_info_view') {
        		$aAplicability = array();$aTmp=array();
        		foreach ($aTmpAplicability as $aVal)
        			if (!$aAplicability[$aVal['cat']]) {
        				$aAplicability[$aVal['cat']] = $aVal;
        				$aTmp[] = $aVal['cat'];
        			}
        		$aAplicability = array_values($aAplicability);
        		array_multisort ($aTmp, SORT_ASC, SORT_STRING, $aAplicability);        			
        	}
        	else
            	$aAplicability = $aTmpAplicability;
        }
        
	    $oTable=new Table();
	    $oTable->sClass .= " model-detail-table mobile-table";
	    $oTable->sType='array';
		$oTable->aDataFoTable=$aAplicability;
	    $oTable->aColumn['name']=array('sTitle'=>'Make','sWidth'=>'55%', 'sClass'=>'cell-name');
	    $oTable->aColumn['year']=array('sTitle'=>'Yaer','sWidth'=>'5%', 'sClass'=>'cell-years');
	    $oTable->aColumn['power_kw']=array('sTitle'=>'Power<br>KW','sWidth'=>'5%', 'sClass'=>'cell-power-kw');
	    $oTable->aColumn['power_hp']=array('sTitle'=>'Power<br>HP','sWidth'=>'5%', 'sClass'=>'cell-power-hp');
	    $oTable->aColumn['V']=array('sTitle'=>'V','sWidth'=>'5%', 'sClass'=>'cell-volume');
	    $oTable->aColumn['assemblage']=array('sTitle'=>'assemblage','sWidth'=>'30%', 'sClass'=>'cell-body');
	    $oTable->aColumn['fuel']=array('sTitle'=>'fuel', 'sClass'=>'cell-body');
	    $oTable->aColumn['engine']=array('sTitle'=>'engine', 'sClass'=>'cell-body');
	    
	    $oTable->aCallback=array($this,'CallParseModelDetail');
	   	$oTable->iRowPerPage=2000;
	    $oTable->bStepperVisible=false;
	    $oTable->sDataTemplate='catalog/row_modeldetail.tpl';
	    //$oTable->aOrdered=" order by name ";
	    Base::$aRequest['return']?$oTable->sButtonTemplate='catalog/button_index.tpl':"";
	
	    Base::$aData['template']['sPageTitle'].=strip_tags(Base::$tpl->get_template_vars('sBranch'));
	
	    if($bOutput) {
	        return $oTable->GetTable();
	    } else {
	        Base::$sText.=$oTable->GetTable();
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseModelDetail(&$aItem, $bSeparator=false)
	{
		if ($aItem) {
			foreach($aItem as $key => $aValue) {
				$sSeoUrl=Content::CreateSeoUrl('catalog_assemblage_view',array(
				'cat'=>$aValue['cat'],
				'model_detail'=>array($aValue['id_model_detail']=>array('name'=>$aValue['name'],'Name'=>$aValue['Name'])),
				'data[id_make]'=>Base::$aRequest['data']['id_make']?Base::$aRequest['data']['id_make']:$aValue['id_make'],
				'data[id_model]'=>$aValue['id_model'],
				//'data[id_brand]'=>$aValue['id_model'],
				'data[id_model_detail]'=>$aValue['id_model_detail'],
				'model_translit'=>Content::Translit($aValue['name'])
				));
				
				$aItem[$key]['seourl']=mb_strtolower(str_ireplace("/cars/".$aValue['cat']."/".$aValue['cat']."_", "/rubricator/c/".$aValue['cat']."_", $sSeoUrl));
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ViewAssemblage()
	{
		$this->aCats=Db::GetAssoc("select c.id_tof,c.id,c.pref,c.title,c.image,c.name from cat as c where c.visible=1");
	    $this->aCat=Db::GetRow("select * from cat as c where name='".Base::$aRequest['cat']."' ");
	    if(!Base::$aRequest['data']['id_make']) Base::$aRequest['data']['id_make']=$this->aCat['id'];
	    if(!Base::$aRequest['data']['id_make'] && Base::$aRequest['data']['id_model']) Base::$aRequest['data']['id_make']=TecdocDb::GetIdMakeByIdModel(Base::$aRequest['data']['id_make']);
	    if(!Base::$aRequest['cat'] && !$this->aCat && Base::$aRequest['data']['id_make']) $this->aCat=Db::GetRow("select * from cat as c where id='".Base::$aRequest['data']['id_make']."' ");
	    if(!Base::$aRequest['cat'] && $this->aCat) Base::$aRequest['cat']=$this->aCat['name'];
	    $this->aModel=TecdocDb::GetModel(array(
			'id_model'=>Base::$aRequest['data']['id_model'],
			'id_make'=>Base::$aRequest['data']['id_make']
		));
	    $this->aModelDetail=TecdocDb::GetModelDetail(array(
			'id_model'=>Base::$aRequest['data']['id_model'],
			'id_model_detail'=>Base::$aRequest['data']['id_model_detail'],
			'id_make'=>Base::$aRequest['data']['id_make']
			),
		    array($this->aCat['id_tof']=>$this->aCat['id'])
		);
	    
	    Base::$aRequest['data']['id_model']=$this->aModelDetail['id_model'];
	    
		$this->GetNavigator(Base::$aRequest['data']);
		Rubricator::SetAll();
		Rubricator::CheckSelectedAuto();
		Rubricator::CheckSelectedAutoName();
		Rubricator::ClearAutoUrl();
		
		$sAutoUrl=Content::CreateSeoUrl('catalog_assemblage_view',array(
			'cat'=>Base::$aRequest['cat'],
			'data[id_make]'=>Base::$aRequest['data']['id_make'],
			'data[id_model]'=>Base::$aRequest['data']['id_model'],
			'data[id_model_detail]'=>Base::$aRequest['data']['id_model_detail'],
		));
		$sAutoUrl=str_replace('/cars/', '', $sAutoUrl);
		$sAutoUrl=str_replace(Base::$aRequest['cat']."/", 'c/', $sAutoUrl);
		MultiLanguage::Redirect("/rubricator/".$sAutoUrl);
		
		//go to rubricator
		if(Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator']) {
		    $sUrl="/rubricator/";
		    if(Base::$aRequest['category']) {
		        $sUrl.=Base::$aRequest['category']."/";
		    }
		    $sUrl.=Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator'];
//		    Base::Redirect($sUrl);
		}

		Base::$bXajaxPresent=true;
		
		//$aTree=Db::GetAll(Base::GetSql("OptiCatalog/Assemblage",Base::$aRequest['data']));
		$aTree=TecdocDb::GetTree(Base::$aRequest['data']);
		
		// localize tree
		if (Base::$aRequest['locale'] == 'en') {
			foreach ($aTree as $aValue) {
				$aTreeLocale[$aValue['id']]=$aValue;
			}
			$aNames=TecdocDb::GetAssoc("
	        select str_id as id,tex_text as data
	        from ".DB_TOF."tof__search_tree
            join ".DB_TOF."tof__designations  on str_des_id=des_id and  des_lng_id = 4
            join ".DB_TOF."tof__des_texts on des_tex_id=tex_id
	    
	        where str_id in ('".implode("','", array_keys($aTreeLocale))."')
	        ");
			foreach ($aTree as $iKey => $aValue) {
				if ($aNames[$aValue['id']])
					$aTree[$iKey]['data'] = $aNames[$aValue['id']]; 
			}
		}
		
		Base::$oContent->ShowTimer('Tree');

		if ($aTree) foreach ($aTree as $sKey => $aValue) {
		    if($aValue['id']=='13771' || $aValue['str_id_parent']=='13771') {
		        unset($aTree[$sKey]);
		        continue;
		    }
			if ($aValue['str_level']==2) $aIdIcon[]=$aValue['id'];
			$aTreeAssoc[$aValue['id']]=$aValue;
		}
		
		$aModel[Base::$aRequest['data']['id_model']]=$this->aModel;
		$aModelDetail[Base::$aRequest['data']['id_model_detail']]=$this->aModelDetail;
		foreach ($aTree as $key=>$aValue) {
			$aTree[$key]['seourl']=Content::CreateSeoUrl('catalog_part_view',array(
			'cat'=>Base::$aRequest['cat'],
			'model'=>$aModel,
			'model_detail'=>$aModelDetail,
			'aCat'=>array($this->aCat['id_tof']=>$this->aCat['id']),
			'data[id_make]'=>Base::$aRequest['data']['id_make'],
			'data[id_model]'=>Base::$aRequest['data']['id_model'],
			//'data[id_brand]'=>$aValue['id_model'],
			'data[id_model_detail]'=>Base::$aRequest['data']['id_model_detail'],
			'data[id_part]'=>$aValue['id'],
			'data[name_part]'=>$aValue['data'],
			));
		}

		Base::$oContent->ShowTimer('TreeURLs');
		Base::$tpl->assign("aTree",$aTree);

		//--------------------------------------------
		function GetBranch($id, $aData)
		{
			if ($aData) foreach ($aData as $aValue) {
				if ($id<=10001)	{ break; }
				elseif($id==$aValue['id'])
				{
					$sBranch.=GetBranch($aValue['str_id_parent'],$aData);
					if ($sBranch)
						$sBranch.=":&nbsp;";
					$sBranch.="<span>".$aValue['data']."</span>";
					break;
				}
			}
			return $sBranch;
		}
		//--------------------------------------------
		//Base::$tpl->assign("aRowModelInfo",Db::GetRow(Base::GetSql("OptiCatalog/ModelInfo",Base::$aRequest['data'])));
		//Base::$tpl->assign("aRowModelInfo",TecdocDb::GetModelInfo(Base::$aRequest['data']));
		Base::$tpl->assign("aRowModelInfo",$aModelDetail[Base::$aRequest['data']['id_model_detail']]);
// 		Base::$tpl->assign("sBranch",GetBranch(Base::$aRequest['data']['id_part'],$aTree));
		
		$sCrumb=GetBranch(Base::$aRequest['data']['id_part'],$aTree,true);
		$this->GetNavigator(Base::$aRequest['data'],$sCrumb);
		if ($sCrumb) { 
			Base::$oContent->AddCrumb($sCrumb);
		}

		$oOwnAuto=new OwnAuto();
		$oOwnAuto->AddSearchAuto();
		
		if (Base::$aRequest['action']=="catalog_part_view")
		{
			Base::$oContent->ShowTimer('BeforePartDetail');
			Base::$bRightSectionVisible=false;
			Base::$tpl->assign('bRightPartCatalog',true);


			$oTable=new Table();
			$oTable->sType='array';
			//$oTable->aDataFoTable=Db::GetAll(Base::GetSql("OptiCatalog/PartDetail",Base::$aRequest['data']));
			$oTable->aDataFoTable=TecdocDb::GetTreeParts(Base::$aRequest['data'],$this->aCats);
			
			Base::$oContent->ShowTimer('PartDetail');

			//$oTable->sSql=Base::GetSql("Catalog/Part",Base::$aRequest['data']);
			//$oTable->aColumn['logo']=array('sTitle'=>'Logo','sWidth'=>'5%');
			$oTable->aColumn['name']=array('sTitle'=>'Name','sWidth'=>'40%', 'sClass'=>'cell-name');
			$oTable->aColumn['make']=array('sTitle'=>'Make','sWidth'=>'10%', 'sClass'=>'cell-brand');
			$oTable->aColumn['code']=array('sTitle'=>'Code','sWidth'=>'10%', 'sClass'=>'cell-code');
			$oTable->aColumn['pic']=array('sTitle'=>'Pic','sWidth'=>'10%', 'sClass'=>'cell-image','nosort'=>1);
			$oTable->aColumn['price']=array('sTitle'=>'Price','sWidth'=>'10%', 'sClass'=>'cell-price');
			$oTable->aColumn['action']=array('sClass'=>'cell-action','nosort'=>1);
			
			$oTable->sClass .= " row-part-table";

			$oTable->iRowPerPage=Language::getConstant('catalog_assemblage:limit_page_items',25);
			$oTable->sDataTemplate='catalog/row_part.tpl';
			$oTable->aCallback=array($this,'CallParsePart');
			$oTable->aOrdered=" ";
			$oTable->bStepperVisible=true;
			$oTable->iRowPerPage=10;
			$oTable->sNoItem='No price items';
			$oTable->sTemplateName = 'catalog/goods_table.tpl';

			// macro sort table
			$this->SortTable();
			
			Base::$tpl->assign('sTablePrice',$oTable->getTable());
			
			Content::SetMetaTagsPage('tecdoc_tree_part:',array(
			    'part' => $aTreeAssoc[Base::$aRequest['data']['id_part']]['data'],
			    'modification' => $this->aModelDetail['name'],
			    'model' => $this->aModel['name'],
			    'brand' => $this->aCat['name'],
			));
		} else {
		    Content::SetMetaTagsPage('tecdoc_tree:',array(
		        'modification' => $this->aModelDetail['name'],
		        'model' => $this->aModel['name'],
		        'brand' => $this->aCat['name'],
		    ));
		}
		$aCod = array();
                if (!Base::$aRequest['data']['id_part']) {
                    foreach($aTree as $aValue) {
                     if ($aValue['str_id_parent'] == $iDefault_select_node) {
                         if ($aValue['seourl']) 
                             $sPath = $aValue['seourl'];
                         else
                             $sPath = '/?action=catalog_part_view&data[id_make]='.Base::$aRequest['data']['id_make'].
                                 '&data[id_model]='.Base::$aRequest['data']['id_model'].
                                 '&data[id_model_detail]='.Base::$aRequest['data']['id_model_detail'].
                                 '&data[id_part]='.$aValue['id'].'&data[sort]='.Base::$aRequest['data']['sort'];
                        
                         Base::Redirect($sPath);
                     }
                    }
                }
                else {
                        // get all parent id in tree for current id_part
                        $this->getAllParent($aTree, Base::$aRequest['data']['id_part'], $iRoot_id, $aCod);
                }
                
                Base::$tpl->assign("sNeed_aCod", 'none');
                // check if need add code
                if (isset($_COOKIE['cookie_id_model_detail']) && isset(Base::$aRequest['data']['id_model_detail']) && 
                        Base::$aRequest['data']['id_model_detail'] == $_COOKIE['cookie_id_model_detail']) {
                        
                        Base::$tpl->assign("sNeed_aCod", 'add');
                        
                } elseif (!isset($_COOKIE['cookie_id_model_detail']) || (isset($_COOKIE['cookie_id_model_detail']) && 
                        isset(Base::$aRequest['data']['id_model_detail']) && 
                        Base::$aRequest['data']['id_model_detail'] != $_COOKIE['cookie_id_model_detail'])) {
                        setcookie("cookie_id_model_detail", Base::$aRequest['data']['id_model_detail'], 0, '/');
                        Base::$tpl->assign("sNeed_aCod", 'replace');
                }
                
                // get url without sort for jscript
                $aParams=array("action"=>Base::$aRequest['action'],
                        		'data[id_make]'=>Base::$aRequest['data']['id_make'],
                        		'data[id_model]'=>Base::$aRequest['data']['id_model'],
                        		'data[id_model_detail]'=>Base::$aRequest['data']['id_model_detail'],
                        		'data[id_part]'=>Base::$aRequest['data']['id_part'],
                        		'data[name_part]'=>$sNamePart,
                        	   );
                $sUrl="";
                $iCount=count($aParams);
                $iCurrent=0;
                foreach ($aParams as $sKey => $aValue) {
                	$sUrl.=$sKey."=".$aValue;
                	if ($iCurrent<$iCount-1) {
                		$sUrl.="&";
                	}
                	$iCurrent++;
                }
                
                Base::$tpl->assign("sUrl",$sUrl);
                Base::$tpl->assign("aCod",$aCod);
		Resource::Get()->Add('/libp/mpanel/dtree/dtree.js',1);
		
		if ($aListOwnAuto = OwnAuto::GetListOwnAuto()) {
			$aListOwnAuto = array('' => Language::getMessage('Select_own_auto')) + $aListOwnAuto;
			Base::$tpl->assign('aListOwnAuto',$aListOwnAuto);
		}
		Base::$sText.=Base::$tpl->fetch('catalog/tree_assemblage.tpl');

//Rubricator begin
		$sSelectedAutoUrl=str_replace("/cars/", "", Content::CreateSeoUrl('catalog_assemblage_view',array(
    		'cat'=>Base::$aRequest['cat']?Base::$aRequest['cat']:$this->aCat['cat'],
    		'model_detail'=>array($this->aModelDetail['id_model_detail']=>array('name'=>$this->aModelDetail['name'],'Name'=>$this->aModelDetail['Name'])),
    		'data[id_make]'=>Base::$aRequest['data']['id_make']?Base::$aRequest['data']['id_make']:$this->aModel['id_make'],
    		'data[id_model]'=>$this->aModelDetail['id_model'],
    		'data[id_model_detail]'=>$this->aModelDetail['id_model_detail'],
    		'model_translit'=>Content::Translit($this->aModelDetail['name'])
		)));
		
		if($aTreeAssoc) {
    		$aAllowedTreeNodes=array_keys($aTreeAssoc);
    		$aRubric=Db::GetAssoc(str_replace("select","select r.id as key_, ",Base::GetSql("Rubricator",array('visible'=>1,'order'=>'sort asc'))));
    		
    		$aMenu=array();
    		foreach ($aRubric as $aValue) {
    		    if($aValue['level']==1) $aMenu[$aValue['id']]=$aValue;
    		    else continue;
    		}
    		
    		foreach ($aRubric as $aValue) {
    		    if($aValue['level']==2) {
    		        //filter by auto
    		        $bAllow=false;
    		        $aRubricTree=explode(",", $aValue['id_tree']);
    		        if($aRubricTree) foreach ($aRubricTree as $iTreeNode) {
    		            if(in_array($iTreeNode, $aAllowedTreeNodes)) {
    		                $bAllow=true;
    		                break;
    		            }
    		        }
    		        
    		        if($bAllow) {
    		            $aMenu[$aValue['id_parent']]['childs'][]=$aValue;
    		        }
    		    }
    		    else continue;
    		}
		}
		
		//clear empty rubric
		if($aMenu) foreach ($aMenu as $sKey => $aValue) {
		    if(!$aValue['childs']) unset($aMenu[$sKey]);
		}
		
		if($aMenu) {
		    //sort by num
		
		    usort($aMenu, function ($a, $b)
		    {
		        if ($a['sort'] == $b['sort']) {
		            return 0;
		        }
		        return ($a['sort'] < $b['sort']) ? -1 : 1;
		    });
		}
		
		Base::$tpl->assign('aMainRubric', $aMenu);
		Base::$tpl->assign('sSelectedAutoUrl', $sSelectedAutoUrl);
		Base::$sText.=Base::$tpl->fetch('rubricator/index.tpl');
		//Rubricator end
	}
	//-----------------------------------------------------------------------------------------------
	public function getAllParent($aTree, $iId, $iRootId, &$aCod) {
	
		if ($iId == $iRootId)
			return;
	
		foreach($aTree as $aValue) {
			if ($aValue['id'] == $iId) {
				$aCod[] = $aValue['str_id_parent'];
	
				$this->getAllParent($aTree, $aValue['str_id_parent'], $iRootId, $aCod);
				return;
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParsePart(&$aItem)
	{
		// rewrite sort
		if (Base::$aRequest['sort']) {
			if (Base::$aRequest['sort'] == 'code')
				Base::$aRequest['sort'] = 'art_article_nr';
			elseif (Base::$aRequest['sort'] == 'make')
				Base::$aRequest['sort'] = 'brand';
		}
		if (!Base::$aRequest['sort'])
			Base::$aRequest['sort'] = 'price';
		
		$aId=array();
		$aItemCode=array();
		$aTmpItems=array();
		if ($aItem) foreach ($aItem as $sKey => $aValue) {
		    $aTmpItems[$aValue['item_code']]=$aValue;
		    
			if (!$aId[$aValue['art_id']] && $aValue['art_id']>0)	
				$aId[$aValue['art_id']]=$aValue['art_id'];
			if (!$aItemCode[$aValue['item_code']]) {
				$aItemCode[$aValue['item_code']]=$aValue['item_code'];
				
				$aCodesForCheck[$aValue['item_code']]=array(
				    'code'=>$aValue['art_article_nr'],
				    'brand'=>$aValue['brand'],
				    'pref'=>$aValue['pref']
				);
			} else {
				unset($aItem[$sKey]);
			}
		}
		
		/* get all  number (arl_search_number) of original part for this make */
		$aCat=$this->aCat;

		/* get price for all  number */
		$aPriceItemCode=array();
		if ($aItemCode && count($aItemCode) > 0) {
		    if(Base::GetConstant('complex_margin_enble','0')) {
    			if (!Base::$aRequest['sort'] || Base::$aRequest['sort'] == 'price')
    				$sOrder = " t.price ";
    			elseif (Base::$aRequest['sort'] == 'term')
    				$sOrder = " t.term ";
    			elseif (Base::$aRequest['sort'] == 'stock')
    				$sOrder = " CONVERT(replace(replace(replace(replace(replace(replace(replace(replace(t.stock,'>',''),'+',''),'++',''),'+++',''),'есть','1'),'X',''),'XX',''),'XXX',''), SIGNED) ";
    			elseif (Base::$aRequest['sort'] == 'brand')
    				$sOrder = " t.brand ";
    			elseif (Base::$aRequest['sort'] == 'name')
    				$sOrder = " t.name_translate ";
    			elseif (Base::$aRequest['sort'] == 'art_article_nr')
    				$sOrder = " t.code ";
		    } else {
		        if (!Base::$aRequest['sort'] || Base::$aRequest['sort'] == 'price')
		            $sOrder = " p.price/cu.value ";
		        elseif (Base::$aRequest['sort'] == 'brand')
		        $sOrder = " c.title ";
		        elseif (Base::$aRequest['sort'] == 'provider')
		        $sOrder = " up.name ";
		        elseif (Base::$aRequest['sort'] == 'term'){
		            if (Base::GetConstant('price:term_from_provider',1)) {
		                $sOrder = " up.term ";
		            } else {
		                $sOrder = " p.term ";
		            }
		        }
		        elseif (Base::$aRequest['sort'] == 'stock')
		        $sOrder = " CONVERT(replace(replace(replace(replace(replace(replace(replace(replace(p.stock,'>',''),'+',''),'++',''),'+++',''),'есть','1'),'X',''),'XX',''),'XXX',''), SIGNED) ";
		        elseif (Base::$aRequest['sort'] == 'name')
		        $sOrder = " coalesce(cp.name_rus,p.part_rus,'') ";
		        elseif (Base::$aRequest['sort'] == 'code')
		        $sOrder = " p.code ";
		    }
				
			if (Base::$aRequest['way'] && Base::$aRequest['way'] == 'down')
				$sOrder .= ' desc ';
			
			if(count($aItemCode)>0)			
				$sSql = Base::GetSql('Catalog/Price', array(
				'aItemCode'=>array_keys($aItemCode),
				'id_part'=>Base::$aRequest['data']['id_part'],
				'customer_discount'=>Discount::CustomerDiscount(Auth::$aUser),
				'where' => ' and p.price > 0 ' .$this->sWhereParams,
 				'order' => $sOrder
				));
			else {
			    $sSql="";
			}
			$aPrice=Db::GetAll($sSql." limit 500 ");
		} else {
		    $aPrice = array();
		}
		
// 		if ($aPrice) foreach ($aPrice as $aValue) {
// 			if (!$aPriceItemCode[$aValue['item_code']] && $aValue['price']>0) {
// 				$aPriceItemCode[$aValue['item_code']]=$aValue['item_code'];
// 				$aIdCatPart[$aValue['item_code']]=$aValue['id_cat_part'];
// 			}
// 		}
		// get min price
		$aMinPrice = array();
		$iMinPrice = 999999999;
		foreach ($aPrice as $sKeyPrice => $aValuePrice) {
			if (!$aMinPrice[$aValuePrice['item_code']] || $iMinPrice > $aValuePrice['price'] && 0!=$aValuePrice['price']) {
			    $aMinPrice[$aValuePrice['item_code']] = $aValuePrice;
			    $iMinPrice = $aValuePrice['price'];
			}
		}
		if($aPrice) {
		    foreach ($aPrice as $aValuePrice) {
		        if(!$aTmpItems[$aValuePrice['item_code']]) {
		            $aData=$aValuePrice;
		        } else {
		            $aData=array_merge($aValuePrice,$aTmpItems[$aValuePrice['item_code']]);
		        }
		        
		        $aTmp[]=$aData;
		        
// 		        $iValue = trim((isset($aData[Base::$aRequest['sort']]) ? $aData[Base::$aRequest['sort']] : ""));
// 		        if (Base::$aRequest['sort'] == 'price')
// 		            $iValue = $aData['price'];
// 		        elseif (Base::$aRequest['sort'] == 'stock')
// 		        $iValue = $aData['stock_filtered'];

                $iValue = trim((isset($aData[Base::$aRequest['sort']]) ? $aData[Base::$aRequest['sort']] : ""));
                if (!Base::$aRequest['sort'] || Base::$aRequest['sort'] == 'price')
                    $iValue = $aData['price'];
                elseif (Base::$aRequest['sort'] == 'stock')
                $iValue = $aData['stock_filtered'];
                elseif (Base::$aRequest['sort'] == 'term')
                $iValue = $aData['term'];
                elseif (Base::$aRequest['sort'] == 'make')
                $iValue = $aData['make'];
                elseif (Base::$aRequest['sort'] == 'code')
                $iValue = $aData['code'];
                elseif (Base::$aRequest['sort'] == 'name')
                $iValue = $aData['name_translate'];
                		        
		        $aTmpSort[] = $iValue;
		    }
		}
		
		if ($aTmp) {
			$sType = SORT_STRING;
			if (!Base::$aRequest['sort'] || Base::$aRequest['sort'] == 'price' 
					|| Base::$aRequest['sort'] == 'term' || Base::$aRequest['sort'] == 'term_day'  
					|| Base::$aRequest['sort'] == 'stock')
				 $sType = SORT_NUMERIC;
			
			if (Base::$aRequest['way'] && Base::$aRequest['way'] == 'down')
				array_multisort ($aTmpSort, SORT_DESC, $sType, $aTmp);
			else 
				array_multisort ($aTmpSort, SORT_ASC, $sType, $aTmp);
		}

		$aItem=$aTmp;
		$aTmp=0;
		$aTmpSort=0;

// 		if($aItem ) {
// 		    $this->PosPriceParse($aItem,false,false);
// 		}
	}
	//-----------------------------------------------------------------------------------------------
	public function GetNavigator($aData,$sCrumb = '')
	{
		$aNavigator['id_make']=array();
		$aNavigator['id_model']=array();
		$aNavigator['id_model_detail']=array();
		if(!$aData['id_make'] && $aData['id_model']){		
			if (Base::$aRequest['cat']){
				$aData['id_make']=Db::GetOne("select id from cat where name='".Base::$aRequest['cat']."' ");
				Base::$aRequest['data']['id_make']=$aData['id_make'];
			}
		}
		foreach ($aNavigator as $sKey => $aValue) {
			if ($aData[$sKey])
			{
				switch ($sKey) {
					case "id_make":
						if(!$this->aCat) $aRow=Db::GetRow(Base::GetSql("Cat",array("id"=>$aData['id_make'])));
						else $aRow=$this->aCat;

						if ($aData['id_model'] || $aData['id_model_detail']) {
							$sAction="catalog_model_view&data[id_make]=".$aData['id_make']
							."&data[id_model]=".$aRow['mod_id'];
							Base::$tpl->assign('sImage',$aRow['image']);
							$sUrl="/cars/".Content::Translit($aRow['name'])."/";
						}
						$aRow['name']=$aRow['title'];
						break;

					case "id_model":
						if(!$this->aModel) $aRow=TecdocDb::GetModel($aData);
					    else $aRow=$this->aModel;

						if ($aData['id_model_detail']) {
							$sAction="catalog_detail_model_view&data[id_make]=".$aData['id_make']
							."&data[id_model]=".$aRow['mod_id'];
							$sUrl=Content::CreateSeoUrl('catalog_detail_model_view',array(
							'cat'=>Base::$aRequest['cat'],
							'model'=>array($aRow['mod_id']=>$aRow),
							'data[id_make]'=>$aData['id_make'],
							'data[id_model]'=>$aRow['mod_id'],
							'model_translit'=>Content::Translit($aRow['name'])
							));
						}
						break;

					case "id_model_detail":
						if(!$this->aModelDetail) {
    					    if(!$this->aCat) $aCat=Db::GetAssoc("select c.id_tof,c.id from cat as c where c.visible=1 and name='".Base::$aRequest['cat']."' ");
    					    else $aCat=array($this->aCat['id_tof']=>$this->aCat['id']);
    					        
    					    $aRow=TecdocDb::GetModelDetail($aData,$aCat);
					    } else $aRow=$this->aModelDetail;

						if ($sCrumb) {
							$sAction="catalog_part_view&data[id_make]=".$aData['id_make']
							."&data[id_model]=".$aData['id_model']
							."&data[id_model_detail]=".$aRow['typ_id'];
							$sUrl=Content::CreateSeoUrl('catalog_assemblage_view',array(
							'cat'=>Base::$aRequest['cat'],
							'model'=>array($aRow['id_model']=>$this->aModel),
							'model_detail'=>array($aRow['id_model_detail']=>$aRow),
							'data[id_make]'=>$aRow['id_make'],
							'data[id_model]'=>$aRow['id_model'],
							'data[id_model_detail]'=>$aRow['id_model_detail'],
							'model_translit'=>Content::Translit($aRow['name'])
							));
						}
						break;
				}
				if (Language::getConstant('global:url_is_lower',0) == 1) {
					$sUrl = mb_strtolower($sUrl,'utf-8');
					$sAction = mb_strtolower($sAction,'utf-8');
				}
				if (Language::getConstant('global:url_is_not_last_slash',0) == 1) {
					if (mb_substr($sUrl, -1, 1, 'utf-8') == "/")
						$sUrl = substr($sUrl, 0, -1);					
					if (mb_substr($sAction, -1, 1, 'utf-8') == "/")
						$sUrl = substr($sAction, 0, -1);
				}
					
				$aNavigator[$sKey]['name']=$aRow['name'];
				$aNavigator[$sKey]['action']=$sAction;
				$aNavigator[$sKey]['url']=$sUrl;
				$sAction = $sUrl = '';
			}
		}
		if($sCrumb) {
		    $aNavigator[]=array(
		        'name'=>$sCrumb
		    );
		}
		Base::$tpl->assign('aNavigator',$aNavigator);

// 		Base::$aData['template']['sPageTitle']=Language::getMessage('navigator title').
// 		strip_tags(Base::$tpl->fetch("catalog/navigator.tpl"));
// 		Base::$tpl->assign("sCatalogNavigator", Base::$tpl->fetch ("catalog/navigator.tpl"));
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Remove ' ','-','#','.','/',',','_',':','[',']','(',')','*','&','+' from code and UPER
	 *
	 * @param string $sCode
	 * @return string
	 */
	public static function StripCode($sCode)
	{
		return strtoupper(str_replace(array('&nbsp;','=',' ','-','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\',' ', '%'),"",trim($sCode)));
	}
	//-----------------------------------------------------------------------------------------------
	public static function StripLogin($sCode)
	{
		return str_replace(array(' ','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\'),"",trim($sCode));
	}
	//-----------------------------------------------------------------------------------------------
	/* not del space and not upper symbols*/
	public static function StripCodeSearch($sCode)
	{
		return str_replace(array('%','-','#','.','/',',','_',':','[',']','(',')','*','&','+','`','\'','"','\\'),"",trim($sCode));
	}
	//-----------------------------------------------------------------------------------------------
	public static function StripStock($sStock)
	{
	    return str_replace(array(' ','>',',','H','-','M','<','+','++','+++','есть','X','XX','XXX'),"",trim($sStock));	     
	}	
	/**
	 * Add sql replace
	 *
	 * @param string $sField
	 * @return string Sql
	 */
	public static function StripCodeSql($sField)
	{
		return "replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(UPPER(".$sField."),' ',''),'-',''),'#',''),'.',''),'/',''),',',''),'_',''),':',''),'[',''),']',''),'(',''),')',''),'*',''),'&',''),'+',''),'`',''),'\"',''),'\'','') ";
	}
	//-----------------------------------------------------------------------------------------------
	/**
	 * Format code for rule
	 *
	 * @param string $sCode
	 * @param string $sPref
	 * @return string
	 */
	public function GetFormattedCode($sCode,$sPref)
	{
		switch ($sPref) {
			case "TY":
			case "DH":
			case "LS":
				return trim(substr($sCode,0,5)."-".substr($sCode,5,5)."-".substr($sCode,10,5),"-");
				break;
			default:
				return " ".$sCode;
				break;
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ViewPrice()
	{
	    if(!Base::$aRequest['code']) {
	        Base::$oContent->AddCrumb(Language::GetMessage('Catalog auto'),'/pages/catalog');
		    Base::$oContent->AddCrumb(Language::GetMessage('price search'),'');
	        Base::$sText.=Language::GetText('price:text');
	        return;
	    }
	    
	    // AT-1271 begin
	    if(strlen(Base::$aRequest['code'])==17) {
	        $oVinDecode = new VinDecode();
	        if($oVinDecode->DecodeVin(Base::$aRequest['code']))
	           return;
	    }
	    // AT-1271 end
	    
		/**
		 * Sphinx search when results is zero
		 */
		if (Base::$aRequest['only_by_name']) {
			MultiLanguage::Redirect('/search_text/'.Base::$aRequest['code'].'/');
			Base::$aRequest['search']['query']=Base::$aRequest['code'];
			$oSearch=new Search();
			$oSearch->Index(false);
			return;
		}
		//******************************//

		Base::Message();
		if (1 || Auth::$aUser['type_']!="manager") {
			Base::$bXajaxPresent=true;
		}

		Base::$tpl->assign('bAddCartVisible',true);
		
		if(strpos($_SERVER['REQUEST_URI'], '/?')!==FALSE) {
			if(!Base::$aRequest['name'] && Base::$aRequest['pref'])
			Base::$aRequest['name']=Db::GetOne("select name from cat 
				where name='".Base::$aRequest['pref']."' or pref='".Base::$aRequest['pref']."'");
			if(Base::$aRequest['name']) {
				MultiLanguage::Redirect('/price/'.Content::Translit(Base::$aRequest['name']).'_'.Catalog::StripCodeSearch(Base::$aRequest['code']));
			} else {
				$sCod=mb_strtoupper(Base::$aRequest['code']);
				$sCodOriginal = urlencode($sCod);
				if(strpos($sCod,'ZZZ_')===false) $sCod=Catalog::StripCodeSearch($sCod);
				if ($sCod != $sCodOriginal) {
					setcookie('incodeoriginal',$sCodOriginal, 0,"/" );
					setcookie('incode',$sCod, 0,"/" );
				}
				else { 
					setcookie('incodeoriginal','', time() - 3600,"/" );
					setcookie('incode','', time() - 3600,"/" );
				}
				if(strpos($sCod,'ZZZ_')===false) MultiLanguage::Redirect('/price/'.$sCod);
			}
		}
		else {
			$sCod=mb_strtoupper(Base::$aRequest['code']);
			if(strpos($sCod,'ZZZ_')===false) $sCod=Catalog::StripCodeSearch($sCod);

			if(mb_strtoupper(Base::$aRequest['name'])=='ZZZ'){
				Base::$aRequest['code']=mb_strtoupper(Base::$aRequest['name']).'_'.Base::$aRequest['code'];
				$sCod = Base::$aRequest['code'];
				unset(Base::$aRequest['name']);
			}
				
			if ($_COOKIE['incode'] == $sCod && $_COOKIE['incodeoriginal']) {
				$sCodOriginal = $_COOKIE['incodeoriginal'];
			}
			else {
				$sCodOriginal = urlencode($sCod);
				if ($sCod != $sCodOriginal)
					setcookie('incode',$sCodOriginal, 0,"/" );
				else {
					setcookie('incode','', time() - 3600,"/" );
					unset($_COOKIE['incode']);
				}
			}
		}
		
		Base::$oContent->AddCrumb(Language::GetMessage('Catalog auto'),'/pages/catalog');
		Base::$oContent->AddCrumb(Language::GetMessage('price for')." ".Base::$aRequest['code'],'');
		
		if(Base::$aRequest['name'] && !Base::$aRequest['pref']){
			Base::$aRequest['pref']=Db::GetOne("
			select c.pref from 
			(select p.name as name, p.pref from cat as p
			where p.visible=1 ) as c
			
			where c.name='".Base::$aRequest['name']."' ");
		}
		
// 		Base::$sText.=Base::$tpl->fetch('price_profile/popup.tpl');
		
		$oPriceSearchLog=new PriceSearchLog();
		$oPriceSearchLog->AddSearch();

		$sCodeTest=Catalog::StripCode(Base::$aRequest['code']);
		if(preg_match('/^A[0-9]{10}$/', $sCodeTest) || preg_match('/^A[0-9]{11}$/', $sCodeTest) || preg_match('/^A[0-9]{12}$/', $sCodeTest))
			Base::$aRequest['code']=ltrim($sCodeTest,'A');

		$this->sPref=Base::$aRequest['pref'];
		$this->aCode = preg_split("/[\s,;]+/", Catalog::StripCode(Base::$aRequest['code']));
		$sCode = "'".implode("','",$this->aCode)."'";

		if (Base::GetConstant("graber:exist",0) && Base::$aGeneralConf['IsLive']) {
			/*$oGraber = new Graber();
			$oGraber->GetExistPrice($sCode,$this->sPref);*/
		}
		
// 		Base::$oContent->ShowTimer('before armtek');
// 		$rSeoBotPattern = '/(adsbot\-google|googlebot|mediapartners\-google|slurp|msnbot|bingbot|bingpreview|teoma|scooter|ia_archiver|lycos|yandex|stackrambler|mail\.ru|aport|webalta|baidu|aolbuild|riddler|blexbot|ahrefsbot|ltx71|mj12bot)/i';
// 		if (!preg_match($rSeoBotPattern, $_SERVER['HTTP_USER_AGENT'])) {
// 		      Webservice::GetArmTek($this->aCode[0]);
// 		}
// 		Base::$oContent->ShowTimer('after armtek');
		
		$sId="";
		if (strpos(Base::$aRequest['code'],"ZZZ_")!==false) {
			$bIsZzz=true;
			$this->bShowSeparator=false;
			$aId[]=str_replace("ZZZ","",$this->aCode[0]);

			$sId="'".implode("','",$aId)."'";
			$aCodeZzz=Db::GetRow("select * from price where id in(".$sId.")");
			$this->aCode[0]= $aCodeZzz['code'];
			$this->sPref = $aCodeZzz['pref'];
			$sCode = strtoupper("'".implode("','",$this->aCode)."'");
		}
		if (/*$this->sPref &&*/ !$bIsZzz) 
			$this->aCodeCross=TecdocDb::GetCross(array(
			    'sCode'=>$sCode,
			    'pref'=>$this->sPref
			));

		// Get OE numbers begin - send TecdocDb::GetCross - check stop cross
		/*if(Base::GetConstant('price:show_oe','1')==1) {
    		$sSql="select  
    		    oe_code, 
    		    code,
    		    oe_brand,
    		    brand
            from ".DB_OCAT."cat_alt_original as c
            where 1=1 and (oe_code like ".$sCode." or code like ".$sCode." ) ";
    		$aOriginals=TecdocDb::GetAll($sSql);
    		
    		if($aOriginals) $aCatAssoc=Db::GetAssoc("select id_tof,pref from cat where id_tof >0");

    		// cut other brand original
    		$aPrefOriginals = array();
    		if ($this->sPref) {
    			$sItemCode = $this->sPref.'_'.Base::$aRequest['code'];
    			$iIdTof = Db::GetOne("select id_tof from cat where pref='".$this->sPref."'");
    			if ($iIdTof) {
	    			if($aOriginals) foreach ($aOriginals as $aValue) {
	    				if ($aValue['code']==Base::$aRequest['code'] && $aValue['brand']==$iIdTof)
	    					$aPrefOriginals[] = $aValue;
	    				elseif ($aValue['oe_code']==Base::$aRequest['code'] && $aValue['oe_brand']==$iIdTof)
	    					$aPrefOriginals[] = $aValue;
	    			}
    				$aOriginals = $aPrefOriginals;
    			}
    		}
    		
    		$aItemCodeOriginal=array();
    		if($aOriginals) foreach ($aOriginals as $skeyOriginal => $aValueOriginal) {
    			// cut other brand original without id_tof
    			if ($sItemCode && 
    				$aCatAssoc[$aValueOriginal['brand']]."_".$aValueOriginal['code'] != $sItemCode &&
    				$aCatAssoc[$aValueOriginal['oe_brand']]."_".$aValueOriginal['oe_code'] != $sItemCode
    			)
    				continue;
    			
    		    $aTmp=array();
    		    $aTmp['pref']=$aCatAssoc[$aValueOriginal['brand']];
    		    $aTmp['pref_crs']=$aCatAssoc[$aValueOriginal['oe_brand']];
    		    
    		    if(!$aTmp['pref'] || !$aTmp['pref_crs']) continue;
    
    		    $aTmp['item_code']=$aCatAssoc[$aValueOriginal['brand']]."_".$aValueOriginal['code'];
    		    $aTmp['item_code_crs']=$aCatAssoc[$aValueOriginal['oe_brand']]."_".$aValueOriginal['oe_code'];
    		    if ($aTmp['pref'] && $aTmp['pref_crs'])
    		    	$aItemCodeOriginal[]=$aTmp;
    		    
    		    
    		    $aTmp=array();
    		    $aTmp['pref_crs']=$aCatAssoc[$aValueOriginal['brand']];
    		    $aTmp['pref']=$aCatAssoc[$aValueOriginal['oe_brand']];
    		    
    		    $aTmp['item_code_crs']=$aCatAssoc[$aValueOriginal['brand']]."_".$aValueOriginal['code'];
    		    $aTmp['item_code']=$aCatAssoc[$aValueOriginal['oe_brand']]."_".$aValueOriginal['oe_code'];
    		    if ($aTmp['pref'] && $aTmp['pref_crs'])
    		    	$aItemCodeOriginal[]=$aTmp;
    		}
    		if(!$aItemCodeOriginal) $aItemCodeOriginal=array();
    		if(!$this->aCodeCross) $this->aCodeCross=array();
    		$this->aCodeCross=array_merge($this->aCodeCross,$aItemCodeOriginal);
		}
		// Get OE numbers end
		*/
		
		if ($this->sPref && $this->aCode[0]) {
		    $this->aItemCodeCross[$this->sPref."_".$this->aCode[0]]=$this->sPref."_".$this->aCode[0];
		    $aCat = Db::GetRow("select * from cat where pref='".$this->sPref."'");
		    if ($aCat['is_cat_virtual']){
		        $aCatVirtual = Db::GetAll("SELECT * FROM cat WHERE id_cat_virtual = ".$aCat['id']);
		        if($aCatVirtual)
		            foreach($aCatVirtual as $aValueVirtual){
		            $this->aItemCodeCross[$aValueVirtual['pref']."_".$this->aCode[0]]=$this->sPref."_".$this->aCode[0];
		        }
		    }
		}
		if ($this->aCodeCross) {
/*			$aVag=array("AU","SC","SE","VW","VAG");
			foreach ($this->aCodeCross as $k => $v) {
				list($sPrefCrs,$sCodeCrs)=explode("_",$v['item_code_crs']);
				if (in_array($sPrefCrs,$aVag)) {
					foreach ($aVag as $sKey => $sValue) $this->aItemCodeCross[$sValue."_".$sCodeCrs]=$v['item_code'];
				} else {
					$this->aItemCodeCross[$v['item_code_crs']]=$v['item_code'];
				}
			}
*/
			foreach ($this->aCodeCross as $k => $v) {
			    $this->aItemCodeCross[$v['item_code_crs']]=$v['item_code'];
			    // add virtual codes
			    if (!$this->aItemCodeCross[$v['item_code']])
			        $this->aItemCodeCross[$v['item_code']] = $v['item_code'];
			}
		}
		
		if(Base::GetConstant('complex_margin_enble','0')) {
		    $sOrder=" pref_order, code_order";
		    if (Base::$aRequest['order'] && Base::$aRequest['way']) {
		        $sOrder.=", ".Base::$aRequest['order']." ".Base::$aRequest['way'];
		    } else {
		        Base::$aRequest ['order']="price";
		        $sOrder.=",t.price, price_order , t.code_, t.item_code  ";
		    }
		} else {
		    $sOrder=" pref_order, code_order";
		    if (Base::$aRequest['order'] && Base::$aRequest['way']) {
		        $sOrder.=", ".Base::$aRequest['order']." ".Base::$aRequest['way'];
		    } else {
		        Base::$aRequest ['order']="price";
		        $sOrder.=" , p.price/cu.value asc , p.code, p.item_code  ";
		    }
		}

		$sId = str_replace("'", "", $sId);
		
		$sSql=Base::GetSql('Catalog/Price',array(
		'aCode'=>$this->aCode,
		'pref'=>$this->sPref,
		'customer_discount'=>Discount::CustomerDiscount(Auth::$aUser),
		'pref_order'=>$this->sPref,
		'code_order'=>$this->aCode[0],
		'sId'=>$sId,
		'order'=>$sOrder
		));

		$aData1=Db::GetAll($sSql);
		
		foreach ($aData1 as $sKey=>$aVals) {
		    if (!isset($aVals['code']) && isset($aVals['code_'])) {
		        $aData1[$sKey]['code'] = $aVals['code_'];
		    }
		}
		
		if(count(array_keys($this->aItemCodeCross))>0) {
    		$sSql=Base::GetSql('Catalog/Price',array(
    		'aItemCode'=>array_keys($this->aItemCodeCross),
    		'customer_discount'=>Discount::CustomerDiscount(Auth::$aUser),
    		'pref_order'=>$this->sPref,
    		'code_order'=>$this->aCode[0],
    		'sId'=>$sId,
	       	'order'=>$sOrder
    		));
    		$aData2=Db::GetAll($sSql);
		} else $aData2=false;
		$aData=array();
		$aPriceId=array();
		if($aData1) foreach ($aData1 as $value) {
			if(!$aPriceId[$value['id']])
			$aData[]=$value;
			$aPriceId[$value['id']]=$value['id'];
		}
		if($aData2) foreach ($aData2 as $value) {
			if(!$aPriceId[$value['id']])
			$aData[]=$value;
			$aPriceId[$value['id']]=$value['id'];
		}

		if(!$bIsZzz && !$aData && Base::GetConstant('global:empty_price_redirect')){
			MultiLanguage::Redirect('/search_text/'.$sCod.'/');
			return;
		}
		
		if (!$aData && $this->aItemCodeCross) {
			$aData[]=array('item_code'=>$this->sPref."_".$this->aCode[0], 'code'=>$this->aCode[0], 'pref'=>$this->sPref);
		}
		
		$aPref=array();
		$aBrand=array();
		$aBrandPrice=array();
		if (!$this->sPref && $aData) {
			foreach ($aData as $sKey => $aValue) {
			    if($aValue['price']>0) {
			        $aBrandPrice[$aValue['pref']]=1;
			    }
				if ($aValue['code']==$this->aCode[0]
				//&& $aValue['price']>0
				) {

					if ($aValue['pref']!=null || $aValue['pref']!=""){
						if (!in_array($aValue['pref'],$aPref)) {
							$aBrand[]=array(
							"pref"=>$aValue['pref'],
							"brand"=>$aValue['brand'],
							"code"=>$aValue['code'],
							"name_translate"=>$aValue['name_translate'],
							"cat"=>$aValue['cat_name'],
							);
						}
						$aPref[$aValue['pref']]=$aValue['pref'];
					}

				} elseif ($this->aItemCodeCross[$aValue['item_code']] && $aValue['price']>0
				&& !in_array(substr($this->aItemCodeCross[$aValue['item_code']],0
				,strpos($this->aItemCodeCross[$aValue['item_code']],"_")),$aPref)) {

					$aCat=Db::GetRow(Base::GetSql("Cat",array(
					'pref'=>substr($this->aItemCodeCross[$aValue['item_code']],0
					,strpos($this->aItemCodeCross[$aValue['item_code']],"_"))
					)));

					if ($aCat) {
						$aBrand[]=array(
						"pref"=>$aCat['pref'],
						"brand"=>$aCat['title'],
						"code"=>$this->aCode[0],
						"name_translate"=>$aValue['name_translate'],
						"cat"=>$aCat['name'],
						);
	
						$aPref[$aCat['pref']]=$aCat['pref'];
					}
				}
			}
			$this->aCats = array();
			$aCatTmp = Db::GetAll("SELECT * FROM cat where visible = 1");
			foreach($aCatTmp as $aCatValue){
				$this->aCats[$aCatValue['id_tof']] = $aCatValue;
			}
			$aUnique = array_unique($this->aItemCodeCross);
			foreach ($aUnique as $sKey => $sValue) {
				if (!in_array(substr($sValue,0,strpos($sValue,"_")),$aPref)) {

					$aCat=Db::GetRow(Base::GetSql("Cat",array(
					'pref'=>substr($sValue,0,strpos($sValue,"_"))
					)));

					$aPartInfo=TecdocDb::GetPartInfo(array(
					    'item_code'=>$sValue
					),$this->aCats);

					if ($aPartInfo) {
						$aBrand[]=array("pref"=>$aPartInfo['pref'],
						"code"=>Catalog::StripCode($aPartInfo['code']),
						"name_translate"=>$aPartInfo['name'],
						"brand"=>$aPartInfo['brand'],
						"cat"=>$aPartInfo['cat_name']
						);
					}
					
					if ($aCat)
						$aPref[$aCat['pref']]=$aCat['pref'];
				}
			}
		}
		
		//Make $aBrand unique
		$aBrandTmp=array();
		foreach ($aPref as $sPref) {
			foreach ($aBrand as $aValue) {
				if($aValue['pref']!=$sPref) continue;
				else {
					if ($aValue['code'] != $this->aCode[0]) continue;
					$aBrandTmp[]=$aValue;
					break;
				}
			}
		}
		$aBrand=$aBrandTmp;
		unset($aBrandTmp);
		
		if (!$this->sPref && count($aBrand)==1 && $aBrand['pref']) {
			$this->sPref = $aBrand['pref'];
		}
		
		// rebuild use only virtual brand
		if (count($aBrand) > 1) {
		    foreach ($aBrand as $aValue) {
		        $aValue['has_price']=$aBrandPrice[$aValue['pref']];
		        
		        $aCat = Db::GetRow("select * from cat where pref='".$aValue['pref']."'");
		        if ($aCat) {
		            if ($aCat['is_cat_virtual'] && !isset($aBrandNew[$aValue['pref']]))
		                $aBrandNew[$aValue['pref']] = $aValue;
		            elseif ($aCat['id_cat_virtual'] > 0) {
		                $aCat = Db::GetRow("select * from cat where id='".$aCat['id_cat_virtual']."'");
		
		                if ($aCat && !isset($aBrandNew[$aCat['pref']])){
		                    $aBrandNew[$aCat['pref']] = array(
		                        'pref' => $aCat['pref'],
		                        'brand' => $aCat['title'],
		                        'code' => $aValue['code'],
		                        'name_translate' => $aValue['name_translate'],
		                        'cat' => $aCat['name']);
		                }
		            }
		            elseif (!isset($aBrandNew[$aValue['pref']]))
		            $aBrandNew[$aValue['pref']] = $aValue;
		        }
		    }
		    $aBrand = array_values($aBrandNew);
		}

		if ($this->sPref || count($aBrand)<=1) {
			if (count($aBrand)==1) $this->sPref=$aBrand[0]['pref'];
			if(!Base::$aRequest['name'] && !$bIsZzz){
				Base::$aRequest['name']=Db::GetOne("select replace(replace(name,'/',''),'_','') from cat 
					where pref='".$this->sPref."'");
				if(Base::$aRequest['name']) {
					if (!preg_match("/[а-яё]/i", Base::$aRequest['code']))
						MultiLanguage::Redirect('/price/'.Content::Translit(Base::$aRequest['name']).'_'.Base::$aRequest['code'].'/');
				}
			}

			if (0 && $this->sPref)
			{
				$oPriceSearchLog=new PriceSearchLog();
				$oPriceSearchLog->AddSearch();
			}

			$oTable = new Table();

			if (!Base::GetConstant('price:lock_table')) {
				$oTable->sType='array';
				$oTable->aDataFoTable=$aData;
				$oTable->sNoItem='No price items';
			} else {
				$oTable->sSql="select null ";
				$oTable->sNoItem="Please. Repeat a query in ".Base::GetConstant('price:lock_table')
				." minutes. The update of prices is going now.";
			}

            Catalog::GetPriceTableHead($oTable);
	        $oTable->aCallback=array($this,'CallParsePrice');
			$oTable->iAllRow=0;
	        $oTable->sTemplateName = 'table/table_analogs.tpl';
			$oTable->sDataTemplate='catalog/row_price.tpl';
			$oTable->bFormAvailable=false;
			$oTable->bHeaderVisible=false;

			// macro sort table
			$this->SortTable();
			Base::$sText.=$oTable->getTable();

		} elseif (count($aBrand)>1) {
			foreach ($aBrand as $sKey => $aValue) {
				$bDelete=Db::GetOne("select visible from cat where pref = '".$aValue['pref']."' ");
				if (!$bDelete) unset($aBrand[$sKey]);
			}
			
			if (count($aBrand)==1) {
				$aCurrentBrand=array_shift($aBrand);
				MultiLanguage::Redirect('/price/'.$aCurrentBrand['cat'].'_'.Base::$aRequest['code'].'/');
			}
			
			// sort function------
			function cmp_brands($a, $b) {
				return strcmp($a["brand"], $b["brand"]);
			}
			//--------------------
			usort($aBrand, "cmp_brands");

			$oTable = new Table();
			$oTable->sType='array';
			$oTable->aDataFoTable=$aBrand;
			$oTable->sNoItem='No brand items';
			$oTable->iRowPerPage=1000;

			$oTable->bStepperVisible=false;
			//$oTable->aColumn['code']=array('sTitle'=>'Code');
			$oTable->aColumn['brand']=array('sTitle'=>'Brand');
			$oTable->aColumn['name_translate']=array('sTitle'=>'Name');
			$oTable->aColumn['action']=array();
			if (Base::GetConstant("price:remove_null_cross","1")) $oTable->aCallback=array($this,'CallParseBrand');

			$oTable->sDataTemplate='catalog/row_price_brand.tpl';

			$oTable->bHeaderVisible=false;
			Base::$sText.=$oTable->getTable();
			
			Content::SetMetaTagsPage('price_brand:',array(
			    'code' => $aBrand[0]['code'],
			    'brand' => $aBrand[0]['brand'],
			    'name' => $aBrand[0]['name_translate']
			));
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function GetPriceTableHead(&$oTable) {
	    $oTable->aColumn['make']=array('sTitle'=>'Make','sWidth'=>'10%');
	    $oTable->aColumn['code']=array('sTitle'=>'Code','sWidth'=>'10%');
	    $oTable->aColumn['name']=array('sTitle'=>'Name');
	    $oTable->aColumn['image']=array('nosort'=>1);
	    Auth::$aUser['type_']=='manager'?$oTable->aColumn['provider']=array('sTitle'=>'Provider','sWidth'=>'10%'):"";
	    $oTable->aColumn['stock']=array('sTitle'=>'Stock','sWidth'=>'5%');
	    $oTable->aColumn['term']=array('sTitle'=>'Term','sWidth'=>'5%');
	    $oTable->aColumn['price']=array('sTitle'=>'Price','sWidth'=>'10%');
	    $oTable->aColumn['number']=array('sTitle'=>'Number','sWidth'=>'5%','nosort' => 1);
	    $oTable->aColumn['action']=array('sWidth'=>'5%','nosort' => 1);
		
		$oTable->sClass.=" mobile-table price-table";
		$oTable->bCheckVisible=false;
		$oTable->bCheckAllVisible=false;
		$oTable->bStepperVisible=false;
		$oTable->bDefaultChecked=false;
		$oTable->sDataTemplate='catalog/row_price.tpl';
		$oTable->iRowPerPage=130;
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseBrand(&$aItem)
	{
		$aName = array();
		foreach ($aItem as $sKey => $aValue) {
			if (!$aName[$aValue['pref'].'_'.$aValue['code']] && $aValue['name_translate']) {
			    $aName[$aValue['pref'].'_'.$aValue['code']] = $aValue['name_translate'];
			}
			
			$aCross = TecdocDb::GetCross(array(
			    'code'=>$aValue['code'],
			    'pref'=>$aValue['pref']
			));
			
			if ($aCross) {
				$sCrossString = "'".$aValue['pref']."_".$aValue['code']."'";
				
				foreach ($aCross as $aValueCross) {
				    $sCrossString .= ",'".$aValueCross['item_code_crs']."'";
				}
				
				$iCount=Db::GetOne("select count(*) from price p
				    inner join user_provider up on up.id_user = p.id_provider
                    inner join user u on u.id = p.id_provider and u.visible=1
                    inner join cat c on c.pref = p.pref and c.visible=1 
				    where p.price > 0
				    /* раскоментить если нужно and CONVERT(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(p.stock,'>',''),',','.'),'Н','1'),'-',''),'M','1'), '<',''),'+',''),'++',''),'+++',''),'есть','1'),'X',''),'XX',''),'XXX',''), SIGNED) > 0 */
				    and p.item_code in (".$sCrossString.")");
				
				if (!$iCount) {
					$iPrice=Db::GetOne("select count(*) from price p 
				    inner join user_provider up on up.id_user = p.id_provider
                    inner join user u on u.id = p.id_provider and u.visible=1
                    inner join cat c on c.pref = p.pref and c.visible=1 
				    where p.price > 0
				    /* раскоментить если нужно and CONVERT(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(p.stock,'>',''),',','.'),'Н','1'),'-',''),'M','1'), '<',''),'+',''),'++',''),'+++',''),'есть','1'),'X',''),'XX',''),'XXX',''), SIGNED) > 0 */
				    where p.item_code='".$aValue['pref']."_".$aValue['code']."' ");
					
					if (!$iPrice && !$aValue['has_price']) {
					    unset($aItem[$sKey]);
					    continue;
					}
				}
			} else {
			    $iPrice=Db::GetOne("select count(*) from price p
				    inner join user_provider up on up.id_user = p.id_provider
                    inner join user u on u.id = p.id_provider and u.visible=1
                    inner join cat c on c.pref = p.pref and c.visible=1
				    where p.price > 0
				    /* раскоментить если нужно and CONVERT(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(p.stock,'>',''),',','.'),'Н','1'),'-',''),'M','1'), '<',''),'+',''),'++',''),'+++',''),'есть','1'),'X',''),'XX',''),'XXX',''), SIGNED) > 0 */
				    where p.item_code='".$aValue['pref']."_".$aValue['code']."' ");
			    
			    if (!$iPrice && !$aValue['has_price']) {
			        unset($aItem[$sKey]);
			        continue;
			    }
			}
			
			if (!$aName[$aValue['pref'].'_'.$aValue['code']]) {
				$sName = Db::getOne("Select part_rus from price where item_code='".$aValue['pref'].'_'.$aValue['code']."' and part_rus!=''");
				if ($sName) {
					$aName[$aValue['pref'].'_'.$aValue['code']] = $sName;
				}
			}
			if (!$aValue['name_translate'] && $aName[$aValue['pref'].'_'.$aValue['code']])
				$aItem[$sKey]['name_translate'] = $aName[$aValue['pref'].'_'.$aValue['code']];
		}
		$aItem=array_values($aItem);
		if (count($aItem)==1) {
			$sUrl = "/price/".$aItem[0]['cat']."_".$aItem[0]['code'];
			$aValue=array_pop($aItem);
			if ($_SERVER['REQUEST_URI'] != $sUrl)
				Base::Redirect($sUrl);
		}
		
		if(count($aItem)==0) {
		    Base::Redirect("/search_text/".Base::$aRequest['code']);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParsePrice(&$aItem, $bSeparator=false)
	{
		if (!Base::$aRequest['sort'])
			Base::$aRequest['sort'] = 'price';
		
		$aTmpSeparatorTop[]=array('separator'=>1, "separator_header"=>Language::GetMessage("Search code"));
		$aTmpSeparatorAnalog[]=array('separator'=>1, "separator_header"=>Language::GetMessage("Cross for search code"));
		
		$sTitle="";
		$aTmpTop=array();

		// original code cat id
		if($this->sPref) {
			$iIdCat=Db::GetOne("select id from cat where pref='".$this->sPref."' ");
		}
		// end
	
		if ($aItem) {//Debug::PrintPre($aItem,0);
			foreach($aItem as $key => $aValue) {

				if ($this->sPref && $aValue['code_']==$this->aCode[0] && $aValue['pref']!=$this->sPref
				&& !array_key_exists($aValue['item_code'],$this->aItemCodeCross)
				&& ( ($aValue['is_cat_virtual'] == 0 && $aValue['id_cat_virtual'] == 0)
				|| ($aValue['is_cat_virtual'] == 0 && $iIdCat!=$aValue['id_cat_virtual']) )
				) continue;

				if (!$bSeparator && !$bSeparatorTop) {
					$bSeparatorTop=true;

					if ($aValue['price']==0 && $aValue['code_']==$this->aCode[0] && $aValue['pref']==$this->sPref) {
						$i=1; $bEmptyData=true;
						while ($aItem[$i]['code_']==$this->aCode[0] && $aItem[$i]['pref']==$this->sPref) {
							if ($aItem[$i]['price']>0) $bEmptyData=false;
							$i++;
						}
						if ($bEmptyData) {
							$aTmpTop[]=$aItem[$key];
							$iValue = trim((isset($aItem[$key][Base::$aRequest['sort']]) ? $aItem[$key][Base::$aRequest['sort']] : ""));
							if (Base::$aRequest['sort'] == 'price')
								$iValue = $aValue['price'];
							elseif (Base::$aRequest['sort'] == 'stock')
								$iValue = $aValue['stock_filtered'];
							
							$aTmpSortTop[] = $iValue;
							continue;
						}

					}  elseif($aValue['code_']!=$this->aCode[0] || $aValue['pref']!=$this->sPref) {
						if (!$this->aCats){
							$aCatTmp=Db::GetAll("select * from cat as c where c.visible=1");
							foreach($aCatTmp as $sKeyCat => $aValueCat)
								$this->aCats[$aValueCat['id_tof']] = $aValueCat;
						}
					    $aPartInfo=TecdocDb::GetPartInfo(array(
					        'item_code'=>$this->sPref."_".$this->aCode[0]
					    ),$this->aCats);

						if ($aPartInfo) {
							$aCat=Db::GetRow(Base::GetSql("Cat",array(
							'pref'=>$aPartInfo['pref']
							)));

							$sImage='';
							if ($aCat['is_use_own_logo'] && $aCat['image'])
								$sImage = $aCat['image'];
							elseif ($aCat['image_tecdoc'])
							 	$sImage = Base::$aGeneralConf['TecDocUrl']."/imgbank/tcd/".$aCat['image_tecdoc'];							 
								
							$aTmpTop[]=array("id_brand"=>$aCat['id_tof'],
							"code"=>Catalog::StripCode($aPartInfo['code']),
							"name_translate"=>$aPartInfo['name'],
							"brand"=>$aPartInfo['brand'],
							"pref"=>$aPartInfo['pref'],
							"name"=>$aPartInfo['name'],
							"item_code"=>$aPartInfo['pref'].'_'.Catalog::StripCode($aPartInfo['code']),
							'image_logo'=>$sImage,
							);
							$iValue = trim((isset($aValue[Base::$aRequest['sort']]) ? $aValue[Base::$aRequest['sort']] : ""));
							if (Base::$aRequest['sort'] == 'price')
								$iValue = $aValue['price'];
							elseif (Base::$aRequest['sort'] == 'stock')
								$iValue = $aValue['stock_filtered'];
						} else {
							$aCat=Db::GetRow(Base::GetSql("Cat",array(
							'pref'=>$this->sPref
							)));
							if ($aCat) {
								if (substr($this->aCode[0],0,3)=="ZZZ") $bHideCode=1; else $bHideCode=0;
								$sImage='';
								if ($aCat['is_use_own_logo'] && $aCat['image'])
									$sImage = $aCat['image'];
								elseif ($aCat['image_tecdoc'])
									$sImage = Base::$aGeneralConf['TecDocUrl']."/imgbank/tcd/".$aCat['image_tecdoc'];
								
								$aTmpTop[]=array(
									'brand'=>$aCat['title'], 
									'id_brand'=>$aCat['id_tof'], 
									'code'=>$this->aCode[0],
									'hide_code'=>$bHideCode,
									'pref'=>$aCat['pref'],
									'item_code'=>$aCat['pref'].'_'.$this->aCode[0],
									'image_logo'=>$sImage,
								);
									$iValue = trim((isset($aValue[Base::$aRequest['sort']]) ? $aValue[Base::$aRequest['sort']] : ""));
									if (Base::$aRequest['sort'] == 'price')
										$iValue = $aValue['price'];
									elseif (Base::$aRequest['sort'] == 'stock')
										$iValue = $aValue['stock_filtered'];
							}
						}
						$bSeparator=true;
						$aTmpAnalog[]=$aValue;
						$aTmpSortAnalog[]=$iValue;
						continue;
					}
				}

				if ($aValue['price']==0) continue;

				if (!$bSeparator && ($aValue['code_']!=$this->aCode[0] || $aValue['pref']!=$this->sPref)) 
					$bSeparator=true;

				if (!$bSeparator  && $this->bShowSeparator
				&& ($aValue['code_']!=$this->aCode[0] || $aValue['pref']!=$this->sPref)) 
					$bSeparator=true;

				if ($aValue['name_translate'])
					$aValue['name'] = $aValue['name_translate'];
				
					
					if ($bSeparator) {
						$aTmpAnalog[]=$aValue;
						
							$iValue = trim((isset($aValue[Base::$aRequest['sort']]) ? $aValue[Base::$aRequest['sort']] : ""));
							if (Base::$aRequest['sort'] == 'price') 
								$iValue = $aValue['price'];
							elseif (Base::$aRequest['sort'] == 'stock')
								$iValue = $aValue['stock_filtered'];
								
							$aTmpSortAnalog[]=$iValue;
					}
					else {
						$aTmpTop[]=$aValue;
						$iValue = trim((isset($aValue[Base::$aRequest['sort']]) ? $aValue[Base::$aRequest['sort']] : ""));
						if (Base::$aRequest['sort'] == 'price') 
							$iValue = $aValue['price'];
						elseif (Base::$aRequest['sort'] == 'stock')
							$iValue = $aValue['stock_filtered'];
						
						$aTmpSortTop[]=$iValue;
					}

				if (!$sTitle && Catalog::StripCode(Base::$aRequest['code'])==$aValue['code'] ) {
					$sTitle=$aValue['name_translate']." ".$aValue['brand']." ". $aValue['code']." "
					.Base::$language->getMessage('buy')
					.", ".$aValue['name_translate']." ".$aValue['brand']." ". $aValue['code']." "
					.Base::$language->getMessage('price').", "
					.Base::$language->getMessage(' - Parts for code auto ');
				}
			}

			$sType = SORT_STRING;
			if ($aTmpTop) {
				if (Base::$aRequest['sort'] == 'price' || Base::$aRequest['sort'] == 'term_day'
						|| Base::$aRequest['sort'] == 'term_provider'
						|| Base::$aRequest['sort'] == 'term'
						|| Base::$aRequest['sort'] == 'stock')
					$sType = SORT_NUMERIC;
					
				if ($aTmpSortTop)
					if (Base::$aRequest['way'] && Base::$aRequest['way'] == 'down')
						array_multisort ($aTmpSortTop, SORT_DESC, $sType, $aTmpTop);
					else
						array_multisort ($aTmpSortTop, SORT_ASC, $sType, $aTmpTop);

				if (!$bSeparator) 
					$aTmpSeparatorTop = array();
			}
			else {
				$aTmpTop = array();
				$aTmpSeparatorTop = array();
				if (!$aTmpAnalog)
					$aTmpSeparatorAnalog = array();
			}
				
			if ($aTmpAnalog) {
				if (Base::$aRequest['sort'] == 'price' || Base::$aRequest['sort'] == 'term' 
						|| Base::$aRequest['sort'] == 'term_provider'
						|| Base::$aRequest['sort'] == 'term_day'
						|| Base::$aRequest['sort'] == 'stock')
					$sType = SORT_NUMERIC;
					
				if (Base::$aRequest['way'] && Base::$aRequest['way'] == 'down')
					array_multisort ($aTmpSortAnalog, SORT_DESC, $sType, $aTmpAnalog);
				else
					array_multisort ($aTmpSortAnalog, SORT_ASC, $sType, $aTmpAnalog);
			}
			else {
				$aTmpAnalog = array();
				$aTmpSeparatorAnalog = array();
			}
			
			
			$aItem=array_merge($aTmpSeparatorTop,$aTmpTop,$aTmpSeparatorAnalog,$aTmpAnalog);
		
			// check if empty
			if (count($aItem) == 2 && $aItem[0]['separator'] && (!isset($aItem[1]['price']) || $aItem[1]['price'] == 0))
				$aItem = array();

            if($aItem) {
            	$bHaveAnalog = true;
            	$bHaveFirstseparator = true;
            	if (!$aTmpSeparatorAnalog)
            		$bHaveAnalog=false;
            	if (!$aTmpSeparatorTop)
            		$bHaveFirstseparator = false;
            	
                $this->PosPriceParse($aItem,$bHaveFirstseparator,$bHaveAnalog);
            }
            
            //provider info
            if ($aItem) {
                foreach($aItem as $key => $aValue) {
                    if (!$aValue['id_provider'])
                        continue;
                    $aItem[$key]['history'] = '';
                    $aProviderInfo = Base::$db->getAll("select * from user_provider up
							inner join user u ON u.id = up.id_user
							where id_user = ".$aValue['id_provider']);
                    if ($aProviderInfo[0]) {
                        Base::$tpl->assign('aProviderInfo',$aProviderInfo[0]);
                        $aItem[$key]['history'] = Base::$tpl->fetch('catalog/row_provider_log.tpl');
                    }
                }
            }
            
			//select images
			$aCode=array();
			$aCodeTecdoc=array();
			if ($aItem) {
				foreach ($aItem as $sKey => $aValue) {
					if($aValue['code']) $aCode[]=$aValue['code'];
					if($aValue['code'] && !$aValue['hide_tof_image']) $aCodeTecdoc[]=$aValue['code'];
				}
			}
			$aCode=array_unique($aCode);
			$sCodes="'".implode("','", $aCode)."'";
				
			$aCodeTecdoc=array_unique($aCodeTecdoc);
			$sCodeTecdoc="'".implode("','", $aCodeTecdoc)."'";
				
			$aArtIds=TecdocDb::GetImages(array(
					'codes'=>$sCodes,
					'codesTD'=>$sCodeTecdoc,
			));

			if ($aItem) {
				foreach ($aItem as $sKey => $aValue) {
					$aItem[$sKey]['image']=$aArtIds[mb_strtoupper($aValue['item_code'],'utf-8')]['img_path'];
				}
			}
			//end images

			// check if empty
			if (count($aItem) == 2 && $aItem[0]['separator'] && (!isset($aItem[1]['price']) || $aItem[1]['price'] == 0))
				$aItem = array();
		}
        if(Base::$aRequest['action']!='catalog_part_info_view'){
            Content::SetMetaTagsPage('price:',array(
                'code' => $aItem[1]['code'],
                'brand' => $aItem[1]['brand'],
                'name' => $aItem[1]['name_translate']
            ));
        }
		
	}
	//-----------------------------------------------------------------------------------------------
	function ActualFilter(&$aItem) {
	    if(count($aItem['childs'])==0) return;
	    
	    $aTmp=array();
	    $aExists=array();
	    
	    //search min term and price
	    $iPrice=9999999;
	    $iTerm=9999999;
	    $iSelectedKey=-1;
	    $sNameTranslate = '';
	    foreach($aItem['childs'] as $sKey => $aValue) {
	    	if ($sNameTranslate == '' && $aValue['name_translate']!='')
	    		$sNameTranslate = $aValue['name_translate'];
	        if($aValue['price_c'] <= $iPrice && $aValue['term_day'] <= $iTerm) {
	            $iPrice=$aValue['price_c'];
	            $iTerm=$aValue['term_day'];
	            $iSelectedKey=$sKey;
	        }
	    }
	    if($iSelectedKey!='-1') {
	        //if(!in_array($aItem['childs'][$iSelectedKey]['id'], $aExists)) {
	        if(!$aExists[$aItem['childs'][$iSelectedKey]['id']]) { 
	            $aTmp[]=$aItem['childs'][$iSelectedKey];
	            $aExists[$aItem['childs'][$iSelectedKey]['id']]=$aItem['childs'][$iSelectedKey]['id'];
	        }
	    }
	    
	    //search min term
	    $iSelectedKey=-1;
	    foreach($aItem['childs'] as $sKey => $aValue) {
	        if($aValue['term_day'] < $iTerm) {
	            $iTerm=$aValue['term_day'];
	            $iSelectedKey=$sKey;
	        }
	    }
	    if($iSelectedKey!='-1') {
	        //if(!in_array($aItem['childs'][$iSelectedKey]['id'], $aExists)) {
	        if(!$aExists[$aItem['childs'][$iSelectedKey]['id']]) {
	            $aTmp[]=$aItem['childs'][$iSelectedKey];
	            $aExists[$aItem['childs'][$iSelectedKey]['id']]=$aItem['childs'][$iSelectedKey]['id'];
	        }
	    }
	    
	    //search min price
	    $iSelectedKey=-1;
	    foreach($aItem['childs'] as $sKey => $aValue) {
	        if($aValue['price_c'] < $iPrice) {
	            $iPrice=$aValue['price_c'];
	            $iSelectedKey=$sKey;
	        }
	    }
	    if($iSelectedKey!='-1' /*&& $iPrice < $aItem['price_c']*/) {
	        //if(!in_array($aItem['childs'][$iSelectedKey]['id'], $aExists)) {
	        if(!$aExists[$aItem['childs'][$iSelectedKey]['id']]) {
	            $aTmp[]=$aItem['childs'][$iSelectedKey];
	            $aExists[$aItem['childs'][$iSelectedKey]['id']]=$aItem['childs'][$iSelectedKey]['id'];
	        }
	    }
	    
	    //search always_show
	    foreach($aItem['childs'] as $sKey => $aValue) {
	        //if($aValue['always_show'] && !in_array($aValue['id'], $aExists)) {
	        if($aValue['always_show'] && !$aExists[$aValue['id']]) {
	            $aTmp[]=$aValue;
	            $aExists[$aValue['id']]=$aValue['id'];
	        }
	    }
	    
	    //search max stock ">+"
	    $aMaxStock=array();$iTerm=9999999;$iStock=0;
	    foreach($aItem['childs'] as $sKey => $aValue) {
		$istock_filtered = $aValue['stock_filtered'];
		if(strpbrk($aValue['stock'], '>+')!==false)
		    $istock_filtered+=1;
	        if($istock_filtered>=$iStock && $aValue['term']<$iTerm) {
	            //if(!in_array($aValue['id'], $aExists)) {
	            if(!$aExists[$aValue['id']]) {
	                $aMaxStock=$aValue;
					$iTerm=$aValue['term'];
					$iStock=$istock_filtered;
	            }
	        }
	    }
	    if ($aMaxStock) {
		$aTmp[]=$aMaxStock;
		$aExists[$aMaxStock['id']]=$aMaxStock['id'];
	    }
	    
        if(Auth::$aUser['type_']=='manager') {
            //not replace
            foreach($aItem['childs'] as $sKey => $aValue) {
                //if(in_array($aValue['id'], $aExists)) {
                if($aExists[$aValue['id']]) {
                    $aItem['childs'][$sKey]['user_view']=1;
                }
            }
             
            //if(in_array($aItem['id'], $aExists)) $aItem['user_view']=1;
            if($aExists[$aItem['id']]) $aItem['user_view']=1;
            
            $aFirst=array_shift($aItem['childs']);
            $aTmp=array_values($aItem['childs']);
            // show stong
        } else {
            //resort
            if(Base::$aRequest['sort']) Base::$aRequest['order']=Base::$aRequest['sort'];
            if(Base::$aRequest['order']=='term') Base::$aRequest['order']="term_day";
            if(!Base::$aRequest['order']) Base::$aRequest['order']='price';
            if(!Base::$aRequest['way']) Base::$aRequest['way']='asc';
             
            usort($aTmp, function($a,$b){
                if ($a[Base::$aRequest['order']] == $b[Base::$aRequest['order']]) {
                    return 0;
                }
                if(Base::$aRequest['way']=='asc' || Base::$aRequest['way']=='up'){
                    return ($a[Base::$aRequest['order']] < $b[Base::$aRequest['order']]) ? -1 : 1;
                } else {
                    return ($a[Base::$aRequest['order']] > $b[Base::$aRequest['order']]) ? -1 : 1;
                }
            });
            
            // replace root position
            $aFirst=array_shift($aTmp);
            $aTmp=array_values($aTmp);
            $aItem['item_code']=$aFirst['item_code'];
            $aItem['id_provider']=$aFirst['id_provider'];
            $aItem['term_day']=$aFirst['term_day'];
            $aItem['term']=$aFirst['term'];
            $aItem['id']=$aFirst['id'];
            $aItem['provider']=$aFirst['provider'];
            $aItem['history']=$aFirst['history'];
            $aItem['id_price_group']=$aFirst['id_price_group'];
            $aItem['price']=$aFirst['price'];
            $aItem['price_original']=$aFirst['price_original'];
            $aItem['stock']=$aFirst['stock'];
            $aItem['stock_filtered']=$aFirst['stock_filtered'];
            $aItem['always_show']=$aFirst['always_show'];
            $aItem['provider_remark']=$aFirst['provider_remark'];
            $aItem['margin_id']=$aFirst['margin_id'];
            $aItem['code_in']=$aFirst['code_in'];
            $aItem['priority']=$aFirst['priority'];
            $aItem['code_']=$aFirst['code_'];
            $aItem['zzz_code']=$aFirst['zzz_code'];
            $aItem['id_currency']=$aFirst['id_currency'];
	        $aItem['sIcons']=$aFirst['sIcons'];
	        $aItem['user_view']=$aFirst['user_view'];
	        $aItem['brand']=$aFirst['brand'];
	        $aItem['post_date']=$aFirst['post_date'];
	        $aItem['code_provider']=$aFirst['code_provider'];
	        $aItem['is_our_store']=$aFirst['is_our_store'];
	        $aItem['name']=$aFirst['name'];
	        $aItem['name_translate']=$aFirst['name_translate'];
	        $aItem['part_rus']=$aFirst['part_rus'];
	        if (!$aItem['name_translate'] && $sNameTranslate!='')
	        	$aItem['name_translate'] = $sNameTranslate;
            $aItem['childs']=$aTmp;
        }
	
	    if(count($aItem['childs'])==0) unset($aItem['childs']);
	}
	//-----------------------------------------------------------------------------------------------
	function PosPriceParse(&$aItem,$bHaveFirstSeparator=true,$bHaveAnalogs=true) {
	    $oCurrency = new Currency();
// 	    Debug::PrintPre($aItem);
	    //for filter
	    $aPriceForFilter=array('min_price'=>0,'max_price'=>0);
	    foreach ($aItem as $sKey => $aValue){
	     
	         
	         if($aPriceForFilter['min_price']==0||$aValue['price']<$aPriceForFilter['min_price'])
	             $aPriceForFilter['min_price']=$aValue['price'];
	         if($aValue['price']>$aPriceForFilter['max_price'])
	             $aPriceForFilter['max_price']=$aValue['price'];
	     }
	     
	     $aPriceForFilter['min_price']=$oCurrency->PrintPrice($aPriceForFilter['min_price'],0,0,'<none>');
	     $aPriceForFilter['max_price']=$oCurrency->PrintPrice($aPriceForFilter['max_price'],0,0,'<none>');
	     Base::$tpl->assign('aPriceForFilter',$aPriceForFilter);

        
	     if($aItem&&Base::$aRequest['max_price']&&(Base::$aRequest['min_price']||Base::$aRequest['min_price']==0))
	     foreach ($aItem as $sKey => $aValue){ 
	         if((float)$oCurrency->PrintPrice($aValue['price'],0,0,'<none>')>(float)Base::$aRequest['max_price'] || (float)$oCurrency->PrintPrice($aValue['price'],0,0,'<none>')<(float)Base::$aRequest['min_price'])
	             unset($aItem[$sKey]);
	     }
	     //end filter
	     
	    if(Base::GetConstant('group_price_items','1')==0 /*|| Auth::$aUser['type_']=='manager'*/) return;
	   
	    
	    if(count($aItem[0])==0) return;

	    /*if($bHaveFirstSeparator) {
	        if ($aItem[0]['separator']==1) {
	           $aFirst=array_shift($aItem); // надпись запрошенный код
	        } else {
	            $bHaveFirstSeparator=false;
	        }
	    }*/
	    
	    $bAnalog=false;
	    if ($aItem[0]['separator']==1) {
	    	if (!$bHaveFirstSeparator) {
	    		$aSecond=array_shift($aItem); // separator analog
	    		$bAnalog=true;
	    	}
	    	else 
	    		$aFirst=array_shift($aItem); // separator code
	    }
	    $aAnalogs=array();
	    $aRequestedCode=array();

	    foreach ($aItem as $sKey => $aValue){

	        //for filter
	        if($aPriceForFilter['min_price']==0||$aValue['price']<$aPriceForFilter['min_price'])
	            $aPriceForFilter['min_price']=$aValue['price'];
	        if($aValue['price']>$aPriceForFilter['max_price'])
	            $aPriceForFilter['max_price']=$aValue['price'];
	        
	            
	        if($bHaveAnalogs) {
    	        if ($aValue['separator']==1) {
    	            $aSecond=$aItem[$sKey]; // надпись аналоги
    	            $bAnalog=true;
    	            continue;
    	        }
	        } else {
	            $bAnalog=true;
	        }
	
	        if($bAnalog) {
	            if(!array_key_exists($aValue['item_code'], $aAnalogs)) {
	                $aValue['price_c']=$oCurrency->PrintPrice($aValue['price'],0,0,'<none>');
	                $aValue['price_original_c']=$oCurrency->PrintPrice($aValue['price_original']);
	                $aAnalogs[$aValue['item_code']]=$aValue;
	                
	                //add to child
	                $aAnalogs[$aValue['item_code']]['childs'][]=array(
	                    'item_code'=>$aValue['item_code'],
	                    'id_provider'=>$aValue['id_provider'],
	                    'term_day'=>$aValue['term_day'],
	                	'term'=>$aValue['term'],
	                    'price_c'=>$oCurrency->PrintPrice($aValue['price'],0,0,'<none>'),
	                    'id'=>$aValue['id'],
	                    'provider'=>$aValue['provider'],
	                    'history'=>$aValue['history'],
	                    'id_price_group'=>$aValue['id_price_group'],
	                    'price_original_c'=>$oCurrency->PrintPrice($aValue['price_original']),
	                    'price'=>$aValue['price'],
	                    'price_original'=>$aValue['price_original'],
	                    'stock'=>$aValue['stock'],
	                	'stock_filtered'=>$aValue['stock_filtered'],
	                    'always_show'=>$aValue['always_show'],
	                    'provider_remark'=>$aValue['provider_remark'],
	                    'margin_id'=>$aValue['margin_id'],
	                    'code_in'=>$aValue['code_in'],
	                    'priority'=>$aValue['priority'],
	                    'code_'=>$aValue['code_'],
	                    'zzz_code'=>$aValue['zzz_code'],
	                    'sIcons'=>$aValue['sIcons'],
	                    'brand'=>$aValue['brand'],
	                    'post_date'=>$aValue['post_date'],
	                    'code_provider'=>$aValue['code_provider'],
	                    'is_our_store'=>$aValue['is_our_store'],
	                    'name'=>$aValue['name'],
	                    'name_translate'=>$aValue['name_translate'],
	                    'part_rus'=>$aValue['part_rus'],
	                    'id_currency'=>$aValue['id_currency'],
	                );
	            }
	            else {
	            	if (!$aAnalogs[$aValue['item_code']]['name_translate']
	            		&& $aValue['name_translate'])
	            		$aAnalogs[$aValue['item_code']]['name_translate'] = $aValue['name_translate'];
	            	
	            	$aAnalogs[$aValue['item_code']]['childs'][]=array(
	                'item_code'=>$aValue['item_code'],
	                'id_provider'=>$aValue['id_provider'],
	                'term_day'=>$aValue['term_day'],
	            	'term'=>$aValue['term'],
	                'price_c'=>$oCurrency->PrintPrice($aValue['price'],0,0,'<none>'),
	                'id'=>$aValue['id'],
	                'provider'=>$aValue['provider'],
	                'history'=>$aValue['history'],
	                'id_price_group'=>$aValue['id_price_group'],
	                'price_original_c'=>$oCurrency->PrintPrice($aValue['price_original']),
	                'price'=>$aValue['price'],
	                'price_original'=>$aValue['price_original'],
	                'stock'=>$aValue['stock'],
	            	'stock_filtered'=>$aValue['stock_filtered'],
	                'always_show'=>$aValue['always_show'],
                    'provider_remark'=>$aValue['provider_remark'],
                    'margin_id'=>$aValue['margin_id'],
                    'priority'=>$aValue['priority'],
                    'code_'=>$aValue['code_'],
                    'code_in'=>$aValue['code_in'],
                    'zzz_code'=>$aValue['zzz_code'],
	                'sIcons'=>$aValue['sIcons'],
	                'brand'=>$aValue['brand'],
                    'post_date'=>$aValue['post_date'],
	                'code_provider'=>$aValue['code_provider'],
	            	'is_our_store'=>$aValue['is_our_store'],
            		'name'=>$aValue['name'],
	            	'name_translate'=>$aValue['name_translate'],
            		'part_rus'=>$aValue['part_rus'],
            		'id_currency'=>$aValue['id_currency'],
	            );
	            }
	        } else {
	            // группировка запрошенного кода
	            if(!$aRequestedCode) {
	                $aValue['price_c']=$oCurrency->PrintPrice($aValue['price'],0,0,'<none>');
	                $aValue['price_original_c']=$oCurrency->PrintPrice($aValue['price_original']);
	                $aRequestedCode=$aValue;
	                
	                //add to child
	                $aRequestedCode['childs'][]=array(
	                    'item_code'=>$aValue['item_code'],
	                    'id_provider'=>$aValue['id_provider'],
	                    'term_day'=>$aValue['term_day'],
	                	'term'=>$aValue['term'],
	                    'price_c'=>$oCurrency->PrintPrice($aValue['price'],0,0,'<none>'),
	                    'id'=>$aValue['id'],
	                    'provider'=>$aValue['provider'],
	                    'history'=>$aValue['history'],
	                    'id_price_group'=>$aValue['id_price_group'],
	                    'price_original_c'=>$oCurrency->PrintPrice($aValue['price_original']),
	                    'price'=>$aValue['price'],
	                    'price_original'=>$aValue['price_original'],
	                    'stock'=>$aValue['stock'],
	                	'stock_filtered'=>$aValue['stock_filtered'],
	                    'always_show'=>$aValue['always_show'],
	                    'provider_remark'=>$aValue['provider_remark'],
	                    'margin_id'=>$aValue['margin_id'],
	                    'code_in'=>$aValue['code_in'],
	                    'priority'=>$aValue['priority'],
	                    'code_'=>$aValue['code_'],
	                    'zzz_code'=>$aValue['zzz_code'],
	                    'sIcons'=>$aValue['sIcons'],
	                    'brand'=>$aValue['brand'],
	                    'post_date'=>$aValue['post_date'],
	                    'code_provider'=>$aValue['code_provider'],
	                    'is_our_store'=>$aValue['is_our_store'],
	                    'name'=>$aValue['name'],
	                    'name_translate'=>$aValue['name_translate'],
	                    'part_rus'=>$aValue['part_rus'],
	                    'id_currency'=>$aValue['id_currency'],
	                );
	            }
	            else {
	            	if (!$aRequestedCode['name_translate']
	            		&& $aValue['name_translate'])
	            		$aRequestedCode['name_translate'] = $aValue['name_translate'];
	            	
	            	$aRequestedCode['childs'][]=array(
	                'item_code'=>$aValue['item_code'],
	                'id_provider'=>$aValue['id_provider'],
	                'term_day'=>$aValue['term_day'],
	            	'term'=>$aValue['term'],
	                'price_c'=>$oCurrency->PrintPrice($aValue['price'],0,0,'<none>'),
	                'id'=>$aValue['id'],
	                'provider'=>$aValue['provider'],
	                'history'=>$aValue['history'],
	                'id_price_group'=>$aValue['id_price_group'],
	                'price_original_c'=>$oCurrency->PrintPrice($aValue['price_original']),
	                'price'=>$aValue['price'],
	                'price_original'=>$aValue['price_original'],
	                'stock'=>$aValue['stock'],
	            	'stock_filtered'=>$aValue['stock_filtered'],
	                'always_show'=>$aValue['always_show'],
                    'provider_remark'=>$aValue['provider_remark'],
                    'margin_id'=>$aValue['margin_id'],
                    'code_in'=>$aValue['code_in'],
                    'priority'=>$aValue['priority'],
                    'code_'=>$aValue['code_'],
                    'zzz_code'=>$aValue['zzz_code'],
	                'sIcons'=>$aValue['sIcons'],
	                'brand'=>$aValue['brand'],
                    'post_date'=>$aValue['post_date'],
	                'code_provider'=>$aValue['code_provider'],
	            	'is_our_store'=>$aValue['is_our_store'],
            		'name'=>$aValue['name'],
	            	'name_translate'=>$aValue['name_translate'],
	            	'part_rus'=>$aValue['part_rus'],
	            	'id_currency'=>$aValue['id_currency'],
	            );
	            }
	        }
	    }
	    unset($bAnalog);
	    unset($aValue);
	    unset($sKey);
	
	    if(1) {
	        Catalog::ActualFilter($aRequestedCode);
	    }
	
	    $aOut=array();
	    if($bHaveFirstSeparator) {
	        $aOut[]=$aFirst;
	    }
	    if($aRequestedCode) $aOut[]=$aRequestedCode;
	    if($aSecond && $bHaveAnalogs) $aOut[]=$aSecond;
	    if($aAnalogs) foreach ($aAnalogs as $aValue) {
	        if(count($aValue['childs'])>0) {
	
	            if(1 ) {
	                Catalog::ActualFilter($aValue);
	            }
	        }
	        $aOut[]=$aValue;
	    }

	    $aItem=$aOut;
	    unset($aValue);
	    unset($aOut);
	    unset($aFirst);
	    unset($aRequestedCode);
	    unset($aSecond);
	    unset($aAnalogs);
	    
	    if(Auth::$aUser['type_']=='manager'){
    	    $aTmp=array();
    	    foreach ($aItem as $aValue) {
    	        if ($aValue['childs']) {
    	            $aFirst=$aValue;
    	            unset($aFirst['childs']);
    	            $aTmp[]=$aFirst;
    	            foreach ($aValue['childs'] as $aValueChild) {
    	                unset($aValue['user_view']);
    	                $aTmpElem=array_merge($aValue,$aValueChild);
    	                unset($aTmpElem['childs']);
    	                $aTmp[]=$aTmpElem;
    	            }
    	        } else {
    	            $aTmp[]=$aValue;
    	        }
    	    }
    	    $aItem=$aTmp;
	    }
	}
	//-----------------------------------------------------------------------------------------------
	function GetJson()
	{
		if (Base::$aRequest['id_make']) {
// 			$aData=Db::GetAll(Base::GetSql("OptiCatalog/Model",array("id_make"=>Base::$aRequest['id_make']))
// 			." order by name ");
			$aData=TecdocDb::GetModels(array("id_make"=>Base::$aRequest['id_make']));

			$aRet=array();
			if ($aData) foreach ($aData as $sKey => $aValue) {
				$aRet[$sKey]['id']=$aValue['id'];
				//$aRet[$sKey]['name']=iconv('windows-1251','utf-8',$aValue['name']);
				$aRet[$sKey]['name']=$aValue['name']
				."&nbsp;&nbsp;&nbsp;(".$aValue['month_start'].".".$aValue['year_start']."-"
				.$aValue['month_end'].".".$aValue['year_end'].")";
			}

			echo json_encode($aRet);
		} elseif (Base::$aRequest['id_model']) {
// 			$aData=Db::GetAll(Base::GetSql("OptiCatalog/ModelDetail",array("id_model"=>Base::$aRequest['id_model']))
// 			." order by name ");
			$aData=TecdocDb::GetModelDetails(array("id_model"=>Base::$aRequest['id_model']));

			$aRet=array();
			if ($aData) foreach ($aData as $sKey => $aValue) {
				$aRet[$sKey]['id']=$aValue['typ_id'];
				$aRet[$sKey]['name']=iconv('windows-1251','utf-8',$aValue['name']);
			}

			echo json_encode($aRet);
		}
		die();
	}
	//-----------------------------------------------------------------------------------------------
	function ViewInfoPart() {
	    Base::$sText.=Base::$tpl->fetch('price_profile/popup.tpl');

	    $this->aCats=Db::GetAssoc("select c.id_tof,c.id,c.pref,c.title,c.image,c.name from cat as c where c.visible=1");
	    $this->aCat=Db::GetRow("select * from cat as c where name='".Base::$aRequest['cat_name']."' ");
	    
		Base::$bXajaxPresent=true;
		Base::$aRequest['code']=Catalog::StripCode(Base::$aRequest['code']);

		if(!Base::$aRequest['pref'] && Base::$aRequest['cat_name']){
		    Base::$aRequest['pref']=$this->aCat['pref'];
		}
		if(Base::$aRequest['pref'] && !Base::$aRequest['item_code']){
			Base::$aRequest['item_code']=Base::$aRequest['pref'].'_'.Base::$aRequest['code'];
		}
		if(Base::$aRequest['pref'] && !Base::$aRequest['id_brand']){
		    Base::$aRequest['id_brand']=$this->aCat['id_tof'];
		}

		if(strpos($_SERVER['REQUEST_URI'], '?')!==FALSE) {
			if(Base::$aRequest['id_provider'])
			MultiLanguage::Redirect('/buy/'.Base::$aRequest['cat_name'].'_'.Base::$aRequest['code'].'_'.Base::$aRequest['id_provider']);
			else
			MultiLanguage::Redirect('/buy/'.Base::$aRequest['cat_name'].'_'.Base::$aRequest['code']);
		}
		if (Base::$aRequest['code'] && Base::$aRequest['id_brand'] && !Base::$aRequest['art_id']) {
		    Base::$aRequest['art_id']=TecdocDb::GetArt(array(
		        'code'=>Base::$aRequest['code'],
		        'id_tof'=>Base::$aRequest['id_brand']
		    ));
		}

		if (!Base::$aRequest['art_id'] && Base::$aRequest['item_code']) {
			$aPartInfo=TecdocDb::GetPartInfo(array(
			'item_code'=>Base::$aRequest['item_code']
			),$this->aCats);
			
			if ($aPartInfo['art_id']) Base::$aRequest['art_id']=$aPartInfo['art_id'];
		}

		if (!$aPartInfo['id_cat_part']) {
		    $aPartInfo=TecdocDb::GetPartInfo(array(
		        'item_code'=>Base::$aRequest['item_code']
		    ),$this->aCats);
		}
		
		//select art_id for virtual cat
		if($this->aCat['is_cat_virtual']!=0) {
	    	$aVag=Db::getAll("Select c.* from cat c	where c.visible=1 and c.id_cat_virtual=".$this->aCat['id']);
	    	
	    	if ($aVag) {
	    	    foreach ($aVag as $aValVag){
    	    	    $aAs=TecdocDb::GetArt(array(
    	    	    'code'=>Base::$aRequest['code'],
    	    	    'id_tof'=>$aValVag['id_tof']
    	    	    ));
    	    	    if(Base::$aRequest['art_id']==""&&$aAs!=''&&$aAs!=0)
    	    	          Base::$aRequest['art_id'].=$aAs;
    	    	    elseif($aAs!=''&&$aAs!=0) 
    	    	          Base::$aRequest['art_id'].=",".$aAs;
    	    	    
	    	    }
	    	}
	    	unset($aVag);
		}
		//Debug::PrintPre(Base::$aRequest['art_id']);
		if ($this->aCat['id_cat_virtual']!=0) {
		    $aVag=Db::getAll("Select c.* from cat c inner join cat c2 on c2.id = c.id_cat_virtual and c2.visible=1
	    				where c.visible=1 and c.id_cat_virtual=".$this->aCat['id_cat_virtual']);
		    if ($aVag) {
	    	    foreach ($aVag as $aValVag){
                    $aAs=TecdocDb::GetArt(array(
    	    	    'code'=>Base::$aRequest['code'],
    	    	    'id_tof'=>$aValVag['id_tof']
    	    	    ));
                    if(Base::$aRequest['art_id']==""&&$aAs!=''&&$aAs!=0)
                        Base::$aRequest['art_id'].=$aAs;
                    elseif($aAs!=''&&$aAs!=0)
                        Base::$aRequest['art_id'].=",".$aAs;
	    	    }
	    	}
	    	unset($aVag);
		}
		//Debug::PrintPre(Base::$aRequest['art_id']);
		
		if (!Base::$aRequest['art_id'] && !$aPartInfo['id_cat_part']) $aPartInfo['id_cat_part']='-1';

		if (Base::$aRequest['art_id'] || $aPartInfo['id_cat_part']) {

			if (!$aPartInfo && Base::$aRequest['art_id'] && Base::$aRequest['item_code'])
			    $aPartInfo=TecdocDb::GetPartInfo(array(
			    'art_id'=>Base::$aRequest['art_id'],
			    'item_code'=>Base::$aRequest['item_code']
			    ),$this->aCats);

			if (!Base::$aRequest['item_code']) Base::$aRequest['item_code']=$aPartInfo['item_code'];

			if (Base::$aRequest['item_code']) {
			$aRow=Db::GetAll(Base::GetSql('Catalog/Price',array(
			'id_provider'=>Base::$aRequest['id_provider']
			,'aItemCode'=>array(Base::$aRequest['item_code'])
			, 'customer_discount'=>Discount::CustomerDiscount(Auth::$aUser)
			)));
			if(!$aRow)
			$aRow=Db::GetAll(Base::GetSql('Catalog/Price',array(
			'aItemCode'=>array(Base::$aRequest['item_code'])
			, 'customer_discount'=>Discount::CustomerDiscount(Auth::$aUser)
			)));
			}

			$this->sPref = $aRow[0]['pref'];
			$this->aCode[0]=$aRow[0]['code'];
			$this->CallParsePrice($aRow,true);
			if ($aRow[0]['separator'] && $aRow[1]['item_code'])
				array_shift($aRow);
			Base::$tpl->assign('aRowPrice',$aRow[0]);
			
			// build crumbs
			if($aPartInfo['art_id'] && $this->aCat['id_tof']) {
			    $aTree=TecdocDb::GetAssoc("
                            select lsg.ID_tree,g.id_src
                    FROM ".DB_OCAT."cat_alt_link_art lta
                    join ".DB_OCAT."cat_alt_link_str_grp lsg on lsg.ID_grp=lta.ID_grp
                    join ".DB_OCAT."cat_alt_groups as g on lsg.ID_grp=g.id_grp
                    join ".DB_OCAT."cat_alt_articles a on a.ID_art=lta.ID_art
                    join ".DB_OCAT."cat_alt_suppliers s on lta.ID_sup=s.ID_sup
                    where a.id_src = '".$aPartInfo['art_id']."' and s.ID_src = ".$this->aCat['id_tof']);
			
			    if($aTree) {
			        $aWhere='';
			        foreach ($aTree as $iTree => $iGrp) {
			            $aWhere[]="(FIND_IN_SET('".$iTree."',id_tree) and FIND_IN_SET('".$iGrp."',id_group))";
			        }
			
			        $aRubricLast=Db::GetRow("
                                    select distinct * from rubricator
                                    where ".implode(" or \n", $aWhere)
			        );
			        if($aRubricLast['level']=='3') {
			            $aRubric2=Db::GetRow("
                                    select * from rubricator
                                    where id='".$aRubricLast['id_parent']."'
                                ");
			
			            $sRubricPrev=Db::GetRow("
                                    select * from rubricator
                                    where id='".$aRubric2['id_parent']."'
                                ");
			        } elseif($aRubricLast['level']=='2') {
			            $sRubricPrev=Db::GetRow("
                                    select * from rubricator
                                    where id='".$aRubricLast['id_parent']."'
                                ");
			        }
			    }
			}
			
			if (Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator'] && Base::$tpl->_tpl_vars['sAutoName'])
			    Base::$oContent->AddCrumb(Base::$tpl->_tpl_vars['sAutoName'], '/rubricator/'.Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator']);
				
			if($sRubricPrev['name'] && $aRubricLast['name']){
			    Base::$oContent->AddCrumb($sRubricPrev['name'],'/rubricator/'.$sRubricPrev['url']);
			    Base::$oContent->AddCrumb($aRubricLast['name'],'/rubricator/'.$aRubricLast['url']);
			}
			elseif($aRubricLast['name']){
			    Base::$oContent->AddCrumb($aRubricLast['name'],'/rubricator/'.$aRubricLast['url']);			     
			}	
			elseif ($aRow[0]['id_price_group'] != 0 && $aRow[0]['price_group_name']!='') {
				Base::$oContent->AddCrumb($aRow[0]['price_group_name'],'/select/'.$aRow[0]['price_group_code_name']);
				Base::$oContent->AddCrumb($aRow[0]['make'],'/select/'.$aRow[0]['price_group_code_name'].'/b/'.mb_strtolower($aRow[0]['cat_name']));
			}else{
			    Base::$oContent->AddCrumb($aPartInfo['name'],'/rubricator/');
			}
			//end
			
			//Debug::PrintPre($aRow[0]);
			//--------------------------------------------------------------------------------------------
			Base::$tpl->assign('sTablePrice',$this->GetPriceForInfoPart(Base::$aRequest['code'],Base::$aRequest['pref']));
			//--------------------------------------------------------------------------------------------
			
			if (!$aPartInfo['item_code'] && $aRow[0]['item_code']) {
				$aPartInfo['item_code']=$aRow[0]['item_code'];
				$aPartInfo['code']=$aRow[0]['code'];
				$aPartInfo['code_name']=$aRow[0]['code'];
				$aPartInfo['pref']=$aRow[0]['pref'];
				$aPartInfo['brand']=$aRow[0]['brand'];
				$aPartInfo['name']=$aRow[0]['name_translate'];
			} elseif($aPartInfo['item_code'] && $aPartInfo['art_id']) {
				$aPartInfo['code_name']=$aPartInfo['code'];
				$aPartInfo['code']=Catalog::StripCode($aPartInfo['code']);
			}
			
			if (!$aPartInfo['art_id']) {
				$sArtId = $this->GetArtId($aPartInfo['item_code']);
				if ($sArtId)
					$aPartInfo['art_id'] = $sArtId;
			}
			
			if(!$aPartInfo['cat_name'] && $aPartInfo['pref']) {
			    $aPartInfo['cat_name']=Db::GetOne("select name from cat where pref='".$aPartInfo['pref']."' ");
			}
			
			$oPriceSearchLog=new PriceSearchLog();
			$oPriceSearchLog->AddSearch($aRow[0]['pref'],$aRow[0]['code']);
			
			Base::$tpl->assign('aPartInfo',$aPartInfo);
            Resource::Get()->Add('/css/slick.css',1);
            Resource::Get()->Add('/css/colorbox.css',1);

			Base::$oContent->ShowTimer('BeforeGraphic');
			$aArtId = array(Base::$aRequest['art_id']);
			if ($aRow[0]['hide_tof_image'])
				$aArtId = array();
				 
			$aGraphic=TecdocDb::GetImages(array(
			    'aIdGraphic'=>$aArtId,
			    'aIdCatPart'=>array($aPartInfo['id_cat_part'],$aRow[0]['id_cat_part']),
			),$this->aCats,false);

			Base::$tpl->assign('aGraphic',$aGraphic);

			$aPdf=TecdocDb::GetImages(array(
			'aIdGraphic'=>array(Base::$aRequest['art_id']),
			'aIdCatPart'=>array($aPartInfo['id_cat_part'],$aRow[0]['id_cat_part']),
			"type_image"=>"pdf"
			),$this->aCats,false);

			Base::$tpl->assign('aPdf',$aPdf);
			
			if ($aRow[0]['name_translate'])
				$sName = $aRow[0]['name_translate'];
			else
				$sName = $aPartInfo['name'];
			
			if ($aPartInfo['brand'])
				$sName = $aPartInfo['brand'].'  '.$aPartInfo['code'].' ';
			
			Base::$oContent->AddCrumb($sName,'');

			Base::$oContent->ShowTimer('BeforeCriteria');

			//LNB-57 show price group characteristic begin
		    $aFilter = array();
    	    if (MultiLanguage::IsLocale()) {
    	        $sIdHandbook=Db::GetOne("select GROUP_CONCAT(pgf.id_handbook) as id_handbook from price_group_filter as pgf where pgf.id_price_group='".$aRow[0]['id_price_group']."'");
    	        if($sIdHandbook){
        	        $aDataLocale=array(
        	            'table'=>'handbook',
        	            'where'=>" and t.id in (".$sIdHandbook.") ",
        	        );
    	            $aFilterTmp=Base::$language->GetLocalizedAll($aDataLocale, false);
    	        }
    	        if($aFilterTmp){
    	            foreach ($aFilterTmp as $aValue){
    	                $aTmp = array();
    	                $aTmp['id'] = $aValue['id'];
    	                $aTmp['table_'] = $aValue['table_'];
    	                $aTmp['krit_name'] = $aValue['name'];
    	                $aFilter[]=$aTmp;
    	            }
    	        }
    	    }else {
			    $sSql="select h.id,h.table_,h.name as krit_name from handbook as h
						inner join price_group_filter as pgf on pgf.id_handbook=h.id and pgf.id_price_group='".$aRow[0]['id_price_group']."' ";
        	    $aFilter=Db::GetAll($sSql);
    	    }
			if($aFilter) foreach ($aFilter as $sKey => $aValue) {
				$iPriceFilterId=Db::GetOne("select id_".$aValue['table_']." from price_group_param where item_code='".$aRow[0]['item_code']."'");
				
				if (MultiLanguage::IsLocale()) {
				    $aDataLocale=array(
				        'table'=>$aValue['table_'],
				        'where'=>" and id='".$iPriceFilterId."' and visible=1 ",
				        'order'=>" order by t.name "
				    );
				    $aSValue=Base::$language->GetLocalizedRow($aDataLocale);
				    $sValue = $aSValue['name'];
				    unset($aSValue);
				}else{
				    $sValue=Db::GetOne("select name from ".$aValue['table_']." where id='".$iPriceFilterId."' and visible=1");
				}
				
				if(Auth::$aUser['type_']!='manager') {
					if(!$sValue) unset($aFilter[$sKey]);
					else $aFilter[$sKey]['krit_value']=$sValue;
				} else {
					$aFilter[$sKey]['krit_value']=$sValue;
					$aParams = Db::GetAssoc("select id,name from ".$aValue['table_']." where visible=1 order by name");
					if ($aParams) {
					    $aFilter[$sKey]['params']=array("0"=>"не выбрано")+Db::GetAssoc("select id,name from ".$aValue['table_']." where visible=1 order by name");
					} else {
					    $aFilter[$sKey]['params']=array("0"=>"не выбрано");
					}
					
					$aFilter[$sKey]['id']=$aValue['id'];
					$aFilter[$sKey]['krit_selected']=$iPriceFilterId;
					$aFilter[$sKey]['table_']=$aValue['table_'];
				}
			}
			if($aFilter) sort($aFilter);
			//LNB-57 end

			$aCriteria=TecdocDb::GetCriterias(array(
    			'aId'=>array(Base::$aRequest['art_id']),
    			'aIdCatPart'=>array($aPartInfo['id_cat_part']),
    			'id_model_detail'=>Base::$aRequest['id_model_detail'],
    			"type_"=>"all"
			));
			if(!$aFilter) $aFilter=array();
			if(!$aCriteria) $aCriteria=array();
			$aCriteriaData=array_merge($aCriteria,$aFilter);
			
			$oTable=new Table();
			$oTable->sType='array';
			$oTable->sDataTemplate='catalog/row_part_criteria.tpl';
			$oTable->aDataFoTable=$aCriteriaData;
			$oTable->bHeaderVisible=false;
			$oTable->aColumn['name']=array('sTitle'=>'Parametre','sWidth'=>'25%','h2'=>1);
			$oTable->aColumn['value']=array('sTitle'=>'Description','sWidth'=>'50%');
			$oTable->iRowPerPage=500;
			$oTable->sNoItem='No description';
			$oTable->bStepperVisible=false;
			$oTable->bFormAvailable=false;
			$oTable->sClass = "at-table-striped";
			$oTable->sTemplateName='table/index2.tpl';
			Base::$tpl->assign('sTableCriteria',$oTable->getTable());

			$aDataForTable=TecdocDb::GetOriginals(array(
			'art_id'=>Base::$aRequest['art_id'],
			'aIdCatPart'=>array($aPartInfo['id_cat_part']),
			'pref'=>$aPartInfo['pref'],
			'code'=>$aPartInfo['code'],
			'limit'=>6
			));
			
			if(Base::GetConstant('catalog:show_oe','1')==1 && $aPartInfo['code'] && $aPartInfo['pref']) {
			    $iIdTof=Db::GetOne("select id_tof from cat where pref='".$aPartInfo['pref']."' ");
			    if($iIdTof) {
			        $bHaveOeLink=TecdocDb::GetOne("select id_oe from ".DB_OCAT."cat_alt_original 
			            where oe_code='".$aPartInfo['code']."' 
			            and oe_brand='".$iIdTof."' ");
			        if($bHaveOeLink) {
			            Base::$tpl->assign('bHaveOriginal',1);
			        }
			    }
			    if(count($aDataForTable)>0) {
			        Base::$tpl->assign('bHaveOriginal',1);
			    }
			}
			
			if (count($aDataForTable)>5) {
				Base::$tpl->assign('bEnableAdvanceOriginal', 1);
				// JPN-144
				$sPath =  "/original_cross/".$aPartInfo['cat_name']."_".$aPartInfo['code'];
				if (Language::getConstant('global:url_is_not_last_slash',0) == 0)
					$sPath .= "/";
				Base::$tpl->assign('sAdvanceOriginaLink',$sPath);
			}
			
			$oTable=new Table();
			$oTable->sType='array';
			$oTable->aDataFoTable=$aDataForTable;

			$oTable->aColumn['brand']=array('sTitle'=>'Brand','sWidth'=>'15%');
			$oTable->aColumn['code']=array('sTitle'=>'Part Code','sWidth'=>'20%');

			$oTable->iRowPerPage=10;
			$oTable->sDataTemplate='catalog/row_part_original.tpl';
			$oTable->aOrdered=" order by name ";
			$oTable->sNoItem='No description';
			$oTable->bStepperVisible=false;
			$oTable->sIdiTr = 'org';
			$oTable->bFormAvailable=false;
			$oTable->sClass = "at-table-striped";
			$oTable->bHeaderVisible = false;
			$oTable->sTemplateName='table/index2.tpl';
			Base::$tpl->assign('sTableOriginal',$oTable->getTable());
			
			Base::$sText.=Base::$tpl->fetch('catalog/info_part.tpl');
			
			Content::SetMetaTagsPage('buy:',array(
			    'code' => $aPartInfo['code'],
			    'name' => $aPartInfo['name'],
			    'brand' => $aPartInfo['brand'],
			));
		}
	}
    //-----------------------------------------------------------------------------------------------
	public function OriginalCross() {
	    
	    Base::$aRequest['pref']=Db::GetOne("select pref from cat where name='".Base::$aRequest['cat']."' ");
		// JPN-144 get art_id
		// Knecht LX8 = 1372083
		if(!Base::$aRequest['art_id']) {
// 			Base::$aRequest['art_id']=Db::GetOne("
// 			select a.ID_src
// 			from ".DB_OCAT."cat_alt_articles a
// 			INNER JOIN ".DB_OCAT."cat_alt_suppliers as s on a.ID_sup=s.ID_sup
// 			inner join cat as c on c.id_tof=s.ID_src and c.pref='".Base::$aRequest['pref']."' 
// 			where a.Search = '".Base::$aRequest['code']."'");
		    Base::$aRequest['art_id']=TecdocDb::GetArt(array(
		        'code'=>Base::$aRequest['code'],
		        'pref'=>Base::$aRequest['pref']
		    ));
		}
		// JPN-144 get id_cat_part
		if(!Base::$aRequest['id_cat_part']) {
			Base::$aRequest['id_cat_part']=Db::GetOne("
				select id from cat_part where
				pref='".Base::$aRequest['pref']."' and code='".Base::$aRequest['code']."' ");
		}

		$sBrand=Db::GetOne("select title from cat where pref='".Base::$aRequest['pref']."' ");
		
		// JPN-178 begin
		Base::$aRequest['item_code']=Base::$aRequest['pref']."_".Base::$aRequest['code'];
		$aRow=Db::GetRow(Base::GetSql('Catalog/Price',array(
			'aItemCode'=>array(Base::$aRequest['item_code'])
			, 'customer_discount'=>Discount::CustomerDiscount(Auth::$aUser)
		)));
		$iIdPriceGroup=Db::GetOne("select id_price_group from price_group_assign where item_code='".Base::$aRequest['pref']."_".Base::$aRequest['code']."'");

		if (!Base::$aRequest['art_id'] && Base::$aRequest['item_code']) {
// 			$aPartInfo=Db::GetRow(Base::GetSql("OptiCatalog/PartInfo",array(
// 				'item_code'=>Base::$aRequest['item_code']
// 			)));
			$aPartInfo=TecdocDb::GetPartInfo(array(
			    'item_code'=>Base::$aRequest['item_code']
			));
		}
		if (Base::$aRequest['art_id'] || $aPartInfo['id_cat_part']) {
// 			$aPartInfo=Db::GetRow(Base::GetSql("OptiCatalog/PartInfo",array(
// 				'art_id'=>Base::$aRequest['art_id'],
// 				'id_cat_part'=>$aPartInfo['id_cat_part']
// 			)));
		    $aPartInfo=TecdocDb::GetPartInfo(array(
		        'art_id'=>Base::$aRequest['art_id'],
		        'id_cat_part'=>$aPartInfo['id_cat_part']
		    ));
		}
		
		if($iIdPriceGroup) {
			$aRowPriceGroup=Db::GetRow(Base::GetSql("Price/Group",array(
				'id'=>$iIdPriceGroup,
				'visible'=>1,
			)));
			if ($aRowPriceGroup) {
				$action = "/zapchasti/".$aRowPriceGroup['code_name'];
				if (Language::getConstant('global:url_is_not_last_slash',0) == 0)
					$action .= "/";
				$aNavigator[]=array(
					'name'=>$aRowPriceGroup['name'],
					'url'=>$action
				);
				$action = "/zapchasti/".$aRowPriceGroup['code_name']."/".$aRow['cat_name'];
				if (Language::getConstant('global:url_is_not_last_slash',0) == 0)
					$action .= "/";
				$aNavigator[]=array(
					'name'=>($aRow['brand']),
					'url'=>$action
				);
			}
			$action = "/buy/".$aRow['cat_name']."_".$aRow['code'];
			if (Language::getConstant('global:url_is_not_last_slash',0) == 0)
				$action .= "/";
				
			$aNavigator[]=array(
				'name'=>$aPartInfo['name'].' '.($aRow['brand'])." ".$aRow['code'],
				'url'=>$action
			);
		} else {
			/*$sBrandName=Db::GetOne("select if(title1<>'',title1,title) as brand from cat where name like '".$aRow['brand']."' or name1 like '".$aRow['brand']."' or title1 like '".$aRow['brand']."'");
			$aNavigator[]=array(
				'name'=>$aRow['part_rus'].' '.$sBrandName." ".$aRow['code'],
				'action'=>"/buy/".$aRow['cat_name']."_".$aRow['code']."/"
			);*/
		}
		
		$aNavigator[]=array(
			'name'=>Language::GetMessage('original_cross:crumbs')." ".$sBrand." ".Base::$aRequest['code'],
			'action'=>""
		);
		
		Base::$tpl->assign('aNavigator', $aNavigator);
		// JPN-178 end
		
		$oTable=new Table();
// 		$oTable->sSql=Base::GetSql("OptiCatalog/PartOriginal",array(
// 			'art_id'=>Base::$aRequest['art_id'],
// 			'aIdCatPart'=>array(Base::$aRequest['id_cat_part']),
// 			'pref'=>Base::$aRequest['pref'],
// 			'code'=>Base::$aRequest['code'],
// 			));
		
		
		
		
		if(1==1){
		    //select art_id for virtual cat
			$this->aCat=Db::GetRow("select * from cat as c where name='".Base::$aRequest['cat']."' ");
		
		    if($this->aCat['is_cat_virtual']!=0) {
		        $aVag=Db::getAll("Select c.* from cat c	where c.visible=1 and c.id_cat_virtual=".$this->aCat['id']);
		         
		        if ($aVag) {
		            foreach ($aVag as $aValVag){
		                $aAs=TecdocDb::GetArt(array(
		                    'code'=>Base::$aRequest['code'],
		                    'id_tof'=>$aValVag['id_tof']
		                ));
		                if(Base::$aRequest['art_id']==""&&$aAs!="")
		                    Base::$aRequest['art_id'].=$aAs;
		               elseif($aAs!="")
		                    Base::$aRequest['art_id'].=",".$aAs;
		                 
		            }
		        }
		        unset($aVag);
		    }
		    //Debug::PrintPre(Base::$aRequest['art_id']);
		    if ($this->aCat['id_cat_virtual']!=0) {
		        $aVag=Db::getAll("Select c.* from cat c inner join cat c2 on c2.id = c.id_cat_virtual and c2.visible=1
	    				where c.visible=1 and c.id_cat_virtual=".$this->aCat['id_cat_virtual']);
		        if ($aVag) {
		            foreach ($aVag as $aValVag){
		                $aAs=TecdocDb::GetArt(array(
		                    'code'=>Base::$aRequest['code'],
		                    'id_tof'=>$aValVag['id_tof']
		                ));
		                if(Base::$aRequest['art_id']==""&&$aAs!="")
		                    Base::$aRequest['art_id'].=$aAs;
		                elseif($aAs!="")
		                    Base::$aRequest['art_id'].=",".$aAs;
		            }
		        }
		        unset($aVag);
		    }
		    //Debug::PrintPre(Base::$aRequest);
		}
		
		
		Base::$oContent->AddCrumb(Language::GetMessage('original_cross:h1').' '.$aRow['brand'].' '.$aRow['code_'],'');
		
        $oTable->sType='array';
        $oTable->aDataFoTable=TecdocDb::GetOriginals(array(
			'art_id'=>Base::$aRequest['art_id'],
			'aIdCatPart'=>array(Base::$aRequest['id_cat_part']),
			'pref'=>Base::$aRequest['pref'],
			'code'=>Base::$aRequest['code'],
		));
		
		$oTable->aColumn['brand']=array('sTitle'=>'Brand','sWidth'=>'15%');
		$oTable->aColumn['code']=array('sTitle'=>'Part Code','sWidth'=>'20%');
		
		$oTable->iRowPerPage=1000;
		$oTable->sDataTemplate='catalog/row_part_original.tpl';
		//$oTable->aCallback=array($this,'CallParseOriginal');
		$oTable->aOrdered=" order by name ";
		$oTable->sNoItem='No description';
		$oTable->iStartStep=1;
		//$oTable->bStepperVisible=false;
		
		//Base::$aData['template']['sPageTitle']=Language::GetMessage('original_cross:title')." ".$sBrand." ".Base::$aRequest['code'];
		//Base::$aData['template']['sPageDescription']=Language::GetMessage('original_cross:desription_before')." ".$sBrand." ".Base::$aRequest['code'].Language::GetMessage('original_cross:description_after');
		//Base::$aData['template']['sPageKeyword']=Language::GetMessage('original_cross:keywords')." ".$sBrand." ".Base::$aRequest['code'];
		
		Base::$sText.="<h1>".Language::GetMessage('original_cross:h1')." ".$sBrand." ".Base::$aRequest['code']."</h1>";
				
		Base::$tpl->assign('brand',$sBrand);
		Base::$tpl->assign('code',Base::$aRequest['code']);
		
		Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function GetPriceForInfoPart($sCodeInput='',$sPrefInput='')
	{
		Base::$aRequest['code']=$sCodeInput;
		Base::$aRequest['pref']=$sPrefInput;
	
		Base::$tpl->assign('bAddCartVisible',true);
	
		$this->sPref=Base::$aRequest['pref'];
		$this->aCode = preg_split("/[\s,;]+/", Catalog::StripCode(Base::$aRequest['code']));
		$sCode = "'".implode("','",$this->aCode)."'";
	
// 		$this->aCodeCross=Base::$db->GetAll(Base::GetSql('OptiCatalog/Cross',array(
// 				'sCode'=>$sCode,
// 				'pref'=>$this->sPref
// 		)));
		$this->aCodeCross=TecdocDb::GetCross(array(
		    'sCode'=>$sCode,
		    'pref'=>$this->sPref
		),$this->aCats);
		
		// Get OE numbers begin
		if(Base::GetConstant('price:show_oe','1')==1) {
		    $sSql="select
    		    oe_code,
    		    code,
    		    oe_brand,
    		    brand
            from ".DB_OCAT."cat_alt_original as c
            where 1=1 and (oe_code like ".$sCode." or code like ".$sCode." ) ";
		    $aOriginals=TecdocDb::GetAll($sSql);
		
		    if($aOriginals) $aCatAssoc=Db::GetAssoc("select id_tof,pref from cat where id_tof >0");
		    
		    // cut other brand original
		    $aPrefOriginals = array();
		    if ($this->sPref) {
				$sItemCode = $this->sPref.'_'.Base::$aRequest['code'];
		    	$iIdTof = Db::GetOne("select id_tof from cat where pref='".$this->sPref."'");
		    	if ($iIdTof) {
		    		if($aOriginals) foreach ($aOriginals as $aValue) {
		    			if ($aValue['code']==Base::$aRequest['code'] && $aValue['brand']==$iIdTof)
		    				$aPrefOriginals[] = $aValue;
		    			elseif ($aValue['oe_code']==Base::$aRequest['code'] && $aValue['oe_brand']==$iIdTof)
		    			$aPrefOriginals[] = $aValue;
		    		}
		    		$aOriginals = $aPrefOriginals;
		    	}
		    }
		    
		    $aItemCodeOriginal=array();
		    if($aOriginals) foreach ($aOriginals as $skeyOriginal => $aValueOriginal) {
    			// cut other brand original without id_tof
    			if ($sItemCode && 
    				$aCatAssoc[$aValueOriginal['brand']]."_".$aValueOriginal['code'] != $sItemCode &&
    				$aCatAssoc[$aValueOriginal['oe_brand']]."_".$aValueOriginal['oe_code'] != $sItemCode
    			)
    				continue;

		        $aTmp=array();
		        $aTmp['pref']=$aCatAssoc[$aValueOriginal['brand']];
		        $aTmp['pref_crs']=$aCatAssoc[$aValueOriginal['oe_brand']];
		
		        $aTmp['item_code']=$aCatAssoc[$aValueOriginal['brand']]."_".$aValueOriginal['code'];
		        $aTmp['item_code_crs']=$aCatAssoc[$aValueOriginal['oe_brand']]."_".$aValueOriginal['oe_code'];
		        $aItemCodeOriginal[]=$aTmp;
		
		
		        $aTmp=array();
		        $aTmp['pref_crs']=$aCatAssoc[$aValueOriginal['brand']];
		        $aTmp['pref']=$aCatAssoc[$aValueOriginal['oe_brand']];
		
		        $aTmp['item_code_crs']=$aCatAssoc[$aValueOriginal['brand']]."_".$aValueOriginal['code'];
		        $aTmp['item_code']=$aCatAssoc[$aValueOriginal['oe_brand']]."_".$aValueOriginal['oe_code'];
		        $aItemCodeOriginal[]=$aTmp;
		
		    }
		    if(!$aItemCodeOriginal) $aItemCodeOriginal=array();
		    if(!$this->aCodeCross) $this->aCodeCross=array();
		    $this->aCodeCross=array_merge($this->aCodeCross,$aItemCodeOriginal);
		}
		// Get OE numbers end
	
		$sId="";
		if ($this->sPref && $this->aCode[0]) {
		    $this->aItemCodeCross[$this->sPref."_".$this->aCode[0]]=$this->sPref."_".$this->aCode[0];
		    $aCat = Db::GetRow("select * from cat where pref='".$this->sPref."'");
		    if ($aCat['is_cat_virtual']){
		        $aCatVirtual = Db::GetAll("SELECT * FROM cat WHERE id_cat_virtual = ".$aCat['id']);
		        if($aCatVirtual)
		            foreach($aCatVirtual as $aValueVirtual){
		            $this->aItemCodeCross[$aValueVirtual['pref']."_".$this->aCode[0]]=$this->sPref."_".$this->aCode[0];
		        }
		    }
		}
		if ($this->aCodeCross) {
/*
			$aVag=array("AU","SC","SE","VW","VAG");
			foreach ($this->aCodeCross as $k => $v) {
				list($sPrefCrs,$sCodeCrs)=explode("_",$v['item_code_crs']);
				if (in_array($sPrefCrs,$aVag)) {
					foreach ($aVag as $sKey => $sValue) $this->aItemCodeCross[$sValue."_".$sCodeCrs]=$v['item_code'];
				} else {
					$this->aItemCodeCross[$v['item_code_crs']]=$v['item_code'];
				}
			}
*/
		    foreach ($this->aCodeCross as $k => $v) {
		        $this->aItemCodeCross[$v['item_code_crs']]=$v['item_code'];
		        // add virtual kodes
		        if (!$this->aItemCodeCross[$v['item_code']])
		            $this->aItemCodeCross[$v['item_code']] = $v['item_code'];
		    }
		}
		
		if (Base::GetConstant('price:term_from_provider','1')) {
		    $sTermDay=" up.term ";
		} else {
		    $sTermDay=" p.term ";
		}
		if(Base::$aRequest['way']=='up') $sWay="asc";
		if(Base::$aRequest['way']=='down') $sWay="desc";
	    if(Base::GetConstant('complex_margin_enble','0')) {
		    $sOrder=" pref_order, code_order";
		    if (Base::$aRequest['order'] && Base::$aRequest['way']) {
		        $sOrder.=", ".Base::$aRequest['order']." ".$sWay;
		    } else {
		        Base::$aRequest ['order']="price";
		        $sOrder.=",t.price, price_order , t.code, t.item_code  ";
		    }
		} else {
		    $sOrder=" pref_order, code_order";
		    if (Base::$aRequest['order'] && Base::$aRequest['way']) {
		        $sOrder.=", ".Base::$aRequest['order']." ".$sWay;
		    } else {
		        Base::$aRequest ['order']="price";
		        $sOrder.=" , p.price/cu.value asc , p.code, p.item_code  ";
		    }
		}
	
		$aData=Db::GetAll(Base::GetSql('Catalog/Price',array(
				'aCode'=>$this->aCode,
				'aItemCode'=>array_keys($this->aItemCodeCross),
				'customer_discount'=>Discount::CustomerDiscount(Auth::$aUser),
				'pref_order'=>$this->sPref,
				'code_order'=>$this->aCode[0],
				'sId'=>$sId,
		        'order'=>$sOrder
		))
		);
	
		$oTable = new Table();
		$oTable->sWidth='100%';
	
		if (!Base::GetConstant('price:lock_table')) {
			$oTable->sType='array';
			$oTable->aDataFoTable=$aData;
			$oTable->sNoItem='no analogs';
		} else {
			$oTable->sSql="select null ";
			$oTable->sNoItem="";
		}

		Catalog::GetPriceTableHead($oTable);
		$oTable->aCallback=array($this,'CallParsePrice');
		$oTable->sCheckField='code_provider';
		$oTable->iRowPerPage=130;
		$oTable->iAllRow=0;
		$oTable->sDataTemplate='catalog/row_price.tpl';
	    $oTable->sTemplateName = 'table/table_analogs.tpl';
		$oTable->bHeaderVisible=false;
		$oTable->bFormAvailable=false;
		// macro sort table
		$this->SortTable();
		
		return $oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function ExportPrice() {
		Auth::NeedAuth('manager');
		error_reporting(E_ALL ^ E_NOTICE);
		set_time_limit(0);

		$sFileName=Base::$aRequest['name_file'];
		//		$aHeader=array(
		//		'a'=>array("value"=>"login_provider"),
		//		'b'=>array("value"=>"pref"),
		//		'c'=>array("value"=>"code"),
		//		'd'=>array("value"=>"price"),
		//		'e'=>array("value"=>"Name", "autosize"=>true),
		//		);

		$r=mysql_query("
		select p.*, u.login as login, c.title as title
		from price as p
		inner join user as u on p.id_provider=u.id
		inner join cat as c on p.pref=c.pref
		order by p.pref");

		$ii=0; $i=0;

		while ($aValue = mysql_fetch_array($r)) {
			if (fmod($i,60000)==0) {
				isset($f)?fclose($f):"";
				$url_f=SERVER_PATH.$this->sPathToFile.$sFileName.$ii."_".date("zHis").".csv";
				$f=fopen($url_f,"a+");
				$i=1; $ii++;
			}
			$i++;

			fputcsv($f,array($aValue['login'],$aValue['title'],$aValue['code']
			,str_replace(".",",",$aValue['price']),$aValue['part_rus']),";");
			//			$oExcel->SetCellValue('a'.$i, $aValue['login']);
			//			$oExcel->SetCellValue('b'.$i, $aValue['pref']);
			//			$oExcel->SetCellValue('c'.$i, $aValue['code']);
			//			$oExcel->SetCellValue('d'.$i, $aValue['price']);
			//			$oExcel->SetCellValue('e'.$i, $aValue['part_rus']);
		}
		isset($f)?fclose($f):"";

		//		$sFileNameZip=$sFileName.".zip";
		//		$sUrl=SERVER_PATH.$this->sPathToFile;
		//		$zip = new ZipArchive;
		//		$res = $zip->open($sUrl.$sNameZip, ZipArchive::CREATE);
		//		if ($res === TRUE) {
		//			$zip->addFile($sUrl.$sName, $sName);
		//			$zip->close();
		//		}
		//		if (is_file($sUrl.$sName)) unlink($sUrl.$sName);
		//		$sMessage="&aMessage[MI_NOTICE]=Price exported";

	}
	//-----------------------------------------------------------------------------------------------
	public function SetImageWidth() {
		Auth::NeedAuth('manager');
		error_reporting(E_ALL ^ E_NOTICE);
		set_time_limit(0);

		$q=mysql_query("
		select lga_art_id, gra_grd_id, concat( '".Base::$aGeneralConf['TecDocUrl']."/imgbank/tcd/', gra_tab_nr, '/', gra_grd_id, '.'
		, case gra_doc_type when 1 then 'bmp' when 2 then 'pdf' when 5 then 'png' else 'jpg' end ) as path
		from ".DB_TOF."tof__link_gra_art
		inner join ".DB_TOF."tof__graphics on gra_id = lga_gra_id
		where gra_doc_type <> 2
		");

		while ($aRow = mysql_fetch_array($q)) {

			if (is_file(SERVER_PATH.$aRow['path'])){
				$aImg=getimagesize(SERVER_PATH.$aRow['path']);
				Db::Execute("update ".DB_TOF."tof__graphics set gra_norm=".$aImg[0]."  where gra_grd_id=".$aRow['gra_grd_id']);
			}
		}
	}
	//------------------------------------------------------------------------------------------------
// 	public function UpdatePrice($sItemCode="",$idProvider="",$dPrice=0) {
// 		$bRedirectAuto=true;
// 		if ($sItemCode && $idProvider && $dPrice) {
// 			$bRedirectAuto=false;
// 		} else {
// 			$sItemCode=Base::$aRequest['item_code'];
// 			$idProvider=Base::$aRequest['id_provider'];
// 			$dPrice=Base::$aRequest['data']['price'];
// 		}

// 		if ($idProvider && $sItemCode) {		
// 			$a=Db::GetRow(Base::GetSql('Catalog/Price',array(
// 			'id_provider'=>$idProvider
// 			, 'aItemCode'=>array($sItemCode)
// 			)));
	
// 			$dNewPrice=round($a['price_original']*str_replace(",",".",$dPrice)/$a['price'],2);
	
// 			if ($dNewPrice) {
// 				Db::Execute("update price set price='".$dNewPrice."' where id=".$a['id']);
// 				$sMessage="&aMessage[MT_NOTICE]=price update";
// 			}
// 		}

// 		if ($bRedirectAuto) Form::RedirectAuto($sMessage);

// 	}
	//-----------------------------------------------------------------------------------------------
	function Cross() {
		Auth::NeedAuth('manager');

		if (Auth::$aUser['type_']=='manager'){
			$this->sPrefixAction="catalog_cross";
			Base::$aTopPageTemplate=array('panel/tab_cross.tpl'=>$this->sPrefixAction);
		}
		
		Base::$tpl->assign("aPref",$aPref=array(""=>"")+Db::GetAssoc("Assoc/Pref", array('all'=>1)));

		/* [ apply  */
		if (Base::$aRequest['is_post'])
		{
			if (!Base::$aRequest['data']['pref'] || !Base::$aRequest['data']['code']
			|| !Base::$aRequest['data']['pref_crs'] || !Base::$aRequest['data']['code_crs'])
			{
				Base::Message(array('MF_ERROR'=>'Required fields brand and code'));
				Base::$aRequest['action']=$this->sPrefix.'_cross_add';
				Base::$tpl->assign('aData', Base::$aRequest['data']);
			}
			else
			{
				$aData=String::FilterRequestData(Base::$aRequest['data']);
				$aData["code"]=Catalog::StripCode(strtoupper($aData["code"]));
				$aData["code_crs"]=Catalog::StripCode(strtoupper($aData["code_crs"]));

				if (Base::$aRequest['id']) {
					$aId=Db::GetRow("select * from cat_cross where id=".Base::$aRequest['id']);

					Db::Execute("delete from cat_cross where pref='".$aId['pref']."' and code='".$aId['code']."'
					and pref_crs='".$aId['pref_crs']."' and code_crs='".$aId['code_crs']."'");

					Db::Execute("delete from cat_cross where pref='".$aId['pref_crs']."' and code='".$aId['code_crs']."'
					and pref_crs='".$aId['pref']."' and code_crs='".$aId['code']."'");

					$this->InsertCross($aData);
					$sMessage="&aMessage[MF_NOTICE]=Cross updated";
				}
				else {
					$bInsert=$this->InsertCross($aData);
					if($bInsert) $sMessage="&aMessage[MF_NOTICE]=Cross added";
					else $sMessage="&aMessage[MF_ERROR]=Cross not added";
				}
				Form::RedirectAuto($sMessage);
			}
		}
		/* ] apply */

		if (Base::$aRequest['action']==$this->sPrefix.'_cross_add' || Base::$aRequest['action']==$this->sPrefix.'_cross_edit')
		{
			if (Base::$aRequest['action']==$this->sPrefix.'_cross_edit')
			{
				Base::$tpl->assign('aData',Base::$db->getRow(Base::GetSql("Catalog/PartCross",
				array("id"=>Base::$aRequest['id']?Base::$aRequest['id']:"-1"))));
			} elseif (Base::$aRequest['action']==$this->sPrefix.'_cross_add' && Base::$aRequest['item_code']) {
				list($aData['pref_crs'],$aData['code_crs'])=explode('_',Base::$aRequest['item_code']);
				$aData['pref_crs']=strtoupper($aData['pref_crs']);
				$aData['code_crs']=Catalog::StripCode($aData['code_crs']);
				Base::$tpl->assign('aData',$aData);
			}
			
			Resource::Get()->Add('/js/form.js',3284);
			
// 			$aField['pref']=array('title'=>'Make','type'=>'select','options'=>$aPref,'selected'=>$aData['pref'],'name'=>'data[pref]','id'=>'pref','szir'=>1);
// 			$aField['code']=array('title'=>'Code Part','type'=>'input','value'=>$aData['code'],'name'=>'data[code]','id'=>'code','szir'=>1);
// 			$aField['pref_crs']=array('title'=>'Make Cross','type'=>'select','options'=>$aPref,'selected'=>$aData['pref_crs'],'name'=>'data[pref_crs]','id'=>'pref','szir'=>1);
// 			$aField['code_crs']=array('title'=>'Code Part Cross','type'=>'input','value'=>$aData['code_crs'],'name'=>'data[code_crs]','id'=>'code','szir'=>1);
// 			$aField['source']=array('title'=>'Source Crs','type'=>'input','value'=>$aData['source'],'name'=>'data[source]','id'=>'source');
			
			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=>"New cross",
			'sContent'=>Base::$tpl->fetch($this->sPrefix.'/form_cross.tpl'),
// 			'aField'=>$aField,
// 			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>$this->sPrefix."_cross",
			'sReturnButton'=>'<< Return',
			'bAutoReturn'=>true,
			);
			$oForm=new Form($aData);
			Base::$sText.=$oForm->getForm();

			return;
		}

		Base::Message();

		Resource::Get()->Add('/js/form.js',3284);
		Resource::Get()->Add('/js/select_search.js');
		unset($aField);
		$aField['pref']=array('title'=>'Make','type'=>'select','options'=>$aPref,'selected'=>Base::$aRequest['search']['pref'],'name'=>'search[pref]','class'=>'js-select');
		$aField['code']=array('title'=>'Code Part','type'=>'input','value'=>Base::$aRequest['search']['code'],'name'=>'search[code]','id'=>'code');
		$aField['source']=array('title'=>'Sorce crs search','type'=>'input','value'=>Base::$aRequest['search']['source'],'name'=>'search[source]','id'=>'source');
		$aField['date_from']=array('title'=>'DFrom','type'=>'date','value'=>Base::$aRequest['search']['date_from']?Base::$aRequest['search']['date_from']:date("d.m.Y",time()-30*86400),'name'=>'search[date_from]','id'=>'date_from','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')",'checkbox'=>1);
		$aField['date_to']=array('title'=>'DTo','type'=>'date','value'=>Base::$aRequest['search']['date_to']?Base::$aRequest['search']['date_to']:date("d.m.Y",time()+86400),'name'=>'search[date_to]','id'=>'date_to','readonly'=>1,'onclick'=>"popUpCalendar(this, this, 'dd.mm.yyyy')");
		$aField['manager']=array('title'=>'Manager','type'=>'input','value'=>Base::$aRequest['search']['manager'],'name'=>'search[manager]','id'=>'manager');
		
		$oForm= new Form();
		//$oForm->sTitle="Catalog Cross";
		//$oForm->sContent=Base::$tpl->fetch($this->sPrefix."/form_cross_search.tpl");
		$oForm->aField=$aField;
		$oForm->bType='generate';
		$oForm->sGenerateTpl='form/index_search.tpl';
		$oForm->sSubmitButton="Search";
		$oForm->sSubmitAction=$this->sPrefix."_cross";
		$oForm->sReturnButton="Clear";
		$oForm->sReturnAction=$this->sPrefix."_cross";
		$oForm->sReturn=Base::RemoveMessageFromUrl($_SERVER ['QUERY_STRING']);
		//$oForm->bAutoReturn=true;
		//$oForm->sAdditionalButtonTemplate=$this->sPrefix."/button_price_request_view.tpl";
		$oForm->bIsPost=0;
		$oForm->sWidth="800px";
		Base::$sText.=$oForm->getForm();


		$oTable= new Table();
		$aData=Base::$aRequest['search'];
		$aData['join'] = 1;
		if (Base::$aRequest['search']['pref']) {
		    $sWhere.=" ";
		}
		if (Catalog::StripCode(Base::$aRequest['search']['code'])) {
			$aData['aCode']=array(Catalog::StripCode(Base::$aRequest['search']['code']));
			$sWhere.=" ";
		}
		if (Base::$aRequest['search']['source']) {
			$aData['source']=Base::$aRequest['search']['source'];
			$sWhere.=" ";
		}
		if(Base::$aRequest['search']['manager']) {
			$sWhere.=" and (um.name like '%".Base::$aRequest['search']['manager']."%' 
				or u.login = '".Base::$aRequest['search']['manager']."')";
		}
		if(Base::$aRequest['search']['date'])
			$sWhere.=" and ((cc.post_date>='".DateFormat::FormatSearch(Base::$aRequest['search']['date_from'])."'
			 and cc.post_date<='".DateFormat::FormatSearch(Base::$aRequest['search']['date_to'])."') or cc.post_date+0=0)";
		$aData['where']=$sWhere;

		if($sWhere) {
			$oTable->sSql=Base::GetSql("Catalog/PartCross",$aData);
		} else {
			//$oTable->sSql="select null";
			$oTable->aDataFoTable=array();
			$oTable->sType='array';
			$oTable->sTableMessage=Language::GetMessage("select filter for show table");
			$oTable->sTableMessageClass="warning_p";
		}
		
		$oTable->aColumn['id']=array('sTitle'=>'Id');
		$oTable->aColumn['pref']=array('sTitle'=>'Pref');
		$oTable->aColumn['code']=array('sTitle'=>'Code part');
		$oTable->aColumn['pref_crs']=array('sTitle'=>'Pref Crs');
		$oTable->aColumn['code_crs']=array('sTitle'=>'Code Crs');
		$oTable->aColumn['post_date']=array('sTitle'=>'Date');
		$oTable->aColumn['source']=array('sTitle'=>'Source Crs');
		$oTable->aColumn['manager_login']=array('sTitle'=>'Manager');
		$oTable->aColumn['action']=array();
		$oTable->iRowPerPage=10;
		$oTable->sWidth='100%';
		$oTable->sDataTemplate=$this->sPrefix.'/row_cross.tpl';
		$oTable->sButtonTemplate=$this->sPrefix.'/button_cross.tpl';
		$oTable->bCheckVisible=false;
		$oTable->aCallback=array($this,'CallParseCross');
		$oTable->aOrdered=" order by cc.id desc";
		if(Base::$aRequest['search']){
		    $_SESSION['manager']['cross_sql']=$oTable->sSql;
		} else {
		    unset($_SESSION['manager']['cross_sql']);
		}

		Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	function CrossLoad(){
		set_time_limit(0);
		
		if (!file_exists(SERVER_PATH."/imgbank/cross/")) mkdir(SERVER_PATH."/imgbank/cross/");
		if (!file_exists(SERVER_PATH."/imgbank/cross/log")) mkdir(SERVER_PATH."/imgbank/cross/log");

		if($_FILES['excel_file']) {
			$excel_file = $_FILES['excel_file']['tmp_name'];
			if (strpos($_FILES['excel_file']['name'],".zip")>0) {
				$oZip = new ZipArchive;
				if ($oZip->open($excel_file) === TRUE) {
					$oZip->extractTo(SERVER_PATH."/imgbank/cross/");
					$oZip->close();
				} else {
					die('Bad zip');
				}
			} elseif (@move_uploaded_file($excel_file, SERVER_PATH."/imgbank/cross/".Auth::$aUser['id'].$_FILES['excel_file']['name'])) {

			}
			
			$aFiles=File::GetFromDir("/imgbank/cross/");
			$iProfile=Base::$aRequest['id_cross_profile'];
			
			$aData=array();
			foreach ($aFiles as $aValue){
				$aData['file_name']=$aValue['path'];
				$aData['id_cross_profile']=$iProfile;
				Db::AutoExecute('cross_advance_import',$aData);
			}
		}
		
		Base::Redirect("/?action=catalog_cross_import_advance");
	}
	//-----------------------------------------------------------------------------------------------
	function CrossInstall() {
		$aFile=Db::GetAll("
			select c.id, c.file_name, p.name, p.type_, p.delimiter, p.row_start, p.col_cat, p.col_code, p.col_cat_crs,
			p.col_code_crs, p.charset as cross_profile_name 
			from cross_advance_import as c 
			left join cross_profile as p on p.id=c.id_cross_profile");
		
		if (is_array($aFile)) {
			
			$aPref=Db::GetAssoc("select name, pref from cat");
			
			foreach ($aFile as $aValue){
				switch ($aValue['type_']) {
					case 'excel':
					case 'excel95':
						Catalog::InstallExcel($aValue,$aPref);
					break;
					
					case 'excel07':
						Catalog::InstallExcel07($aValue,$aPref);
					break;
					
					case 'csv':
					case 'cvs':
						Catalog::InstallCSV($aValue,$aPref);
					break;
				}
				
				copy($aValue['file_name'], str_replace("cross/","cross/log/",$aValue['file_name']));
				unlink($aValue['file_name']);
				File::RemoveToDir(array("name"=>basename($aValue['file_name'])),"/imgbank/cross/log/");
				$sSql="delete from cross_advance_import where id='".$aValue['id']."' ";
				Db::Execute($sSql);
			}
		}
		
		Base::Redirect("/?action=catalog_cross");
		
	}
	//-----------------------------------------------------------------------------------------------
	public function InstallExcel($aCrossProfile, $aPref){
		require_once("excel/reader.php");
		unset($data);
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('UTF-8');
		$data->read($aCrossProfile['file_name']);

		for ($iList=0;$iList<1;$iList++){
			for( $i=$aCrossProfile['row_start']; $i <= $data->sheets[$iList]['numRows']; $i++) {
				$aData=$data->sheets[$iList]['cells'][$i];
				
				$aDataInsert=array(
				"code"=>$aData[intval($aCrossProfile['col_code'])],
				"code_crs"=>$aData[intval($aCrossProfile['col_code_crs'])],
				"pref"=> $aPref[$aData[intval($aCrossProfile['col_cat'])]],
				"pref_crs"=>$aPref[$aData[intval($aCrossProfile['col_cat_crs'])]],
				);
				$this->InsertCross($aDataInsert);
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function InstallExcel07($aCrossProfile, $aPref){
		$oExcel= new Excel();
		$oExcel->ReadExcel7($aCrossProfile['file_name'],true);
		for ($iList=0;$iList<1;$iList++){
			$oExcel->SetActiveSheetIndex($iList);
			$data=$oExcel->GetSpreadsheetData();
			foreach ($data as $sKey => $aData) {
				if ($aCrossProfile['row_start']>$sKey) continue;
				$aDataInsert=array(
				"code"=>$aData[intval($aCrossProfile['col_code'])],
				"code_crs"=>$aData[intval($aCrossProfile['col_code_crs'])],
				"pref"=> $aPref[$aData[intval($aCrossProfile['col_cat'])]],
				"pref_crs"=>$aPref[$aData[intval($aCrossProfile['col_cat_crs'])]],
				);
				$this->InsertCross($aDataInsert);
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function InstallCSV($aCrossProfile, $aPref){
		if (strtoupper($aCrossProfile['charset'])=="UTF-8"
		|| strtoupper($aCrossProfile['charset'])=="UTF8" ) {
			setlocale(LC_CTYPE,"ru_RU.utf8");
		}

		$handle = fopen($aCrossProfile['file_name'], "r");
		$sDelimiter=$aCrossProfile['delimiter'];
		$i=$aCrossProfile['row_start'];

		while (($data = fgetcsv($handle, 2000, $sDelimiter)) !== FALSE) {
			$i++;
			
			$aDataInsert=array(
			"code"=>$data[intval($aCrossProfile['col_code']-1)],
			"code_crs"=>$data[intval($aCrossProfile['col_code_crs']-1)],
			"pref"=> $aPref[$data[intval($aCrossProfile['col_cat']-1)]],
			"pref_crs"=>$aPref[$data[intval($aCrossProfile['col_cat_crs']-1)]],
			);
			$this->InsertCross($aDataInsert);
		}

		fclose($handle);
	}
	//-----------------------------------------------------------------------------------------------
	function CrossClearImport() {
		
		$aFile=Db::GetAll("select * from cross_advance_import c");
		
		if ($aFile){
			foreach ($aFile as $sFile){
				$bDelete=unlink($sFile['file_name']);
				$sSql="delete from cross_advance_import where id='".$sFile['id']."' ";
				Db::Execute($sSql);
			}
		}

		Base::Redirect("/?action=catalog_cross_import_advance");
	}
	//-----------------------------------------------------------------------------------------------
	function CrossImportAdvance(){
		if (Auth::$aUser['type_']=='manager'){
			$this->sPrefixAction="catalog_cross_import_advance";
			Base::$aTopPageTemplate=array('panel/tab_cross.tpl'=>$this->sPrefixAction);
		}
		
		Base::$tpl->assign('aCrossProfile',$aCrossProfile=Db::GetAssoc("select id,name from cross_profile"));
		
		$aField['id_cross_profile']=array('title'=>'Profile','type'=>'select','options'=>$aCrossProfile,'selected'=>'','name'=>'id_cross_profile');
		$aField['excel_file']=array('title'=>'File','type'=>'file','name'=>'excel_file','multiple'=>1);
		$aField['the_maximum_size']=array('type'=>'text','value'=>Language::GetText("The maximum size of an uploaded file 8M"),'colspan'=>2);
		
		$aData=array(
		'sHeader'=>"method=post enctype=\"multipart/form-data\"" ,
		'sHidden'=>"<input type=hidden name=\"style\" value='segment'>",
		//'sContent'=>Base::$tpl->fetch('catalog/cross_advance.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'catalog_cross_load',
		'sSubmitAction'=>'catalog_cross_load',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);
		Base::$sText.=$oForm->getForm();
		
		
		$oTable=new Table();
		$oTable->aDataFoTable=Db::GetAll("
			select c.file_name, p.name as cross_profile_name 
			from cross_advance_import as c 
			left join cross_profile as p on p.id=c.id_cross_profile");
		$oTable->bStepperVisible=false;
		$oTable->sType = 'array';
		$oTable->aColumn=array(
		'file_name'=>array('sTitle'=>'file_name',),
		'id_cross_profile'=>array('sTitle'=>'Profile',),
		);
		$oTable->sDataTemplate='catalog/row_cross_import.tpl';
		$oTable->sButtonTemplate='catalog/button_cross_import.tpl';

		Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	function CrossProfile(){
		if (Auth::$aUser['type_']=='manager'){
			$this->sPrefixAction="catalog_cross_profile";
			Base::$aTopPageTemplate=array('panel/tab_cross.tpl'=>$this->sPrefixAction);
		}
		
	if (Base::$aRequest['is_post']){
				$aData=String::FilterRequestData(Base::$aRequest['data']);

				if (Base::$aRequest['id']) {
					Db::AutoExecute("cross_profile",$aData,"UPDATE","id=".Base::$aRequest['id']);
					$sMessage="&aMessage[MT_NOTICE]=Price profile updated";
				} else {
					Db::AutoExecute("cross_profile",$aData);
					$sMessage="&aMessage[MT_NOTICE]=Price profile added";
				}

				Form::RedirectAuto($sMessage);
		}

		if (Base::$aRequest['action']=='catalog_cross_profile_add'||Base::$aRequest['action']=='catalog_cross_profile_edit') {

			$a[""]="";
			Base::$tpl->assign('aType_',$aType_=array("excel"=>"excel","excel07"=>"excel07","csv"=>"csv","cvs"=>"cvs"));
			Base::$tpl->assign('aDelimiter',$aDelimiter=$a+array(";"=>";","tab"=>"tab",","=>","));

			if (Base::$aRequest['action']=='catalog_cross_profile_edit') {
				$aData=Db::GetRow("select * from cross_profile where id='".Base::$aRequest['id']."' ");
				Base::$tpl->assign('aData',$aData);
			} else {
				$aData['coef']=1;
				$aData['list_count']=1;
				$aData['num']=1+Db::GetOne("select max(num) from price_profile");
				Base::$tpl->assign('aData',$aData);
			}

			$aField['name']=array('title'=>'Name Profile','type'=>'input','value'=>$aData['name'],'name'=>'data[name]');
			$aField['type_']=array('title'=>'Type','type'=>'select','options'=>$aType_,'selected'=>$aData['type_'],'name'=>'data[type_]');
			$aField['delimiter']=array('title'=>'Delimiter','type'=>'select','options'=>$aDelimiter,'selected'=>$aData['delimiter'],'name'=>'data[delimiter]');
			$aField['row_start']=array('title'=>'Row Start','type'=>'input','value'=>$aData['row_start'],'name'=>'data[row_start]');
			$aField['col_cat']=array('title'=>'Col Catalog','type'=>'input','value'=>$aData['col_cat'],'name'=>'data[col_cat]');
			$aField['col_code']=array('title'=>'Col Code','type'=>'input','value'=>$aData['col_code'],'name'=>'data[col_code]');
			$aField['col_cat_crs']=array('title'=>'Col cat crs','type'=>'input','value'=>$aData['col_cat_crs'],'name'=>'data[col_cat_crs]');
			$aField['col_code_crs']=array('title'=>'Col code crs','type'=>'input','value'=>$aData['col_code_crs'],'name'=>'data[col_code_crs]');
			$aField['charset']=array('title'=>'Charset','type'=>'input','value'=>$aData['charset'],'name'=>'data[charset]');
			
			$oForm=new Form();
			$oForm->sHeader="method=post";
			$oForm->sTitle="Edit";
// 			$oForm->sContent=Base::$tpl->fetch('catalog/form_cross_profile_add.tpl');
			$oForm->aField=$aField;
			$oForm->bType='generate';
			$oForm->sSubmitButton='Apply';
			$oForm->sSubmitAction='catalog_cross_profile_add';
			$oForm->sReturnButton='<< Return';
			$oForm->bAutoReturn=true;
			$oForm->bIsPost=true;
			$oForm->sWidth="600px";

			Base::$sText.=$oForm->getForm();

			return;
		}

		if (Base::$aRequest['action']=='catalog_cross_profile_delete' && Base::$aRequest['id']) {
			Db::Execute("delete from cross_profile where id='".Base::$aRequest['id']."' ");
			$sMessage="&aMessage[MT_NOTICE]=Cross profile deleted";
			Form::RedirectAuto($sMessage);
		}
		
		$oTable=new Table();
		$oTable->sSql="select * from cross_profile";
		$oTable->bStepperVisible=false;
		$oTable->aColumn=array(
		'name'=>array('sTitle'=>'name',),
		'type_'=>array('sTitle'=>'type',),
		'delimiter'=>array('sTitle'=>'delimiter',),
		'row_start'=>array('sTitle'=>'row_start',),
		'col_cat'=>array('sTitle'=>'col_cat',),
		'col_code'=>array('sTitle'=>'col_code',),
		'col_cat_crs'=>array('sTitle'=>'col_cat_crs',),
		'col_code_crs'=>array('sTitle'=>'col_code_crs',),
		'charset'=>array('sTitle'=>'charset',),
		'action'=>array()
		);
		$oTable->sDataTemplate='catalog/row_cross_profile.tpl';
		$oTable->sButtonTemplate='catalog/button_cross_profile.tpl';

		Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	function InsertCross($aData) {
		static $sPrefMers;
		
		if (!$sPrefMers) 
			$sPrefMers = Db::GetOne("SELECT pref FROM `cat` WHERE id_tof = 553"); // MERCEDES || MERCEDESBENZ
		
		if ($aData['pref'] && $aData['code'] && $aData['pref_crs'] && $aData['code_crs']
		&& !(strcasecmp($aData['code'],$aData['code_crs'])==0 && $aData['pref']==$aData['pref_crs'])
		) {
			if( (preg_match('/^A[0-9]{10}$/', $aData['code']) || preg_match('/^A[0-9]{11}$/', $aData['code'])
					|| preg_match('/^A[0-9]{12}$/', $aData['code'])) && $sPrefMers == $aData['pref'])
				$aData['code'] = ltrim($aData['code'],'A');
				
			if( (preg_match('/^A[0-9]{10}$/', $aData['code_crs']) || preg_match('/^A[0-9]{11}$/', $aData['code_crs'])
					|| preg_match('/^A[0-9]{12}$/', $aData['code_crs'])) && $sPrefMers == $aData['pref_crs'])
				$aData['code_crs'] = ltrim($aData['code_crs'],'A');

		    // мерседесные коды сам на себя записи создавались
			if (strcasecmp($aData['code'],$aData['code_crs'])==0 && $aData['pref']==$aData['pref_crs'])
			 return false;
			
			Db::Execute(" insert ignore into cat_cross (pref, code, pref_crs, code_crs, source, id_manager)
					values ('".$aData['pref']."','".$aData['code']."','".$aData['pref_crs']."','".$aData['code_crs']."','".$aData['source']."','".Auth::$aUser['id_user']."')
					, ('".$aData['pref_crs']."','".$aData['code_crs']."','".$aData['pref']."','".$aData['code']."','".$aData['source']."','".Auth::$aUser['id_user']."')
			    on duplicate key update source=values(source), id_manager=values(id_manager)
					");
			return true;
		} else {
			return false;
		}
	}
	//-----------------------------------------------------------------------------------------------
	function InsertCrossStop($aData) {
		if ($aData['pref'] && $aData['code'] && $aData['pref_crs'] && $aData['code_crs']
		&& $aData['code']!=$aData['code_crs']
		) {
			Db::Execute(" insert ignore into cat_cross_stop (pref, code, pref_crs, code_crs, source)
					values ('".$aData['pref']."','".$aData['code']."','".$aData['pref_crs']."','".$aData['code_crs']."','".$aData['source']."')
					, ('".$aData['pref_crs']."','".$aData['code_crs']."','".$aData['pref']."','".$aData['code']."','".$aData['source']."')
					");
			return true;
		} else {
			return false;
		}
	}
	//-----------------------------------------------------------------------------------------------
	function DeleteGroupCross() {
		Auth::NeedAuth('manager');
		if($_SESSION['manager']['cross_sql']){
			$a=Db::GetAll($_SESSION['manager']['cross_sql']);
			if($a) foreach ($a as $aValue) {
				Base::$aRequest['id']=$aValue['id'];
				$this->DeleteCross(false);
			}
		}
		$sMessage="&aMessage[MF_NOTICE]=Deleted";
		Base::Redirect('/?action=catalog_cross'.$sMessage);
	}
	//-----------------------------------------------------------------------------------------------
	function DeleteCross($bRedirect=true) {
		Auth::NeedAuth('manager');
		if (Base::$aRequest['id'])
		{
			$aId=Db::GetRow("select * from cat_cross where id=".Base::$aRequest['id']);

			Db::Execute("delete from cat_cross where pref='".$aId['pref']."' and code='".$aId['code']."'
					and pref_crs='".$aId['pref_crs']."' and code_crs='".$aId['code_crs']."'");

			Db::Execute("delete from cat_cross where pref='".$aId['pref_crs']."' and code='".$aId['code_crs']."'
					and pref_crs='".$aId['pref']."' and code_crs='".$aId['code']."'");
		}
		else $sMessage="&aMessage[MF_ERROR]=You must enter id";
		if($bRedirect) Form::RedirectAuto($sMessage);
	}
	//-----------------------------------------------------------------------------------------------
	function DeleteGroupCrossStop() {
		Auth::NeedAuth('manager');
		if($_SESSION['manager']['cross_stop_sql']){
			$a=Db::GetAll($_SESSION['manager']['cross_stop_sql']);
			if($a) foreach ($a as $aValue) {
				Base::$aRequest['id']=$aValue['id'];
				$this->DeleteCrossStop(false);
			}
		}
		$sMessage="&aMessage[MF_NOTICE]=Deleted";
		Base::Redirect('/?action=catalog_cross_stop'.$sMessage);
	}
	//-----------------------------------------------------------------------------------------------
	function DeleteCrossStop($bRedirect=true) {
		Auth::NeedAuth('manager');
		if (Base::$aRequest['id'])
		{
			$aId=Db::GetRow("select * from cat_cross_stop where id=".Base::$aRequest['id']);

			Db::Execute("delete from cat_cross_stop where pref='".$aId['pref']."' and code='".$aId['code']."'
					and pref_crs='".$aId['pref_crs']."' and code_crs='".$aId['code_crs']."'");

			Db::Execute("delete from cat_cross_stop where pref='".$aId['pref_crs']."' and code='".$aId['code_crs']."'
					and pref_crs='".$aId['pref']."' and code_crs='".$aId['code']."'");
		}
		else $sMessage="&aMessage[MF_ERROR]=You must enter id";
		if($bRedirect) Form::RedirectAuto($sMessage);
	}
	//-----------------------------------------------------------------------------------------------
	function ImportCross() {
		set_time_limit(0);
		Auth::NeedAuth('manager');

		if (Base::$aRequest['is_post'])
		{
			if (is_uploaded_file($_FILES['import_file']['tmp_name']))
			{

				$aPref=Base::$db->getAssoc("
				select upper(title) as name, pref from cat
				union
				select upper(name) as name, pref from cat
				union
				select upper(cp.name) as name,c.pref FROM cat_pref as cp
				inner join cat as c on c.id=cp.cat_id
				");
				
				ini_set("memory_limit",-1);
				$aPathInfo = pathinfo($_FILES['import_file']['name']);
				
				if($aPathInfo['extension']=='xlsx') {
					$oExcel = new Excel();
					$oExcel->ReadExcel7($_FILES['import_file']['tmp_name'],true,false);
					$oExcel->SetActiveSheetIndex();
					$aResult=$oExcel->GetSpreadsheetData();
				} else {
					$oExcel= new Excel();
					$oExcel->ReadExcel5($_FILES['import_file']['tmp_name'],true);
					$oExcel->SetActiveSheetIndex();
					$oExcel->GetActiveSheet();
	
					$aResult=$oExcel->GetSpreadsheetData();
				}

				if ($aResult)
				foreach ($aResult as $sKey=>$aValue) {
					if ($sKey>1)
					{
						$aData['pref']=$aPref[strtoupper(trim($aValue[1]))];
						$aData['code']=Catalog::StripCode(strtoupper($aValue[2]));
						if (trim($aValue[3]) == '' && trim($aValue[1]) != '')
							$aData['pref_crs']=$aData['pref'];
						else
							$aData['pref_crs']=$aPref[strtoupper(trim($aValue[3]))];

						$aData['code_crs']=Catalog::StripCode(strtoupper($aValue[4]));
						$aData['source']=Catalog::StripCode(strtoupper($aValue[5]));

						if ($aData['pref'] && $aData['code'] && $aData['pref_crs'] && $aData['code_crs'])
						{
							if (strpos($aData['code'],";")===false) {
								$this->InsertCross($aData);
							} else {
								$aCode=explode(";",$aData['code']);
								foreach ($aCode as $sCode) {
									$aData['code']=$sCode;
									$this->InsertCross($aData);
								}
							}
						} else {
							if (!$aData['pref'])
								Db::Execute("insert ignore into cat_pref (name) values (upper('".trim($aValue[1])."'))");
							if (!$aData['pref_crs'])
								Db::Execute("insert ignore into cat_pref (name) values (upper('".trim($aValue[3])."'))");
						}
					}
				}
				$sMessage="&aMessage[MF_NOTICE_NT]=".Language::GetMessage("Upload and processing")." ".$_FILES['import_file']['name']." ".Language::GetMessage("succsessfully");
				Form::RedirectAuto($sMessage);
			}
			else Base::Message(array('MI_ERROR'=>'Possible file upload attack'));
		}

		Base::Message();

		$aField['default_file_to_import']=array('type'=>'text','value'=>Language::GetText("Default File to import"),'colspan'=>2);
		$aField['import_file']=array('title'=>'File to import','type'=>'file','name'=>'import_file');
		
		$aData=array(
		'sHeader'=>"method=post enctype='multipart/form-data'",
		//'sTitle'=>"Import cross",
		//'sContent'=>Base::$tpl->fetch($this->sPrefix.'/form_cross_import.tpl'),
		'aField'=>$aField,
		'bType'=>'generate',
		'sSubmitButton'=>'Load',
		'sSubmitAction'=>$this->sPrefix.'_cross_import',
		'sReturnButton'=>'<< Return',
		'bAutoReturn'=>true,
		'sWidth'=>"400px",
		);
		$oForm=new Form($aData);
		Base::$sText.=$oForm->getForm();
	}
	//-----------------------------------------------------------------------------------------------
	public function SearchAdvance()
	{

		Base::$tpl->assign("aPref",Db::GetAssoc("Assoc/Pref"));

		$this->sPrefixAction='catalog_search_advance';
		$this->aCode=array(Catalog::StripCode(Base::$aRequest['data']['code']));
		$this->sPref=Base::$aRequest['data']['pref'];

		$aData=array(
		'sHeader'=>"method=get",
		'sTitle'=>"Search advance",
		'sContent'=>Base::$tpl->fetch($this->sPrefix.'/form_'.$this->sPrefixAction.'.tpl'),
		'sSubmitButton'=>'Search',
		'sSubmitAction'=>$this->sPrefixAction,
		'bIsPost'=>0,
		'sWidth'=>'450px',
		'sError'=>$sError,
		);
		$oForm=new Form($aData);
		Base::$sText.=$oForm->getForm();

		if ($this->aCode && strlen($this->aCode[0])>=3 && $this->sPref) {
			$oTable=new Table();
			$oTable->sSql=Base::GetSql('Catalog/Price',array(
			'is_advance'=>1,
			'aCode'=>$this->aCode,
			'pref'=>$this->sPref,
			));

			$oTable->aOrdered=" ";
			$oTable->iRowPerPage=50;
			$oTable->aOrdered="order by p.item_code";
			$oTable->aColumn=array(
			'brand'=>array('sTitle'=>'Brand'),
			'code'=>array('sTitle'=>'Code'),
			'name_translate'=>array('sTitle'=>'Name'),
			'action'=>array(),
			);
			$oTable->sDataTemplate=$this->sPrefix.'/row_'.$this->sPrefixAction.'.tpl';
			//$oTable->aCallback=array($this,'CallParsePrice');

			Base::$sText.=$oTable->getTable("Search advance");
			//Base::$sText.=Base::$tpl->fetch($this->sPrefix.'/button_'.$this->sPrefix.'.tpl');
		} elseif ($this->sPref && strlen($this->aCode[0])<3) {
			Base::Message(array('MI_ERROR'=>Language::getMessage('Need check code 3 charset')));
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function PrintPartName($aRow)
	{
		$aRow['name']=strip_tags($aRow['name']);
		$aRow['name_translate']=strip_tags($aRow['name_translate']);
		Base::$tpl->assign('aRow',$aRow);
		return Base::$tpl->fetch("catalog/part_name.tpl");
	}
	//-----------------------------------------------------------------------------------------------
	public function ChangeSelect() {

		//Debug::PrintPre(Base::$aRequest);

		if (Base::$aRequest['data']['id_make'] && !Base::$aRequest['data']['id_model']) {
			//opti
// 			$aModels=Db::GetAssoc("Assoc/OptiCatModel",array(
// 			"id_make"=>Base::$aRequest['data']['id_make'],
// 			"sOrder"=>" order by name "
// 			));
			$aModels=TecdocDb::GetModelAssoc(array(
			"id_make"=>Base::$aRequest['data']['id_make'],
			"sOrder"=>" order by name "
			));
			Base::$tpl->assign('aModel',array(""=>Language::getMessage('choose model'))+$aModels);

			Base::$oResponse->addAssign('id_model','outerHTML',
			Base::$tpl->fetch($this->sPrefix."/select_model.tpl"));

			Base::$oResponse->addAssign('id_model_detail','outerHTML',
			Base::$tpl->fetch($this->sPrefix."/select_model_detail.tpl"));
		}

		if (Base::$aRequest['data']['id_make'] && Base::$aRequest['data']['id_model']
		&& !Base::$aRequest['data']['id_model_detail']) {

			//opti
			//$aModelDetailAll=Db::GetAll(Base::GetSql("OptiCatalog/ModelDetail",Base::$aRequest['data']));
			$aModelDetailAll=TecdocDb::GetModelDetails(Base::$aRequest['data']);
			$aModelDetail['']=Language::getMessage('choose model detail');
			foreach ($aModelDetailAll as $sKey => $aValue) {
				$aModelDetail[$aValue['id_model_detail']]=$aValue['name']." ".$aValue['year_start']
				//$aModelDetail[$aValue['id_model_detail']]=$aValue['Description']." ".$aValue['year_start']
				."-".$aValue['year_end']; //$aValue['name']." ".$aValue['month_start'].".".     $aValue['month_end'].".".
			}
			Base::$tpl->assign('aModelDetail',$aModelDetail);

			Base::$oResponse->addAssign('id_model_detail','outerHTML',
			Base::$tpl->fetch($this->sPrefix."/select_model_detail.tpl"));

		}

		if (Base::$aRequest['data']['id_make'] && Base::$aRequest['data']['id_model']
		&& Base::$aRequest['data']['id_model_detail']) {

			if (0) {
				$aTree=Db::GetAll(Base::GetSql("OptiCatalog/Assemblage",Base::$aRequest['data']+array(
				//'bTreeFilter'=>true,
				"order"=>" order by str_sort "
				)));

				foreach ($aTree as $sKey => $aValue) {
					$aGroup[$aValue['id']]=str_repeat("&nbsp;&nbsp;",$aValue['str_level']-2).$aValue['data'];
				}

				Base::$tpl->assign('aPart',array(""=>Language::getMessage('choose group'))+$aGroup);

				Base::$oResponse->addAssign('select_part','innerHTML',
				Base::$tpl->fetch($this->sPrefix."/select_part.tpl"));
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function LoadAutotechnics() {
		$aUser=Auth::IsUser('system',Base::$aRequest['p'],true,true);
		if(!$aUser) die("Ok");
		Auth::Login('system',Base::$aRequest['p'],true,true,true);
		Auth::IsAuth();
			
		if($_FILES['file']) {
			$excel_file = $_FILES['file']['tmp_name'];
			if (strpos($_FILES['file']['name'],".zip")>0) {
				$oZip = new ZipArchive;
				if ($oZip->open($excel_file) === TRUE) {
					$oZip->extractTo(SERVER_PATH."/imgbank/price/");
					$oZip->close();
				} else {
					die('Bad zip');
				}
			} elseif (@move_uploaded_file($excel_file, SERVER_PATH."/imgbank/price/".$_FILES['file']['name'])) {

			}
		}
		$aPrice_profile=Db::GetRow($s=Base::GetSql("Price/Profile", array('name'=>'autotechnics')));
		if ($aPrice_profile['delete_before']==1 && $aPrice_profile['id_provider']) {
			Base::$db->Execute("update price set price=0 where id_provider in (".$aPrice_profile['id_provider'].")");
		}
		Base::$aRequest['action']="cron_autotechnics"; //чтобы не проверялся менеджер в Price
		$oPrice=new Price;
		$oPrice->LoadFromCsv(array('path'=>SERVER_PATH."/imgbank/price/".$_FILES['file']['name']),
				$aPrice_profile,Auth::$aUser['id']);
		unlink(SERVER_PATH."/imgbank/price/".$_FILES['file']['name']);
		$oPrice->Install(false);
		Base::$db->Execute("delete from `price_import` where id_user=".Auth::$aUser['id']);
		//Base::$db->Execute("optimize table `price_import`");
		die('File load');
	}
	//-----------------------------------------------------------------------------------------------
	function CrossStop() {
		Auth::NeedAuth('manager');

		if (Auth::$aUser['type_']=='manager'){
			$this->sPrefixAction="catalog_cross_stop";
			Base::$aTopPageTemplate=array('panel/tab_cross.tpl'=>$this->sPrefixAction);
		}
		
		Base::$tpl->assign("aPref",$aPref=array(""=>"")+Db::GetAssoc("Assoc/Pref", array('all'=>1)));

		/* [ apply  */
		if (Base::$aRequest['is_post'])
		{
			if (!Base::$aRequest['data']['pref'] || !Base::$aRequest['data']['code']
			|| !Base::$aRequest['data']['pref_crs'] || !Base::$aRequest['data']['code_crs'])
			{
				Base::Message(array('MF_ERROR'=>'Required fields brand and code'));
				Base::$aRequest['action']=$this->sPrefix.'_cross_stop_add';
				Base::$tpl->assign('aData', Base::$aRequest['data']);
			}
			else
			{
				$aData=String::FilterRequestData(Base::$aRequest['data']);
				$aData["code"]=Catalog::StripCode(strtoupper($aData["code"]));
				$aData["code_crs"]=Catalog::StripCode(strtoupper($aData["code_crs"]));
				if(!$aData["source"])$aData["source"]='manager:'.Auth::$aUser['login'];

				if (Base::$aRequest['id']) {
					$aId=Db::GetRow("select * from cat_cross_stop where id=".Base::$aRequest['id']);

					Db::Execute("delete from cat_cross_stop where pref='".$aId['pref']."' and code='".$aId['code']."'
					and pref_crs='".$aId['pref_crs']."' and code_crs='".$aId['code_crs']."'");

					Db::Execute("delete from cat_cross_stop where pref='".$aId['pref_crs']."' and code='".$aId['code_crs']."'
					and pref_crs='".$aId['pref']."' and code_crs='".$aId['code']."'");

					$this->InsertCrossStop($aData);
					$sMessage="&aMessage[MF_NOTICE]=Cross stop updated";
				}
				else {
					$this->InsertCrossStop($aData);
					$sMessage="&aMessage[MF_NOTICE]=Cross stop added";
				}
				Form::RedirectAuto($sMessage);
			}
		}
		/* ] apply */

		if (Base::$aRequest['action']==$this->sPrefix.'_cross_stop_add' || Base::$aRequest['action']==$this->sPrefix.'_cross_stop_edit')
		{
			if (Base::$aRequest['action']==$this->sPrefix.'_cross_stop_edit')
			{
				Base::$tpl->assign('aData',$aData=Base::$db->getRow(Base::GetSql("Catalog/PartCrossStop",
				array("id"=>Base::$aRequest['id']?Base::$aRequest['id']:"-1"))));
			} elseif (Base::$aRequest['action']==$this->sPrefix.'_cross_stop_add' && Base::$aRequest['item_code']) {
				list($aData['pref_crs'],$aData['code_crs'])=explode('_',Base::$aRequest['item_code']);
				Base::$tpl->assign('aData',$aData);
			}
			
// 			$aField['pref']=array('title'=>'Make','type'=>'select','options'=>$aPref,'selected'=>$aData['pref'],'name'=>'data[pref]','id'=>'pref','szir'=>1);
// 			$aField['code']=array('title'=>'Code Part','type'=>'input','value'=>$aData['code'],'name'=>'data[code]','id'=>'code','szir'=>1);
// 			$aField['pref_crs']=array('title'=>'Make Cross','type'=>'select','options'=>$aPref,'selected'=>$aData['pref_crs'],'name'=>'data[pref_crs]','id'=>'pref','szir'=>1);
// 			$aField['code_crs']=array('title'=>'Code Part Cross','type'=>'input','value'=>$aData['code_crs'],'name'=>'data[code_crs]','id'=>'code','szir'=>1);
			
			$aData=array(
			'sHeader'=>"method=post",
			'sTitle'=>"New cross stop",
			'sContent'=>Base::$tpl->fetch($this->sPrefix.'/form_cross_stop.tpl'),
// 			'aField'=>$aField,
// 			'bType'=>'generate',
			'sSubmitButton'=>'Apply',
			'sSubmitAction'=>$this->sPrefix."_cross_stop",
			'sReturnButton'=>'<< Return',
			'bAutoReturn'=>true,
			);
			$oForm=new Form($aData);
			Base::$sText.=$oForm->getForm();

			return;
		}

		Base::Message();
        unset($aField);
		$aField['pref']=array('title'=>'Make','type'=>'select','options'=>$aPref,'selected'=>Base::$aRequest['search']['pref'],'name'=>'search[pref]','id'=>'pref');
		$aField['code']=array('title'=>'Code Part','type'=>'input','value'=>Base::$aRequest['search']['code'],'name'=>'search[code]','id'=>'code');
		
		$oForm= new Form();
		//$oForm->sTitle="Catalog Cross";
		//$oForm->sContent=Base::$tpl->fetch($this->sPrefix."/form_cross_stop_search.tpl");
		$oForm->aField=$aField;
		$oForm->bType='generate';
		$oForm->sGenerateTpl='form/index_search.tpl';
		$oForm->sSubmitButton="Search";
		$oForm->sSubmitAction=$this->sPrefix."_cross_stop";
		$oForm->sReturnButton="Clear";
		$oForm->sReturnAction=$this->sPrefix."_cross_stop";
		$oForm->sReturn=Base::RemoveMessageFromUrl($_SERVER ['QUERY_STRING']);
		//$oForm->bAutoReturn=true;
		//$oForm->sAdditionalButtonTemplate=$this->sPrefix."/button_price_request_view.tpl";
		$oForm->bIsPost=0;
		$oForm->sWidth="900px";
		Base::$sText.=$oForm->getForm();


		$oTable= new Table();
		$aData=Base::$aRequest['search'];

		if (Catalog::StripCode(Base::$aRequest['search']['code'])) {
			$aData['aCode']=array(Catalog::StripCode(Base::$aRequest['search']['code']));
		}
		if (Base::$aRequest['search']['source']) {
			$aData['source']=Base::$aRequest['search']['source'];
		}

		$oTable->sSql=Base::GetSql("Catalog/PartCrossStop",$aData);
		$oTable->aColumn['id']=array('sTitle'=>'Id');
		$oTable->aColumn['pref']=array('sTitle'=>'Pref');
		$oTable->aColumn['code']=array('sTitle'=>'Code part');
		$oTable->aColumn['pref_crs']=array('sTitle'=>'Pref Crs');
		$oTable->aColumn['code_crs']=array('sTitle'=>'Code Crs');
		//$oTable->aColumn['source']=array('sTitle'=>'Source Crs');
		$oTable->aColumn['action']=array();
		$oTable->iRowPerPage=10;
		$oTable->sDataTemplate=$this->sPrefix.'/row_cross_stop.tpl';
		$oTable->sButtonTemplate=$this->sPrefix.'/button_cross_stop.tpl';
		$oTable->bCheckVisible=false;
		$oTable->aOrdered=" order by cc.id desc";
		if(Base::$aRequest['search'])
		$_SESSION['manager']['cross_stop_sql']=$oTable->sSql;
		else
		unset($_SESSION['manager']['cross_stop_sql']);

		Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function ViewBrand()
	{
		//Debug::PrintPre(Base::$aRequest);
		if(Base::$aRequest['pref'])
			$sWhere = " and c.pref='".Base::$aRequest['pref']."'";
		elseif(Base::$aRequest['name'])
		$sWhere = " and c.name='".Base::$aRequest['name']."'";
		else
			$sWhere = " and 1=0";
		$aBrand=Db::GetRow(Base::GetSql("Cat/Brand"
				, array("where"=>$sWhere)
		));
		if(Base::$aRequest['xajax']){
			Base::$aRequest['pref']=$aBrand['pref'];
			$this->ViewBrandShow();
			return;
		}
		if(strpos($_SERVER['REQUEST_URI'],'?')!==FALSE) Base::Redirect("/brand/".$aBrand['name']."/");
		Content::RedirectOnSlash();
		//Base::$sBaseTemplate="index_popup.tpl";
		mb_internal_encoding("UTF-8");
		$aPageTitle=String::GetSmartyTemplate('catalog_brand:page_title', array('user_data'=>$aBrand),false);
		$sText = strip_tags($aPageTitle['parsed_text']);
		$sText = trim(str_replace('&nbsp;', ' ', $sText));
		If (mb_strlen($sText) <= 100)
		$sFirstPart = $sNextPart = $sTreePart = $sText;
		else {
			$sFirstPart = mb_substr($sText, 0, mb_strripos(mb_substr($sText,0,100),' '));
			$sNextPart = trim(mb_substr($sText, mb_strlen($sFirstPart)));
			if (mb_strlen($sNextPart) > 150)
				$sNextPart = mb_substr($sNextPart, 0, (mb_strripos(mb_substr($sNextPart,0,150),' ') ? mb_strripos(mb_substr($sNextPart,0,150),' ') : strlen($sNextPart)));
			if (mb_strlen($sText) <= 150)
				$sTreePart = $sText;
			else
				$sTreePart = mb_substr($sText, 0, (mb_strripos(mb_substr($sText,0,150),' ') ? mb_strripos(mb_substr($sText,0,150),' ') : strlen($sText)));
		}
		//Debug::PrintPre($aPageTitle['parsed_text']);
		Base::$aData['template']['sPageTitle'] = $sFirstPart;
		$aPageDescription=String::GetSmartyTemplate('catalog_brand:page_description', array('user_data'=>$aBrand),false);
		Base::$aData['template']['sPageDescription']=$sNextPart;
		$aPageKeyword=String::GetSmartyTemplate('catalog_brand:page_keyword', array('user_data'=>$aBrand),false);
		//Debug::PrintPre($aPageKeyword);
		$sText = strip_tags($aPageKeyword['parsed_text']);
		$sText = trim(str_replace('&nbsp;', ' ', $sText));
		if (mb_strlen($sText) <= 150)
			$sTreePart = $sText;
		else
			$sTreePart = mb_substr($sText, 0, (mb_strripos(mb_substr($sText,0,150),' ') ? mb_strripos(mb_substr($sText,0,150),' ') : strlen($sText)));
	
		Base::$aData['template']['sPageKeyword']=$sTreePart;
	
		$this->CorrectBrandLink($aBrand);
		Base::$tpl->assign('aRow',$aBrand);
		Base::$tpl->assign('sReturn',Base::RemoveMessageFromUrl($_SERVER ['QUERY_STRING']));
		Base::$sText.=Base::$tpl->fetch('catalog/brand.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function ViewBrandShow(){
		$aBrand=Db::GetRow(Base::GetSql("Cat/Brand"
				, array("pref"=>Base::$aRequest['pref']?Base::$aRequest['pref']:-1)
		));
		if(!Base::$aRequest['xajax']) Base::Redirect ("/brand/".$aBrand['name']."/");
		Base::$tpl->assign('sReturn',Base::$aRequest['return']);
		$this->CorrectBrandLink($aBrand);
		Base::$tpl->assign('aRow',$aBrand);

		Base::$oResponse->AddAssign('popup_caption_id','innerHTML', Language::GetMessage('Brand info'));
		Base::$tpl->assign('sContent',Base::$tpl->fetch('catalog/brand.tpl'));
		Base::$oResponse->AddAssign('popup_content_id','innerHTML',
		Base::$tpl->fetch('price_queue/message.tpl'));
	}
	//-----------------------------------------------------------------------------------------------
	private function CorrectBrandLink(&$aBrand){
		$s=$aBrand['link'];
		if(!$s) return;
		$s=strip_tags($s);
		$s=trim($s);
		$s=str_replace('http://','',$s);
		$aBrand['link']=$s;
	}
	//-----------------------------------------------------------------------------------------------
    public function ChangePartParam() {
		if(Base::$aRequest['param_id'] && isset(Base::$aRequest['param_value']) && Base::$aRequest['item_code']) {
			Db::Execute("insert into price_group_param (item_code,".Base::$aRequest['param_id'].") values ('".Base::$aRequest['item_code']."','".Base::$aRequest['param_value']."')
			    on duplicate key
			    update ".Base::$aRequest['param_id']."=values(".Base::$aRequest['param_id'].")  ");
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseCross(&$aItem)
	{
		if ($aItem) {
			$iNeedAddFields = 1;
			$aIdManagers = array();
			$aPref = array();
			foreach ($aItem as $iKey =>$aValue) {
				if (isset($aValue['brand']) && isset($aValue['manager_name']) && isset($aValue['manager_login'])) {
					$iNeedAddFields = 1;
					break;
				}
// 				if ($aValue['id_manager'])
// 					$aIdManagers[$aValue['id_manager']] = $aValue['id_manager'];
				if ($aValue['pref'])
					$aPref[$aValue['pref']] = $aValue['pref'];  
			}
			if ($iNeedAddFields) {
// 				if ($aUsersInfo)
// 					$aUsersInfo = Db::GetAssoc("Select u.id, um.name manager_name,u.login manager_login 
// 						from user u 
// 						left join user_manager um on um.id_user=u.id
// 						where u.id in (".implode(',',array_keys($aIdManagers)).")");
// 				if ($aCatInfo)
					$aCatInfo = Db::GetAssoc("Select pref,title from cat where pref in ('".implode('\',\'',array_keys($aPref))."')");
				foreach ($aItem as $iKey => $aValue) {
					$aItem[$iKey]['brand'] = $aCatInfo[$aValue['pref']];
// 					$aItem[$iKey]['manager_name'] = $aUsersInfo[$aValue['id_manager']]['manager_name'];
// 					$aItem[$iKey]['manager_login'] = $aUsersInfo[$aValue['id_manager']]['manager_login'];
				}
			}
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function ViewOwnAuto() {
		if (Base::$aRequest['id']) {
			$aUserInfo = OwnAuto::GetInfoById(Base::$aRequest['id']);
			
			$sType = 'catalog_detail_model_view';
			if ($aUserInfo['id_model_detail'])
				$sType = 'catalog_assemblage_view';
				
			$sUrl = Content::CreateSeoUrl($sType,array(
			'data[id_make]'=>$aUserInfo['id_make'],
			'data[id_model]'=>$aUserInfo['id_model'],
			'data[id_model_detail]'=>$aUserInfo['id_model_detail'],
			'model_translit'=>Content::Translit($aUserInfo['name_model'])
			));
			if ($sUrl)
				Base::Redirect($sUrl);
		}
		
		Base::Redirect('/pages/catalog/');
	}
	//-----------------------------------------------------------------------------------------------
	public function MotoBrands() {
	    $sSql="select Replace(mfa.Name,' MOTORCYCLES','') as title, mfa.id_src as id
    	    from ".DB_OCAT."cat_alt_manufacturer as mfa
    	    inner join ".DB_OCAT."cat_alt_types as t on t.id_mfa=mfa.id_mfa and t.body='Мотоцикл' 
	        group by mfa.id_src
	        order by mfa.Name";
	    $aData=TecdocDb::GetAll($sSql);
	    
        $oTable=new Table();
        $oTable->bHeaderVisible=false;
        $oTable->iGallery=4;
        $oTable->iRowPerPage=10000;
        $oTable->sStepperAlign='center';
        $oTable->bStepperVisible = false;
        $oTable->sTemplateName='gallery.tpl';
        //$oTable->sSql =$sSql;
        $oTable->sType="array";
        $oTable->aDataFoTable=$aData;
        
        $oTable->sClass .= ' catalog-brands-table mobile-table';
        $oTable->bIsGallery= true;
        $oTable->sNoItem='No items';
        $oTable->aColumn['item']=array('sTitle'=>'Name','sWidth'=>'20%');
        $oTable->sDataTemplate = "catalog/row_moto_brands.tpl";
        
        Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function MotoModels() {
	    $sSql="select mfa.Name as title, mfa.id_src as id_tof, cm.id_src as id_model, cm.Name as name 
	        ,substr(cm.DateStart,1,2) as month_start, 
	        substr(cm.DateStart,4,4) as year_start
    		,substr(cm.DateEnd,1,2) as month_end, 
	        substr(cm.DateEnd,4,4) as year_end
	        
    	    from ".DB_OCAT."cat_alt_manufacturer as mfa
	        inner join ".DB_OCAT."cat_alt_models as cm on cm.id_mfa=mfa.id_mfa and mfa.id_src='".Base::$aRequest['brand']."' 
    	    inner join ".DB_OCAT."cat_alt_types as t on t.id_mod=cm.id_mod and t.body='Мотоцикл'
	        group by cm.id_src
	        order by cm.Name";
	    $aData=TecdocDb::GetAll($sSql);
	    
	    $oTable=new Table();
	    $oTable->sType='array';
	    $oTable->aDataFoTable=$aData;
	    
	    $oTable->aColumn['name']=array('sTitle'=>'Make','sWidth'=>'75%');
	    $oTable->aColumn['year']=array('sTitle'=>'Year','sWidth'=>'25%');
	    
	    //$oTable->aCallback=array($this,'CallParseModel');
	    $oTable->iRowPerPage=500;
	    $oTable->sDataTemplate='catalog/row_moto_model.tpl';
	    Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function MotoDetails() {
	    $sSql="select mfa.Name as title, mfa.id_src as id_tof, cm.id_src as id_model, t.id_src as id_model_detail, t.Name as name
	        , substr(t.DateStart,5,2) as month_start
             , substr(t.DateStart,1,4) as year_start
    		, substr(t.DateEnd,5,2) as month_end
    		, substr(t.DateEnd,1,4) as year_end
    
    		, t.ID_src as id_model_detail
    		, t.Description as name
    		, LEFT(KwHp, LOCATE('/', KwHp)-1) kw_from
    		, SUBSTR(KwHp, LOCATE('/', KwHp)+1) hp_from
    		, CCM as ccm, Body as body
    		, t.ID_src as typ_id
	    
    	    from ".DB_OCAT."cat_alt_manufacturer as mfa
	        inner join ".DB_OCAT."cat_alt_models as cm on cm.id_mfa=mfa.id_mfa and mfa.id_src='".Base::$aRequest['brand']."' and cm.id_src='".Base::$aRequest['model']."'
    	    inner join ".DB_OCAT."cat_alt_types as t on t.id_mod=cm.id_mod and t.body='Мотоцикл'
	        group by t.id_src
	        order by t.Name";
	    $aData=TecdocDb::GetAll($sSql);
	    
	    $oTable=new Table();
	    $oTable->sClass .= " model-detail-table mobile-table";
	    
	    $oTable->sType='array';
	    $oTable->aDataFoTable=$aData;
	    
	    $oTable->aColumn['name']=array('sTitle'=>'Make','sWidth'=>'55%', 'sClass'=>'cell-name');
	    $oTable->aColumn['year']=array('sTitle'=>'Yaer','sWidth'=>'5%', 'sClass'=>'cell-years');
	    $oTable->aColumn['power_kw']=array('sTitle'=>'Power<br>KW','sWidth'=>'5%', 'sClass'=>'cell-power-kw');
	    $oTable->aColumn['power_hp']=array('sTitle'=>'Power<br>HP','sWidth'=>'5%', 'sClass'=>'cell-power-hp');
	    $oTable->aColumn['V']=array('sTitle'=>'V','sWidth'=>'5%', 'sClass'=>'cell-volume');
	    $oTable->aColumn['assemblage']=array('sTitle'=>'assemblage','sWidth'=>'30%', 'sClass'=>'cell-body');
	    
	    $oTable->iRowPerPage=500;
	    $oTable->sDataTemplate='catalog/row_moto_modeldetail.tpl';
	    Base::$sText.=$oTable->GetTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function MotoAssemblage() {
		Resource::Get()->Add('/css/tree.css',1);
		Resource::Get()->Add('/js/tree.js');
		
		if(Base::$aRequest['id_lang']) $iIdLng=Base::$aRequest['id_lang'];
		else {
		    $iIdLng=4;
		    $_REQUEST['id_lang']=4;
		}
		
		$aParams=Base::$aRequest;
		unset($aParams['id_lang']);
		unset($aParams['action']);
		$sUrl='/pages/catalog_moto_assemblage/?';
		$aUrl=array();
		foreach ($aParams as $sKey => $sValue) $aUrl[]=$sKey."=".$sValue;
		$sUrl.=implode("&", $aUrl);
		Base::$tpl->assign('sUrlWithoutLang',$sUrl);
		
// 		$aLangAssoc=Db::GetAssoc("SELECT l.lng_id, t.TEX_TEXT
//             FROM ".DB_TOF."`tof__languages` as l
//             left join ".DB_TOF."tof__designations as d on l.lng_des_id=d.des_id and d.DES_LNG_ID=4
//             left join ".DB_TOF."tof__des_texts as t on d.DES_TEX_ID=t.TEX_ID
//             where l.lng_id<>255
// 		");
// 		Base::$tpl->assign('aLangAssoc',$aLangAssoc);
		
	    TecdocDb::SetWhere($sWhere, Base::$aRequest, 'level', 't');
	    TecdocDb::SetWhere($sWhere, Base::$aRequest, 'id_parent', 't');
	    
	    $sSql="select t.ID_src as id,
            t.Level+1 str_level,
            t.Sort str_sort,
            0 expand,
            CONCAT(UCASE(MID(t.Name,1,1)),MID(t.Name,2)) as data,
            t.ID_parent str_id_parent,
            0 color
            from ".DB_OCAT."cat_alt_tree t
              
            INNER JOIN (
            SELECT link.ID_tree
            FROM ".DB_OCAT."`cat_alt_link_typ_art` ltyp
            INNER JOIN ".DB_OCAT."cat_alt_link_str_grp link ON link.`ID_grp` = ltyp.`ID_grp`
            INNER JOIN ".DB_OCAT."cat_alt_types t on ltyp.`ID_typ`=t.ID_typ and  t.ID_src='".Base::$aRequest['model_detail']."'
            GROUP BY link.ID_tree
            )groups ON t.ID_tree = groups.ID_tree
                
            where t.Level > 0
            ".$sWhere."
            order by t.Name";
	    
	    $aTreeItem=array();
	    $aTreeAll=TecdocDb::GetAll($sSql);
	    $aTree=array();
	    if ($aTreeAll) foreach ($aTreeAll as $aValue) {
	        $aTree[$aValue['id']]=$aValue;
	    }
	    unset($aTreeAll);
	    
	    //show tecdoc names begin
// 	    $aNames=Db::GetAssoc("
// 	        select str_id as id,tex_text as data
// 	        from ".DB_TOF."tof__search_tree
//             join ".DB_TOF."tof__designations  on str_des_id=des_id and  des_lng_id = ".$iIdLng."
//             join ".DB_TOF."tof__des_texts on des_tex_id=tex_id
	        
// 	        where str_id in ('".implode("','", array_keys($aTree))."')
// 	        ");
// 	    if($aNames) foreach ($aTree as $sKey => $aValue) {
// 	        if($aNames[$sKey]) $aTree[$sKey]['data']=$aNames[$sKey];
// 	    }
	    //show tecdoc names end
	    
	    if (Base::$aRequest['id_part']) $this->SetSelectedPart($aTree, Base::$aRequest['id_part']);
	    
	    if ($aTree) foreach ($aTree as $aValue) {
	        $aTreeItem[$aValue['str_id_parent']][]=array(
	            "selected"=>$aValue['selected'],
	            "name"=>$aValue['data'],
	            "id"=>$aValue['id'],
	            "level"=>$aValue['str_level'],
	            "id_parend"=>$aValue['str_id_parent'],
	            "url"=>'/pages/catalog_moto_assemblage/?brand='.Base::$aRequest['brand'].'&model='.Base::$aRequest['model'].'&model_detail='.Base::$aRequest['model_detail'].'&id_part='.$aValue['id']
	        );
	    }
	    
	    Base::$tpl->assign("sTecdocTree",$this->outTree($aTreeItem, 13771, 0));
	    
	    if (Base::$aRequest['id_part'])
	    {   
	        $sSql="select a.ID_src art_id, UPPER(a.Search) art_article_nr
			, a.Name as name
	        , s.ID_src as id_tof
	        , s.Search as make
	        , REPLACE(s.Search,'&','') as cat_name
	        , s.Search as brand
	        , UPPER(a.Search) as code
	        , 0 as price
	        , concat( '".Base::$aGeneralConf['TecDocUrl']."/imgbank/tcd/' , g.path) as img_path
	            
			FROM ".DB_OCAT."cat_alt_link_typ_art lta
			join ".DB_OCAT."cat_alt_types t on lta.ID_typ=t.ID_typ and t.ID_src = '".Base::$aRequest['model_detail']."'
			join ".DB_OCAT."cat_alt_link_str_grp lsg on lsg.ID_tree = '".Base::$aRequest['id_part']."' and lsg.ID_grp=lta.ID_grp
			join ".DB_OCAT."cat_alt_articles a on a.ID_art=lta.ID_art
			join ".DB_OCAT."cat_alt_suppliers s on lta.ID_sup=s.ID_sup
			join ".DB_OCAT."cat_alt_images g on a.ID_art = g.ID_art and g.path not like 'pdf%'
            	where 1=1
            ".$sWhere;
	        $aData=TecdocDb::GetAll($sSql);
	        
	        //show tecdoc names begin
// 	        if($aData) {
// 	            $aArts=array();
// 	            foreach ($aData as $aValue) $aArts[$aValue['art_id']]=$aValue['art_id'];
	            
//     	        $aNames=Db::GetAssoc("
//     	           select cta.art_id, dest.tex_text as name
//     	           from ".DB_TOF."tof__articles as cta
//                    inner join ".DB_TOF."tof__designations as des on cta.art_complete_des_id = des.des_id  and des.des_lng_id = ".$iIdLng." 
//                    inner join ".DB_TOF."tof__des_texts dest on des.des_tex_id=dest.tex_id
//     	           where cta.art_id in ('".implode("','", $aArts)."')
//     	        ");
    	        
//     	        if($aNames) foreach ($aData as $sKey => $aValue) {
//     	            if($aNames[$aValue['art_id']]) $aData[$sKey]['name']=$aNames[$aValue['art_id']];
//     	        }
    	        
// 	        }
	        //show tecdoc names end
	        
	        $oTable=new Table();
	        $oTable->sType='array';
	        $oTable->aDataFoTable=$aData;
	        
	        $oTable->aColumn['name']=array('sTitle'=>'Name','sWidth'=>'40%', 'sClass'=>'cell-name');
	        $oTable->aColumn['make']=array('sTitle'=>'Make','sWidth'=>'10%', 'sClass'=>'cell-brand');
	        $oTable->aColumn['code']=array('sTitle'=>'Code','sWidth'=>'10%', 'sClass'=>'cell-code');
	        $oTable->aColumn['pic']=array('sTitle'=>'Pic','sWidth'=>'10%', 'sClass'=>'cell-image');
	        $oTable->aColumn['price']=array('sTitle'=>'Price','sWidth'=>'10%', 'sClass'=>'cell-price');
	        $oTable->aColumn['action']=array('sClass'=>'cell-action');
	        	
	        $oTable->sClass .= " row-part-table";
	    
	        $oTable->iRowPerPage=500;
	        $oTable->sDataTemplate='catalog/row_part.tpl';
	        
	        $oTable->bStepperVisible=false;
	        $oTable->sNoItem='No price items';
	    
			Base::$tpl->assign('sTablePrice',$oTable->getTable());
		}
	    
	    
	    Base::$sText.=Base::$tpl->fetch('catalog/new_tree_assemblage.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	function mb_ucfirst($str, $enc = 'utf-8') {
	    return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc).mb_substr($str, 1, mb_strlen($str, $enc), $enc);
	}
	//-----------------------------------------------------------------------------------------------
	public function outTree(&$category_arr=array(), $parent_id, $level) {
	    $sText="";
	    if (isset($category_arr[$parent_id])) { //Если категория с таким parent_id существует
	        $iChildCount=count($category_arr[$parent_id]);
	        $iEndCounter=0;
	        foreach ($category_arr[$parent_id] as $value) { //Обходим
	            $iEndCounter++;
	
	            $level = $level + 1; //Увеличиваем уровень вложености
	            //Рекурсивно вызываем эту же функцию, но с новым $parent_id и $level
	            $sResult=$this->outTree($category_arr, $value["id"], $level);
	
	            if ($value["selected"]) {
	                $sExpandStatus=' ExpandOpen';
	                if(!$sResult) $sBold='style="font-weight: bold;"';
	                else $sBold='';
	            } else {
	                $sExpandStatus=' ExpandClosed';
	                $sBold='';
	            }
	
	            if ($sResult) {
	                // have child and need expand
	                $sText.='<li class="Node';
	                if($level==1) $sText.=' IsRoot';
	                $sText.=$sExpandStatus;
	                if($iEndCounter==$iChildCount) $sText.=' IsLast';
	                $sText.='">';
	                $sText.='  <div class="Expand"></div>';
	                $sText.='  <div class="dContent"><a href="#" onclick="return false" '.$sBold.'>'.$this->mb_ucfirst($value["name"]).'</a></div>';
	            } else {
	                // not need expand
	                $sText.='<li class="Node';
	                if($level==1) $sText.=' IsRoot';
	                $sText.=' ExpandLeaf';
	                if($iEndCounter==$iChildCount) $sText.=' IsLast';
	                $sText.='">';
	                $sText.='	<div class="Expand"></div>';
	                $sText.='	<div class="dContent"><a href="'.$value["url"].'" '.$sBold.'>'.$this->mb_ucfirst($value["name"]).'</a></div>';
	            }
	
	            if ($sResult) {
	                //expand
	                $sText.='	<ul class="Container">';
	                $sText.=$sResult;
	                $sText.='	</ul>';
	            }
	
	            $level = $level - 1; //Уменьшаем уровень вложености
	            $sText.="</li>";
	
	        }
	    }
	    return $sText;
	}
	//-----------------------------------------------------------------------------------------------
	public function SetSelectedPart(&$aTree=array(), $iPart) {
	    if(!$aTree[$iPart] || !$iPart) return;
	    $aTree[$iPart]['selected']=1;
	    if ($iPart=='10001') return;
	    $this->SetSelectedPart($aTree, $aTree[$iPart]['str_id_parent']);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetArtId($sItemCode)
	{
		list($sPref,$sCode)=explode('_', $sItemCode);
		return TecdocDb::GetArt(array(
				'pref'=>$sPref,
				'code'=>$sCode
		));
	}
	//-----------------------------------------------------------------------------------------------
	public function SortTable() {
		if (!Base::$aRequest['sort'])
			Base::$aRequest['sort'] = 'price';
			
		if (!Base::$aRequest['way'])
			Base::$aRequest['way'] = 'up';
			
		Base::$tpl->assign('sTablePriceSort',Base::$aRequest['sort']);
		Base::$tpl->assign('sTablePriceSortWay',Base::$aRequest['way']);
	
		// cut order table
		if (strpos($_SERVER['REQUEST_URI'],'?') !== false) {
			Base::$tpl->assign('iSeoUrlAmp',1);
			$aSeoUrl = explode("&",str_replace("?","",str_replace("/?","",$_SERVER['REQUEST_URI'])));
		}
		else
			$aSeoUrl = explode("/",$_SERVER['REQUEST_URI']);
	
		$aSeoUrlSave = $aSeoUrl;
		foreach($aSeoUrl as $iKey => $sValue) {
			if (!$sValue)
				unset($aSeoUrlSave[$iKey]);
			
			if (strpos($sValue, 'sort=') !== false || strpos($sValue, 'way=') !== false)
				unset($aSeoUrlSave[$iKey]);
		}
		$aSeoUrl = $aSeoUrlSave;
	
		if (strpos($_SERVER['REQUEST_URI'],'?') !== false)
			$sSeoUrl = "/?".implode("&",$aSeoUrl);
		else
			$sSeoUrl = implode("/",$aSeoUrl);
	
		Base::$tpl->assign('sSeoUrl',"/".$sSeoUrl);
	
	}
	//-----------------------------------------------------------------------------------------------
	public function TruckIndex() {
	    $aCats=Db::GetAll(Base::GetSql("Cat",array(
	        'is_brand'=>1,
	        'visible'=>1,
	    )));
	    if($aCats) {
	       $aAssocCat=TecdocDb::GetAssoc("select mfa.ID_Src, mfa.ID_Src as tof
		        from ".DB_OCAT."cat_alt_manufacturer_mfa as mfa
		        where mfa.MFA_cv = 1 ");
	       if($aAssocCat) {
	           foreach ($aCats as $sKey => $aValue) {
	               if(!in_array($aValue['id_tof'], $aAssocCat)) unset($aCats[$sKey]);
	           }
	           sort($aCats);
	       } else {
	           $aCats=array();
	       }
       }
	     
	    $oTable=new Table();
	    $oTable->bHeaderVisible=false;
	    $oTable->iGallery=4;
	    $oTable->iRowPerPage=10000;
	    $oTable->sStepperAlign='center';
	    $oTable->bStepperVisible = false;
	    $oTable->sTemplateName='gallery.tpl';
	    //$oTable->sSql =$sSql;
	    $oTable->sType="array";
	    $oTable->aDataFoTable=$aCats;
	    
	    $oTable->sClass .= ' catalog-brands-table mobile-table';
	    $oTable->bIsGallery= true;
	    $oTable->sNoItem='No items';
	    $oTable->aColumn['item']=array('sTitle'=>'Name','sWidth'=>'20%');
	    $oTable->sDataTemplate = "catalog/row_truck_brands.tpl";
	    
	    Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function TruckModels() {
	    $sSelectedCatName=Db::GetOne("select title from cat where id_tof='".Base::$aRequest['brand']."'");
	    Base::$oContent->AddCrumb(Language::GetMessage('Catalog truck'),'/?action=catalog_truck');
	    Base::$oContent->AddCrumb($sSelectedCatName,'');
	    
	    $aDataForSql['id_tof'] = Base::$aRequest['brand'];
		$aDataForSql['engines'] = 1;
		$aDataForSql['join'] = "INNER JOIN ".DB_OCAT."cat_alt_models_mod as camd on camd.ID_src = m.ID_src";
	    $aDataForSql['where'] = " AND camd.MOD_cv = 1 ";
	     
	    $oTable=new Table();
	    $oTable->sType='array';
		$oTable->aDataFoTable=TecdocDb::GetModels($aDataForSql);
	     
	    $oTable->aColumn['name']=array('sTitle'=>'Make','sWidth'=>'50%');
		$oTable->aColumn['engines']=array('sTitle'=>'engines','sWidth'=>'25%');
	    $oTable->aColumn['year']=array('sTitle'=>'Year','sWidth'=>'25%');
	     
	    //$oTable->aCallback=array($this,'CallParseModel');
	    $oTable->iRowPerPage=500;
	    $oTable->sDataTemplate='catalog/row_truck_model.tpl';
	    Base::$sText.=$oTable->getTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function TruckDetails() {
	    
	    $aModel=TecdocDb::GetModel(array(
	        'id_tof'=>Base::$aRequest['brand'],
	        'id_model'=>Base::$aRequest['model'],
	        'is_truck'=>1
	    ));
	    $sSelectedCatName=Db::GetOne("select title from cat where id_tof='".Base::$aRequest['brand']."'");
	    
	    Base::$oContent->AddCrumb(Language::GetMessage('Catalog truck'),'/?action=catalog_truck');
	    Base::$oContent->AddCrumb($sSelectedCatName,'/?action=catalog_truck_model&brand='.Base::$aRequest['brand'].'/');
	    Base::$oContent->AddCrumb($aModel['name'],'');
	    
	    $oTable=new Table();
	    $oTable->sClass .= " model-detail-table mobile-table";
	    
	    $oTable->sType='array';
	    $oTable->aDataFoTable=TecdocDb::GetModelDetails(array(
	        'id_tof'=>Base::$aRequest['brand'],
	        'id_model'=>Base::$aRequest['model'],
	        'is_truck'=>1
	    ));
	    
	    $oTable->aColumn['name']=array('sTitle'=>'Make','sWidth'=>'55%', 'sClass'=>'cell-name');
	    $oTable->aColumn['year']=array('sTitle'=>'Yaer','sWidth'=>'5%', 'sClass'=>'cell-years');
	    $oTable->aColumn['power_kw']=array('sTitle'=>'Power<br>KW','sWidth'=>'5%', 'sClass'=>'cell-power-kw');
	    $oTable->aColumn['power_hp']=array('sTitle'=>'Power<br>HP','sWidth'=>'5%', 'sClass'=>'cell-power-hp');
	    $oTable->aColumn['V']=array('sTitle'=>'V','sWidth'=>'5%', 'sClass'=>'cell-volume');
	    $oTable->aColumn['fuel']=array('sTitle'=>'catalog fuel','sWidth'=>'10%');
	    $oTable->aColumn['engines']=array('sTitle'=>'catalog engines','sWidth'=>'10%');
        $oTable->aColumn['max_weight']=array('sTitle'=>'catalog max_weight','sWidth'=>'5%');
        $oTable->aColumn['axle']=array('sTitle'=>'catalog axle','sWidth'=>'5%');
        $oTable->aColumn['model_des']=array('sTitle'=>'catalog model_des','sWidth'=>'5%');
	        
// 	    $oTable->aCallback=array($this,'CallParseModelDetail');
	    $oTable->iRowPerPage=200;
	    $oTable->sDataTemplate='catalog/row_truck_model_detail.tpl';
	    Base::$sText.=$oTable->GetTable();
	}
	//-----------------------------------------------------------------------------------------------
	public function TruckAssemblage() {
	    
	    $aModel=TecdocDb::GetModel(array(
	        'id_tof'=>Base::$aRequest['brand'],
	        'id_model'=>Base::$aRequest['model'],
	        'is_truck'=>1
	    ));
	    $aModelDetail=TecdocDb::GetModelDetail(array(
	        'id_tof'=>Base::$aRequest['brand'],
	        'id_model'=>Base::$aRequest['model'],
	        'id_model_detail'=>Base::$aRequest['id_model_detail'],
	    ));
	    $sSelectedCatName=Db::GetOne("select title from cat where id_tof='".Base::$aRequest['brand']."'");
	    
	    Base::$oContent->AddCrumb(Language::GetMessage('Catalog truck'),'/?action=catalog_truck');
	    Base::$oContent->AddCrumb($sSelectedCatName,'/?action=catalog_truck_model&brand='.Base::$aRequest['brand'].'/');
	    Base::$oContent->AddCrumb($aModel['name'],'/?action=catalog_truck_model_detail&brand='.Base::$aRequest['brand'].'&model='.$aModel['mod_id']);
	    Base::$oContent->AddCrumb($aModelDetail['name'],'');
	    
	    $aTree=TecdocDb::GetTreeTruck(array(
	        'id_tof'=>Base::$aRequest['brand'],
	        'id_model'=>Base::$aRequest['model'],
	        'id_model_detail'=>Base::$aRequest['id_model_detail']
	    ));
	    
	    if ($aTree) foreach ($aTree as $sKey => $aValue) {
	        if($aValue['id']=='13771' || $aValue['str_id_parent']=='13771') {
	            unset($aTree[$sKey]);
	            continue;
	        }
	        if ($aValue['str_level']==2) $aIdIcon[]=$aValue['id'];
	        $aTreeAssoc[$aValue['id']]=$aValue;
	    }
	    
	    Base::$tpl->assign("aTree",$aTree);
	    
	    
	    if (Base::$aRequest['part'])
	    {
	        array_pop(Base::$oContent->aCrumbs);
	        Base::$oContent->AddCrumb($aModelDetail['name'],'/?action=catalog_truck_assemblage&brand='.Base::$aRequest['brand'].'&model='.$aModel['mod_id'].'&id_model_detail='.$aModelDetail['id_model_detail']);
	        Base::$oContent->AddCrumb($aTreeAssoc[Base::$aRequest['part']]['data'],'');
	        
	        Base::$bRightSectionVisible=false;
	        Base::$tpl->assign('bRightPartCatalog',true);
	    
	    
	        $oTable=new Table();
	        $oTable->sType='array';
	        $oTable->aDataFoTable=TecdocDb::GetTreeParts(array(
	            'id_model_detail'=>Base::$aRequest['id_model_detail'],
	            'id_part'=>Base::$aRequest['part']
	        ),$this->aCats,1);
	        	
	        Base::$oContent->ShowTimer('PartDetail');
	    
	        $oTable->aColumn['name']=array('sTitle'=>'Name','sWidth'=>'40%', 'sClass'=>'cell-name');
	        $oTable->aColumn['make']=array('sTitle'=>'Make','sWidth'=>'10%', 'sClass'=>'cell-brand');
	        $oTable->aColumn['code']=array('sTitle'=>'Code','sWidth'=>'10%', 'sClass'=>'cell-code');
	        $oTable->aColumn['pic']=array('sTitle'=>'Pic','sWidth'=>'10%', 'sClass'=>'cell-image','nosort'=>1);
	        $oTable->aColumn['price']=array('sTitle'=>'Price','sWidth'=>'10%', 'sClass'=>'cell-price');
	        $oTable->aColumn['action']=array('sClass'=>'cell-action','nosort'=>1);
	        	
	        $oTable->sClass .= " row-part-table";
	    
	        $oTable->iRowPerPage=Language::getConstant('catalog_assemblage:limit_page_items',25);
	        $oTable->sDataTemplate='catalog/row_part.tpl';
// 	        $oTable->aCallback=array($this,'CallParsePart');
            $oTable->aCallbackAfter=array($this,'ParseTruckPartsImages');
	        $oTable->aOrdered=" ";
	        $oTable->bStepperVisible=true;
	        $oTable->iRowPerPage=10;
	        $oTable->sNoItem='No price items';
	        $oTable->sTemplateName = 'catalog/goods_table.tpl';
	    
	        Base::$tpl->assign('sTablePrice',$oTable->getTable());
	        	
// 	        Content::SetMetaTagsPage('tecdoc_tree_part:',array(
// 	            'part' => $aTreeAssoc[Base::$aRequest['data']['id_part']]['data'],
// 	                'modification' => $this->aModelDetail['name'],
// 	                'model' => $this->aModel['name'],
// 	                    'brand' => $this->aCat['name'],
// 	        ));
        } else {
//     	    Content::SetMetaTagsPage('tecdoc_tree:',array(
//     	        'modification' => $this->aModelDetail['name'],
//     	        'model' => $this->aModel['name'],
//     	        'brand' => $this->aCat['name'],
//     	    ));
    	}
    	
    	$aCod = array();
    	if (!Base::$aRequest['part']) {
    	    foreach($aTree as $aValue) {
    	        if ($aValue['str_id_parent'] == $iDefault_select_node) {
    	            if ($aValue['seourl'])
    	                $sPath = $aValue['seourl'];
    	            else
    	                $sPath = '/?action=catalog_part_view&data[id_make]='.Base::$aRequest['brand'].
    	                '&data[id_model]='.Base::$aRequest['model'].
    	                '&data[id_model_detail]='.Base::$aRequest['id_model_detail'].
    	                '&data[id_part]='.$aValue['id'].'&data[sort]='.Base::$aRequest['data']['sort'];
    	
    	            Base::Redirect($sPath);
    	        }
    	    }
    	}
    	else {
    	    // get all parent id in tree for current id_part
    	    $this->getAllParent($aTree, Base::$aRequest['part'], 2002, $aCod);
    	}
    	
    	Base::$tpl->assign("sNeed_aCod", 'none');
    	// check if need add code
    	if (isset($_COOKIE['cookie_id_model_detail']) && isset(Base::$aRequest['id_model_detail']) &&
    	    Base::$aRequest['id_model_detail'] == $_COOKIE['cookie_id_model_detail']) {
	        Base::$tpl->assign("sNeed_aCod", 'add');
	    } elseif (!isset($_COOKIE['cookie_id_model_detail']) || (isset($_COOKIE['cookie_id_model_detail']) &&
	        isset(Base::$aRequest['id_model_detail']) &&
	        Base::$aRequest['id_model_detail'] != $_COOKIE['cookie_id_model_detail'])) {
	        setcookie("cookie_id_model_detail", Base::$aRequest['id_model_detail'], 0, '/');
	        Base::$tpl->assign("sNeed_aCod", 'replace');
	    }
	
	    // get url without sort for jscript
	    $aParams=array("action"=>Base::$aRequest['action'],
	        'data[id_make]'=>Base::$aRequest['brand'],
	        'data[id_model]'=>Base::$aRequest['model'],
	        'data[id_model_detail]'=>Base::$aRequest['id_model_detail'],
	        'data[id_part]'=>Base::$aRequest['part'],
	        'data[name_part]'=>$sNamePart,
	    );
	    $sUrl="";
	    $iCount=count($aParams);
	    $iCurrent=0;
	    foreach ($aParams as $sKey => $aValue) {
	        $sUrl.=$sKey."=".$aValue;
	        if ($iCurrent<$iCount-1) {
	            $sUrl.="&";
	        }
	        $iCurrent++;
	    }
	
	    Base::$tpl->assign("sUrl",$sUrl);
	    Base::$tpl->assign("aCod",$aCod);
	    
    	Resource::Get()->Add('/libp/mpanel/dtree/dtree.js',1);
		Base::$sText.=Base::$tpl->fetch('catalog/truck_tree_assemblage.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function ParseTruckPartsImages(&$aItem) {
	    $aCodeTecdoc=array();
	    $aCodePic=array();
	    if ($aItem) {
	        foreach ($aItem as $sKey => $aValue) {
	            if($aValue['art_id'] && !$aValue['hide_tof_image']) $aCodeTecdoc[]=$aValue['art_id'];
	            else $aCodePic[]=$aValue['id_cat_part'];
	        }
	    }
	    $aGraphic=TecdocDb::GetImages(array(
	        'aIdGraphic'=>$aCodeTecdoc,
	        'aIdCatPart'=>$aCodePic
	    ),$this->aCats);
	    
	    if($aGraphic) foreach ($aItem as $sKey => $aValue) {
	        if ($aGraphic[$aValue['item_code']])
	        {
	            $aItem[$sKey]['image']=$aGraphic[$aValue['item_code']];
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function Original() {
	    Base::$sText.='
	        <script type="text/javascript" src="//static.ilcats.ru/API.v2/JS/JQuery-3.1.0.min.js"></script>
            <script type="text/javascript" src="//static.ilcats.ru/API.v2/JS/JQueryUI-1.12.0/JQueryUI.min.js"></script>
            <script type="text/javascript" src="//static.ilcats.ru/API.v2/JS/JQueryUI-1.12.0/JQueryUI.min.js"></script>
            <link type="text/css" rel="stylesheet" href="//static.ilcats.ru/API.v2/JS/JQueryUI-1.12.0/JQueryUI.css">
            <script type="text/javascript" src="//static.ilcats.ru/API.v2/JS/jquery.scrollTo.min.js"></script>
            <script type="text/javascript" src="//static.ilcats.ru/API.v2/JS/jquery.pep.js"></script>
            <script type="text/javascript" src="//static.ilcats.ru/API.v2/JS/Common.js"></script>
        ';
	    //<link type="text/css" rel="stylesheet" href="//static.ilcats.ru/API.v2/CSS/Template.css">
	    Resource::Get()->Add('/single/ilcats_catalogs/api.v2/css/template.css');
	    
	    include_once(SERVER_PATH.'/single/ilcats_catalogs/settings.php');
	    
	    include_once(SERVER_PATH.'/single/ilcats_catalogs/api.v2/php/Functions.Common.php');
	    include_once(SERVER_PATH.'/single/ilcats_catalogs/api.v2/php/Functions.Blocks.php');
	    
	    if (!$_GET['function']) {$_GET['function']='defaultFunction';}
	    if (!$_GET['language'])	$_GET['language']=$_COOKIE['language'] ? $_COOKIE['language'] : "ru";
	    $vinTmp = (empty($_GET["vin"]) ? array() : array("vin" => $_GET["vin"]));
	    
	    if ($_GET['brand']) $data=getApiData($_GET);
	    else $data=getApiData(array_merge(array("function"=>"getBrands","apiVersion"=>'2.0',"shopClientId"=>$_GET["clid"], "catalogId"=>$_GET["pid"],"shopid"=>$_GET["shopid"],"language"=>$_GET["language"]),$vinTmp));
	    
	    $SiteLabels=$data['siteLabels'];
	    if ($data['mainMenu']) $Page['MainMenu']=MainMenu($data['mainMenu']);
	    if ($data['availableLanguages']) {
	        $Page['Languages']=Languages($data['availableLanguages']); 
	    } else {
            $Page['Languages']="No 'availableLanguages'";
	    }
	    if ($data['data']) {
	        foreach ($data["data"] as $Data) {
	            $Page['Content'][]=($Data['caption'] ? "<h2>{$Data['caption']}</h2>" : "").$Data['format']($Data);
	        }
	    } else {
	        $Page['Content'][]="No 'data'";
	    }

	    
	    Base::$sText.="<div class='Top'>".$Page['MainMenu']." ".$Page['Languages']."</div>".VinForm($data['vinSearchParameters']);
	    Base::$sText.="<h1>".$data["stageName"]."</h1>";
	    
	    if ($data['data'][0]['format']=='ifImage'){
	        $TempPageContent[0]=$Page['Content'][0];
	        array_shift($Page['Content']);
	        $TempPageContent[1]="<div class='Info'>".ImplodeIfArray($Page['Content'])."</div>";
	        $Page['Content']="<div class='ifImage'>".ImplodeIfArray($TempPageContent)."</div>";
	    }
	    
	    Base::$sText.=ImplodeIfArray($Page['Content']);
	}
	//-----------------------------------------------------------------------------------------------
}
?>