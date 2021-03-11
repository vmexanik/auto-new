<?
/**
 * @author 
 */

class CarSelect extends Base
{

	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
       
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		Resource::Get()->Add('/js/popupform.js',15);
		
	    if(Base::$aRequest['cat']) Base::$aRequest['car_select']['brand']=Base::$aRequest['cat'];
	    if(Base::$aRequest['cat'] && !Base::$aRequest['car_select']['model'] && Base::$aRequest['data']['id_model']) {
	        Base::$aRequest['car_select']['model']=Db::GetOne("
	            select cmg.code 
	            from cat_model_group as cmg
	            inner join cat as c on c.id=cmg.id_make and c.name='".Base::$aRequest['cat']."'
	            where FIND_IN_SET('".Base::$aRequest['data']['id_model']."', cmg.id_models)
	        ");
	    }
	    if(Base::$aRequest['category'] && !$_REQUEST['category']) {
	        $_REQUEST['category']=Base::$aRequest['category'];
	    }
	    if(Base::$aRequest['subcategory'] && !$_REQUEST['subcategory']) {
	        $_REQUEST['subcategory']=Base::$aRequest['subcategory'];
	    }
		
	    if(Base::$aRequest['xajax']) {
    	    if(Base::$aRequest['year'] && !Base::$aRequest['car_select']['brand'] && !Base::$aRequest['car_select']['model'] && !Base::$aRequest['body'] && !Base::$aRequest['volume'] && !Base::$aRequest['modification']) {
    	        $aBrand=CarSelect::GetBrands(Base::$aRequest);
    	        
    	        Base::$tpl->assign('sCarSelectedYear',Base::$aRequest['year']);
    	        Base::$tpl->assign('aCarSelectBrandGroup',($aBrand ? array_chunk($aBrand, 4) : array()));
    	        
    	        Base::$oResponse->addAssign('selected_car_link','outerHTML',Base::$tpl->fetch('car_select/link.tpl'));
    	        Base::$oResponse->addAssign('car_selected_brand_selector','innerHTML',Base::$tpl->fetch('car_select/brand.tpl'));

    	        Base::$oResponse->addScript("
    	            $('.js-select-brand').uniform({selectClass: 'at-select'});
    	            $('.js-select-brand').parent().addClass('hover focus');
    	        ");
	            
	            if(!isset(Base::$aRequest['car_select']['model']) || Base::$aRequest['car_select']['model']=='') Base::$oResponse->addAssign('car_selected_model_selector','innerHTML',"");
	            if(!isset(Base::$aRequest['body'])) Base::$oResponse->addAssign('car_selected_body_selector','innerHTML',"");
	            if(!isset(Base::$aRequest['volume'])) Base::$oResponse->addAssign('car_selected_volume_selector','innerHTML',"");
	            if(!isset(Base::$aRequest['modification'])) Base::$oResponse->addAssign('car_selected_modif_selector','innerHTML',"");
    	        
    	    } elseif(Base::$aRequest['year'] && Base::$aRequest['car_select']['brand'] && !Base::$aRequest['car_select']['model'] && !Base::$aRequest['body'] && !Base::$aRequest['volume'] && !Base::$aRequest['modification']) {
    	        $aModel=CarSelect::GetModels(Base::$aRequest);
    	         
    	        Base::$tpl->assign('sCarSelectedYear',Base::$aRequest['year']);
    	        Base::$tpl->assign('sCarSelectedBrand',Base::$aRequest['car_select']['brand']);
    	        Base::$tpl->assign('aCarSelectModelGroup',array_chunk($aModel, 4));
    	        
    	        Base::$oResponse->addAssign('selected_car_link','outerHTML',Base::$tpl->fetch('car_select/link.tpl'));
    	        Base::$oResponse->addAssign('car_selected_model_selector','innerHTML',Base::$tpl->fetch('car_select/models.tpl'));

    	        Base::$oResponse->addScript("
    	            $('.js-select-model').uniform({selectClass: 'at-select'});
    	            $('.js-select-model').parent().addClass('hover focus');
    	            $('.js-select-brand').parent().removeClass('hover focus');
    	        ");

    	        if(!isset(Base::$aRequest['body'])) Base::$oResponse->addAssign('car_selected_body_selector','innerHTML',"");
    	        if(!isset(Base::$aRequest['volume'])) Base::$oResponse->addAssign('car_selected_volume_selector','innerHTML',"");
    	        if(!isset(Base::$aRequest['modification'])) Base::$oResponse->addAssign('car_selected_modif_selector','innerHTML',"");
    	        
    	    } elseif(Base::$aRequest['year'] && Base::$aRequest['car_select']['brand'] && Base::$aRequest['car_select']['model'] && !Base::$aRequest['body'] && !Base::$aRequest['volume'] && !Base::$aRequest['modification']) {
    	        $aBody=CarSelect::GetBodys(Base::$aRequest);
    	        
    	        Base::$tpl->assign('sCarSelectedYear',Base::$aRequest['year']);
    	        Base::$tpl->assign('sCarSelectedBrand',Base::$aRequest['car_select']['brand']);
    	        Base::$tpl->assign('sCarSelectedModel',Base::$aRequest['car_select']['model']);
    	        Base::$tpl->assign('aCarSelectBodyGroup',($aBody ? array_chunk($aBody, 2) : array()));
    	        
    	        Base::$oResponse->addAssign('selected_car_link','outerHTML',Base::$tpl->fetch('car_select/link.tpl'));
    	        Base::$oResponse->addAssign('car_selected_body_selector','innerHTML',Base::$tpl->fetch('car_select/body.tpl'));
    	        
    	        Base::$oResponse->addScript("
    	            $('.js-select-body').uniform({selectClass: 'at-select'});
    	            $('.js-select-body').parent().addClass('hover focus');
    	            $('.js-select-model').parent().removeClass('hover focus');
    	        ");
    	        
    	        if(count($aBody)==1) {
    	            Base::$aRequest['body']=$aBody[0]['body'];
    	            
    	            Base::$oResponse->addAssign('car_selected_cuzove','innerHTML',$aBody[0]['body']);

    	            Base::$oResponse->addScript("
        	            $('.js-select-volume').uniform({selectClass: 'at-select'});
    	                $('.js-select-volume').parent().addClass('hover focus');
    	                $('.js-select-body').parent().removeClass('hover focus');
        	        ");
    	            if(!isset(Base::$aRequest['modification'])) Base::$oResponse->addAssign('car_selected_modif_selector','innerHTML',"");
    	            
    	            CarSelect::Index();
    	        }else{
    	            
    	        }
    	        
    	        if(!isset(Base::$aRequest['volume'])) Base::$oResponse->addAssign('car_selected_volume_selector','innerHTML',"");
    	        if(!isset(Base::$aRequest['modification'])) Base::$oResponse->addAssign('car_selected_modif_selector','innerHTML',"");
    	        
    	    } elseif(Base::$aRequest['year'] && Base::$aRequest['car_select']['brand'] && Base::$aRequest['car_select']['model'] && Base::$aRequest['body'] && !Base::$aRequest['volume'] && !Base::$aRequest['modification']) {
    	        $aVolume=CarSelect::GetVolumes(Base::$aRequest);
    	         
    	        Base::$tpl->assign('sCarSelectedYear',Base::$aRequest['year']);
    	        Base::$tpl->assign('sCarSelectedBrand',Base::$aRequest['car_select']['brand']);
    	        Base::$tpl->assign('sCarSelectedModel',Base::$aRequest['car_select']['model']);
    	        Base::$tpl->assign('sCarSelectedBody',Base::$aRequest['body']);
    	        Base::$tpl->assign('aCarSelectVolumeGroup',($aVolume ? array_chunk($aVolume, 3) : array()));
    	        
    	        Base::$oResponse->addAssign('selected_car_link','outerHTML',Base::$tpl->fetch('car_select/link.tpl'));
    	        Base::$oResponse->addAssign('car_selected_volume_selector','innerHTML',Base::$tpl->fetch('car_select/volume.tpl'));

    	        Base::$oResponse->addScript("
    	            $('.js-select-volume').uniform({selectClass: 'at-select'});
    	            $('.js-select-volume').parent().addClass('hover focus');
    	            $('.js-select-body').parent().removeClass('hover focus');
    	        ");

    	        if(count($aVolume)==1) {
    	            Base::$aRequest['volume']=$aVolume[0]['volume'];
    	             
    	            Base::$oResponse->addAssign('car_selected_engine','innerHTML',$aVolume[0]['volume']);
    	            
        	        Base::$oResponse->addScript("
        	            $('.js-select-modification').uniform({selectClass: 'at-select'});
        	            $('.js-select-modification').parent().addClass('hover focus');
    	                $('.js-select-volume').parent().removeClass('hover focus');
        	        ");

        	        CarSelect::Index();
    	        }else{

    	        }
    	        if(!isset(Base::$aRequest['modification'])) Base::$oResponse->addAssign('car_selected_modif_selector','innerHTML',"");
    	        
    	    } elseif(Base::$aRequest['year'] && Base::$aRequest['car_select']['brand'] && Base::$aRequest['car_select']['model'] && Base::$aRequest['body'] && Base::$aRequest['volume'] && !Base::$aRequest['modification']) {
    	        $aModif=CarSelect::GetModifications(Base::$aRequest);
    	        if(count($aModif )==1) {
    	            $aModif = reset($aModif);
    	            if ($aModif['id'])
                        Base::$aRequest['modification']=$aModif['id'];
    	            elseif ($aModif[0]['id'])
                        Base::$aRequest['modification']=$aModif['0']['id'];
	                Base::$oResponse->addAssign('car_selected_modif','innerHTML',$aModif[0]['name']);
	                CarSelect::Index();
    	        }
    	        else {
    	            $aYearModif = array();
    	            foreach ($aModif as $aValue) {
    	                list($sYearStart,$sYearEnd) = explode('_',$aValue['start_end']);
    	                $aYearModif[$sYearStart][] = $aValue;
    	            }
        	        Base::$tpl->assign('sCarSelectedYear',Base::$aRequest['year']);
        	        Base::$tpl->assign('sCarSelectedBrand',Base::$aRequest['car_select']['brand']);
        	        Base::$tpl->assign('sCarSelectedModel',Base::$aRequest['car_select']['model']);
        	        Base::$tpl->assign('sCarSelectedBody',Base::$aRequest['body']);
        	        Base::$tpl->assign('sCarSelectedVolume',Base::$aRequest['volume']);    	        
        	        Base::$tpl->assign('aCarSelectModifGroup',$aYearModif);
        	        
        	        Base::$oResponse->addAssign('selected_car_link','outerHTML',Base::$tpl->fetch('car_select/link.tpl'));
        	        Base::$oResponse->addAssign('car_selected_modif_selector','innerHTML',Base::$tpl->fetch('car_select/modif.tpl'));
        	        
        	        Base::$oResponse->addScript("
        	            $('.js-select-modification').uniform({selectClass: 'at-select'});
        	            $('.js-select-modification').parent().addClass('hover focus');
    	                $('.js-select-volume').parent().removeClass('hover focus');
        	        ");
    	        }
    	    } elseif(Base::$aRequest['year'] && Base::$aRequest['car_select']['brand'] && Base::$aRequest['car_select']['model'] && Base::$aRequest['body'] && Base::$aRequest['volume'] && Base::$aRequest['modification']) {
                    Base::$aRequest['data']['id_model_detail']=Base::$aRequest['modification'];
                    $aModelDetails=TecdocDb::GetModelDetail(Base::$aRequest['data']);
                    //file_put_contents('./id_model_details.txt',print_r($aModelDetails,true));
                    Base::$aRequest['data']['id_model']=$aModelDetails['id_model'];
                    Base::$aRequest['data']['id_make']=$aModelDetails['id_make'];
                    //Debug::PrintPre(Base::$aRequest['data']);
                    
                     if(Auth::$aUser){
                        //file_put_contents('./id_model_detail.txt', Base::$aRequest['data']['id_model_detail']);
                        
                        $aData=String::FilterRequestData(Base::$aRequest['data']);

                        $aData['id_user'] = Auth::$aUser['id_user'];
                        $aData['year']=Base::$aRequest['year'];
                        $aData['engine']=Base::$aRequest['volume'];
                        $aData['post'] = $aData['modified'] = time();
                        //$aData['current_auto'] = 1;
                        Db::AutoExecute("user_auto",$aData);
                        
                 
                    }
                    /////end of added car to garazhe
                     
                    //$this->GetNavigator(Base::$aRequest['data']);
                    Rubricator::SetAll();
                    Rubricator::CheckSelectedAuto();
                    Rubricator::CheckSelectedAutoName();
                    Rubricator::ClearAutoUrl();
                      
                    //Base::Redirect('/rubricator/');
//                     Base::$oResponse->AddRedirect('/rubricator/');
                    $sUrl=CarSelect::GetSelectedAuto(Base::$aRequest);
                    Base::$oResponse->AddRedirect($sUrl);
    	    }
    	    
    	    return 0;
	    }
	    
	    Base::$tpl->assign('aCarSelectYear',CarSelect::GetYears());
	    
	    $sShowCarSelect=Base::$tpl->fetch("car_select/car_selector.tpl");
	    Base::$tpl->assign('sShowCarSelect',$sShowCarSelect);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetYears() {
		if(Base::$aRequest['car_select']['model'] || Base::$aRequest['model_group']){
			$sModel=Base::$aRequest['car_select']['model']?Base::$aRequest['car_select']['model']:Base::$aRequest['model_group'];
			
			$sSql="select cm.tof_mod_id,cm.tof_mod_id as id
            from cat_model as cm
    	    inner join cat_model_group as cmg on cmg.visible=1 and FIND_IN_SET(cm.tof_mod_id, cmg.id_models)
    	    where cm.visible=1 and cmg.code like '".$sModel."' and cmg.id_make='".Base::$aRequest['data']['id_make']."'
    	    ";
			$aModelVisible=Db::GetAssoc($sSql);
			if($aModelVisible) {
			    $sSql = "SELECT distinct
	           ifnull( substr(m.DateStart,4,4) ,0) as year,
	           ifnull( substr(m.DateEnd, 4, 4) , ".date("Y")." ) AS year_end
			FROM ".DB_OCAT."cat_alt_models as m
	        WHERE /*ifnull( substr(m.DateStart,4,4) ,0)>=1980 and*/ m.ID_src in ('".implode("','", $aModelVisible)."')
	        order by year";
			
			    $aTecDocYears=TecdocDb::GetAll($sSql);
			}
		}else{
	        $aTecDocYears=TecdocDb::GetAll("SELECT distinct
	           ifnull( substr(m.DateStart,4,4) ,0) as year,
	           ifnull( substr(m.DateEnd, 4, 4) , ".date("Y")." ) AS year_end
			FROM ".DB_OCAT."cat_alt_models as m
	        WHERE ifnull( substr(m.DateStart,4,4) ,0)>=1980 
	        order by year");
    	   
		}
		
		$aYearsTmp = array();
		if($aTecDocYears) foreach($aTecDocYears as $aValueYear) {
			if ($aValueYear['year']<1980 && $aValueYear['year_end'] < 1980)
				continue;
			if ($aValueYear['year']<1980 && $aValueYear['year_end'] >= 1980)
				$aValueYear['year'] = 1980;
		
			$aYearsTmp = array_merge($aYearsTmp, range($aValueYear['year'],$aValueYear['year_end']));
		}
		$aYearsDistinct = array();
		if($aYearsTmp) foreach($aYearsTmp as $aValueTmp)
		if(!$aYearsDistinct[($aValueTmp -  $aValueTmp % 10)] || !in_array($aValueTmp, $aYearsDistinct[($aValueTmp -  $aValueTmp % 10)]))
			$aYearsDistinct[($aValueTmp -  $aValueTmp % 10)][] = $aValueTmp;
		$aGroupYears = $aYearsDistinct;
		
	    return $aGroupYears;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetBrands($aData) {
	    if(!$aData['year']) return 0;
	     
	    $aBrands=Db::GetAll("
	        select cat.id_mfa, cat.title, cat.name, cm.tof_mod_id
            from cat
            inner join cat_model as cm on cat.id_mfa=cm.id_mfa and cm.visible=1
            where cat.is_brand = 1 and cat.is_main = 1 and cat.visible
	        order by cat.title
        ");
	    
	    if($aBrands) {
	        $aTcd=array();
	        foreach ($aBrands as $aValue) {
	            $aTcd[]=$aValue['tof_mod_id'];
	        }
	        
	        if($aTcd) {
	            $sSql="select m.id_src, m.id_src as id
	                from ".DB_OCAT."cat_alt_models m 
	                where (ifnull( substr(m.DateStart,4,4) ,0) <= '".$aData['year']."' and 
	                      ifnull( substr(m.DateEnd,4,4) ,9999) >= '".$aData['year']."' ) and
	                      m.ID_src in ('".implode("','", $aTcd)."')
	            ";
	            $aTcdModel=TecdocDb::GetAssoc($sSql);
	            
	            if($aTcdModel) {
	                $aResult=array();
	                foreach ($aBrands as $aValue) {
	                    if(!in_array($aValue['id_mfa'], $aResult)) {
	                        if(in_array($aValue['tof_mod_id'],$aTcdModel)) {
	                            $aResult[$aValue['id_mfa']]=$aValue;
	                        }
	                    }
	                }
	            }
	        }
	    }
	    
	    return $aResult;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetModels($aData) {
	    if(!$aData['year'] || !$aData['car_select']['brand']) return 0;
	    
	    $iIdTofBrandSelected=Db::GetOne("select id_mfa from cat where name='".$aData['car_select']['brand']."' ");
	    if($iIdTofBrandSelected) {
	        $sSql="select m.ID_src, m.ID_src as id
    	        from ".DB_OCAT."cat_alt_manufacturer man 
    	        inner join ".DB_OCAT."cat_alt_models m on m.ID_mfa=man.ID_mfa
    	        where (ifnull( substr(m.DateStart,4,4) ,0) <= '".$aData['year']."' and ifnull( substr(m.DateEnd,4,4) ,9999) >= '".$aData['year']."')
    	            and man.ID_src='".$iIdTofBrandSelected."' 
	        ";
	        $aTcdModels=TecdocDb::GetAssoc($sSql);
	        if($aTcdModels) {
	            $aModels=Db::GetAll("
        	        select 
	                   cm.name name, cm.id, 
	                   if(trim(REPLACE(cmg.name,cat.title,''))<>'',trim(REPLACE(cmg.name,cat.title,'')),cmg.name) as short_name,
	                   cm.tof_mod_id as id_src,
	                   cmg.code
                    from cat
                    inner join cat_model as cm on cm.tof_mod_id in ('".implode("','", $aTcdModels)."') and cm.visible=1
                    inner join cat_model_group as cmg on cmg.id_make=cat.id and cmg.visible=1 and FIND_IN_SET(cm.tof_mod_id, cmg.id_models)
        	        group by REPLACE(cmg.name,cat.title,'')
        	        order by cm.name
                ");
	        }
	    }
	    
	    return $aModels;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetBodys($aData) {
	    if(!$aData['year'] || !$aData['car_select']['brand'] || !$aData['car_select']['model']) return 0;
	    $aData['car_select']['model'] = addslashes($aData['car_select']['model']);
	    
	    $aModelAssoc=Db::GetAssoc("select cm.tof_mod_id, cm.tof_mod_id as id
	        from cat
            inner join cat_model as cm 
            inner join cat_model_group as cmg on cm.visible=1 and cmg.id_make=cat.id and cmg.visible=1 and FIND_IN_SET(cm.tof_mod_id, cmg.id_models)
            where
               cat.name='".$aData['car_select']['brand']."' and
               cmg.code like '".$aData['car_select']['model']."'");
	    //Debag::PrintPre($aModelAssoc);
	    if($aModelAssoc) {
	        $sSql="select distinct REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(t.Body,'Наклонная задняя часть','хэтчбек'),'вездеход закрытый','внедорожник'),'Вездеход открытый','внедорожник'),'вэн','минивэн'),'кабрио','кабриолет'),'c бортовой платформой/ходовая часть','с бортом') as body
	                from ".DB_OCAT."cat_alt_manufacturer man
                    inner join ".DB_OCAT."cat_alt_models m on m.ID_mfa=man.ID_mfa
                    inner join ".DB_OCAT."cat_alt_types as t on m.id_mod=t.id_mod
                    where m.id_src in ('".implode("','", $aModelAssoc)."') and
                        ( ifnull( substr(m.DateStart,4,4) ,0) <= '".$aData['year']."' and ifnull( substr(m.DateEnd,4,4) ,9999) >= '".$aData['year']."' ) 
	                order by body
	            ";
	        $aBody=TecdocDb::GetAll($sSql);
	        
	        if ($aBody[0]['body']=='')
	            $aBody[0]['body']='xxx';
	    }

	    	    
	    $aImage=array(
	        'хэтчбек'=>'40888.png',
	        'седан'=>'23470.png',
	        'купе'=>'32151.png',
	        'кабриолет'=>'41263.png',
	        'универсал'=>'6144.png',
	        'минивэн'=>'66916.png',
	        'внедорожник'=>'40890.png',
	        'пикап'=>'40889.png',
	        'c бортом'=>'66925.png',
	        'фургон'=>'23469.png',
	        'автобус'=>'32152.png',
	        'фургон/универсал'=>'23469.png',
	    );
	    if($aBody) foreach($aBody as $sKey => $aValue) {
	        $aBody[$sKey]['image']=$aImage[$aValue['body']];
	    }
	     
	    return $aBody;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetVolumes($aData) {
	    if(!$aData['year'] || !$aData['car_select']['brand'] || !$aData['car_select']['model'] || !$aData['body']) return 0;
            $aData['car_select']['model'] = addslashes($aData['car_select']['model']);

            $aModelAssoc=Db::GetAssoc("select cm.tof_mod_id, cm.tof_mod_id as id
                from cat
                inner join cat_model as cm on cm.visible=1
            inner join cat_model_group as cmg on cmg.id_make=cat.id and cmg.visible=1 and FIND_IN_SET(cm.tof_mod_id, cmg.id_models)
            where cat.name='".$aData['car_select']['brand']."' and
               cmg.code like '".$aData['car_select']['model']."' ");

            if($aModelAssoc) {
                $sSql="select distinct if(litres.TYP_LITRES<>'',trim(concat( litres.TYP_LITRES, ' ', lower(t.Fuel))), trim(concat(SUBSTRING_INDEX(t.name, ' ', 1),' ',lower(t.Fuel))))  as volume, t.Fuel as fuel, t.Name as name
                    from ".DB_OCAT."cat_alt_manufacturer man
                    inner join ".DB_OCAT."cat_alt_models m on m.ID_mfa=man.ID_mfa
                    inner join ".DB_OCAT."cat_alt_types as t on m.id_mod=t.id_mod
                     left join ".DB_OCAT."cat_alt_litres as litres on t.ID_src=litres.TYP_ID
                    where m.id_src in ('".implode("','", $aModelAssoc)."') and
                       (ifnull( substr(t.DateStart,1,4) ,0) <= '".$aData['year']."' and ifnull( substr(t.DateEnd,1,4) ,9999) >= '".$aData['year']."') and           
                       t.body like REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE('".$aData['body']."','хэтчбек','Наклонная задняя часть'),'внедорожник','вездеход%'),'минивэн','вэн'),'кабриолет','кабрио'),'с бортом','c бортовой платформой/ходовая часть'),'xxx','')
                   group by ( if(litres.TYP_LITRES<>'',trim(concat( litres.TYP_LITRES,' ', lower(t.Fuel))), trim(concat(SUBSTRING_INDEX(t.name, ' ', 1),' ',lower(t.Fuel)))) )
                        order by SUBSTRING_INDEX(t.name, ' ', 1)
                ";
                $aVolume=TecdocDb::GetAll($sSql);
            }
            foreach ($aVolume as $iV=>$aVol){
                $aV=explode(" ", $aVol['volume']);
                if(!(strpos($aV[0], '.')!==false))
                    $aV[0]=$aV[0].'.0';
                $aVolume[$iV]['new_name']=$aV[0]." ".mb_strtolower($aVol['fuel'],'utf-8');
            }
            function cmp($a, $b)
            {
                if ($a == $b) {
                    return 0;
                }
                return ($a < $b) ? -1 : 1;
            }
            
            usort($aVolume,'cmp');
            return $aVolume;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetModifications($aData) {
	    if(!$aData['year'] || !$aData['car_select']['brand'] || !$aData['car_select']['model'] || !$aData['body'] || !$aData['volume']) return 0;
	    $aData['car_select']['model'] = addslashes($aData['car_select']['model']);
	    
	    $aModelAssoc=Db::GetAssoc("select cm.tof_mod_id, cm.tof_mod_id as id
	        from cat
	        inner join cat_model as cm on cm.visible=1
            inner join cat_model_group as cmg on cmg.id_make=cat.id and cmg.visible=1 and FIND_IN_SET(cm.tof_mod_id, cmg.id_models)
	        where cat.name='".$aData['car_select']['brand']."' and
               cmg.code like '".$aData['car_select']['model']."' 
	        ");
	    
	    if($aModelAssoc) {
	    $sSql="select concat(t.Name,' (',SUBSTRING(KwHp, LOCATE('/', KwHp)+1),' л.с.)', ' - ' , ifnull(t.Engines,'')) as name,
                       t.Description as full_name, t.id_src as id, t.fuel,  concat ( ifnull( substr(m.DateStart,4,4) ,0), '-',ifnull( substr(m.DateEnd,4,4) ,0)) as start_end, m.name as mod_grp
	                from ".DB_OCAT."cat_alt_manufacturer man
                    inner join ".DB_OCAT."cat_alt_models m on m.ID_mfa=man.ID_mfa
                    inner join ".DB_OCAT."cat_alt_types as t on m.id_mod=t.id_mod
                          left join ".DB_OCAT."cat_alt_litres as litres on t.ID_src=litres.TYP_ID
                    where m.id_src in ('".implode("','", $aModelAssoc)."') and
                       (ifnull( substr(t.DateStart,1,4) ,0) <= '".$aData['year']."' and ifnull( substr(t.DateEnd,1,4) ,9999) >= '".$aData['year']."') and
                       t.body like REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE('".$aData['body']."','хэтчбек','Наклонная задняя часть'),'внедорожник','вездеход%'),'минивэн','вэн'),'кабриолет','кабрио'),'с бортом','c бортовой платформой/ходовая часть'),'xxx','') and
                       (if (litres.TYP_LITRES<>'',trim(concat( litres.TYP_LITRES, ' ', lower(t.Fuel)   )) like '".$aData['volume']."' ,
                         trim(concat(SUBSTRING_INDEX(t.name, ' ', 1),' ',lower(t.Fuel))) like '".$aData['volume']."'))
                       group by id order by t.Name
                ";
	        $aType=TecdocDb::GetAll($sSql);
	        if($aType) foreach($aType as $sKey => $aValue){
	            $aValue['name'] = trim($aValue['name']);
	            if(!$aValue['name'] || $aValue['name'] == '') {
	                $aType[$sKey]['name'] = $aType[$sKey]['full_name'];
	                $aType[$aValue['mod_grp']][]=$aType[$sKey];
	                unset($aType[$sKey]);
	            }
	        }
	    }
	    return $aType;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetSelectedAuto($aData) {
	    if(!$aData['year'] || !$aData['car_select']['brand'] || !$aData['car_select']['model'] || !$aData['body'] || !$aData['volume'] || !$aData['modification']) return 0;

	    $aData['car_select']['model'] = addslashes($aData['car_select']['model']);
	    
	    $aModelAssoc=Db::GetAssoc("select cm.tof_mod_id, cm.tof_mod_id as id
	        from cat
	        inner join cat_model as cm on cm.visible=1
            inner join cat_model_group as cmg on cmg.id_make=cat.id and cmg.visible=1 and FIND_IN_SET(cm.tof_mod_id, cmg.id_models)
	        where cat.name='".$aData['car_select']['brand']."' and
               cmg.code like '%".$aData['car_select']['model']."'
	        ");
	     
	    if($aModelAssoc) {
	        $sSql="select t.Name as name, t.Description as full_name, t.id_src as id, t.fuel, ifnull( substr(t.DateStart,1,4) ,0)
                ,m.id_src as id_model
                ,t.id_src as id_model_detail
	            ,man.id_src as make_tof_id
                from ".DB_OCAT."cat_alt_manufacturer man
                inner join ".DB_OCAT."cat_alt_models m on m.ID_mfa=man.ID_mfa
                inner join ".DB_OCAT."cat_alt_types as t on m.id_mod=t.id_mod
                where m.id_src in ('".implode("','", $aModelAssoc)."') and
                   (ifnull( substr(t.DateStart,1,4) ,0) <= '".$aData['year']."' and ifnull( substr(t.DateEnd,1,4) ,9999) >= '".$aData['year']."') and
                   t.body like REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE('".$aData['body']."','хэтчбек','Наклонная задняя часть'),'внедорожник','вездеход%'),'минивэн','вэн'),'кабриолет','кабрио'),'с бортом','c бортовой платформой/ходовая часть'),'xxx','') and
                   /*trim(concat(SUBSTRING_INDEX(t.name, ' ', 1),' ',lower(t.Fuel))) like '".$aData['volume']."' and*/
                   t.id_src = '".$aData['modification']."'
    	        order by t.Name
            ";
	        $aAuto=TecdocDb::GetRow($sSql);
	        if($aAuto) {
	            $aAuto['id_make']=Db::GetOne("select id from cat where id_mfa='".$aAuto['make_tof_id']."' ");
	        }
	    }
	    
		if(Base::$aRequest['subcategory']){
			$aRubric = Db::GetRow("SELECT * FROM rubricator WHERE url like '".Base::$aRequest['subcategory']."'");
		}
		if(Base::$aRequest['action'] == 'price_group'){
			if(Base::$aRequest['category']){
				$aPriceGroup = Db::GetRow("SELECT * FROM price_group where code_name like '".Base::$aRequest['category']."'");
				$aRubric = Db::GetRow("SELECT * FROM rubricator WHERE id_price_group = '".$aPriceGroup['id']."'");
			}			
		}
		
		if(Base::$aRequest['category']){
		    $aBaseRubric = Db::GetRow("SELECT * FROM rubricator WHERE url like '".Base::$aRequest['category']."'");
		}
		
		if($aBaseRubric && Base::$aRequest['action']=='cars_category_model_group_view') {
		    $sUrl="/rubricator/".Base::$aRequest['category']."/";
		    $sCarUrl=Content::CreateSeoUrl('catalog_assemblage_view',array(
		        'data[id_make]'=>$aAuto['id_make'],
		        'data[id_model]'=>$aAuto['id_model'],
		        'data[id_model_detail]'=>$aAuto['id_model_detail'],
		        'model_translit'=>Content::Translit($aAuto['name'])
		    ));
		    $sUrl.=str_replace("/catalog/", "", $sCarUrl);
		    return $sUrl;
		}
		
		if($aBaseRubric && Base::$aRequest['action']=='cars_subcategory_model_group_view') {
		    $sUrl="/rubricator/".Base::$aRequest['category']."/".Base::$aRequest['subcategory']."/";
		    $sCarUrl=Content::CreateSeoUrl('catalog_assemblage_view',array(
		        'data[id_make]'=>$aAuto['id_make'],
		        'data[id_model]'=>$aAuto['id_model'],
		        'data[id_model_detail]'=>$aAuto['id_model_detail'],
		        'model_translit'=>Content::Translit($aAuto['name'])
		    ));
		    $sUrl.=str_replace("/catalog/", "", $sCarUrl);
		    return $sUrl;
		}
		
		if($aBaseRubric && Base::$aRequest['action']=='car_select' && Base::$aRequest['category'] && !Base::$aRequest['subcategory']) {
		    $sUrl="/rubricator/".Base::$aRequest['category']."/";
		    $sCarUrl=Content::CreateSeoUrl('catalog_assemblage_view',array(
		        'data[id_make]'=>Base::$aRequest['data']['id_make'],
		        'data[id_model]'=>Base::$aRequest['data']['id_model'],
		        'data[id_model_detail]'=>Base::$aRequest['data']['id_model_detail'],
		    ));
		    $sUrl.=str_replace("/rubricator/", "", $sCarUrl);
		    return $sUrl;
		}
		
		if($aBaseRubric && Base::$aRequest['action']=='car_select' && Base::$aRequest['category'] && Base::$aRequest['subcategory']) {
		    $sUrl="/rubricator/".Base::$aRequest['category']."/".Base::$aRequest['subcategory']."/";
		    $sCarUrl=Content::CreateSeoUrl('catalog_assemblage_view',array(
		        'data[id_make]'=>Base::$aRequest['data']['id_make'],
		        'data[id_model]'=>Base::$aRequest['data']['id_model'],
		        'data[id_model_detail]'=>Base::$aRequest['data']['id_model_detail'],
		    ));
		    $sUrl.=str_replace("/rubricator/", "", $sCarUrl);
		    return $sUrl;
		}

		if($aRubric){
		    if (Base::$aRequest['order_brand']) {
		        $sBrand="/".Base::$aRequest['order_brand']."/";
		    } else {
		        $sBrand='';
		    }
		    
			$sUrl = Content::RubricatorFullUrl($aRubric).$sBrand;
			$sSelectedCarUrl="/".mb_strtolower(Base::$tpl->get_template_vars('sSelectedCarUrl'));
			
			$sUrl.= '/';
			$sUrl.= Content::CreateSeoUrl('car_link',array(
				'subcategory'=> Base::$aRequest['subcategory'],
				'category'=> Base::$aRequest['category'],
				'data[id_make]'=>$aAuto['id_make'],
				'data[id_model]'=>$aAuto['id_model'],
				'data[id_model_detail]'=>$aAuto['id_model_detail'],
			));
			if($sSelectedCarUrl!='/') {
			    $sUrl=str_replace($sSelectedCarUrl, '', $sUrl);
			}
			
		} else {
		    $sUrl=Content::CreateSeoUrl('catalog_assemblage_view',array(
		        'data[id_make]'=>$aAuto['id_make'],
		        'data[id_model]'=>$aAuto['id_model'],
		        'data[id_model_detail]'=>$aAuto['id_model_detail'],
		        'model_translit'=>Content::Translit($aAuto['name'])
		    ));
		    $sUrl="/rubricator/".$sUrl;
		}
		$sUrl=str_replace("//", "/", $sUrl);
		
		//check rubricator page
		if(Base::$aRequest['action']=='rubricator' || Base::$aRequest['action']=='rubricator_category' || Base::$aRequest['action']=='rubricator_subcategory') {
		    $NewUrl="/rubricator/";
 		    if(Base::$aRequest['category']) {
 		        //check wrong field
 		        $bExist=Db::GetOne("select id from rubricator where url like '".Base::$aRequest['category']."' ");
 		        if($bExist) $NewUrl.=Base::$aRequest['category']."/";
 		    }
 		    if(Base::$aRequest['subcategory']) {
 		        $bExist=Db::GetOne("select id from rubricator where url like '".Base::$aRequest['subcategory']."' ");
 		        if($bExist) $NewUrl.=Base::$aRequest['subcategory']."/";
 		    }

		    //save selected auto
		    Base::$aRequest['data']=$aAuto;
		    Rubricator::SetAll();
		    $sUrl=$NewUrl;
		}
		
		if($aAuto['id_make']) {
		    $sMake=Db::GetOne("select name from cat where id='".$aAuto['id_make']."' ");
		   $sMake=strtolower($sMake);
		    $sUrl=str_replace("/".$sMake."/", "/", $sUrl);
		}
		
		$sUrl=str_replace("cars/", "c/", $sUrl);
		$sUrl=str_replace("//", "/", $sUrl);
		
		if(MultiLanguage::IsLocale()){
		    $sUrl="/".Language::$sLocale.$sUrl;
		}
	    return $sUrl;
	}
	//-----------------------------------------------------------------------------------------------

}
?>