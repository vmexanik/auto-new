<?

/**
 * @author 
 *
 */

class Rubricator extends Catalog
{
    public $aCategory=array();
	//-----------------------------------------------------------------------------------------------
	public function __construct($bReturn=false)
	{
	    if($bReturn) return ;
		Resource::Get()->Add('/css/thickbox.css');
		Resource::Get()->Add('/libp/jquery/jquery.thickbox.js');
		Base::$bXajaxPresent=true;
		Base::$tpl->assign('aMakeName', array(""=>Language::getMessage('choose make'))+Db::GetAssoc("
		   select c.id, c.title 
	       from cat as c
	       where 1=1 and c.is_main='1' and c.is_brand='1' and c.visible='1'
		"));

		if(!Base::$aRequest['xajax']){
			if(isset($_COOKIE['id_make'])) {
				$this->GetModels();
			}
			
			if(isset($_COOKIE['id_make']) && isset($_COOKIE['id_model'])) {
				$this->GetModelDetails();
			}
		}

		if (Base::$aRequest["all_params"]) {
		    $sLast=substr(Base::$aRequest["all_params"], strlen(Base::$aRequest["all_params"])-1);
		    if($sLast=='/') {
		        Base::$aRequest["all_params"]=substr(Base::$aRequest["all_params"], 0, -1);
		    }
		    
		    //convert filter string
		    $iPos=strpos(Base::$aRequest["all_params"], "/f/");
		    if($iPos!==false) {
		        $sBeforeFilter=substr(Base::$aRequest["all_params"], 0, $iPos);
		        $sFilter=substr(Base::$aRequest["all_params"], $iPos);
		        $sFilter=str_replace("_", ",", $sFilter);
		        
		        Base::$aRequest["all_params"]=$sBeforeFilter.$sFilter;
		    }
		}
		self::ParsingParameter(Base::$aRequest);
		if(Base::$aRequest['category']=='c') {
		    unset(Base::$aRequest['category']);
		    unset($_REQUEST['category']);
		    //selected auto
		    if(1) {
		        $sAuto=Base::$aRequest['all_params'];
		        //audi_100_-11-12
		        $iTmp=strpos($sAuto, "_");
		        $sCat=0;
		        $sModelGroup=0;
		        $sModel=0;
		        $sModelDetail=0;
		        if($iTmp===false) {
		            $sCat=$sAuto;
		        } else {
		            $sCat=substr($sAuto, 0, $iTmp);
		            //have model or model_group
		            $sModelTmp=substr($sAuto, $iTmp);
		            $iTmp=strpos($sModelTmp, "-");
		            if($iTmp===false) {
		                //only model group
		                Base::$aRequest['model_group']=str_replace("/", '', substr($sModelTmp,1));
		            } else {
		                list($sModelGroup,$sModel,$sModelDetail)=explode("-", $sModelTmp);
		                Base::$aRequest['model_group']=str_replace("/", '', substr($sModelGroup,1));
		                Base::$aRequest['data']['id_model']=$sModel;
		                Base::$aRequest['data']['id_model_detail']=$sModelDetail;
		            }
		        }
		        Base::$aRequest['cat'] = $sCat;
		        $_REQUEST['cat'] = $sCat;
		        if(Base::$aRequest['data']['id_model_detail']) {
		            unset(Base::$aRequest['model_group']);
		        }
		    }
		}
		// rewrite cat так как баг на урлах вида
		// ../rubricator/c/mercedes_sprinter_3_t_furgon_903_312_d_2.9_4x4_312_d_2.9_4x4-3906-17063
		if (Base::$aRequest['data']['id_model']) {
			$sCatFromModel = Db::getOne("Select c.name from cat c
			        	inner join cat_model cm on cm.id_tof = c.id_tof
			        	where cm.tof_mod_id = ".Base::$aRequest['data']['id_model']);
			if ($sCatFromModel)
				$sCat = $sCatFromModel;
			
			Base::$aRequest['cat']=$sCat;
			$_REQUEST['cat']=$sCat;
		}
		
		if(Base::$aRequest['brand']) {
		    Base::$aRequest['brand']=str_replace("_", ",", Base::$aRequest['brand']);
		}
		
		$_REQUEST['model_group']=Base::$aRequest['model_group'];
		if(!Base::$aRequest['data']['id_make'] && Base::$aRequest['cat']){
		    Base::$aRequest['data']['id_make']=Db::GetOne("select id from cat where name='".Base::$aRequest['cat']."' ");
		    $_REQUEST['data']['id_make']=Base::$aRequest['data']['id_make'];
		}
		if(Base::$aRequest['data']['id_make'] && Base::$aRequest['data']['id_model'] && Base::$aRequest['data']['id_model_detail']) {
		    Rubricator::SetAll();
		}
		if($_COOKIE['id_model_detail'] && $_COOKIE['id_model'] && $_COOKIE['id_make']) {
    		Rubricator::CheckSelectedAuto();
    		Rubricator::CheckSelectedAutoName();
		}
		
		//check correct link
		if(Base::$aRequest['action']=="rubricator_category") {
		    $aRequestData=Base::$aRequest;
		    if(Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator'] && !Base::$aRequest['model_group'] && (Base::$aRequest['category'] || (Base::$aRequest['data']['id_make'] && Base::$aRequest['data']['id_model'] && Base::$aRequest['data']['id_model_detail'])) ) {
	           $aRequestData['selected_auto']=Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator'];
		    } elseif(Base::$aRequest['cat']) {
		        $aRequestData['selected_auto']="/c/".Base::$aRequest['cat'];
		        Base::$tpl->assign('bShowCarSelector',1);
		        if(Base::$aRequest['model_group']) {
		            $aRequestData['selected_auto'].="_".Base::$aRequest['model_group'];
		        }
		        if(Base::$aRequest['data']['id_model']) {
		            $aRequestData['selected_auto'].="-".Base::$aRequest['data']['id_model'];
		        }
		        if(Base::$aRequest['data']['id_model_detail']) {
		            $aRequestData['selected_auto'].="-".Base::$aRequest['data']['id_model_detail'];
		        }
		    }
		    $sEtalonUrl=self::GenerateFilterLink($aRequestData);
		    if(substr($sEtalonUrl, strlen($sEtalonUrl)-1)=="/") {
		        $sUrl1=substr($sEtalonUrl, 0,-1);
		    } else {
		        $sUrl1=$sEtalonUrl;
		    }
		    if(substr($_SERVER['REQUEST_URI'], strlen($_SERVER['REQUEST_URI'])-1)=="/") {
		        $sUrl2=substr($_SERVER['REQUEST_URI'], 0,-1);
		    } else {
		        $sUrl2=$_SERVER['REQUEST_URI'];
		    }
		    if(urldecode($sUrl1)!=urldecode($sUrl2)) {
		        Base::Redirect($sEtalonUrl);
		    }
		}elseif(Base::$aRequest['action']=="rubricator") {
            $sUrl1=Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator'];
            if(MultiLanguage::IsLocale()){
                $sEtalonUrl="/".Language::$sLocale."/rubricator/".$sUrl1;
            }else{
                $sEtalonUrl='/rubricator/'.$sUrl1;
            }
            $sUrl2=$_SERVER['REQUEST_URI'];
            
            if (Language::getConstant('global:url_is_not_last_slash',0) == 1) {
                if ($sEtalonUrl != "/" && mb_substr($sEtalonUrl, -1, 1, 'utf-8') == "/")
                    $sEtalonUrl = substr($sEtalonUrl, 0, -1);
            }
            
			if(urldecode($sEtalonUrl)!=urldecode($sUrl2)) {
		        Base::Redirect($sEtalonUrl);
		    }
		}
		//check filter end
		
		if(Base::$aRequest['model_group']) {
		    $sSelectedModelGroupName=Db::GetRow("select * from cat_model_group where code='".Base::$aRequest['model_group']."' and visible=1 and id_make='".Db::GetOne("select id from cat where name='".Base::$aRequest['cat']."' ")."' ");
		    if ($sSelectedModelGroupName['code']==Base::$aRequest['model_group'])
		        $sSelectedModelGroupName = $sSelectedModelGroupName['name'];
		    else
		        unset($sSelectedModelGroupName);
		    Base::$tpl->assign('model_preselected', $sSelectedModelGroupName);
		}
		Content::showCarSelect();
		Base::$aRequest['cat'] = mb_strtolower(Base::$aRequest['cat']);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetModels()
	{
		$aModel=TecdocDb::GetModels(array(
			"id_make"=>Base::$aRequest['data']['id_make'],
			"sOrder"=>" order by name "
		));
		$aModelNew=array();
		if ($aModel) {
			foreach($aModel as $iKey => $aValue) {
			    $aModelNew[$aValue['id']] = $aValue['name'];
			}
		}
		Base::$tpl->assign('aModel',$aModelNew);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetModelDetails()
	{
	    $aModelDetailAll=TecdocDb::GetModelDetails(array(
			"id_make"=>Base::$aRequest['data']['id_make'],
			"id_model"=>Base::$aRequest['data']['id_model'],
		));
		if($aModelDetailAll) foreach ($aModelDetailAll as $sKey => $aValue) {
			$sName = $aValue['Name']." ".$aValue['year_start']."-".$aValue['year_end'].
			" ".$aValue['kw_from']."kW/".$aValue['hp_from']."ps ".$aValue['ccm']."ccm ".$aValue['Engines'];
			
			$aModelDetail[$aValue['id_model_detail']] = $sName;
		}
		Base::$tpl->assign('aModelDetail',$aModelDetail);
	}
	//-----------------------------------------------------------------------------------------------
	public function SetMake() 
	{
		if(!isset(Base::$aRequest['data']['id_make'])) return false;
		
		$this->GetModels();
		Base::$oResponse->addAssign('id_model','innerHTML',Base::$tpl->fetch('rubricator/select_model.tpl'));
		$this->GetModelDetails();
		Base::$oResponse->addAssign('id_model_detail','innerHTML',Base::$tpl->fetch('rubricator/select_model_detail.tpl'));
		Base::$tpl->assign('sRubricatorSelectUrl',false);
		Base::$oResponse->addAssign('id_rubricator_button','outerHTML',Base::$tpl->fetch('rubricator/button_rubricator.tpl'));
	}
	//-----------------------------------------------------------------------------------------------
	public function SetModel() 
	{
		if(!isset(Base::$aRequest['data']['id_model'])) return false;
		
		$this->GetModelDetails();
		Base::$oResponse->addAssign('id_model_detail','innerHTML',Base::$tpl->fetch('rubricator/select_model_detail.tpl'));
		Base::$tpl->assign('sRubricatorSelectUrl',false);
		Base::$oResponse->addAssign('id_rubricator_button','outerHTML',Base::$tpl->fetch('rubricator/button_rubricator.tpl'));
	}
	//-----------------------------------------------------------------------------------------------
	public function SetModelDetail() 
	{
		if(!isset(Base::$aRequest['data']['id_model_detail'])) return false;
		
		$sRubricatorSelectUrl=$this->GetRubricatorUrl();
		
		Base::$tpl->assign('sRubricatorSelectUrl',$sRubricatorSelectUrl);
		Base::$oResponse->addAssign('id_rubricator_button','outerHTML',Base::$tpl->fetch('rubricator/button_rubricator.tpl'));
	}
	//-----------------------------------------------------------------------------------------------
	public function SetAll()
	{
	    if(Base::$aRequest['data']['id_make']) {
	        setcookie('id_make',Base::$aRequest['data']['id_make'], strtotime( '+30 days' ),"/" );
	        $_COOKIE['id_make']=Base::$aRequest['data']['id_make'];
	    }
	    if(Base::$aRequest['data']['id_model']) {
	        setcookie('id_model',Base::$aRequest['data']['id_model'], strtotime( '+30 days' ),"/" );
	        $_COOKIE['id_model']=Base::$aRequest['data']['id_model'];
	    }
	    if(Base::$aRequest['data']['id_model_detail']) {
	        setcookie('id_model_detail',Base::$aRequest['data']['id_model_detail'], strtotime( '+30 days' ),"/" );
	        $_COOKIE['id_model_detail']=Base::$aRequest['data']['id_model_detail'];
	    }
	    OwnAuto::AddSearchAuto();
	}
	//-----------------------------------------------------------------------------------------------
	public function GetRubricatorUrl() 
	{
		$sUrl="/rubricator/";
		if(Base::$aRequest['category']) $sUrl.=Base::$aRequest['category'].'/';
		
		if(Base::$aRequest['data']['id_model_detail'] && Base::$aRequest['data']['id_model'] && Base::$aRequest['data']['id_make']) {
		    $sUrl2=Content::CreateSeoUrl('catalog_assemblage_view',array(
				'data[id_make]'=>Base::$aRequest['data']['id_make'],
				'data[id_model]'=>Base::$aRequest['data']['id_model'],
				'data[id_model_detail]'=>Base::$aRequest['data']['id_model_detail'],
			));
		    $sUrl.=str_replace('/cars/', 'c/', $sUrl2);
		} elseif($_COOKIE['id_model_detail'] && $_COOKIE['id_model'] && $_COOKIE['id_make']) {
		    $sUrl2=Content::CreateSeoUrl('catalog_assemblage_view',array(
		        'data[id_make]'=>$_COOKIE['id_make'],
		        'data[id_model]'=>$_COOKIE['id_model'],
		        'data[id_model_detail]'=>$_COOKIE['id_model_detail'],
		    ));
		    $sUrl.=str_replace('/cars/', 'c/', $sUrl2);
		}
		
		$sMake=Db::GetOne("select name from cat where id='".(Base::$aRequest['data']['id_make']?Base::$aRequest['data']['id_make']:$_COOKIE['id_make'])."' ");
		
		return str_replace("/".$sMake."/", '/', $sUrl);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetRubricatorUrlForFilter() {
	    return $this->GetRubricatorUrl()."?rb_filter=1";
	}
	//-----------------------------------------------------------------------------------------------
	public function GetPart($sUrlGroup='',$iIdPriceGroup=0)
	{
	    if(is_array($iIdPriceGroup) && ($iIdPriceGroup[0]==null||$iIdPriceGroup[0]==0)) {
	        $iIdPriceGroup=0;
	    }
	    
	    
	    $bNoIndexNofollow=false;
		if(Base::$aRequest['filter']) {
			$aFilterParamsTmp=String::FilterRequestData(Base::$aRequest['filter']);
			
			foreach($aFilterParamsTmp as $sTableName => $sValue) {
				$sExTableName = Db::GetOne("SHOW TABLES LIKE  '".$sTableName."'");
				if(empty($sExTableName)) {
					Form::Error404(true);
				} else{
					//if(is_numeric($sValue)){
						$sExValue = Db::GetOne($s="select name from ".$sTableName." where id in (".$sValue.") ");
						if(empty($sExValue)) {
							Form::Error404(true);
						} else {}
					//} else Form::Error404(true);
				}
				if(count(explode(',',$sValue))>2){
				    $bNoIndexNofollow=true;
				}
			}
		}
		if(Base::$aRequest['brand']) {
			$aBrandSelected=explode(",", Base::$aRequest['brand']);
			foreach ($aBrandSelected as $sValueBrand) {
				$aExBrend=Db::GetRow("select * from cat where title='".$sValueBrand."' or name='".$sValueBrand."'");
				if(empty($aExBrend)) {
					Form::Error404(true);
				}
			}
			if(count($aBrandSelected)>2){
			    $bNoIndexNofollow=true;
			}
		}
		Base::$tpl->assign('bNoIndexNofollow',$bNoIndexNofollow);
		
	    Base::$tpl->assign('iRubricatorPriceGroup',$iIdPriceGroup);
	    
		if(Base::$aRequest['parts']) {
		    if (Base::$aRequest['data']['id_model_detail']) {
		        $aMakeAssoc=Base::$tpl->get_template_vars('aMakeName');
    		    $aModelAssoc=Base::$tpl->get_template_vars('aModel');
    		    $aModelDetailAssoc=Base::$tpl->get_template_vars('aModelDetail');
    		    
    		    if(count($aModelAssoc)==1) {
    		        $aModelAssoc=TecdocDb::GetModelAssoc(Base::$aRequest['data']);
    		    }
		    
    		    $aAuto=TecdocDb::GetSelectCar(array('id_model_detail'=>Base::$aRequest['data']['id_model_detail']));
    		    $sAutoName=$aAuto['name'];
//     		    $sAutoName=$aMakeAssoc[Base::$aRequest['data']['id_make']]." ".$aModelAssoc[Base::$aRequest['data']['id_model']]." ".$aModelDetailAssoc[Base::$aRequest['data']['id_model_detail']];
		    }
		    
		    
		    //price_group filter begin
		    // LNB-57 filter fill begin
		    $aPref=Db::GetAssoc("select pref,pref as p1 from cat where
				    name in ('".implode("','", explode(',', Base::$aRequest['brand']))."')
				");
		    if($aPref) $aPref=array_unique($aPref);
		    
		    $aFilterParams=String::FilterRequestData(Base::$aRequest['filter']);
		    $sWhereParams='';
		    if($aFilterParams) foreach ($aFilterParams as $sKey => $sValue) {
		        if($sValue!=0) {
		            $sWhereParams.=" and pgp.id_".$sKey." in (".$sValue.") ";
		        }
		    }
			$this->sWhereParams=$sWhereParams;
			

			$iIdMfa=Db::GetOne("select id_tof from cat where id='".Base::$aRequest['data']['id_make']."' ");
		    //price_group filter end		    
		    
		    if(!Base::$aRequest['groups']) Base::$aRequest['groups']=array();
		    //-------------------------------------------------------
// 		    if (!Base::$aRequest['data']['id_model_detail'] || strpos(Base::$aRequest['data']['id_model_detail'], ",")!==false ) {
		    if ((!Base::$aRequest['data']['id_model_detail'] || strpos(Base::$aRequest['data']['id_model_detail'], ",")!==false ) && !Base::$aRequest['data']['id_model']) {
		            Base::$aRequest['parts']=array_unique(Base::$aRequest['parts']);
		            Base::$aRequest['groups']=array_unique(Base::$aRequest['groups']);
		            $sNameCache=serialize(Base::$aRequest['parts']).serialize(Base::$aRequest['groups'])."mfa_".$iIdMfa."limit_".Language::GetConstant('rubricator::limit_all_parts',1000)."tryes_".Language::GetConstant('rubricator::limit_all_parts_tryes',50);
		            $sNameCache=DB_OCAT.md5($sNameCache);
		            
		            if($iIdMfa) {
		                $aItemCodes=FileCache::GetValue('get_part_'.DB_OCAT.'/'.Base::$aRequest['category'].'/'.$iIdMfa, $sNameCache);
		            } else {
		                $aItemCodes=FileCache::GetValue('get_part_'.DB_OCAT.'/'.Base::$aRequest['category'], $sNameCache);
		            }
		            
		            if(!$aItemCodes) $aItemCodes=array();
		            if(!$aItemCodes) {
    		            $aItemCodes=array();
    		            for($i=0; $i<Language::GetConstant('rubricator::limit_all_parts_tryes',50); $i++) {
    		                $sLimit = "limit ".($i*Language::GetConstant('rubricator::limit_all_parts',1000)).",".Language::GetConstant('rubricator::limit_all_parts',1000);
    		                $aTmpPart=TecdocDb::GetTreePartsRubricator(array(
    		                    'id_part'=>implode("','",Base::$aRequest['parts']),
    		                    'id_group'=>implode("','",Base::$aRequest['groups']),
    		                    'limit'=>$sLimit,
                                'id_mfa'=>$iIdMfa
    		                ));
    		    
    		                if($aTmpPart) {
    		                    $aItemCodes=array_merge($aItemCodes,$aTmpPart);
    		                } else {
    		                    break;
    		                }
    		            }
    		            if($aItemCodes){
    		                if($iIdMfa) {
    		                    FileCache::SetValue('get_part_'.DB_OCAT.'/'.Base::$aRequest['category'].'/'.$iIdMfa, $sNameCache, $aItemCodes);
    		                } else {
    		                    FileCache::SetValue('get_part_'.DB_OCAT.'/'.Base::$aRequest['category'], $sNameCache, $aItemCodes);
    		                }
    		            }
		            }
		    } else {
		        foreach (Base::$aRequest['groups'] as $sKey => $aValue){
		            if($aValue==0){
		                unset(Base::$aRequest['groups'][$sKey]);
		            }
		        }
		        if(Base::$aRequest['groups']==array()){
		            Base::$aRequest['groups'][]='0';
		        }
		       
		        $aData=array(
		            'id_group'=>implode("','",Base::$aRequest['groups']),
		            'id_part'=>implode("','",Base::$aRequest['parts']),
		            'id_tof'=>Base::$aRequest['data']['id_tof'],
		            'id_model'=>Base::$aRequest['data']['id_model'],
		            'id_model_detail'=>Base::$aRequest['data']['id_model_detail'],
		            'limit'=>Base::GetConstant('tubricator:brand_model','1000')
		        );
		    
		        //cache
		        if(Base::GetConstant('rubricator:use_cache','0')==1) {
		            $sNameCache=md5(serialize($aData));
		            if(!($aItemCodes=FileCache::GetValue('get_part_'.DB_OCAT.'/'.Base::$aRequest['category'].'/'.$iIdMfa."/typ_".Base::$aRequest['data']['id_model_detail'], $sNameCache))) {
		                $aItemCodes=TecdocDb::GetTreePartsWihtoutSite($aData);
		                FileCache::SetValue('get_part_'.DB_OCAT.'/'.Base::$aRequest['category'].'/'.$iIdMfa."/typ_".Base::$aRequest['data']['id_model_detail'], $sNameCache, $aItemCodes);
		            }
		        } else {
		            $aItemCodes=TecdocDb::GetTreePartsWihtoutSite($aData);
		        }
		        	
// 		        //split cat_model_type_link query
		        $sSqlSiteParts="
            		select 0 art_id
            			, cp.code art_article_nr
            			, cp.item_code as item_code
            			, cp.name as name
            			, cat.title as brand
            			, cat.image as image_logo
            			, cat.pref as pref
            		from cat_part cp
            		inner join cat on cat.pref=cp.pref
            		inner join cat_model_type_link cm on cm.id_cat_part=cp.id and cm.id_cat_model_type in ('".$aData['id_model_detail']."')
            		inner join cat_tree_link ct on ct.id_cat_part=cp.id and ct.id_tree IN ('".$aData['id_part']."') and ct.id_group in ('".$aData['id_group']."')
            		";
		    
		        $aPartsSite=Db::GetAll($sSqlSiteParts);
		    
		        if(!$aItemCodes) $aItemCodes=array();
		        if(!$aPartsSite) $aPartsSite=array();
		        $aItemCodes=array_merge($aItemCodes,$aPartsSite);
		    }
		    //-------------------------------------------------------
			
			if($aItemCodes) {
			    $aBrandFilterCodes=array();
			    $aItemCodesUnique=array();
			    foreach($aItemCodes as $aValue) {
			        $aBrandFilterCodes[$aValue['item_code']]=$aValue['item_code'];
			        $aItemCodesUnique[$aValue['item_code']]=$aValue['item_code'];
			    }
			}
			
			//price group filter begin
			if($iIdPriceGroup) {
    			if(!is_array($iIdPriceGroup)) {
        			$sSql="select h.* from handbook as h
        						inner join price_group_filter as pgf on pgf.id_handbook=h.id and pgf.id_price_group='".$iIdPriceGroup."'
        						order by h.number asc";
    			} else {
    			    $sSql="select h.* from handbook as h
        						inner join price_group_filter as pgf on pgf.id_handbook=h.id and pgf.id_price_group in('".implode("','", $iIdPriceGroup)."')
        						order by h.number asc";
    			}
			} else {
			    $sSql="select * from handbook where 1=0";
			}
			
			$aFilter=Db::GetAll($sSql);
			if($aFilter) foreach ($aFilter as $sKey => $aValue) {
			    $aFilter[$sKey]['params']=Db::GetAll("select * from ".$aValue['table_']." where visible=1 order by name");
			    if($aFilter[$sKey]['params']) foreach($aFilter[$sKey]['params'] as $sParamKey => $aParam) {
			
			        $aSelParams=explode(",", $aFilterParams[$aValue['table_']]);
			
			        if(in_array($aParam['id'], $aSelParams)) {
			            $aFilter[$sKey]['params'][$sParamKey]['checked']=1;
			             
			            $aFilterSelected[]=array(
			                'name'=>$aValue['name'],
			                'value'=>$aParam['name'],
			                'id'=>$aParam['id'],
			                'table_'=>$aValue['table_'],
			            );
			        }
			
			        //count products
			        $aFilterParamsForCount=String::FilterRequestData(Base::$aRequest['filter']);
			        $sWhereParams='';
			        if($aFilterParamsForCount)
			        {
			            foreach ($aFilterParamsForCount as $sKeyForCount => $sValueCount) {
			                if($aValue['table_']==$sKeyForCount) {
			                    if($sValueCount!=0) {
			                        $sWhereParams.=" and pgp.id_".$sKeyForCount." in (".$sValueCount.")"
			                            ." and pgp.id_".$aValue['table_']." in ('".$aParam['id']."') ";
			                    } else {
			                        $sWhereParams.=" and pgp.id_".$sKeyForCount." in ('".$aParam['id']."') ";
			                    }
			                } else {
			                    $sWhereParams.=" and pgp.id_".$sKeyForCount." in (".$sValueCount.") "
			                        ." and pgp.id_".$aValue['table_']." in ('".$aParam['id']."') ";
			                }
			            }
			        } else {
			            $sWhereParams.=" and pgp.id_".$aValue['table_']." in ('".$aParam['id']."') ";
			        }
			        
			        //collect params for filter
			        $aParamsForFiterArray=array(
		                "pref"=>$aPref,
		                "where"=>" and p.price>0 ".$sWhereParams,
		                "pgpf"=>1,
		                'aItemCode'=>$aItemCodesUnique
		            );
			        
			        if(Base::$aRequest['data']['id_model_detail']) {
			            //selected auto by rubricator
			        } else {
			            //auto not selected
			            if(!is_array($iIdPriceGroup)) {
			                $aParamsForFiterArray['id_price_group']=$iIdPriceGroup;
			            } else {
			                $aParamsForFiterArray['childs']=$iIdPriceGroup;
			            }
			        }
			        $sFullSql=Base::GetSql("Catalog/PriceForCount",$aParamsForFiterArray);
			        $aFilter[$sKey]['params'][$sParamKey]['count']=Db::GetOne($sFullSql);
			    }
			}
			
			if(Base::$aRequest['brand']){
			    $aBrandSelected=explode(",", Base::$aRequest['brand']);
			     
			    foreach ($aBrandSelected as $sKeyBrand => $sValueBrand) {
			        $aBrandReplace=Db::GetRow("select lower(name) as name,title as title from cat where name='".$sValueBrand."'  ");
			        if($aBrandReplace) $aBrandSelected[$sKeyBrand]=$aBrandReplace;
			    }
			}
			
			    if($aFilter) foreach ($aFilter as $iKey => $aValue) {
			        usort($aValue['params'], function ($a, $b)
			        {
			            if ($a['sort'] == $b['sort']){
			                return 0;
			            }
			            return ($a['sort'] < $b['sort']) ? -1 : 1;
			        });
			        $aFilter[$iKey]['params']=$aValue['params'];
			    }
			    //---------------------------------------
			    $aUrl=array();
			    if($aFilterParams) foreach ($aFilterParams as $sKey => $sValue) $aUrl[]='filter['.$sKey.']='.$sValue;
			
			    $sUrl.=$this->GetRubricatorUrlForFilter();
			    if(Base::$aRequest['step'] && $sUrl=='?rb_filter=1' && Base::$aRequest['action']=='cars_subcategory_model_group_view') {
			        //wrong url
			        parse_str($_SERVER['REQUEST_URI'],$aUrlTmp);
			        if($aUrlTmp) {
			            unset($aUrlTmp['step']);
			            $sUrl="";
			            foreach ($aUrlTmp as $sKeyUrl => $aValueUrl) {
			                $sUrl.=$sKeyUrl."=".$aValueUrl."&";
			            }
			            
			            $sUrl.="rb_filter=1";
			        }
			    }
			
			    if(Base::$aRequest["brand"]) $sUrl.="&brand=".Base::$aRequest["brand"];
			    if($aUrl) $sUrl.='&'.implode("&", $aUrl);
			    Base::$tpl->assign('sUrl',$sUrl);
			    
			
			Rubricator::SetBrandPriceOrderUrl($sUrl);
			$aPriceGroupBrand=Rubricator::GetBrandsForFilter('array',$aBrandFilterCodes,$iIdPriceGroup,$aItemCodesUnique);
			if(Base::$aRequest["max_price"])
			    $aPriceSelected=array("min_price"=> Base::$aRequest["min_price"],"max_price"=> Base::$aRequest["max_price"],);
		    //AT-1254 Generate SeoUrl
		    $sUrlRemoveAll='';
		    self::CreateSeoLink($sUrl,$aFilter,$aPriceGroupBrand,$aFilterSelected,$aBrandSelected,$sUrlRemoveAll,$aPriceSelected);
		    //end
		    
		    Base::$tpl->assign('aFilter',$aFilter);
		    Base::$tpl->assign('aFilterSelected',$aFilterSelected);
		    Base::$tpl->assign('aPriceGroupBrand', $aPriceGroupBrand);
		    Base::$tpl->assign('aBrandSelected',$aBrandSelected);
		    Base::$tpl->assign('sUrlRemoveAll',$sUrlRemoveAll);
		    Base::$tpl->assign('aPriceSelected',$aPriceSelected);
			//price group filter end
			
		    //noindex follow begin
		    if( count(Base::$aRequest['filter'])>1 ||
		        (count(Base::$aRequest['filter'])==1 && Base::$aRequest['brand']) ||
		        strpos(Base::$aRequest['brand'], ",")!==false ||
		        strpos(Base::$aRequest['all_params'], ",")!==false
		    ) {
		        Base::$tpl->assign('bNoFollow',1);
		        Base::$tpl->assign('bNoIndex',1);
		    }
		    //noindex follow end
		    
		    $sGroupChangeTableUrl=$_SERVER['REQUEST_URI'];
		    $iQstPos=strpos($sGroupChangeTableUrl, '/g/1');
		    if($iQstPos===false) {
		        $sGroupChangeTableUrl.='/g/1';
		    } else {
		        $sGroupChangeTableUrl=str_replace("/g/1", "", $sGroupChangeTableUrl);
		    }
		    Base::$tpl->assign("sGroupChangeTableUrl",$sGroupChangeTableUrl);
		    Base::$tpl->assign('sGroupTableUrl',$_SERVER['REQUEST_URI']);
			Base::$tpl->assign("aPartData",Base::$aRequest['data']);
			$oCatalog = new Catalog();
			$oTable=new Table();
			$oTable->sType='array';
			$oTable->aDataFoTable=$aItemCodes;
			
			Catalog::GetPriceTableHead($oTable);
			$oTable->sStepperType='step_chpu_rubricator';
			
			$oTable->aCallback=array($this,'CallParsePartRubricator');
			$oTable->aCallbackAfter=array($this,'CallParseRubricatorImages');
			$oTable->bCheckVisible=false;
			$oTable->bCheckAllVisible=false;
			$oTable->bStepperVisible=true;
			$oTable->bDefaultChecked=false;
			$oTable->sCheckField='code_provider';
			$oTable->iAllRow=0;
			$oTable->iStartStep=1;
			
			if(Base::$aRequest['table']=='gallery') {
			    $oTable->sTemplateName = 'table/table_list.tpl';
		        $oTable->sDataTemplate='catalog/row_price.tpl';
		        $oTable->iRowPerPage=10;
			} else {
			    $oTable->sTemplateName = 'table/table_thumb.tpl';
		        $oTable->sDataTemplate='catalog/row_price_gallery.tpl';
			    $oTable->iRowPerPage=9;
			}
			
			Base::$tpl->assign("sPriceTable",$oTable->GetTable());

			//SetMetaTagsPage
			if($aFilterParams) foreach ($aFilterParams as $sKey => $sValue) {
			    $sTable=Db::GetOne("select name from handbook where table_='".$sKey."' ");
			    $sValue=Db::GetOne("select group_concat(name separator ', ') from ".$sKey." where id in (".$sValue.") ");
			    
			    $sH1.=" ".$sTable.": ".$sValue.",";
			}
			if($sH1) {
			    $sLast=substr($sH1, strlen($sH1)-1);
			    if($sLast==",") {
			        $sH1=substr($sH1, 0, -1);
			    }
			}
			
			// 3 filter = noindex,nofollow
			// 2 filter + brand = noindex,nofollow
			if(isset(Base::$aRequest['order_brand']) && count($aFilterParams)>=2) {
			    Base::$tpl->assign('bNoFollow','1');
			    Base::$tpl->assign('bNoIndex','0');
			} elseif(!isset(Base::$aRequest['order_brand']) && count($aFilterParams)>2) {
			    Base::$tpl->assign('bNoFollow','1');
			    Base::$tpl->assign('bNoIndex','0');
			}
			if($sH1) return " -".mb_strtolower($sH1);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParsePartRubricator(&$aItem)
	{
		if (!$aItem)
			return;
		
	    if(Base::$aRequest['brand']) {
	        $aOrderBrandPref=Db::GetAssoc("select id, pref from cat where name in ('".str_replace(",", "','", Base::$aRequest['brand'])."') ");
	        
	        if($aItem && $aOrderBrandPref) foreach ($aItem as $sKey => $aValue) {
	            if(!in_array($aValue['pref'], $aOrderBrandPref)) unset($aItem[$sKey]);
	        }
	        sort($aItem);
	    }
	    
	    
	    if(Base::$aRequest['order_price'] != 'desc') {
	        if (!Base::$aRequest['sort'])
	            Base::$aRequest['sort'] = 'price';
	    
	        if (!Base::$aRequest['way'])
	            Base::$aRequest['way'] = 'up';
	        $this->CallParsePart($aItem);
	    } else {
	        if (!Base::$aRequest['sort'])
	            Base::$aRequest['sort'] = 'price';
	    
	        if (!Base::$aRequest['way'])
	            Base::$aRequest['way'] = 'down';
	        $this->CallParsePart($aItem);
	    }
	    
	    if($aItem) {
	        $this->PosPriceParse($aItem,false,false);
	    }

		if(!Base::$aRequest['table']=='gallery'){
	    $aTempItem=array();
	    foreach ($aItem as &$aValuel){
	        if(!isset($aTempItem[$aValuel['item_code']]))
	        $aTempItem[$aValuel['item_code']]=$aValuel;
	        else $aTempItem[$aValuel['item_code']]['childs'][]=$aValuel;
	    }
	    $aItem=array_values($aTempItem);}
	    
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseRubricatorImages(&$aItem)
	{
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
	            $aItem[$sKey]['image']=$aGraphic[$aValue['item_code']]['img_path'];
	        }
	    }
	    
	    if(Auth::$aUser['type_']=='manager') {
    	    $aProvidersAssoc=Db::GetAssoc("select up.id_user as id, up.* from user_provider up
        		inner join user u ON u.id = up.id_user");
    	    
    	    //provider info
    	    if ($aItem) {
    	        foreach($aItem as $key => $aValue) {
    	            if (!$aValue['id_provider'])
    	                continue;
    	            $aItem[$key]['history'] = '';
    	            $aProviderInfo = $aProvidersAssoc[$aValue['id_provider']];
    	            if ($aProviderInfo) {
    	                Base::$tpl->assign('aProviderInfo',$aProviderInfo);
    	                $aItem[$key]['history'] = Base::$tpl->fetch('catalog/row_provider_log.tpl');
    	            }
    	        }
    	    }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
	    if ((Base::$aRequest['data']['id_model_detail'] || $_COOKIE['id_model_detail']) && (Base::$aRequest['data']['id_make'] && Base::$aRequest['data']['id_model'] && Base::$aRequest['data']['id_model_detail'])) {
		    $this->IndexModelDetail();
		    return;
		} elseif(Base::$aRequest['cat'] && !Base::$aRequest['model_group']) {
		    $this->IndexBrand();
		    return;
		} elseif(Base::$aRequest['cat'] && Base::$aRequest['model_group']) {
		    $this->IndexModel();
		    return;
		}
		
		if( (Base::$aRequest['cat'] && (Base::$aRequest['model_group'] || Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator'])) || !Base::$aRequest['cat']) {
            $aMenu=$this->GetRubricatorMenu();
		}
        
		Base::$oContent->AddCrumb(Language::GetMessage('spareparts'));
		
		// rubricator
		Content::SetMetaTagsPage('rubricator_view:', array(
		    
		));
		
		Base::$tpl->assign('aMainRubric', $aMenu);
		Base::$sText.=Base::$tpl->fetch('rubricator/index.tpl');
		
		Rubricator::GetAllModelsList();
	}
	//-----------------------------------------------------------------------------------------------
	public function IndexBrand()
	{
	    $sSelectedBrandTitle=Db::GetOne("select title from cat where name='".Base::$aRequest['cat']."' ");
        Base::$tpl->assign('sAutoPreSelected',"c/".Base::$aRequest['cat']."/");
        Base::$tpl->assign('sSelectedCarUrlRubricator',"c/".Base::$aRequest['cat']);
        Base::$oContent->AddCrumb($sSelectedBrandTitle,'');
        
        // rubricator + brand
        Content::SetMetaTagsPage('rubricator_brand_view:', array(
            'brand' => $sSelectedBrandTitle,
        ));
        
        $aCatValue=Db::GetRow("select * from cat where name='".Base::$aRequest['cat']."' ");
        Base::$tpl->assign('sSelectedBrandTitle',$aCatValue['title']);
        Base::$sText.=Base::$tpl->fetch('rubricator/index.tpl');
        
        $sOtherModels=Db::GetAll($sql = "select * from cat_model_group where visible=1 and id_make='".$aCatValue['id']."' order by id_make,name");
        if ($sOtherModels) foreach ($sOtherModels as $sKey => $aValue){
            $sOtherModels[$sKey]['brand']=$aCatValue['title'];
            $sAutoCode="c/".$aCatValue['name']."_".$sOtherModels[$sKey]['code']."/";
            $sAutoCode=mb_strtolower($sAutoCode);
            $sOtherModels[$sKey]['seourl']="/rubricator/".$sAutoCode;
        }
        
        Base::$tpl->Assign('bSelectedModel',1);
        
        Base::$tpl->assign('sAutoPreSelected',"c/".$aCatValue['name']."/");
        Base::$tpl->assign('sSelectedCarUrlRubricator',"c/".$aCatValue['name']."/");
        Base::$tpl->assign('sOtherModels',$sOtherModels);
        
        Base::$sText.=Base::$tpl->fetch('rubricator/model_list.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function IndexModel()
	{
// 	    $aRubric=Db::GetAssoc(str_replace("select","select r.id as key_, ",Base::GetSql("Rubricator",array('visible'=>1))));
// 	    //filter rubric by auto
// 	    if(Base::$aRequest['data']['id_model_detail'] && Base::$aRequest['data']['id_model'] && Base::$aRequest['data']['id_make']) {
// 	        $aTree=TecdocDb::GetTree(Base::$aRequest['data']);
// 	        if ($aTree) foreach ($aTree as $sKey => $aValue) {
// 	            if($aValue['id']=='13771' || $aValue['str_id_parent']=='13771') {
// 	                unset($aTree[$sKey]);
// 	                continue;
// 	            }
// 	            $aTreeAssoc[$aValue['id']]=$aValue;
// 	        }
// 	    }
// 	    if($aTreeAssoc) {
// 	        $aAllowedTreeNodes=array_keys($aTreeAssoc);
	    
// 	        $aMenu=array();
// 	        foreach ($aRubric as $aValue) {
// 	            if($aValue['level']==1) $aMenu[$aValue['id']]=$aValue;
// 	            else continue;
// 	        }
	    
// 	        foreach ($aRubric as $aValue) {
// 	            if($aValue['level']==2) {
// 	                //filter by auto
// 	                $bAllow=false;
// 	                $aRubricTree=explode(",", $aValue['id_tree']);
// 	                if($aRubricTree) foreach ($aRubricTree as $iTreeNode) {
// 	                    if(in_array($iTreeNode, $aAllowedTreeNodes)) {
// 	                        $bAllow=true;
// 	                        break;
// 	                    }
// 	                }
	    
// 	                if($bAllow) {
// 	                    $aMenu[$aValue['id_parent']]['childs'][]=$aValue;
// 	                }
// 	            }
// 	            else continue;
// 	        }
// 	    } else {
// 	        $aMenu=array();
// 	        foreach ($aRubric as $aValue) {
// 	            if($aValue['level']==1) $aMenu[$aValue['id']]=$aValue;
// 	            else continue;
// 	        }
	    
// 	        foreach ($aRubric as $aValue) {
// 	            if($aValue['level']==2) {
// 	                $aMenu[$aValue['id_parent']]['childs'][]=$aValue;
// 	            }
// 	            else continue;
// 	        }
// 	    }
// 	    if($aMenu) {
// 	        //sort by num
	    
// 	        usort($aMenu, function ($a, $b)
// 	        {
// 	            if ($a['sort'] == $b['sort']) {
// 	                return 0;
// 	            }
// 	            return ($a['sort'] < $b['sort']) ? -1 : 1;
// 	        });
// 	    }
	    
	    $aMenu=$this->GetRubricatorMenu();
	    
	    $sSelectedBrandTitle=Db::GetOne("select title from cat where name='".Base::$aRequest['cat']."' ");
	    
	    Base::$tpl->assign('sAutoPreSelected',"c/".Base::$aRequest['cat']."/");
	    Base::$tpl->assign('sSelectedCarUrlRubricator',"c/".Base::$aRequest['cat']);
	    
	    $aSelectedModelGroup=Db::GetRow("select * from cat_model_group where code = '".Base::$aRequest['model_group']."' ");
	    
	    Base::$tpl->assign('sAutoPreSelected',"c/".Base::$aRequest['cat']."_".Base::$aRequest['model_group']."/");
	    Base::$oContent->AddCrumb($sSelectedBrandTitle,"/rubricator/c/".Base::$aRequest['cat']."/");
	    Base::$oContent->AddCrumb($aSelectedModelGroup['name']);
	    
	    //rubricator brand + model
	    Content::SetMetaTagsPage('rubricator_brand_model_view:', array(
	        'model' => $aSelectedModelGroup['name'],
	        'brand' => $sSelectedBrandTitle,
	    ));
	    
	    Base::$tpl->assign('aMainRubric', $aMenu);
	    
	    $aCatValue=Db::GetRow("select * from cat where name='".Base::$aRequest['cat']."' ");
	    Base::$tpl->assign('sSelectedBrandTitle',$aCatValue['title']);
	    Base::$sText.=Base::$tpl->fetch('rubricator/index.tpl');
	    
	    $aSelectedModelGroup=Db::GetRow("select * from cat_model_group where code='".Base::$aRequest['model_group']."' and id_make='".Base::$aRequest['data']['id_make']."' ");
	    
	    $sOtherModels=Db::GetAll($sql = "select * from cat_model_group where visible=1 and id_make='".$aCatValue['id']."' and code not like '".$aSelectedModelGroup['code']."' order by id_make,name");
	    if ($sOtherModels) foreach ($sOtherModels as $sKey => $aValue){
	        $sOtherModels[$sKey]['brand']=$aCatValue['title'];
	        $sAutoCode="c/".$aCatValue['name']."_".$sOtherModels[$sKey]['code']."/";
	        $sAutoCode=mb_strtolower($sAutoCode);
	        $sOtherModels[$sKey]['seourl']="/rubricator/".$sAutoCode;
	    }
	    
	    Base::$tpl->Assign('bSelectedModel',1);
	    
	    Base::$tpl->assign('sAutoPreSelected',"c/".$aCatValue['name']."_".$aSelectedModelGroup['code']."/");
	    Base::$tpl->assign('sSelectedCarUrlRubricator',"c/".$aCatValue['name']."_".$aSelectedModelGroup['code']."/");
	    Base::$tpl->assign('sOtherModels',$sOtherModels);
	    
	    Base::$sText.=Base::$tpl->fetch('rubricator/other_models.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function IndexModelDetail()
	{
	    if(!Base::$aRequest['data'] && $_COOKIE['id_model_detail']) {
	        Base::$aRequest['data']['id_model_detail']=$_COOKIE['id_model_detail'];
	        Base::$aRequest['data']['id_model']=$_COOKIE['id_model'];
	        Base::$aRequest['data']['id_make']=$_COOKIE['id_make'];
	    }
	    
	    $this->GetModels();
	    $this->GetModelDetails();
	    
	    $aMakeAssoc=Base::$tpl->get_template_vars('aMakeName');
	    $aModelAssoc=Base::$tpl->get_template_vars('aModel');
	    $aModelDetailAssoc=Base::$tpl->get_template_vars('aModelDetail');
	     
	    if(count($aModelAssoc)==1) {
	        $aModelAssoc=TecdocDb::GetModelAssoc(Base::$aRequest['data']);
	    }
	    if(count($aModelDetailAssoc)==1) {
	        $aModelDetailAssoc=TecdocDb::GetModelDetailAssoc(Base::$aRequest['data']);
	    }
	    
	    $aAuto=TecdocDb::GetSelectCar(array('id_model_detail'=>Base::$aRequest['data']['id_model_detail']));
	    $sAutoName=$aAuto['name'];
// 	    $sAutoName=$aMakeAssoc[Base::$aRequest['data']['id_make']]." ".$aModelAssoc[Base::$aRequest['data']['id_model']]." ".$aModelDetailAssoc[Base::$aRequest['data']['id_model_detail']];
	    Base::$oContent->AddCrumb($sAutoName,'');
	    
	    //rubricator + full auto
	    $sSelectedBrandTitle=Db::GetOne("select title from cat where name='".Base::$aRequest['cat']."' ");
	    Content::SetMetaTagsPage('rubricator_auto_view:', array(
	        'model' => $sAutoName,
	        'step' => (Base::$aRequest['step']>0?(Base::$aRequest['step']+1):0),
	        'brand' => $sSelectedBrandTitle,
	    ));

	    $aMenu=$this->GetRubricatorMenu();
	    
	    $sSelectedBrandTitle=Db::GetOne("select title from cat where name='".Base::$aRequest['cat']."' ");
	    Base::$tpl->assign('sAutoPreSelected',Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator']);
	    Base::$tpl->assign('aMainRubric', $aMenu);
	    
	    $aCatValue=Db::GetRow("select * from cat where name='".Base::$aRequest['cat']."' ");
	    Base::$tpl->assign('sSelectedBrandTitle',$aCatValue['title']);
	    Base::$sText.=Base::$tpl->fetch('rubricator/index.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function Category() 
	{
		if(Base::$aRequest['category']){
		    if(Base::$aRequest['category']=='c') {
		        unset($_REQUEST['category']);
		        unset(Base::$aRequest['category']);
		        Rubricator::Index();
		    }
		    
		    $aData = array(
		        'visible'=>1,
		        'where'=>" and r.url='".Base::$aRequest['category']."' "
		    );
		    $aCategory=MultiLanguage::GetLocalizedRubricatorRow($aData);
		    //$aCategory=Db::GetRow("select * from rubricator where url='".Base::$aRequest['category']."' and visible=1");
		    $this->aCategory = $aCategory;

			//404
			if(!$aCategory) {
			    Form::Error404(false);
			    return;
			}
			
			if(Base::$aRequest['cat'] && !Base::$aRequest['model_group'] && !Base::$aRequest['data']['id_model'] && !Base::$aRequest['data']['id_model_detail']) {
			    if($aCategory['level']>1) {
			        Rubricator::SubCategoryBrandView();
			        return;
			    } else {
			        Rubricator::CategoryBrandView();
			        return;
			    }
			} elseif(Base::$aRequest['model_group'] && !Base::$aRequest['data']['id_model'] && !Base::$aRequest['data']['id_model_detail']) {
			    if($aCategory['level']>1) {
			        Rubricator::SubcategoryModelGroupView();
			        return;
			    } else {
			        Rubricator::CategoryModelGroupView();
			        return;
			    }
			}
			
			if($aCategory['level']>1) {
			    Rubricator::SubCategory();
			    return;
			}
			
			if (Base::$aRequest['data']['id_model_detail']) {
			    $this->GetModels();
			    $this->GetModelDetails();
			    
			    $aMakeAssoc=Base::$tpl->get_template_vars('aMakeName');
			    $aModelAssoc=Base::$tpl->get_template_vars('aModel');
			    $aModelDetailAssoc=Base::$tpl->get_template_vars('aModelDetail');
			
			    if(count($aModelAssoc)==1) {
			        $aModelAssoc=TecdocDb::GetModelAssoc(Base::$aRequest['data']);
			    }
			
			    $aAuto=TecdocDb::GetSelectCar(array('id_model_detail'=>Base::$aRequest['data']['id_model_detail']));
			    $sAutoName=$aAuto['name'];
// 			    $sAutoName=$aMakeAssoc[Base::$aRequest['data']['id_make']]." ".$aModelAssoc[Base::$aRequest['data']['id_model']]." ".$aModelDetailAssoc[Base::$aRequest['data']['id_model_detail']];
			    
			    $sAutoUrl=Content::CreateSeoUrl('catalog_assemblage_view',array(
			        'data[id_make]'=>$_COOKIE['id_make'],
			        'data[id_model]'=>$_COOKIE['id_model'],
			        'data[id_model_detail]'=>$_COOKIE['id_model_detail'],
			    ));
			    $sAutoUrl=str_replace('/cars/', '', $sAutoUrl);
			    $sAutoUrl=str_replace(Base::$aRequest['cat']."/", 'c/', $sAutoUrl);
			    
			    Base::$oContent->AddCrumb($sAutoName,'/rubricator/'.$sAutoUrl);
			    Base::$oContent->AddCrumb($aCategory['name'],'');
			} else {
			    Base::$oContent->AddCrumb($aCategory['name']);
			}
			
			if($aCategory) {
			    $aData = array(
			        'visible'=>1,
			        'where'=>" and r.id_parent='".$aCategory['id']."' "
			    );
			    $aCategory['childs'] = MultiLanguage::GetLocalizedRubricator($aData, ' order by sort asc');
			    if($aCategory['childs']) {
			        foreach ($aCategory['childs'] as $sKeyChild => $aValueChild) {
			            $aData = array(
			                'visible'=>1,
			                'where'=>" and r.id_parent='".$aValueChild['id']."' "
			            );
			            $aChilds3lvl = MultiLanguage::GetLocalizedRubricator($aData, ' order by sort asc');
			            if ($aChilds3lvl) {
			                Rubricator::FilterCategoryByAuto($aChilds3lvl);
			                foreach ($aChilds3lvl as $sK=>$aVal) {
			                    if($aVal['url']=='0') {
			                        unset($aChilds3lvl[$sK]);
			                    }
			                }
			                $aCategory['childs'][$sKeyChild]['childs']=$aChilds3lvl;
			            }
			        }
			    }
			}
			Base::$tpl->assign('aCategory', $aCategory);

			if(!Base::$aRequest['data']['id_model_detail']) {
			    Content::SetMetaTagsPage('rubricator_category:', array(
			        'category' => $aCategory['name'],
			        'step' => (Base::$aRequest['step']>0?(Base::$aRequest['step']+1):0),
			    ));
			} else {
			    $sOrderBrands=Db::GetOne("select group_concat(title separator ', ') from cat where name in ('".str_replace(",", "','", Base::$aRequest['brand'])."') ");
			    $sSelectedBrandTitle=Db::GetOne("select title from cat where name='".Base::$aRequest['cat']."' ");
			    
			    Content::SetMetaTagsPage('rubricator_category_car:', array(
			        'category' => $aCategory['name'],
			        'step' => (Base::$aRequest['step']>0?(Base::$aRequest['step']+1):0),
			        'model' => $sAutoName,
			        'brand' =>$sSelectedBrandTitle,
			        'order_brand' => $sOrderBrands
			    ));
			}

			if(Base::$aRequest['data']['id_model_detail']) $sSelectedAutoUrl=str_replace("/cars/", "", Content::CreateSeoUrl('catalog_assemblage_view',array(
			    'data[id_make]'=>Base::$aRequest['data']['id_make'],
			    'data[id_model]'=>Base::$aRequest['data']['id_model'],
			    'data[id_model_detail]'=>Base::$aRequest['data']['id_model_detail'],
			)));
			
			$sUrl = '/rubricator/'.$aCategory['url'].'/';
			Base::$aRequest['parts']=array();
			Base::$aRequest['groups']=array();
			$aPriceGroups=array();
			if($aCategory['childs']) {
			    foreach ($aCategory['childs'] as $aValue) {
			        $aPriceGroups[]=$aValue['id_price_group'];
			
			        $aPartsTmp=explode(",", $aValue['id_tree']);
			        $aGroupsTmp=explode(",", $aValue['id_group']);
			        if(!$aPartsTmp) $aPartsTmp=array();
			        if(!$aGroupsTmp) $aGroupsTmp=array();
			        Base::$aRequest['parts']=array_merge(Base::$aRequest['parts'],$aPartsTmp);
			        Base::$aRequest['groups']=array_merge(Base::$aRequest['groups'],$aGroupsTmp);
			        
			        //check level3
			        if($aValue['level']==2) {
			            $aL3Childs=Db::GetAll("select * from rubricator where id_parent='".$aValue['id']."' and visible=1 order by sort asc");
			            
			            if($aL3Childs) {
			                foreach ($aL3Childs as $aValueC3) {
			                    $aPriceGroups[]=$aValueC3['id_price_group'];
			                    	
			                    $aPartsTmp=explode(",", $aValueC3['id_tree']);
			                    $aGroupsTmp=explode(",", $aValueC3['id_group']);
			                    if(!$aPartsTmp) $aPartsTmp=array();
			                    if(!$aGroupsTmp) $aGroupsTmp=array();
			                    Base::$aRequest['parts']=array_merge(Base::$aRequest['parts'],$aPartsTmp);
			                    Base::$aRequest['groups']=array_merge(Base::$aRequest['groups'],$aGroupsTmp);
			                }
			            }
			        }
			    }
			} else {
			    $aPriceGroups[]=$aCategory['id_price_group'];
			    Base::$aRequest['parts']=explode(",", $aCategory['id_tree']);
			    Base::$aRequest['groups']=explode(",", $aCategory['id_group']);
			}
			if(!Base::$aRequest['data'] && Base::$aRequest['car_select']) {
			    Base::$aRequest['data']=Base::$aRequest['car_select'];
			}
		    Base::$tpl->assign('sSelectedAutoUrl', $sSelectedAutoUrl);
		    if(Base::$aRequest['data']['id_model_detail']) {
		        Base::$tpl->assign('sAutoPreSelected',Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator']);
		        $this->GetPart('',$aPriceGroups);
		        if(!$aCategory['childs']) {
		            Base::$sText.=Base::$tpl->fetch('rubricator/subcategory.tpl');
		        }
		    }
			if($aCategory['childs']) {
			    Base::$sText.=Base::$tpl->fetch('rubricator/category.tpl');
			}
			
			if(!Base::$aRequest['data']['id_model_detail']) {
			    Rubricator::GetAllModelsList();
			}
		} else {
			Rubricator::Index();
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function SubCategory($aCategory=array())
	{
	    if(Base::$aRequest['xajax']) {
	        //show car select exit point
	        return;
	    }
	    $aPriceGroups=array();
	    
	    //check subcategory
	    $bSubcategoryExists=Db::GetOne("select id from rubricator where url like '".Base::$aRequest['category']."' ");
	    if(!$bSubcategoryExists) {
	        //may be it is subcategory and order_brand
	        $aBrandTofAssoc=Db::GetAssoc("select name, id_tof from cat order by name");
	        if(in_array(Base::$aRequest['order_brand'], array_keys($aBrandTofAssoc)) || in_array(Base::$aRequest['category'], array_keys($aBrandTofAssoc))) {
	            //normal
	        } else {
	            //not normal
	            Form::Error404(false);
	            return;
	        }
	        
	        //this is category
	        $_REQUEST['action']='rubricator_category';
	        Rubricator::Category();
	        return;
	    }
	    
	    if($this->aCategory && $this->aCategory['url']==Base::$aRequest['category']){
	        $aSubCategory=$this->aCategory;
	    }else{
	        $aData = array(
	            'visible'=>1,
	            'where'=>" and r.url='".Base::$aRequest['category']."' "
	        );
	        $aSubCategory=MultiLanguage::GetLocalizedRubricatorRow($aData);
	    }
		if(!$aCategory){
		    $aData = array(
		        'visible'=>1,
		        'where'=>" and r.id='".$aSubCategory['id_parent']."' "
		    );
		    $aCategory=MultiLanguage::GetLocalizedRubricatorRow($aData);
		}
		if($aCategory['id_price_group']) $aPriceGroups[]=$aCategory['id_price_group'];
		
		if (Base::$aRequest['data']['id_model_detail']) {
		    $this->GetModels();
		    $this->GetModelDetails();
		
		    $aMakeAssoc=Base::$tpl->get_template_vars('aMakeName');
		    $aModelAssoc=Base::$tpl->get_template_vars('aModel');
		    $aModelDetailAssoc=Base::$tpl->get_template_vars('aModelDetail');
		
		    if(count($aModelAssoc)==1) {
		        $aModelAssoc=TecdocDb::GetModelAssoc(Base::$aRequest['data']);
		    }
		
		    $aAuto=TecdocDb::GetSelectCar(array('id_model_detail'=>Base::$aRequest['data']['id_model_detail']));
		    $sAutoName=$aAuto['name'];
// 		    $sAutoName=$aMakeAssoc[Base::$aRequest['data']['id_make']]." ".$aModelAssoc[Base::$aRequest['data']['id_model']]." ".$aModelDetailAssoc[Base::$aRequest['data']['id_model_detail']];
		    
		    $sAutoUrl=Content::CreateSeoUrl('catalog_assemblage_view',array(
		        'data[id_make]'=>$_COOKIE['id_make'],
		        'data[id_model]'=>$_COOKIE['id_model'],
		        'data[id_model_detail]'=>$_COOKIE['id_model_detail'],
		    ));
		    $sAutoUrl=str_replace('/cars/', 'c/', $sAutoUrl);
		    $sMake=Db::GetOne("select lower(name) from cat where id='".$_COOKIE['id_make']."' ");
		    $sAutoUrl=str_replace("/".$sMake."/", '/', $sAutoUrl);
		    Base::$oContent->AddCrumb($sAutoName,"/rubricator/".$sAutoUrl);
		    if($aCategory){
		        if($aCategory['id_parent']){
		            $aParent=Db::GetRow("select * from rubricator where id='".$aCategory['id_parent']."' and visible=1");
		            if($aParent) Base::$oContent->AddCrumb($aParent['name'],'/rubricator/'.$aParent['url'].'/'.$sAutoUrl);
		        }
// 		        Base::$oContent->AddCrumb($aCategory['name'],'/rubricator/'.$aCategory['url'].'/'.$sAutoUrl);
		    }
		    Base::$oContent->AddCrumb($aSubCategory['name'],'');
		} else {
		    $sUrl = '/rubricator/'.$aCategory['url'].'/';
		    if($aCategory){
		        if($aCategory['id_parent']){
		            $aData = array(
		                'visible'=>1,
		                'where'=>" and r.id='".$aCategory['id_parent']."' "
		            );
		            $aParent=MultiLanguage::GetLocalizedRubricatorRow($aData);
		            if($aParent) Base::$oContent->AddCrumb($aParent['name'],'/rubricator/'.$aParent['url'].'/');
		        }
		        //Base::$oContent->AddCrumb($aCategory['name'],'/rubricator/'.$aCategory['url'].'/');
		    }
	        if($aCategory['level']==2) {
	            //$aLevel1Category=Db::GetRow("select * from rubricator where id='".$aCategory['id_parent']."' and visible=1");
	            Base::$oContent->AddCrumb($aSubCategory['name']);
	        } else {
	            Base::$oContent->AddCrumb($aSubCategory['name']);
	        }
		}
	    
		if(!Base::$aRequest['data']['id_model_detail']) {
    		Content::SetMetaTagsPage('rubricator_subcategory:', array(
    		    'category' => $aSubCategory['name'],
    		    'step' => (Base::$aRequest['step']>0?(Base::$aRequest['step']+1):0),
    		));
		} else {
		    $sOrderBrands=Db::GetOne("select group_concat(title separator ', ') from cat where name in ('".str_replace(",", "','", Base::$aRequest['brand'])."') ");
		    $sSelectedBrandTitle=Db::GetOne("select title from cat where name='".Base::$aRequest['cat']."' ");
		    
		    Content::SetMetaTagsPage('rubricator_category_car:', array(
		        'category' => $aSubCategory['name'],
		        'step' => (Base::$aRequest['step']>0?(Base::$aRequest['step']+1):0),
		        'model' => $sAutoName,
		        'brand' => $sSelectedBrandTitle,
		        'order_brand' => $sOrderBrands
		    ));
		}
		
		//checkChilds
		$aSubCategoryLevel3=Db::GetAll("select * from rubricator where id_parent='".$aSubCategory['id']."' and visible=1 order by sort asc");
		if($aSubCategoryLevel3) {
		    $aSubCategory['childs']=$aSubCategoryLevel3;
		    Base::$tpl->assign('aCategory', $aSubCategory);
		    Base::$tpl->assign('sAutoPreSelected',$sAutoUrl);
		    Base::$sText.=Base::$tpl->fetch('rubricator/category.tpl');
		    
		    if(!Base::$aRequest['data']['id_model_detail']) {
		        Rubricator::GetAllModelsList();
		    } else {
		        if(!Base::$aRequest['parts']) Base::$aRequest['parts']=array();
		        if(!Base::$aRequest['groups']) Base::$aRequest['groups']=array();
		        
		        foreach ($aSubCategoryLevel3 as $aValueC3) {
		            $aPriceGroups[]=$aValueC3['id_price_group'];
		        
		            $aPartsTmp=explode(",", $aValueC3['id_tree']);
		            $aGroupsTmp=explode(",", $aValueC3['id_group']);
		            if(!$aPartsTmp) $aPartsTmp=array();
		            if(!$aGroupsTmp) $aGroupsTmp=array();
		            Base::$aRequest['parts']=array_merge(Base::$aRequest['parts'],$aPartsTmp);
		            Base::$aRequest['groups']=array_merge(Base::$aRequest['groups'],$aGroupsTmp);
		        }
		        
		        $this->GetPart('',$aPriceGroups);
		        Base::$sText.=Base::$tpl->fetch('rubricator/subcategory.tpl');
		    }
		} else {
		    if($aSubCategory)
		    {
		        $aPart=explode(",", $aSubCategory['id_tree']);
		        $aSelectedParts=array();
		        if($aPart) foreach ($aPart as $sValue) $aSelectedParts[]=intval($sValue);
		    
		        $aGroup=explode(",", $aSubCategory['id_group']);
		        $aSelectedGroups=array();
		        if($aGroup) foreach ($aGroup as $sValue) $aSelectedGroups[]=intval($sValue);
		         
		        Base::$aRequest['parts']=$aSelectedParts;
		        Base::$aRequest['groups']=$aSelectedGroups;
		        
		        $aPriceGroups[]=$aSubCategory['id_price_group'];
		    }
		    
		    //check auto selected
		    if(Base::$aRequest['data']['id_model_detail']) {
		        $this->GetPart('',$aPriceGroups);
		        Base::$sText.=Base::$tpl->fetch('rubricator/subcategory.tpl');
		    } else {
		        Base::$sText.=Base::$tpl->fetch('rubricator/subcategory.tpl');
		        //select auto
		        Rubricator::GetAllModelsList();
		    }
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function GetAllModelsList() {
	    $aBrands=Db::Getall("select id,name,title,image from cat where visible='1' and is_brand='1' ");
	    if($aBrands) {
	        $aBrandIdForModel=array();
	        foreach ($aBrands as $sKey => $aCatValue) {
	            $aBrandIdForModel[]=$aCatValue['id'];
	        }
	        
	        $aCatModelGroupTmp=Db::GetAll("select * from cat_model_group where visible=1 and id_make in ('".implode("','", $aBrandIdForModel)."') order by id_make,name");
	        $aCatModelGroupAll=array();
	        if($aCatModelGroupTmp) {
	            foreach ($aCatModelGroupTmp as $aValue) {
	                $aCatModelGroupAll[$aValue['id_make']][]=$aValue;
	            }
	        }
	    }
	    
	    
	    if($aBrands) foreach ($aBrands as $sKey => $aCatValue) {
	        $aBrands[$sKey]['url']=str_replace("//", "/", mb_strtolower("/rubricator/".Base::$aRequest['category']."/c/".$aCatValue['name']."/"));
	        
	        //$sOtherModels=Db::GetAll("select * from cat_model_group where visible=1 and id_make='".$aCatValue['id']."' order by id_make,name");
	        $sOtherModels=$aCatModelGroupAll[$aCatValue['id']];
	        if ($sOtherModels) foreach ($sOtherModels as $aValue){
	            $aDataModel=array(
	                'name'=>$aValue['name'],
	                'url'=>str_replace("//", "/", mb_strtolower("/rubricator/".Base::$aRequest['category']."/c/".$aCatValue['name']."_".$aValue['code']."/"))
	            );
	            $aBrands[$sKey]['models'][]=$aDataModel;
	        }
	    }
	    
	    Base::$tpl->assign('aAllModels',$aBrands);
	    Base::$sText.=Base::$tpl->fetch('rubricator/all_models.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function GetMainMenu()
	{
	    $aData = array(
	        'visible'=>1,
	        'is_menu_visible'=>1,
	    );
	    $aRubric=MultiLanguage::GetLocalizedRubricator($aData, '', true);
		
		$aMenu=array();
		if($aRubric) foreach ($aRubric as $aValue) {
			if($aValue['level']==1){
			    $aValue['is_rubricator']=1;
			    $aMenu[$aValue['id']]=$aValue;
			    continue;
			}
			if($aValue['level']==2) $aMenuL2[$aValue['id']]=$aValue;
			else continue;
		}
		
		if($aRubric) foreach ($aRubric as $aValue) {
            if(array_key_exists($aValue['id_parent'], $aMenuL2)){
                if($aValue && count($aValue)>0) $aMenuL2[$aValue['id_parent']]['childs'][]=$aValue;
            }
		}
		
		if($aMenuL2) foreach ($aMenuL2 as $aValue) {
		    if(array_key_exists($aValue['id_parent'], $aMenu)){
		        Rubricator::FilterCategoryByAuto($aValue['childs']);
		        if($aValue['childs']) {
		            foreach ($aValue['childs'] as $schlK => $schlV) {
		                if($schlV['url']=='0') unset($aValue['childs'][$schlK]);
		            }
		        }
		        
		        $aMenu[$aValue['id_parent']]['childs'][]=$aValue;
		    }
		}
		
		if($aMenu) foreach ($aMenu as $sKey => $aValue) {
		    if(!$aValue['url']) unset($aMenu[$sKey]);
		}
		
		// Sort by field sort
		// AT-1236
		usort($aMenu, function ($a, $b)
	    	{
		        if ($a['sort'] == $b['sort']) {
		            return 0;
		        }
		        return ($a['sort'] < $b['sort']) ? -1 : 1;
	    	});

		// --------------------
		
		return $aMenu;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetMain()
	{
		$aRubric=Db::GetAssoc(str_replace("select","select r.id as key_, ",Base::GetSql("Rubricator",array(
		    'visible'=>1,
		    'is_mainpage'=>1
		))));
	
		$aMenu=array();
		if($aRubric) foreach ($aRubric as $aValue) {
			if($aValue['level']==1) $aMenu[$aValue['id']]=$aValue;
			else continue;
		}
	
		if($aRubric) foreach ($aRubric as $aValue) {
			if($aValue['level']==2) {
				if(count($aMenu[$aValue['id_parent']]['childs'])>2) {
					$aMenu[$aValue['id_parent']]['more']=1;
					continue;
				}
				if(array_key_exists($aValue['id_parent'], $aMenu) && count($aMenu[$aValue['id_parent']]['childs'])<7)
				    $aMenu[$aValue['id_parent']]['childs'][]=$aValue;
			}
			else continue;
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
	
		return $aMenu;
	}
	//-----------------------------------------------------------------------------------------------
	public function SetBrandPriceOrderUrl($sUrlParamsInclude)
	{
	    $sBaseUrl = substr($_SERVER['REQUEST_URI'],0, strpos($_SERVER['REQUEST_URI'], '?'));
	    if($sBaseUrl=='/' && Base::$aRequest['category']) {
	        $sBaseUrl="/rubricator/".Base::$aRequest['category']."/";
	        $sBaseUrl=mb_strtolower($sBaseUrl);
	    }
	    if(!$sBaseUrl) {
	        $sBaseUrl=mb_strtolower($_SERVER['REQUEST_URI']);
	    }
	    if(Base::$aRequest['order_brand']) {
	        $sBaseUrl=substr($sBaseUrl, 0, strpos($sBaseUrl, Base::$aRequest['order_brand']));
	    }
	     
	    if(Base::$aRequest['data']['id_model_detail']) {
	        $sSelectedCarUrl=Content::CreateSeoUrl('catalog_assemblage_view',array(
	            'data[id_make]'=>Base::$aRequest['data']['id_make'],
	            'data[id_model]'=>Base::$aRequest['data']['id_model'],
	            'data[id_model_detail]'=>Base::$aRequest['data']['id_model_detail'],
	        ));
	        $sSelectedCarUrl=str_replace('/cars/', 'c/', $sSelectedCarUrl);
	        $sMake=Db::GetOne("select name from cat where id='".(Base::$aRequest['data']['id_make']?Base::$aRequest['data']['id_make']:$_COOKIE['id_make'])."' ");
	        $sSelectedCarUrl=str_replace("/".$sMake."/", '/', $sSelectedCarUrl);
	        
	        $sBaseUrl=str_replace($sSelectedCarUrl, "", $sBaseUrl);
	    } else {
	        $sSelectedCarUrl="";
	    }
	     
	    if(substr($sBaseUrl, strlen($sBaseUrl)-1)!='/') {
	        $sBaseUrl.="/";
	    }
	     
// 	    $sBrandUrl = $sBaseUrl;
// 	    $sPriceUrl = $sBaseUrl.'?';
// 	    if(Base::$aRequest['order_brand']) {
// 	        $sPriceUrl = $sBaseUrl.Base::$aRequest['order_brand']."/".$sSelectedCarUrl.'?';
// 	    } else {
// 	        $sPriceUrl = $sBaseUrl.$sSelectedCarUrl.'?';
// 	    }
	    
	    
	    
	    $sPriceUrl=$sUrlParamsInclude;
	    
	    
	
// 	    Base::$tpl->assign('sSelectedCarUrl', $sSelectedCarUrl);
// 	    Base::$tpl->assign('sRubricatorUrlForPrice', $sPriceUrl);
	    //Base::$tpl->assign('sRubricatorUrlForBrand', $sBrandUrl);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetBrandsForFilter($sType,$source,$iIdPriceGroup,$aItemCodesUnique)
	{
 	    $aBrandsFilter=array();
	     
	    if($sType=='sql') {
	        //select brand filter
	        $sSqlBrandsFilter="select lower(c.name),c.title ".substr($source->sSql, strpos($source->sSql, "from"));
	        $sSqlBrandsFilter=substr($sSqlBrandsFilter, 0, strpos($sSqlBrandsFilter, "order"))." order by c.title";
	        //remove brand selected
	        $iPrefSelectedPos=strpos($sSqlBrandsFilter, "and p.pref='");
	        if($iPrefSelectedPos!==false) {
	            $sRemoveBrand=substr($sSqlBrandsFilter, $iPrefSelectedPos);
	            $sRemoveBrand=substr($sRemoveBrand, 0 ,strpos($sRemoveBrand, "'",12)+1);
	
	            $sSqlBrandsFilter=str_replace($sRemoveBrand, '', $sSqlBrandsFilter);
	        }
	        $aBrandsFilter=Db::GetAssoc($sSqlBrandsFilter);
	    } else {
	        if($source) $aBrandsFilter=Db::GetAssoc("select lower(c.name), c.title
			        from cat as c
			        inner join price as p on p.pref=c.pref and p.price > 0 and c.visible='1' /*and p.stock >0*/
			        inner join user_provider as up on up.id_user=p.id_provider
			        inner join user as u on up.id_user=u.id and u.visible=1
			        where p.item_code in ('".implode("','", $source)."')
			        order by c.title");
	    }
	    Base::$tpl->assign('aBrandsFilter', array("0"=>"не выбрано")+$aBrandsFilter);
	    
	    if($aBrandsFilter) {
	        $aPriceGroupBrand=array();
	        foreach ($aBrandsFilter as $sName => $sTitle) {
	            $aPriceGroupBrand[]=array(
	                'c_name'=>$sName,
	                'c_title'=>$sTitle
	            );
	        }
	        
	        //count products
	        $aBrandsForCount=array();
	        if($aPriceGroupBrand) foreach ($aPriceGroupBrand as $sBrandKey => $aBrandValue) {
	            $aTmpBrand=explode(",", Base::$aRequest['brand']);
	        
	            if(in_array($aBrandValue['c_name'], $aTmpBrand)) {
	                $aPriceGroupBrand[$sBrandKey]['checked']=1;
	            }
	        
	            if(Base::$aRequest['brand']) {
	                //$aTmpBrand[]=$aBrandValue['c_name'];
	                $sBrandWhere=implode("','", $aTmpBrand);
	                //." and c.name in('".$sBrandWhere."')"
	            } else {
	                $sBrandWhere=$aBrandValue['c_name'];
	            }
	            
	            $aParamsForFiterArray=array(
	                "where"=>" and p.price>0 ".$this->sWhereParams." and c.name='".$aBrandValue['c_name']."' ",
	                "pgpf"=>1,
	                'aItemCode'=>$aItemCodesUnique
	            );
	             
	            if(Base::$aRequest['data']['id_model_detail']) {
	                //selected auto by rubricator
	            } else {
	                if($iIdPriceGroup) {
    	                //auto not selected
    	                if(!is_array($iIdPriceGroup)) {
    	                    $aParamsForFiterArray['id_price_group']=$iIdPriceGroup;
    	                } else {
    	                    $aParamsForFiterArray['childs']=$iIdPriceGroup;
    	                }
	                }
	            }
	            $sFullSql=Base::GetSql("Catalog/PriceForCount",$aParamsForFiterArray);
	            
	            $aPriceGroupBrand[$sBrandKey]['count']=Db::GetOne($sFullSql);
	        }
	        unset($aTmpBrand);
	        unset($sBrandKey);
	        unset($aBrandValue);
	        
	        //--------------------------------------------------------------
	        //add brand sort $aPriceGroupBrand
	        function cmp($a, $b)
	        {
	            if ($a['c_title'] == $b['c_title']) {
	                return 0;
	            }
	            return ($a['c_title'] < $b['c_title']) ? -1 : 1;
	        }
	        if($aPriceGroupBrand) usort($aPriceGroupBrand, "cmp");
	        //--------------------------------------------------------------
	    }
	    return $aPriceGroupBrand;
	}
	//-----------------------------------------------------------------------------------------------
	public function GetRubricForModelGroup($aModelGroup,$sCat) {
	    $aTypes=TecdocDb::GetModelDetails(array(
	        'id_make'=>$aModelGroup['id_make'],
	        'id_model'=>Base::$aRequest['data']['id_model']?Base::$aRequest['data']['id_model']:$aModelGroup['id_models']
	    ));
	    
	    if(!$aTypes) return;
	    $aModelDetails=array();
	    foreach ($aTypes as $aValue) {
	        $aModelDetails[]=$aValue['ID_src'];
	    }
	    
	    $aTree=TecdocDb::GetTree(array(
	        'id_model_detail'=>implode("','", $aModelDetails)
	    ));
	    
	    if ($aTree) foreach ($aTree as $sKey => $aValue) {
	        if($aValue['id']=='13771' || $aValue['str_id_parent']=='13771') {
	            unset($aTree[$sKey]);
	            continue;
	        }
	        if ($aValue['str_level']==2) $aIdIcon[]=$aValue['id'];
	        $aTreeAssoc[$aValue['id']]=$aValue;
	    }
	    
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
	    
	    if(Base::$aRequest['have_model']) {
	        $sCarUrl=Content::CreateSeoUrl('catalog_detail_model_view',array(
				'cat'=>Base::$aRequest['cat'],
				'data[id_make]'=>Base::$aRequest['data']['id_make'],
				'data[id_model]'=>Base::$aRequest['data']['id_model'],
			))."/";
	    } else {
	        $sCarUrl="/cars/".$sCat."/".$aModelGroup['code']."/";
	    }

	    Base::$tpl->assign('aMainRubric', $aMenu);
	    Base::$tpl->assign('sCarUrl', $sCarUrl);
	    return Base::$tpl->fetch('rubricator/rubrics.tpl');
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckSelectedAuto() {
	    if($_COOKIE['id_model_detail'] && $_COOKIE['id_model'] && $_COOKIE['id_make']) {
	        $sSelectedCarUrl=Content::CreateSeoUrl('catalog_assemblage_view',array(
	            'data[id_make]'=>$_COOKIE['id_make'],
	            'data[id_model]'=>$_COOKIE['id_model'],
	            'data[id_model_detail]'=>$_COOKIE['id_model_detail'],
	        ));
	        $sSelectedCarUrl=str_replace('/cars/', '', $sSelectedCarUrl);
	         
	        if($sSelectedCarUrl && !Base::$aRequest['step'] && Base::$aRequest['action']!='price_group') {
	            if(strpos($_SERVER['REQUEST_URI'], $sSelectedCarUrl)===false) {
	                //redirect to selected car url
	                $sRubricatorUrl="/rubricator/";
	                if(Base::$aRequest['category']) $sRubricatorUrl.=Base::$aRequest['category']."/";
	                $sRubricatorUrl.=$sSelectedCarUrl;
	                 
	                if(Base::$aRequest['category']) {
// 	                    if(!Base::$aRequest['xajax']) Base::Redirect($sRubricatorUrl);
	                }
	                if(!Base::$aRequest['category']) {
	                    // to do view assemblage
	                }
	            }
	        }
	        $sMake=Db::GetOne("select name from cat where id='".(Base::$aRequest['data']['id_make']?Base::$aRequest['data']['id_make']:$_COOKIE['id_make'])."' ");
	        $sSelectedCarUrlRubricator=str_ireplace($sMake."/", 'c/', $sSelectedCarUrl);
	        
	        Base::$tpl->assign('sSelectedCarUrl',$sSelectedCarUrl);
	        Base::$tpl->assign('sSelectedCarUrlRubricator',$sSelectedCarUrlRubricator);
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function CheckSelectedAutoName() {
	    if ($_COOKIE['id_make'] && $_COOKIE['id_model'] && $_COOKIE['id_model_detail']) {
	        $aSelectedAuto=array(
	            'id_model_detail'=>$_COOKIE['id_model_detail'],
	            'id_model'=>$_COOKIE['id_model'],
	            'id_make'=>$_COOKIE['id_make']
	        );
	    
	        $aMakeAssoc=Base::$tpl->get_template_vars('aMakeName');
	        $aModelAssoc=Base::$tpl->get_template_vars('aModel');
	        $aModelDetailAssoc=Base::$tpl->get_template_vars('aModelDetail');
	        
	        if(count($aMakeAssoc)==1 || !$aMakeAssoc) {
	            $aMakeAssoc=Db::GetAssoc("
        		   select c.id, c.title 
        	       from cat as c
        	       where 1=1 and c.is_main='1' and c.is_brand='1' and c.visible='1'
        		");
	        }
	        if(count($aModelAssoc)==1 || !$aModelAssoc) {
	            $aModelAssoc=TecdocDb::GetModelAssoc($aSelectedAuto);
	        }
	        if(count($aModelDetailAssoc)==1 || !$aModelDetailAssoc) {
	            $aModelDetailAssoc=TecdocDb::GetModelDetailAssoc($aSelectedAuto);
	        }
	         
	        //$aAuto=TecdocDb::GetSelectCar(array('id_model_detail'=>Base::$aRequest['data']['id_model_detail']));
	        //$sAutoName=$aAuto['name'];
// 	        $sAutoName=$aMakeAssoc[$aSelectedAuto['id_make']]." ".$aModelAssoc[$aSelectedAuto['id_model']]." ".$aModelDetailAssoc[$aSelectedAuto['id_model_detail']];
		$sAutoName=$aMakeAssoc[$aSelectedAuto['id_make']]." ".$aModelAssoc[$aSelectedAuto['id_model']];
	        Base::$tpl->assign('sAutoName',$sAutoName);
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function ClearAutoUrl() {
	    //output in smarty
	    $sClearAutoUrl='';
	    if(Base::$aRequest['category']) {
	        //check wrong subcategory
	        $bExists=Db::GetOne("select id from rubricator where url='".Base::$aRequest['category']."' ");
	        if($bExists) {
	            $sClearAutoUrl="/rubricator/".Base::$aRequest['category']."/".Base::$aRequest['category']."?clear_auto=1";
	        } else {
	            $sClearAutoUrl="/rubricator/".Base::$aRequest['category']."?clear_auto=1";
	        }
	    } elseif(Base::$aRequest['category']) {
	        $sClearAutoUrl="/rubricator/".Base::$aRequest['category']."?clear_auto=1";
	    } elseif(!Base::$aRequest['category'] && !Base::$aRequest['category'] && Base::$aRequest['action']=='rubricator') {
	        $sClearAutoUrl="/rubricator?clear_auto=1";
	    } else {
	        $sClearAutoUrl="/pages/catalog/?clear_auto=1";
	    }
	    
	    Base::$tpl->assign('sClearAutoUrl',$sClearAutoUrl);
	}
	//-----------------------------------------------------------------------------------------------
	public function CategoryModelGroupView() {
	    //check correct link
        $iIdModelGroup=Db::GetOne("select id from cat_model_group where code='".Base::$aRequest['model_group']."' ");
        $iIdCat=Db::GetOne("select id from cat where name='".Base::$aRequest['cat']."' ");

	    if(Base::$aRequest['category']){
    	    if($this->aCategory && $this->aCategory['url']==Base::$aRequest['category']){
    	        $aCategory=$this->aCategory;
    	    }else{
    	        $aData = array(
    	            'visible'=>1,
    	            'where'=>" and r.url='".Base::$aRequest['category']."' "
    	        );
    	        $aCategory=MultiLanguage::GetLocalizedRubricatorRow($aData);
    	    }
	         
	        //404
	        if(!$aCategory) {
	            Form::Error404(false);
	            return;
	        }
	
	        if (Base::$aRequest['cat']) {
	            Base::$aRequest['data']['id_make']=Db::GetOne("select id from cat where name='".Base::$aRequest['cat']."'");
	        }
	        Base::$aRequest['car_select']['brand'] = Base::$aRequest['cat'];
	        Base::$aRequest['car_select']['model'] = Base::$aRequest['model_group'];
	        $sSizeStr = strlen(Base::$aRequest['model_group']);
	        if(Base::$aRequest['model_group']{$sSizeStr-1} == '_')
	            Base::$aRequest['car_select']['model'] = substr(Base::$aRequest['model_group'],0 , $sSizeStr-1);
	        else
	            Base::$aRequest['car_select']['model'] = Base::$aRequest['model_group'];
	        $aSelectedModelGroup=Db::GetRow("select * from cat_model_group where code='".Base::$aRequest['model_group']."' and id_make='".Base::$aRequest['data']['id_make']."' ");
	        if(!$aSelectedModelGroup && Base::$aRequest['data']['id_model']) {
	            $aSelectedModelGroup=Db::GetRow("select * from cat_model_group where FIND_IN_SET('".Base::$aRequest['data']['id_model']."', id_models) ");
	            Base::$aRequest['code'] = $aSelectedModelGroup['code'];
	
	            if(Base::$aRequest['cat'])
	                Base::$aRequest['car_select']['brand'] = Base::$aRequest['cat'];
	            if($aSelectedModelGroup['code'])
	                Base::$aRequest['car_select']['model'] = $aSelectedModelGroup['name'];
	        }
	        
	        //pre selected model begin
	        $aModelNew=array(
	            $aSelectedModelGroup['id_models']=>$aSelectedModelGroup['name']
	        );
	        Base::$tpl->assign('aModel',$aModelNew);
	        $_REQUEST['data']['id_model']=$aSelectedModelGroup['id_models'];
	        Base::$aRequest['data']['id_model']=$aSelectedModelGroup['id_models'];
	        $this->GetModelDetails();
	        //pre selected model end
	
	        Base::$aRequest['car_select']['name_model_group'] = $aSelectedModelGroup['name'];
	
	        if($aCategory){
	            $aData = array(
	                'visible'=>1,
	                'where'=>" and r.id_parent='".$aCategory['id']."' "
	            );
	            $aCategory['childs'] = MultiLanguage::GetLocalizedRubricator($aData, ' order by sort asc');
	            if($aCategory['childs']) {
	                foreach ($aCategory['childs'] as $sKeyChild => $aValueChild) {
	                    $aData = array(
	                        'visible'=>1,
	                        'where'=>" and r.id_parent='".$aValueChild['id']."' "
	                    );
	                    $aCategory['childs'][$sKeyChild]['childs'] = MultiLanguage::GetLocalizedRubricator($aData, ' order by sort asc');
	                    Rubricator::FilterCategoryByAuto($aCategory['childs'][$sKeyChild]['childs']);
	                }
	            }
	        }
	        if($aCategory['childs'] && Base::$aRequest['data']['id_model_detail']) {
	            $aTree=TecdocDb::GetTree(Base::$aRequest['data']);
	            if ($aTree) foreach ($aTree as $sKey => $aValue) {
	                if($aValue['id']=='13771' || $aValue['str_id_parent']=='13771') {
	                    unset($aTree[$sKey]);
	                    continue;
	                }
	                $aTreeAssoc[$aValue['id']]=$aValue;
	            }
	            $aAllowedTreeNodes=array_keys($aTreeAssoc);
	             
	            foreach ($aCategory['childs'] as $sChildKey => $aValue) {
	                //filter by auto
	                $bAllow=false;
	                $aRubricTree=explode(",", $aValue['id_tree']);
	                if($aRubricTree) foreach ($aRubricTree as $iTreeNode) {
	                    if(in_array($iTreeNode, $aAllowedTreeNodes)) {
	                        $bAllow=true;
	                        break;
	                    }
	                }
	
	                if(!$bAllow) {
	                    unset($aCategory['childs'][$sChildKey]);
	                }
	            }
	        }
	        Base::$tpl->assign('aCategory', $aCategory);
	
	        $sSelectedBrandTitle=Db::GetOne("select title from cat where name='".Base::$aRequest['cat']."' ");
	        $sCarSelected=" ".$sSelectedBrandTitle." ".$aSelectedModelGroup['name'];
	        Base::$tpl->Assign('sH1',$aCategory['name'].$sCarSelected);
	        Base::$tpl->Assign('sSelectedBrandTitle',$sSelectedBrandTitle);
	        Base::$tpl->Assign('sSelectedCategory',$aCategory['name']);
	
// 	        Content::showCarSelect();
	
	        Base::$aRequest['parts']=array();
	        Base::$aRequest['groups']=array();
	        $aPriceGroups=array();
	        if($aCategory['childs']) {
	            foreach ($aCategory['childs'] as $aValue) {
	                $aPriceGroups[]=$aValue['id_price_group'];
	                 
	                $aPartsTmp=explode(",", $aValue['id_tree']);
	                $aGroupsTmp=explode(",", $aValue['id_group']);
	                if(!$aPartsTmp) $aPartsTmp=array();
	                if(!$aGroupsTmp) $aGroupsTmp=array();
	                Base::$aRequest['parts']=array_merge(Base::$aRequest['parts'],$aPartsTmp);
	                Base::$aRequest['groups']=array_merge(Base::$aRequest['groups'],$aGroupsTmp);
	            }
	            
	            foreach ($aCategory['childs'] as $sKeyChilds => $aValueChilds) {
	                $aCategoryChilds=Db::GetAll("select * from rubricator where id_parent='".$aValueChilds['id']."' and visible=1 order by sort asc");
	                foreach ($aCategoryChilds as $aVal) {
	                    $aPriceGroups[]=$aValue['id_price_group'];
	                
	                    $aPartsTmp=explode(",", $aVal['id_tree']);
	                    $aGroupsTmp=explode(",", $aVal['id_group']);
	                    if(!$aPartsTmp) $aPartsTmp=array();
	                    if(!$aGroupsTmp) $aGroupsTmp=array();
	                    Base::$aRequest['parts']=array_merge(Base::$aRequest['parts'],$aPartsTmp);
	                    Base::$aRequest['groups']=array_merge(Base::$aRequest['groups'],$aGroupsTmp);
	                }
	            }
	            
	        }
	        if(!Base::$aRequest['data'] && Base::$aRequest['car_select']) {
	            Base::$aRequest['data']=Base::$aRequest['car_select'];
	        }
	
	        if($aSelectedModelGroup && !Base::$aRequest['data']['id_model']) {
	            $aTypes=TecdocDb::GetModelDetails(array(
	                'id_make'=>$aSelectedModelGroup['id_make'],
	                'id_model'=>$aSelectedModelGroup['id_models']
	            ));
	        } else {
	            $aTypes=TecdocDb::GetModelDetails(array(
	                'id_make'=>Base::$aRequest['data']['id_make'],
	                'id_model'=>Base::$aRequest['data']['id_model']
	            ));
	        }
	         
	        if(!$aTypes) return;
	        $aModelDetails=array();
	        foreach ($aTypes as $aValue) {
	            $aModelDetails[]=$aValue['ID_src'];
	        }
	        Base::$aRequest['data']['id_model_detail']=implode(",", $aModelDetails);
	
	        Base::$tpl->assign('sAutoPreSelected',"c/".Base::$aRequest['cat']."_".Base::$aRequest['model_group']."/");
	        Base::$tpl->assign('sSelectedCarUrlRubricator',"c/".Base::$aRequest['cat']."_".Base::$aRequest['model_group']."/");
	        
	        
	        
	        $this->GetPart('',$aPriceGroups);
	        if(!$aCategory['childs']) {
	        Base::$sText.=Base::$tpl->fetch('rubricator/subcategory.tpl');
	        }
	
	        $aCatValue=Db::GetRow("select * from cat where name='".Base::$aRequest['cat']."' ");
	        $sOtherModels=Db::GetAll($sql = "select * from cat_model_group where visible=1 and id_make='".$aCatValue['id']."' and code not like '".$aSelectedModelGroup['code']."' order by id_make,name");
	        if ($sOtherModels) foreach ($sOtherModels as $sKey => $aValue){
	            $sOtherModels[$sKey]['brand']=$aCatValue['title'];
	            $sAutoCode="c/".$aCatValue['name']."_".$sOtherModels[$sKey]['code']."/";
	        $sAutoCode=mb_strtolower($sAutoCode);
                $sOtherModels[$sKey]['seourl']="/rubricator/".Base::$aRequest['category']."/".$sAutoCode;
	        }
	        
	        Base::$oContent->AddCrumb($aCatValue['title'], "/rubricator/c/".$aCatValue['name']."/" );
	        Base::$oContent->AddCrumb($aSelectedModelGroup['name'], "/rubricator/c/".$aCatValue['name']."_".$aSelectedModelGroup['code']."/" );
	        Base::$oContent->AddCrumb($aCategory['name']);
	        
	        Base::$tpl->Assign('bSelectedModel',1);
	
	        Base::$tpl->assign('sAutoPreSelected',"c/".$aCatValue['name']."_".$aSelectedModelGroup['code']."/");
	        Base::$tpl->assign('sSelectedCarUrlRubricator',"c/".$aCatValue['name']."_".$aSelectedModelGroup['code']."/");
	        Base::$tpl->assign('sOtherModels',$sOtherModels);
	        Base::$sText.=Base::$tpl->fetch('rubricator/category.tpl');
	        Base::$sText.=Base::$tpl->fetch('rubricator/other_models.tpl');
	        
	        $sOrderBrands=Db::GetOne("select group_concat(title separator ', ') from cat where name in ('".str_replace(",", "','", Base::$aRequest['brand'])."') ");
	        Content::SetMetaTagsPage('category_model_group_view:', array(
	            'brand'=> $sSelectedBrandTitle,
	            'category' => $aCategory['name'],
	            'model' => $aSelectedModelGroup['name'],
	            'step' => (Base::$aRequest['step']>0?(Base::$aRequest['step']+1):0),
	            'order_brand' => $sOrderBrands
	        ));
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public function SubcategoryModelGroupView() {
	    if($this->aCategory && $this->aCategory['url']==Base::$aRequest['category']){
	        $aSubCategory=$this->aCategory;
	    }else{
	        $aData = array(
	            'visible'=>1,
	            'where'=>" and r.url='".Base::$aRequest['category']."' "
	        );
	        $aSubCategory=MultiLanguage::GetLocalizedRubricatorRow($aData);
	    }
	    $aData = array(
	        'visible'=>1,
	        'where'=>" and r.id='".$aSubCategory['id_parent']."' "
	    );
	    $aCategory=MultiLanguage::GetLocalizedRubricatorRow($aData);
	
	    if (Base::$aRequest['cat']) {
	        Base::$aRequest['data']['id_make']=Db::GetOne("select id from cat where name='".Base::$aRequest['cat']."'");
	    }
	    Base::$aRequest['car_select']['brand'] = Base::$aRequest['cat'];
	    Base::$aRequest['car_select']['model'] = Base::$aRequest['model_group'];
	    $sSizeStr = strlen(Base::$aRequest['model_group']);
	    if(Base::$aRequest['model_group']{$sSizeStr-1} == '_') {
	        Base::$aRequest['car_select']['model'] = substr(Base::$aRequest['model_group'],0 , $sSizeStr-1);
	    } else {
	        Base::$aRequest['car_select']['model'] = Base::$aRequest['model_group'];
	    }
	    $aSelectedModelGroup=Db::GetRow("select * from cat_model_group where code='".Base::$aRequest['model_group']."' and id_make='".Base::$aRequest['data']['id_make']."' ");
	    if(!$aSelectedModelGroup && Base::$aRequest['data']['id_model']) {
	        $aSelectedModelGroup=Db::GetRow("select * from cat_model_group where FIND_IN_SET('".Base::$aRequest['data']['id_model']."', id_models) ");
	        Base::$aRequest['code'] = $aSelectedModelGroup['code'];
	
	        if(Base::$aRequest['cat'])
	            Base::$aRequest['car_select']['brand'] = Base::$aRequest['cat'];
	        if($aSelectedModelGroup['code'])
	            Base::$aRequest['car_select']['model'] = $aSelectedModelGroup['name'];
	    }
	    
	    //pre selected model begin
	    $aModelNew=array(
	        $aSelectedModelGroup['id_models']=>$aSelectedModelGroup['name']
	    );
	    Base::$tpl->assign('aModel',$aModelNew);
	    $_REQUEST['data']['id_model']=$aSelectedModelGroup['id_models'];
	    Base::$aRequest['data']['id_model']=$aSelectedModelGroup['id_models'];
	    $this->GetModelDetails();
	    //pre selected model end
	
	    //filter begin
	    if(Base::$aRequest['brand']) {
	        Base::$aRequest['order_brand']=Base::$aRequest['brand'];
	    }
	
	    Base::$aRequest['car_select']['name_model_group'] = $aSelectedModelGroup['name'];
	
	    if($aSelectedModelGroup && !Base::$aRequest['data']['id_model']) {
	        $aTypes=TecdocDb::GetModelDetails(array(
	            'id_make'=>$aSelectedModelGroup['id_make'],
	            'id_model'=>$aSelectedModelGroup['id_models']
	        ));
	    } else {
	        $aTypes=TecdocDb::GetModelDetails(array(
	            'id_make'=>Base::$aRequest['data']['id_make'],
	            'id_model'=>Base::$aRequest['data']['id_model']
	        ));
	    }
	     
	    if(!$aTypes) return;
	    $aModelDetails=array();
	    foreach ($aTypes as $aValue) {
	        $aModelDetails[]=$aValue['ID_src'];
	    }
	    Base::$aRequest['data']['id_model_detail']=implode(",", $aModelDetails);
	     
	    if($aSubCategory)
	    {
	        $aPart=explode(",", $aSubCategory['id_tree']);
	        $aSelectedParts=array();
	        if($aPart) foreach ($aPart as $sValue) $aSelectedParts[]=intval($sValue);
	
	        $aGroup=explode(",", $aSubCategory['id_group']);
	        $aSelectedGroups=array();
	        if($aGroup) foreach ($aGroup as $sValue) $aSelectedGroups[]=intval($sValue);
	
	        Base::$aRequest['parts']=$aSelectedParts;
	        Base::$aRequest['groups']=$aSelectedGroups;
	    }
	
	    $aSubChilds=Db::GetAll("select * from rubricator where id_parent='".$aSubCategory['id']."' and visible=1");
	    //checkChilds
	    if($aSubChilds) {
	        $aSubCategory['childs']=$aSubChilds;
	        Base::$tpl->assign('aCategory', $aSubCategory);
	        Base::$tpl->assign('sAutoPreSelected',"c/".Base::$aRequest['cat']."_".$aSelectedModelGroup['code']."/");
	        Base::$tpl->assign('sSelectedCarUrlRubricator',"c/".Base::$aRequest['cat']."_".$aSelectedModelGroup['code']);
	        Base::$sText.=Base::$tpl->fetch('rubricator/category.tpl');
	    }
	    if($aSubChilds) {
	        foreach ($aSubChilds as $aChildValue) {
	            $aPart=explode(",", $aChildValue['id_tree']);
	            $aSelectedParts=array();
	            if($aPart) foreach ($aPart as $sValue) $aSelectedParts[]=intval($sValue);
	
	            $aGroup=explode(",", $aChildValue['id_group']);
	            $aSelectedGroups=array();
	            if($aGroup) foreach ($aGroup as $sValue) $aSelectedGroups[]=intval($sValue);
	
	            if(!$aSelectedParts) $aSelectedParts=array();
	            if(!$aSelectedGroups) $aSelectedGroups=array();
	            if(!Base::$aRequest['parts']) Base::$aRequest['parts']=array();
	            if(!Base::$aRequest['groups']) Base::$aRequest['groups']=array();
	
	            Base::$aRequest['parts']=array_merge(Base::$aRequest['parts'],$aSelectedParts);
	            Base::$aRequest['groups']=array_merge(Base::$aRequest['groups'],$aSelectedGroups);
	        }
	    }
	
	    $this->GetPart('',$aPriceGroups);
	
	    $sSelectedBrandTitle=Db::GetOne("select title from cat where name='".Base::$aRequest['cat']."' ");
	    $sCarSelected=" ".$sSelectedBrandTitle." ".$aSelectedModelGroup['name'];
	    Base::$tpl->Assign('sH1',$aSubCategory['name'].$sCarSelected);
	    Base::$tpl->Assign('sSelectedBrandTitle',$sSelectedBrandTitle);
	    Base::$tpl->Assign('sSelectedSubcategory',$aSubCategory['name']);
	    
	    Base::$tpl->Assign('bSelectedModel',1);
	
// 	    Content::showCarSelect();
	
	    $aCatValue=Db::GetRow("select * from cat where name='".Base::$aRequest['cat']."' ");
	    $sOtherModels=Db::GetAll($sql = "select * from cat_model_group where visible=1 and id_make='".$aCatValue['id']."' and code not like '".$aSelectedModelGroup['code']."' order by id_make,name");
	    if ($sOtherModels) foreach ($sOtherModels as $sKey => $aValue){
	        $sOtherModels[$sKey]['brand']=$aCatValue['title'];
	        $sAutoCode="c/".$aCatValue['name']."_".$sOtherModels[$sKey]['code']."/";
	        $sAutoCode=mb_strtolower($sAutoCode);
            $sOtherModels[$sKey]['seourl']="/rubricator/".Base::$aRequest['category']."/".$sAutoCode;
	    }
	    
	    //crumbs
	    $this->sAutoUrl=mb_strtolower("c/".$aCatValue['name']."_".$aSelectedModelGroup['code']."/");
	    Base::$oContent->AddCrumb($aCatValue['title'], "/rubricator/c/".$aCatValue['name']."/" );
	    Base::$oContent->AddCrumb($aSelectedModelGroup['name'], "/rubricator/c/".$aCatValue['name']."_".$aSelectedModelGroup['code']."/" );
	    if($aCategory){
	        if($aCategory['id_parent']){
	            $aParent=Db::GetRow("select * from rubricator where id='".$aCategory['id_parent']."' and visible=1");
	            if($aParent) Base::$oContent->AddCrumb($aParent['name'],'/rubricator/'.$aParent['url'].'/'.$this->sAutoUrl);
	        }
	    }
	    Base::$oContent->AddCrumb($aSubCategory['name']);

	    Base::$tpl->assign('sAutoPreSelected',"c/".$aCatValue['name']."_".$aSelectedModelGroup['code']."/");
	    Base::$tpl->assign('sSelectedCarUrlRubricator',"c/".$aCatValue['name']."_".$aSelectedModelGroup['code']."/");
	    Base::$tpl->assign('sOtherModels',$sOtherModels);
	    Base::$sText.=Base::$tpl->fetch('rubricator/subcategory.tpl');
	    Base::$sText.=Base::$tpl->fetch('rubricator/other_models.tpl');	
	    
	    $sOrderBrands=Db::GetOne("select group_concat(title separator ', ') from cat where name in ('".str_replace(",", "','", Base::$aRequest['order_brand'])."') ");
	    
	    Content::SetMetaTagsPage('category_model_group_view:', array(
	        'category' => $aSubCategory['name'],
	        'step' => (Base::$aRequest['step']>0?(Base::$aRequest['step']+1):0),
	        'model' => $aSelectedModelGroup['name'],
	        'brand' => $sSelectedBrandTitle,
	        'order_brand' => $sOrderBrands
	    ));
	}
	//-----------------------------------------------------------------------------------------------
	public function CategoryBrandView() {
	    Base::$aRequest['car_select']['brand'] = Base::$aRequest['cat'];
	    $aCategory=Db::GetRow("select * from rubricator where url='".Base::$aRequest['category']."' and visible=1");
	
	    if($aCategory) $aCategory['childs']=Db::GetAll("select * from rubricator where id_parent='".$aCategory['id']."' and visible=1 order by sort asc");
	    if($aCategory['childs'] && Base::$aRequest['data']['id_model_detail']) {
	        $aTree=TecdocDb::GetTree(Base::$aRequest['data']);
	        if ($aTree) foreach ($aTree as $sKey => $aValue) {
	            if($aValue['id']=='13771' || $aValue['str_id_parent']=='13771') {
	                unset($aTree[$sKey]);
	                continue;
	            }
	            $aTreeAssoc[$aValue['id']]=$aValue;
	        }
	        $aAllowedTreeNodes=array_keys($aTreeAssoc);
	         
	        foreach ($aCategory['childs'] as $sChildKey => $aValue) {
	            //filter by auto
	            $bAllow=false;
	            $aRubricTree=explode(",", $aValue['id_tree']);
	            if($aRubricTree) foreach ($aRubricTree as $iTreeNode) {
	                if(in_array($iTreeNode, $aAllowedTreeNodes)) {
	                    $bAllow=true;
	                    break;
	                }
	            }
	
	            if(!$bAllow) {
	                unset($aCategory['childs'][$sChildKey]);
	            }
	        }
	    }
	    Base::$tpl->assign('aCategory', $aCategory);
	    $this->GetModels();
	
	    $aCatValue=Db::GetRow("select * from cat where name='".Base::$aRequest['cat']."' ");
	    $sSelectedBrandTitle=$aCatValue['title'];
	    $sCarSelected=" ".$sSelectedBrandTitle;
	    Base::$tpl->Assign('sSelectedBrandTitle',$sSelectedBrandTitle);
	    Base::$tpl->Assign('sH1',$aCategory['name'].$sCarSelected);
	    
	    //crumbs
// 	    Base::$oContent->AddCrumb(Language::GetMessage('rubricator'),'/rubricator/');
	    Base::$oContent->AddCrumb($aCategory['name'], '/rubricator/'.$aCategory['url']."/");
	    Base::$oContent->AddCrumb($aCatValue['title'], '');
	
// 	    Content::showCarSelect();
	    
	    $sOtherModels=Db::GetAll($sql = "select * from cat_model_group where visible=1 and id_make='".$aCatValue['id']."' order by id_make,name");
	    if ($sOtherModels) foreach ($sOtherModels as $sKey => $aValue){
	        $sOtherModels[$sKey]['brand']=$aCatValue['title'];
	        $sAutoCode="c/".$aCatValue['name']."_".$sOtherModels[$sKey]['code']."/";
	        $sAutoCode=mb_strtolower($sAutoCode);
            $sOtherModels[$sKey]['seourl']="/rubricator/".Base::$aRequest['category']."/".$sAutoCode;
	    }
	
	    Base::$tpl->assign('sAutoPreSelected',"c/".$aCatValue['name']."/");
	    Base::$tpl->assign('sSelectedCarUrlRubricator',"c/".$aCatValue['name']."/");
	    Base::$tpl->assign('sOtherModels',$sOtherModels);
	    Base::$sText.=Base::$tpl->fetch('rubricator/category.tpl');
	    Base::$sText.=Base::$tpl->fetch('rubricator/other_models.tpl');
	
	    Content::SetMetaTagsPage('category_brand_view:', array(
	        'category' => $aCategory['name'],
	        'brand' => $sSelectedBrandTitle
	    ));
	}
	//-----------------------------------------------------------------------------------------------
	public function SubCategoryBrandView() {
	    $aSubCategory=Db::GetRow("select * from rubricator where url='".Base::$aRequest['category']."' and visible=1");
	    $aCategory=Db::GetRow("select * from rubricator where id='".$aSubCategory['id_parent']."' and visible=1");
	
	    if (Base::$aRequest['cat']) {
	        Base::$aRequest['data']['id_make']=Db::GetOne("select id from cat where name='".Base::$aRequest['cat']."'");
	    }
	    Base::$aRequest['car_select']['brand'] = Base::$aRequest['cat'];
	
	    $aCatValue=Db::GetRow("select * from cat where name='".Base::$aRequest['cat']."' ");
	    $sSelectedBrandTitle=$aCatValue['title'];
	    $sCarSelected=" ".$sSelectedBrandTitle;
	    Base::$tpl->Assign('sH1',$aSubCategory['name'].$sCarSelected);
	    
	    $this->GetModels();
	
// 	    Content::showCarSelect();
	
	    $sOtherModels=Db::GetAll($sql = "select * from cat_model_group where visible=1 and id_make='".$aCatValue['id']."' order by id_make,name");
	    if ($sOtherModels) foreach ($sOtherModels as $sKey => $aValue){
	        $sOtherModels[$sKey]['brand']=$aCatValue['title'];
	        $sAutoCode="c/".$aCatValue['name']."_".$sOtherModels[$sKey]['code']."/";
	        $sAutoCode=mb_strtolower($sAutoCode);
            $sOtherModels[$sKey]['seourl']="/rubricator/".Base::$aRequest['category']."/".$sAutoCode;
	    }
	    
	    //checkChilds
	    $aSubCategoryLevel3=Db::GetAll("select * from rubricator where id_parent='".$aSubCategory['id']."' and visible=1 order by sort asc");
	    if($aSubCategoryLevel3) {
	        $aSubCategory['childs']=$aSubCategoryLevel3;
	        Base::$tpl->assign('aCategory', $aSubCategory);
	        Base::$tpl->assign('sAutoPreSelected',"c/".$aCatValue['name']."/");
	        Base::$sText.=Base::$tpl->fetch('rubricator/category.tpl');
	    } 
	    
	    //crumbs
	    Base::$oContent->AddCrumb($aCatValue['title'], "/rubricator/c/".$aCatValue['name']."/");
	    Base::$oContent->AddCrumb($aSubCategory['name']);

	    Base::$tpl->Assign('sSelectedSubcategory',$aSubCategory['name']);
	    Base::$tpl->Assign('sSelectedBrandTitle',$sSelectedBrandTitle);
	    Base::$tpl->assign('sSelectedCarUrlRubricator',"c/".$aCatValue['name']."/");
	    Base::$tpl->assign('sOtherModels',$sOtherModels);
	    Base::$sText.=Base::$tpl->fetch('rubricator/subcategory.tpl');
	    Base::$sText.=Base::$tpl->fetch('rubricator/other_models.tpl');

	    Content::SetMetaTagsPage('subcategory_brand_view:', array(
	        'category' => $aSubCategory['name'],
	        'brand' => $sSelectedBrandTitle
	    ));
	}
	//-----------------------------------------------------------------------------------------------
	public static function CreateSeoLink($sUrl,&$aFilter=0,&$aPriceGroupBrand=0,&$aFilterSelected=0,&$aBrandSelected=0,&$sUrlRemoveAll,&$aPriceSelected=0) {
	    if($aFilter)
	    foreach ($aFilter as $iKFilter=>$aVFilter){
	        foreach ($aVFilter['params'] as $iKParam=>$aParam){
	            $sTmp=$sUrl."&";
	            $sTmp.=$aParam['checked'] ? "remove=" : "add=";
	            $sTmp.=$aVFilter['table_']."&value=".$aParam['id'];
	            $sTmp.=Base::$aRequest["table"]=='gallery' ? "&table=gallery" : '';
	            $sTmp=self::PreGenerateFilterLink($sTmp);
	            $aFilter[$iKFilter]['params'][$iKParam]['url']=$sTmp;
	        }
	    }
	    if($aPriceGroupBrand)
	        foreach ($aPriceGroupBrand as $iKBrand=>$aVBrand){
	        $sTmp=$sUrl."&";
	        $sTmp.=$aVBrand['checked'] ? "remove=" : "add=";
	        $sTmp.="brand&value=".$aVBrand['c_name'];
	        $sTmp.=Base::$aRequest["table"]=='gallery' ? "&table=gallery" : '';
	        $sTmp=self::PreGenerateFilterLink($sTmp);
	        $aPriceGroupBrand[$iKBrand]['url']=$sTmp;
	    }
	    if($aFilterSelected)
	        foreach ($aFilterSelected as $iKFilter=>$aParam){
	        $sTmp=$sUrl."&";
	        $sTmp.="remove=".$aParam['table_']."&value=".$aParam['id'];
	        $sTmp.=Base::$aRequest["table"]=='gallery' ? "&table=gallery" : '';
	        $sTmp=self::PreGenerateFilterLink($sTmp);
	        $aFilterSelected[$iKFilter]['url']=$sTmp;
	    }
	    if($aBrandSelected)
	        foreach ($aBrandSelected as $iKBrand=>$aVBrand){
	        $sTmp=$sUrl."&";
	        $sTmp.="remove=brand"."&value=".$aVBrand['name'];
	        $sTmp.=Base::$aRequest["table"]=='gallery' ? "&table=gallery" : '';
	        $sTmp=self::PreGenerateFilterLink($sTmp);
	        $aBrandSelected[$iKBrand]['url']=$sTmp;
	    }

	    if($aPriceSelected){
	        $sTmp=$sUrl."&";
	        $sTmp=self::PreGenerateFilterLink($sTmp,0);
	        $aPriceSelected['url']=$sTmp;
	        // 	        $sTmp.="remove=price"."&value=1";
	        $sTmp.=Base::$aRequest["table"]=='gallery' ? "&table=gallery" : '';
	    }
	    $sTmp2=$sUrl."&";
	    $sTmp2.=Base::$aRequest["table"]=='gallery' ? "&table=gallery" : '';;
	    $aPriceSelected['url_2']=self::PreGenerateFilterLink($sTmp2,2);
	    
	    $sUrlRemoveAll=$sUrl."&remove_all=1";
	    $sUrlRemoveAll=self::PreGenerateFilterLink($sUrlRemoveAll,0);
	    if(stripos(Base::$aRequest["all_params"], "g/1")!==false) $sUrlRemoveAll.='g/1';
	}
	//-----------------------------------------------------------------------------------------------
	public static function PreGenerateFilterLink($sLink,$bclear=1) {
	    $aLink=parse_url($sLink);
	    parse_str($aLink['query'],$aRequestData);
	    if(Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator']) {
	        $aRequestData['selected_auto']=Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator'];
	    } elseif(Base::$aRequest['cat']) {
	        $aRequestData['selected_auto']="/c/".Base::$aRequest['cat'];
	        if(Base::$aRequest['model_group']) {
	            $aRequestData['selected_auto'].="_".Base::$aRequest['model_group'];
	        }
	    }
	    $sLink=self::GenerateFilterOldLink($aRequestData);
	    $aLink=parse_url($sLink);
	    parse_str($aLink['query'],$aRequestData);
	    $aRequestData['action']=Base::$aRequest['action'];
	    $aRequestData['category']=Base::$aRequest['category'];
	    if(Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator']) {
	        $aRequestData['selected_auto']=Base::$tpl->_tpl_vars['sSelectedCarUrlRubricator'];
	    } elseif(Base::$aRequest['cat']) {
	        $aRequestData['selected_auto']="/c/".Base::$aRequest['cat'];
	        if(Base::$aRequest['model_group']) {
	            $aRequestData['selected_auto'].="_".Base::$aRequest['model_group'];
	        }
	    }
	    if($bclear&&Base::$aRequest['max_price'])
	    {
	        $aRequestData['min_price']=Base::$aRequest['min_price'];
	        $aRequestData['max_price']=Base::$aRequest['max_price'];
	    }
	    if($bclear==2){
	        $aRequestData['min_price']='min_price';
	        $aRequestData['max_price']='max_price';
	    }
	    $sLink=self::GenerateFilterLink($aRequestData);
	    return $sLink;
	}
	//-----------------------------------------------------------------------------------------------
	public static function GenerateFilterLink($aData) {
	    if(MultiLanguage::IsLocale()){
	        $sUrl="/".Language::$sLocale."/rubricator/";
	    }else{
	        $sUrl="/rubricator/";
	    }
	    $sUrl.=$aData['category']."/";
	    if($aData['selected_auto']) $sUrl.=$aData['selected_auto']."/";
	    if($aData['brand']) {
	        $aBrandList=explode(",", $aData['brand']);
	        sort($aBrandList);
	        $aData['brand']=implode(",", $aBrandList);
	        $sUrl.="b/".$aData['brand']."/";
	    }
	    if($aData['filter']||($aData['max_price'])) {
	        //sort filter
	        $sUrl.="f/";
	        if($aData['filter']){
    	        $aFilteredParams=Db::GetAssoc("select table_,id from handbook where table_ in ('".implode("','", array_keys($aData['filter']))."') order by id");
    	        if($aFilteredParams) {
    	            foreach ($aData['filter'] as $sTable => $iIdParam) {
    	                $aFilteredParams[$sTable]=$aFilteredParams[$sTable]."-".$iIdParam;
    	            }
    	            $sUrl.=implode("-", $aFilteredParams);
    	        }
    	       
	        }
	        if($aData['filter']&&$aData['max_price'])
	            $sUrl.="-";
	        if($aData['max_price']){
	            $sUrl.="p".$aData['min_price']."-p".$aData['max_price'];
	        }
	         $sUrl.="/";
	    }
	    if($aData['step']) {
	        $sUrl.="s/".$aData['step']."/";
	    }
	    if($aData['table']=='gallery') {
	        $sUrl.="g/1/";
	    }
	    if($aData['sort'] && $aData['way']) {
	        $sUrl.="o/".$aData['sort']."-".$aData['way']."/";
	    }
	    
	    $sUrl=str_ireplace(",", "_", $sUrl);
	    return str_replace("//", "/", str_replace("/c/c/", "/c/", str_replace("//", "/", $sUrl)));
	}
	//-----------------------------------------------------------------------------------------------
	public static function ParsingParameter(&$aRequestData){
	    // разбор параметров
	    $aUrlParams=explode("/", $aRequestData['all_params']);
	    if($aUrlParams) {
	        foreach ($aUrlParams as $sKey => $sParam) {
	            //order_brand
	            if($sParam=="b") {
	                $aRequestData['brand']=$aUrlParams[($sKey+1)];
	                continue;
	            }
	            
	            //selected auto
	            if($sParam=="c") {
	                $sAuto=$aUrlParams[($sKey+1)];
	                //audi_100_-11-12
	                $iTmp=strpos($sAuto, "_");
	                $sCat=0;
	                $sModelGroup=0;
	                $sModel=0;
	                $sModelDetail=0;
	                if($iTmp===false) {
	                    $sCat=$sAuto;
	                } else {
	                    $sCat=substr($sAuto, 0, $iTmp);
	                    //have model or model_group
	                    $sModelTmp=substr($sAuto, $iTmp);
	                    $iTmp=strpos($sModelTmp, "-");
	                    if($iTmp===false) {
	                        //only model group
	                        $aRequestData['model_group']=str_replace("/", '', substr($sModelTmp,1));
	                    } else {
	                        list($sModelGroup,$sModel,$sModelDetail)=explode("-", $sModelTmp);
	                        $aRequestData['model_group']=str_replace("/", '', substr($sModelGroup,1));
	                        $aRequestData['data']['id_model']=$sModel;
	                        $aRequestData['data']['id_model_detail']=$sModelDetail;
	                    }
	                }
	                
	                $aRequestData['cat']=$sCat;
	                $_REQUEST['cat']=$sCat;
	                if($aRequestData['data']['id_model_detail']) {
	                    unset($aRequestData['model_group']);
	                }
	                continue;
	            }
	             
	            //gallery
	            if($sParam=="g") {
	                $aRequestData['table']="gallery";
	                $_REQUEST['table']="gallery";
	                continue;
	            }
	             
	            //stepper
	            if($sParam=="s" && !isset($aRequestData['step'])) {
	                $aRequestData['step']=$aUrlParams[($sKey+1)];
					if ($aRequestData['step']<=0)
						$aRequestData['step']=0;
	                continue;
	            }
	             
	            //filters example: filter[patrol_diesel]=1
	            if($sParam=="f") {
	                $aFilters=explode("-", $aUrlParams[($sKey+1)]);
	                if($aFilters) {
	                    foreach ($aFilters as $k1 => $v1) {
	                        if($k1%2 ==0) {
	                            if(strpos($v1, 'p')!==false){
	                                $aRequestData['min_price']=str_replace('p','',$v1);
	                                $aRequestData['max_price']=str_replace('p','',$aFilters[($k1+1)]);
	                            }else{
    	                            $sTableName=Db::GetOne("select lower(table_) from handbook where id='".$v1."' ");
    	                            if($sTableName) {
    	                                $aRequestData['filter'][$sTableName]=$aFilters[($k1+1)];
    	                            }
	                            }
	                        } else {
	                            continue;
	                        }
	                    }
	                }
	            }
	            //sorting
	            if(strpos($sParam, "sort")!==false || strpos($sParam, "way")!==false) {
	                list($sK,$sV)=explode("=", $sParam);
	                $aRequestData[$sK]=$sV;
	                continue;
	            }elseif($sParam=="o") {
	                list($sS,$sW)=explode("-", $aUrlParams[($sKey+1)]);
	                $aRequestData['sort']=$sS;
	                $aRequestData['way']=$sW;
	                $_GET['sort']=$sS;
	                continue;
	            }
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
	public static function GenerateFilterOldLink($aRequestData)	{
	    $sUrl='/rubricator/';
	    $aUrl=array();
	    if($aRequestData["category"]) $sUrl.=$aRequestData["category"].'/';
	    if($aRequestData['selected_auto']) $sUrl.=$aRequestData['selected_auto']."/";
	
	    if(!$aRequestData['remove_all']) {
	        $aFilterParams=String::FilterRequestData($aRequestData['filter']);
	        $sWhereParams='';
	        if($aFilterParams) foreach ($aFilterParams as $sKey => $sValue) {
	            if($sKey==$aRequestData['add']) {
	                $sValue.=','.$aRequestData['value'];
	                $aFilterParams[$sKey]=$sValue;
	            }
	
	            if($sKey==$aRequestData['remove']) {
	                $aTmpVal=explode(",", $sValue);
	                if($aTmpVal) foreach ($aTmpVal as $sTmpValKey => $sTmpValVal) {
	                    if($sTmpValVal==$aRequestData['value']) unset($aTmpVal[$sTmpValKey]);
	                }
	                $sValue=implode(",", $aTmpVal);
	                $aFilterParams[$sKey]=$sValue;
	            }
	
	            if($sValue) $aUrl[]='filter['.$sKey.']='.$sValue;
	        }
	        if (!$aFilterParams)
	            $aFilterParams = array();
	        if (!$aRequestData['add'])
	            $aRequestData['add'] = '';
	
	        if(!in_array($aRequestData['add'], array_keys($aFilterParams)) && $aRequestData['add'] && $aRequestData['add']!='brand') {
	            $aUrl[]='filter['.$aRequestData['add'].']='.$aRequestData['value'];
	        } elseif(!in_array($aRequestData['add'], array_keys($aFilterParams)) && $aRequestData['add'] && $aRequestData['add']=='brand') {
	            if($aRequestData["brand"]) $aRequestData["brand"].=",".$aRequestData['value'];
	            else $aRequestData["brand"]=$aRequestData['value'];
	        }
	
	        if($aRequestData['remove']=='brand') {
	            //unset($aRequestData["brand"]);
	            $aTmpVal=explode(",", $aRequestData["brand"]);
	            if($aTmpVal) foreach ($aTmpVal as $sTmpValKey => $sTmpValVal) {
	                if($sTmpValVal==$aRequestData['value']) unset($aTmpVal[$sTmpValKey]);
	            }
	            $aRequestData["brand"]=implode(",", $aTmpVal);
	        }
	
	        if($aRequestData["brand"]) {
	            $aUrl[]="brand=".$aRequestData["brand"];
	        }
	
	        if($aRequestData["table"]=='gallery') {
	            $aUrl[]="table=gallery";
	        }
	    } else {
	        if($aRequestData["table"]=='gallery') {
	            $aUrl[]="table=gallery";
	        }
	    }
	
	    if($aUrl) $sUrl.='?'.implode("&", $aUrl);
	
	    return str_replace("//", "/", $sUrl);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetRubricatorMenu() {
	    $aData = array(
	        'visible'=>1,
	        'where'=>" and r.level='1' "
	    );
	    $aMenu=MultiLanguage::GetLocalizedRubricator($aData);
	    //$aMenu=Db::GetAll(Base::GetSql("Rubricator",$aData));
	    
	    if($aMenu) {
	        //sort by num
	        usort($aMenu, function ($a, $b)
	        {
	            if ($a['sort'] == $b['sort']) {
	                return 0;
	            }
	            return ($a['sort'] < $b['sort']) ? -1 : 1;
	        });
	        //get leve3 categories as childs
	        foreach ($aMenu as $sKey => $aValue) {
	            $aL2Ids=Db::GetAssoc("select id, id as id2 from rubricator where visible='1' and id_parent='".$aValue['id']."' /*and level='2'*/ ");
	            
	            if($aL2Ids) {
	                $aData = array(
	                    'visible'=>1,
	                    'where'=>" and r.id_parent in ('".implode("','", $aL2Ids)."') "
	                );
	                $aL3Childs=MultiLanguage::GetLocalizedRubricator($aData, ' order by sort asc ');
	                Rubricator::FilterCategoryByAuto($aL3Childs);
	                if($aL3Childs) {
	                    foreach ($aL3Childs as $skTmp => $aValueTmp) {
	                        if($aValueTmp['url']=='0') {
	                            unset($aL3Childs[$skTmp]);
	                        }
	                    }
	                }
	                $aL3Childs=array_slice($aL3Childs, 0,Base::GetConstant('rubricator:menu_limit',5));
	                $aMenu[$sKey]['childs']=$aL3Childs;
	            }
	        }
	    }
	    
	    $aData=array(
	        'table'=>'price_group',
	        'where'=>" and t.is_main=1 and t.visible=1 and t.level=0 ",
	    );
	    $aGroups=Base::$language->GetLocalizedAll($aData);
	    if ($aGroups) {
	        foreach ($aGroups as $sKey => $aValue){
	            //need update
	            $aData=array(
	                'table'=>'price_group',
	                'where'=>" and t.visible=1 and id_parent='".$aValue['id']."'"
	            );
	            $aChildsData=Base::$language->GetLocalizedAll($aData);
	            if($aChildsData)
	            foreach ($aChildsData as $iKey => $aVal){
	                $aChildsData[$iKey]['url']=$aVal['code_name'];
	            }
// 	            Debug::PrintPre($aChildsData,false);
	            if($aChildsData) $aGroups[$sKey]['childs']=array_slice($aChildsData,0,3);
	            $aGroups[$sKey]['url']=$aValue['code_name'];
	            $aGroups[$sKey]['is_price_group']=1;
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
	    }else $aMenu=array();
	    
	    if(!$aGroups) $aGroups=array();
	     
	    $aMenu=array_merge($aMenu,$aGroups);
	    
	    return $aMenu;
	}
	//-----------------------------------------------------------------------------------------------
	public static function FilterCategoryByAuto(&$aL3Childs) {
	    $iIdModelDetail=Base::$aRequest['data']['id_model_detail'];
	    $iIdModel=Base::$aRequest['data']['id_model'];

	    if(!$iIdModelDetail && !$iIdModel && $_COOKIE['id_model_detail']) $iIdModelDetail=$_COOKIE['id_model_detail'];
	    if(!$iIdModel && $_COOKIE['id_model']) $iIdModel=$_COOKIE['id_model'];
	    
	    if(!$iIdModelDetail && !$iIdModel) {
	        return;
	    }
	    
	    if($iIdModelDetail || strpos($iIdModelDetail, ",")!==false) {
	         
	        if(strpos($iIdModelDetail, ",")!==false) {
	            return;
	        }
	         
	        $sNameCache="t_".$iIdModelDetail;
	        $aAllowedTreeGroup=FileCache::GetValue("tree_group_auto_".DB_OCAT, $sNameCache);
	        if(!$aAllowedTreeGroup || !is_array($aAllowedTreeGroup)) {
	            $aAllowedTreeGroup=TecdocDb::GetAssoc("
    	            SELECT
    	               concat(lsg.ID_tree,'_',g.id_src),
    	               count(t.id_art)
                    FROM ".DB_OCAT."cat_alt_link_typ_art as t
    	            join ".DB_OCAT."cat_alt_types as tp on t.id_typ=tp.id_typ and tp.id_src in (".$iIdModelDetail.")
                    join ".DB_OCAT."cat_alt_link_str_grp as lsg on lsg.ID_grp=t.id_grp
                    join ".DB_OCAT."cat_alt_groups as g on lsg.id_grp=g.ID_grp
    	            group by concat(lsg.ID_tree,'_',g.id_src)
                ");
	            FileCache::SetValue("tree_group_auto_".DB_OCAT, $sNameCache, $aAllowedTreeGroup);
	        }
	    } else {
	        $sNameCache="t_".$iIdModel;
	        $aAllowedTreeGroup=FileCache::GetValue("tree_group_model_".DB_OCAT, $sNameCache);
	        if(!$aAllowedTreeGroup || !is_array($aAllowedTreeGroup)) {
    	        $aAllowedTreeGroup=TecdocDb::GetAssoc("
        	            SELECT
        	               concat(lsg.ID_tree,'_',g.id_src),
        	               count(t.id_art)
                        FROM ".DB_OCAT."cat_alt_link_mod_art as t
        	            join ".DB_OCAT."cat_alt_models as tp on t.id_mod=tp.id_mod and tp.id_src in (".$iIdModel.")
                        join ".DB_OCAT."cat_alt_link_str_grp as lsg on lsg.ID_grp=t.id_grp
                        join ".DB_OCAT."cat_alt_groups as g on lsg.id_grp=g.ID_grp
        	            group by concat(lsg.ID_tree,'_',g.id_src)
                    ");
	            FileCache::SetValue("tree_group_model_".DB_OCAT, $sNameCache, $aAllowedTreeGroup);
	        }
	    }
	    
	    if($aAllowedTreeGroup) {
	        if($aL3Childs)
	        foreach ($aL3Childs as $sKeyCh3 => $aValueCh3) {
	            $bAllow=false;
	            $aRubAssoc=array();
	            $aTreeTmp=explode(",", $aValueCh3['id_tree']);
	            $aGroupTmp=explode(",", $aValueCh3['id_group']);
	            foreach ($aTreeTmp as $sVt) {
	                if($sVt==0) {
	                    continue;
	                }
	                foreach ($aGroupTmp as $sVg) {
	                    if($sVg==0) {
	                        continue;
	                    }
	                    $aRubAssoc[]=$sVt."_".$sVg;
	                }
	            }
	        
	            $iCountProducts=0;
	            if($aRubAssoc) foreach ($aRubAssoc as $sTreeGroup) {
	                if(array_key_exists($sTreeGroup, $aAllowedTreeGroup)) {
	                    $bAllow=true;
	                    $iCountProducts=$iCountProducts+$aAllowedTreeGroup[$sTreeGroup];
	                }
	            }
	            $aL3Childs[$sKeyCh3]['cnt_pos']=$iCountProducts;
	
	            if(!$bAllow) {
	                $aL3Childs[$sKeyCh3]['url']=0;
	                $aL3Childs[$sKeyCh3]['id_tree']=0;
	                $aL3Childs[$sKeyCh3]['id_group']=0;
	                $aL3Childs[$sKeyCh3]['cnt_pos']=0;
	            }
	        }
	    }
	}
	//-----------------------------------------------------------------------------------------------
}
?>
