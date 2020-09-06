<?

class LevamCatalog extends Base
{
    //-----------------------------------------------------------------------------------------------
    public function __construct()
    {
        include SERVER_PATH."/single/levan_catalogs/library/levam_oem.php";
        include SERVER_PATH."/single/levan_catalogs/library/translations.php";
        
        Base::$oContent->AddCrumb(Language::GetMessage('Оригинальный каталог'),'/pages/levam_catalog');
    }
    //-----------------------------------------------------------------------------------------------
    public function Index()
    {
        include_once SERVER_PATH."/single/levan_catalogs/config.php";
        $type = 0;
        
        $out = ListCatalogs($api_key,$type);
        $out = json_decode($out,true);
        
        if($out['catalogs']) {
            $aBrandList=array('0'=>"Выберите марку");
            foreach ($out['catalogs'] as $aValue) {
                $aBrandList[$aValue['code']]=$aValue['name'];
            }
        }
        
        Base::$tpl->assign('aBrandList',$aBrandList);
        Base::$sText.=Base::$tpl->fetch('levam_catalog/index.tpl');
    }
    //-----------------------------------------------------------------------------------------------
    public function ChangeMark()
    {
        include_once SERVER_PATH."/single/levan_catalogs/config.php";
        $type = 0;
        
        $models = FindModels($api_key,Base::$aRequest['mark'],$type);
        $models = json_decode($models,true);
        
        if($models['models']) {
            $aModelList=array('0'=>"Выберите модель");
            foreach ($models['models'] as $sKey => $aValue) {
                $aModelList[$sKey]=$sKey;
            }
        }

        Base::$tpl->assign('sMark',Base::$aRequest['mark']);
        Base::$tpl->assign('aModelList',$aModelList);
        Base::$oResponse->AddAssign('model','outerHTML',Base::$tpl->fetch('levam_catalog/select_model.tpl'));
    }
    //-----------------------------------------------------------------------------------------------
    public function ChangeModel()
    {
        include_once SERVER_PATH."/single/levan_catalogs/config.php";
        $type = 0;
        
        $models = FindModels($api_key,Base::$aRequest['mark'],$type);
        $models = json_decode($models,true);
        
        if($models['models']) {
            $aParamListTmp=$models['models'][stripcslashes(Base::$aRequest['model'])];
            
            if($aParamListTmp) {
                $aParamList=array('0'=>"Уточните модель");
                foreach ($aParamListTmp as $aValue) {
                    $aParamList[$aValue[0]]=$aValue[0];
                }
            }
        }

        Base::$tpl->assign('sMark',Base::$aRequest['mark']);
        Base::$tpl->assign('sModel',Base::$aRequest['model']);
        Base::$tpl->assign('sModelCode',$aValue[1]);
        Base::$tpl->assign('aParamList',$aParamList);
        Base::$oResponse->AddAssign('param','outerHTML',Base::$tpl->fetch('levam_catalog/select_param.tpl'));
    }
    //-----------------------------------------------------------------------------------------------
    public function ChangeParam()
    {
        include_once SERVER_PATH."/single/levan_catalogs/config.php";
        $type = 0;
        
       /* $brandCode =  Base::$aRequest['mark'];
        $familyCode = stripslashes(Base::$aRequest['model']);
        $modelCode = Base::$aRequest['param'];
        $catalogCode = Base::$aRequest['model_code'];
         
        $params = FindParams($api_key,$catalogCode,$modelCode,$ssd,$param,$lang);
        $params = json_decode($params,true);
         */
        $brandCode =Base::$aRequest['mark'];
        $modelCode = Base::$aRequest['param'];
        //$ssd = $params['client']['ssd'];
        $param = '';
        $catalogCode = Base::$aRequest['model_code'];
        $family = stripslashes(Base::$aRequest['model']);
        
        $errorText = array();
        
        $params = FindParams($api_key,$catalogCode,$modelCode,$ssd,$param,$lang,$family);
        $params = json_decode($params,true);
        
        $ssd = $params['client']['ssd'];
        Base::$tpl->assign('sSSD',$ssd);

//         Base::$oResponse->addAssign('result','innerHTML',print_r($params['params'],true));
        Base::$oResponse->addAssign('button','innerHTML',Base::$tpl->fetch('levam_catalog/button_modification.tpl'));
    }
    //-----------------------------------------------------------------------------------------------
    public function Modification()
    {
        include_once SERVER_PATH."/single/levan_catalogs/config.php";
        $type = 0;
        
        $ssd = Base::$aRequest['ssd'];
         
        $modifications = FindModifications($api_key,$ssd,$lang);
        $modifications = json_decode($modifications,true);
        
        $aDataFoTable=array_values($modifications['modifications']);
        $sMake='';
        if($aDataFoTable) {
            foreach ($aDataFoTable as $sKeyRoot => $aValueRoot) {
                foreach ($aValueRoot as $sKey=>$sValue) {
                    if($sKey=='Марка') {
                    	$aDataFoTable[$sKeyRoot]['make']=$sValue;
                    	if (!$sMake)
                    		$sMake = $sValue;
                    }
                    if($sKey=='Регион' || $sKey=='Рынок') $aDataFoTable[$sKeyRoot]['region']=$sValue;
                    if($sKey=='Руль') $aDataFoTable[$sKeyRoot]['rudder']=$sValue;
                    if($sKey=='Тип трансмиссии') $aDataFoTable[$sKeyRoot]['trans']=$sValue;
                    if($sKey=='Год производства' || $sKey=='Дата выпуска') $aDataFoTable[$sKeyRoot]['year']=$sValue;
                    if($sKey=='Месяц производства') $aDataFoTable[$sKeyRoot]['month']=$sValue;
                }
            }
        }
        
        if ($sMake)
        	Base::$oContent->AddCrumb('Модификации ' . $sMake .' '. $modifications['client']['family']." (".$modifications['client']['model'].")");
        
        $oTable=new Table();
        $oTable->sType='array';
        $oTable->aDataFoTable=$aDataFoTable;
        $oTable->iRowPerPage=2000;
        $oTable->sDataTemplate='levam_catalog/row_modification.tpl';
        
        Base::$sText.=$oTable->GetTable();
    }
    //-----------------------------------------------------------------------------------------------
    public function Group()
    {
        include_once SERVER_PATH."/single/levan_catalogs/config.php";
        $type = 0;
        
        $ssd = Base::$aRequest['ssd'];
        $link = Base::$aRequest['link'];
        $group = Base::$aRequest['group'];
		$from_data = Base::$aRequest['from_data'];

		if ($from_data) {
			$from_data_decode = base64_decode(str_replace(' ','+',$from_data));
			$aFromData = explode('&',$from_data_decode);
			$aFromDataAssoc = array();
			foreach ($aFromData as $sValue) {
				list($key,$val) = explode('=',$sValue);
				$aFromDataAssoc[$key] = $val;
			}
		} 
        
        $groups = FindGroups($api_key,$lang,$ssd,$link,$group);
        $groups = json_decode($groups,true);
		
        foreach ($groups['groups'] as $iKey => $aValue) {
	        	if (!isset(Base::$aRequest['group'])) { // top list groups
	        		$groups['groups'][$iKey]['sFromData'] = base64_encode('link='.$groups['link'].'&name='.$aValue['full_name']."&group=".$aValue['group_name']);
	        	}
	        	elseif ($from_data) {// next level group
	        		if ($aFromDataAssoc['group2'])
	        			$groups['groups'][$iKey]['sFromData'] = base64_encode($from_data_decode . '&link3='.$groups['link'].'&name3='.$aValue['full_name']."&group3=".$aValue['group_name']);
	        		else
	        			$groups['groups'][$iKey]['sFromData'] = base64_encode($from_data_decode . '&link2='.$groups['link'].'&name2='.$aValue['full_name']."&group2=".$aValue['group_name']);
	        	}
        }
        Base::$tpl->assign('aGroupsLVM',$groups);

        Base::$oContent->AddCrumb('Модификации '.$groups['model_info']['Марка']." ".$groups['client']['family']." (".$groups['client']['model'].")",'/pages/levam_modification?ssd='.$groups['client']['ssd']);
         if(!isset(Base::$aRequest['group'])) {
            Base::$oContent->AddCrumb('Группы запчастей '.$groups['model_info']['Марка']." ".$groups['client']['family']." (".$groups['client']['model'].")");
            Base::$tpl->assign('type_url','group');
         }else {
         	if($groups['next'])
         		Base::$tpl->assign('type_url','group');
         	else         	
         		Base::$tpl->assign('type_url','parts');
         	
         	Base::$oContent->AddCrumb('Группы запчастей '.$groups['model_info']['Марка']." ".$groups['client']['family']." (".$groups['client']['model'].")",
         		'/pages/levam_group?ssd='.$ssd.'&link='.$link);

         	$sFromData = base64_decode(str_replace(' ','+',Base::$aRequest['from_data']));
         	$aFromData = explode('&',$sFromData);
         	if (Base::$aRequest['from_data']) {
         		$sFromData = base64_decode(str_replace(' ','+',Base::$aRequest['from_data']));
         		$aFromData = explode('&',$sFromData);
         		$aFromDataAssoc = array();
         		foreach ($aFromData as $sValue) {
         			list($key,$val) = explode('=',$sValue);
         			$aFromDataAssoc[$key] = $val;
         		}
         		if ($aFromDataAssoc['name'] && $aFromDataAssoc['link'] && $aFromDataAssoc['group'])
         			Base::$oContent->AddCrumb($aFromDataAssoc['name'].' '.$groups['model_info']['Марка']." ".$groups['client']['family']." (".$groups['client']['model'].")",
         				'/pages/levam_group?ssd='.$ssd.'&link='.$aFromDataAssoc['link'].'&group='.$aFromDataAssoc['group'].'&from_data='.$from_data);

         		if ($aFromDataAssoc['name2'] && $aFromDataAssoc['link2'] && $aFromDataAssoc['group2'])
         			Base::$oContent->AddCrumb($aFromDataAssoc['name2'].' '.$groups['model_info']['Марка']." ".$groups['client']['family']." (".$groups['client']['model'].")",
         					'/pages/levam_group?ssd='.$ssd.'&link='.$aFromDataAssoc['link2'].'&group='.$aFromDataAssoc['group2'].'&from_data='.$from_data);
         		 
         		if ($aFromDataAssoc['name'] && $aFromDataAssoc['group']==$group)
         			Base::$tpl->assign('sGroupName',$aFromDataAssoc['name']);
         	}
         	
         }
        
        Base::$sText.=Base::$tpl->fetch('levam_catalog/groups.tpl');
    }
    //-----------------------------------------------------------------------------------------------
    public function Parts()
    {
        include_once SERVER_PATH."/single/levan_catalogs/config.php";
        $type = 0;
    
        $ssd = Base::$aRequest['ssd'];
        $link = Base::$aRequest['link'];
        $group = Base::$aRequest['group'];
         
        $parts = FindParts($api_key,$lang,$ssd,$link,$group);
        $parts = json_decode($parts,true);
        
        $aCoords=$parts['parts']['coord']['0'];
        if($aCoords) {
        	// cut not fount parts
        	$aPartsAssoc = array();
        	foreach ($parts['parts']['parts'] as $sKey => $aValue) {
        		$aPartsAssoc[$aValue['standart']['part_number']] = $aValue['standart']['part_number']; 
        	}
            foreach ($aCoords as $sKey => $aValue) {
            	if (!$aPartsAssoc[$aValue['name']]) {
            		unset($aCoords[$sKey]);
            		continue;
            	}
                $aCoords[$sKey]['margintop']=$aValue['margin-top'];
                $aCoords[$sKey]['marginleft']=$aValue['margin-left'];
            }
           $aCoords = array_values($aCoords);
        }
        
        Base::$oContent->AddCrumb('Модификации '.$parts['model_info']['Марка']." ".$parts['client']['family']." (".$parts['client']['model'].")",'/pages/levam_modification?ssd='.$parts['client']['ssd']);
        
        Base::$oContent->AddCrumb('Группы запчастей '.$parts['model_info']['Марка']." ".$parts['client']['family']." (".$parts['client']['model'].")",
        '/pages/levam_group?ssd='.$ssd.'&link='.$link);
        
        if (Base::$aRequest['from_data']) {
        	$sFromData = base64_decode(str_replace(' ','+',Base::$aRequest['from_data']));
        	$aFromData = explode('&',$sFromData);
        	$aFromDataAssoc = array();
        	foreach ($aFromData as $sValue) {
        		list($key,$val) = explode('=',$sValue);
        		$aFromDataAssoc[$key] = $val;
        	}
        	if ($aFromDataAssoc['name'] && $aFromDataAssoc['link'] && $aFromDataAssoc['group']) {
        		$sFromData1 = base64_encode('link='.$aFromDataAssoc['link'].'&name='.$aFromDataAssoc['name']."&group=".$aFromDataAssoc['group']);
        		Base::$oContent->AddCrumb($aFromDataAssoc['name'].' '.$parts['model_info']['Марка']." ".$parts['client']['family']." (".$parts['client']['model'].")",
       				'/pages/levam_group?ssd='.$ssd.'&link='.$aFromDataAssoc['link'].'&group='.$aFromDataAssoc['group']
       				.'&from_data='.$sFromData1);
        	}
        	if ($aFromDataAssoc['name3'] && $aFromDataAssoc['link3'] && $aFromDataAssoc['group3']) {
        		$sFromData2 = base64_encode('link='.$aFromDataAssoc['link'].'&name='.$aFromDataAssoc['name']."&group=".$aFromDataAssoc['group'].'&link2='.$aFromDataAssoc['link2'].'&name2='.$aFromDataAssoc['name2']."&group2=".$aFromDataAssoc['group2']);
        		Base::$oContent->AddCrumb($aFromDataAssoc['name2'].' '.$parts['model_info']['Марка']." ".$parts['client']['family']." (".$parts['client']['model'].")",
        		'/pages/levam_group?ssd='.$ssd.'&link='.$aFromDataAssoc['link2'].'&group='.$aFromDataAssoc['group2']
        		.'&from_data='.$sFromData2);
        		
        		Base::$oContent->AddCrumb($aFromDataAssoc['name3'].' '.$parts['model_info']['Марка']." ".$parts['client']['family']." (".$parts['client']['model'].")",
	        	'/pages/levam_parts?ssd='.$ssd.'&link='.$aFromDataAssoc['link3'].'&group='.$aFromDataAssoc['group3']
	        	.'&from_data='.base64_encode($sFromData));
        	}
			elseif ($aFromDataAssoc['name2'] && $aFromDataAssoc['link2'] && $aFromDataAssoc['group2'])
        		Base::$oContent->AddCrumb($aFromDataAssoc['name2'].' '.$parts['model_info']['Марка']." ".$parts['client']['family']." (".$parts['client']['model'].")",
        				'/pages/levam_parts?ssd='.$ssd.'&link='.$aFromDataAssoc['link2'].'&group='.$aFromDataAssoc['group2']
        	.'&from_data='.base64_encode($sFromData));
        }
        
        Base::$tpl->assign('aCoords',$aCoords);
        Base::$tpl->assign('aParts',$parts);
        
        Base::$sText.=Base::$tpl->fetch('levam_catalog/parts.tpl');
        
        $oTable=new Table();
        $oTable->sType='array';
        $oTable->aDataFoTable=$parts['parts']['parts'];
        $oTable->iRowPerPage=2000;
        $oTable->sDataTemplate='levam_catalog/row_parts.tpl';
        $oTable->aCallback=array($this,'CallParseParts');
        
        Base::$sText.=$oTable->GetTable();
    }
    //-----------------------------------------------------------------------------------------------
    public function Vin()
    {
        include_once SERVER_PATH."/single/levan_catalogs/config.php";
        $type = 0;
        
        Base::$oContent->AddCrumb(Language::GetMessage('поиск по VIN'),'');
        
        $vin = Base::$aRequest['vin'];
         
        $vin = FindVin($api_key,$lang,$vin);
        $vin = json_decode($vin,true);
        
        Base::$tpl->assign('sSSD',$vin['client']['ssd']);
        
        $aDataForTable=$vin['models'];
        if($aDataForTable) {
            foreach ($aDataForTable as $sKey =>$aValue) {
                $aDataForTable[$sKey]['make']=$aValue['Марка'];
                $aDataForTable[$sKey]['model']=$aValue['Модели'];
                $aDataForTable[$sKey]['description']=$aValue['Описание модификации'];
            }
        }
        
        $oTable=new Table();
        $oTable->sType='array';
        $oTable->aDataFoTable=$aDataForTable;
        $oTable->iRowPerPage=2000;
        $oTable->sDataTemplate='levam_catalog/row_vin.tpl';
        
        Base::$sText.=$oTable->GetTable();
    }
	//-----------------------------------------------------------------------------------------------
    public function CallParseParts(&$aItem)
    {
    	if (!$aItem)
    		return;

    	foreach ($aItem as $sKey => $aValue) {
    		$aItem[$sKey]['tr_add_text'] = 'data-part="part_'.$aValue['standart']['part_number'].'"';
    		$aItem[$sKey]['code'] = Catalog::StripCode($aValue['standart']['part_code']);
    	}	
    }
    //-----------------------------------------------------------------------------------------------
    public function Test() {
        include_once SERVER_PATH."/single/levan_catalogs/config.php";
        $type = 0;

        $code = Base::$aRequest['code'];
        $brand = Base::$aRequest['brand'];
         
        $code_result = FindCode($api_key,$lang,$code,$brand);
        $code_result = json_decode($code_result,true);
        
        $aDataFoTable=array();
        $aTmp=$code_result['applicability'];
        if($aTmp) {
            foreach ($aTmp as $sBrand => $aValue) {
                if($aValue['models']) {
                    foreach ($aValue['models'] as $sModel => $aValueModel) {
                        foreach ($aValueModel as $aValueModelTmp) {
                            if($aValueModelTmp['modifications']) {
                                foreach ($aValueModelTmp['modifications'] as $aValueModification) {
                                    $aDataFoTable[]=array(
                                        'make'=>$sBrand,
                                        'catalog_code'=>$aValue['catalog_code'],
                                        'image'=>$aValueModelTmp['image'],
                                        'region'=>$aValueModification['Рынок'],
                                        'year'=>$aValueModification['Дата выпуска'],
                                        'link'=>$aValueModification['link'],
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
        
        
        
        if ($sMake)
            Base::$oContent->AddCrumb('Модификации ' . $sMake .' '. $modifications['client']['family']." (".$modifications['client']['model'].")");
        
        $oTable=new Table();
        $oTable->sType='array';
        $oTable->aDataFoTable=$aDataFoTable;
        $oTable->iRowPerPage=2000;
        $oTable->sDataTemplate='levam_catalog/row_modification.tpl';
        
        Base::$sText.="<h1>".$code_result['brand']." ".$code_result['part_name']."</h1>";
        Base::$sText.=$oTable->GetTable();
        
    }
    //-----------------------------------------------------------------------------------------------
}
?>