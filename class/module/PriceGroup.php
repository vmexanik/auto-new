<?
/**
 * @author Oleksandr Starovoit
 * @author Mikhail Starovoyt
 * @author Yuriy Korzun
 * @version 4.5.2
 */

class PriceGroup extends Catalog
{
	var $sPrefix="price_group";

	//-----------------------------------------------------------------------------------------------
	public function __construct()
	{
// 		Db::Execute("SET @lng_id = 16");
// 		Db::Execute("SET @cou_id = 187");
// 		if(Base::$aRequest['category']){
// 			$aRedirectLinks=Db::GetAssoc("select link_from,link_to from redirect where link_from like '/select/%'");
// 			if ($aRedirectLinks){
// 				$sRedirectLinkFrom = "/select/".Base::$aRequest['category']."/";
// 				$sRedirectLinkTo = $aRedirectLinks[$sRedirectLinkFrom];
// 				if ($sRedirectLinkTo && ($sRedirectLinkTo!=$sRedirectLinkFrom)){
// 					$sRedirectLinkTo .= Base::$aRequest['brand'] ? Base::$aRequest['brand']."/" : "";
// 					Base::Redirect(Language::GetConstant('global:project_url').$sRedirectLinkTo);
// 				}
// 			}
// 		}
		if (Base::$aRequest["brand"]) Base::$aRequest["brand"]=str_replace("_", ",", Base::$aRequest["brand"]);
		if (Base::$aRequest["all_params"]) Base::$aRequest["all_params"]=str_replace("_", ",", Base::$aRequest["all_params"]);
		// разбор параметров
		self::ParsingParameter(Base::$aRequest);
		
		//check correct link
		if(Base::$aRequest['action']=="price_group") {
		    $sEtalonUrl=PriceGroup::GenerateFilterLink(Base::$aRequest);
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
		    // для русских символов надо раскожировать иначе бесконечный редирект
		    if($sUrl1!=urldecode($sUrl2)) {
		        Base::Redirect($sEtalonUrl);
		    }
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function Index()
	{
		Base::$bXajaxPresent=true;
		Base::$tpl->assign('sBaseAction',$this->sPrefix);
        
        // check brand
        if (Base::$aRequest['brand']) {
            $aCat = Db::getAssoc("Select lower(name), pref from cat where visible=1");
            $aBrands = explode(",",Base::$aRequest['brand']);
            foreach ($aBrands as $sBrand) {
                if (!$aCat[$sBrand]) {
                    Form::Error404(false);
                    return;
                }
            }
        }
		if (
		(isset(Base::$aRequest["step"]) && Base::$aRequest["step"]==0)
		|| strpos($_SERVER['REQUEST_URI'],'?')!==FALSE
		) {
			if(Base::$aRequest["category"]) $sUrl.=Base::$aRequest["category"].'/';
			if(Base::$aRequest["brand"]) $sUrl.=Base::$aRequest["brand"].'/';
			elseif(Base::$aRequest["step"]) $sUrl.='0/';
			if(Base::$aRequest["step"]) $sUrl.=Base::$aRequest["step"].'/';
			if(Base::$aRequest["table"]=="gallery") $sUrl.='gallery/';
			if(Base::$aRequest["sort"]) $sUrl.='sort='.Base::$aRequest["sort"].'/';
			if(Base::$aRequest["way"]) $sUrl.='way='.Base::$aRequest["way"].'/';
			//Base::Redirect('/select/'.$sUrl);
		}

		if (!Base::$aRequest["category"]) {
			$oTable=new Table();
			/*$oTable->sSql=Base::GetSql("Price/Group",array(
			'visible'=>1,
			"where"=>" and pg.code_name is not null",
			'order'=>' ORDER BY (code+0)'
			));*/
			$aData=array(
				'table'=>'price_group',
				'where'=>" and t.visible=1",
				'order'=>"order by (t.code+0)"
			);
			$aPriceGroupList=Base::$language->GetLocalizedAll($aData);
			$oTable->aDataFoTable=$aPriceGroupList;
			$oTable->sType='array';
			$oTable->aColumn=array(
			'code'=>array('sTitle'=>'code'),
			'code_name'=>array('sTitle'=>'code_name'),
			'action'=>array(),
			);
			$oTable->sDataTemplate=$this->sPrefix."/row_".$this->sPrefix.".tpl";
			$oTable->iRowPerPage=500;
			$oTable->bStepperVisible=false;

			Base::$sText.=$oTable->getTable("Category price");
			Base::$oContent->AddCrumb(Language::GetMessage("price groups"));
		}elseif (Base::$aRequest["category"]){
		    if (MultiLanguage::IsLocale()) {
    			$aData=array(
    			    'table'=>'price_group',
    			    'where'=>" and code_name='".Base::$aRequest["category"]."' and visible=1",
    			);
    			$aRowPriceGroup=Base::$language->GetLocalizedRow($aData);
			}else{
    			$aRowPriceGroup=Db::GetRow(Base::GetSql("Price/Group",array(
    			'code_name'=>Base::$aRequest["category"]?Base::$aRequest["category"]:-1,
    			'visible'=>1,
    			)));
			}
			
			if ($aRowPriceGroup) {
				$aIdGroup[] = $aRowPriceGroup['id'];
				if (1){
					$aData=array(
							'table'=>'price_group',
							'where'=>" and t.id_parent='".$aRowPriceGroup['id']."' and t.visible=1",
					);
					$aChilds=Base::$language->GetLocalizedAll($aData);
					//$aChilds=Db::GetAll("select * from price_group where id_parent='".$aRowPriceGroup['id']."' and visible=1");
					if ($aChilds)
					foreach ($aChilds as $sKey => $aValue){
						$action = "/select/".$aValue['code_name'];
						if (Language::getConstant('global:url_is_not_last_slash',0) == 0)
							$action .= "/";
						if (Language::getConstant('global:url_is_lower',0) == 1)
							$action = mb_strtolower($action,'utf-8');
						$aChildNavigator[$sKey]=array(
							'name'=>$aValue['name'],
							'url'=>$action,
						);
						//$aChildsId[]=$aValue['id'];
						$aIdGroup[] = $aValue['id'];

						$aData=array(
								'table'=>'price_group',
								'where'=>" and t.id_parent='".$aValue['id']."' and t.visible=1",
						);
						$aChilds1=Base::$language->GetLocalizedAll($aData);
						if ($aChilds1)
							foreach ($aChilds1 as $sKey1 => $aValue1){
							$aIdGroup[] = $aValue1['id'];
						}
					}
					
					$aPriceGroupBrand=Db::GetAll(Base::GetSql("Price/GroupPref",array(
					    "id_price_group"=>implode(',',$aIdGroup),
					    "join_price"=>1,
					    "order"=> " order by c.name",
					    "where"=>" and c.visible=1",
					)));
					$sPg_code_name=$aRowPriceGroup['code_name'];
					
					//----------------------------------------------------------------
					function GetGroupNavi($iParent,&$aNavigator=array()){
					    $aData=array(
						'table'=>'price_group',
						'where'=>" and id='".$iParent."' and visible=1",
					    );
					    $aParent=Base::$language->GetLocalizedRow($aData);
					    //$aParent = Db::GetRow("Select * from price_group where visible=1 and id=".$iParent);
					    if ($aParent) {
					        if($aParent['id_parent']) GetGroupNavi($aParent['id_parent'],$aNavigator);
							$action = "/select/".$aParent['code_name'];
							if (Language::getConstant('global:url_is_not_last_slash',0) == 0)
								$action .= "/";
					        $aNavigator[]=array(
					            'name'=>$aParent['name'],
					            'url'=>$action,
					        );
					        
					    }
					}
					GetGroupNavi($aRowPriceGroup['id'],$aNavigator);
					//----------------------------------------------------------------
				}
				
			}
			else {
				Form::Error404(true);
			}

			if (Base::$aRequest["brand"])
				$aRowCat=Db::GetRow(Base::GetSql('Catalog/CatPref',array(
				'brand'=>Base::$aRequest["brand"],
				'id_category'=>$aRowPriceGroup['id'],
				'childs'=>isset($aChilds),
				)));
			
			if(!$aRowCat['pref'] && Base::$aRequest['brand']) {
			    $aPref=Db::GetAssoc("select pref,pref as p1 from cat where
				    name in ('".implode("','", explode(',', Base::$aRequest['brand']))."')
				");
			    if($aPref) $aPref=array_unique($aPref);
			}

			if ($aRowCat) {
				if(mb_strtolower(Base::$aRequest["brand"],'utf-8')!=mb_strtolower($aRowCat['name'],'utf-8')) Base::Redirect('/select/'.Base::$aRequest["category"].'/'.$aRowCat['name'].'/');
				$action = "/select/".$aRowPriceGroup['code_name']."/".$aRowCat['name'];
				
				if (Language::getConstant('global:url_is_not_last_slash',0) == 0) 
					$action .= "/";
				if (Language::getConstant('global:url_is_lower',0) == 1)
					$action = mb_strtolower($action,'utf-8');
				

				$aNavigator[]=array(
				 'name'=>$aRowCat['title']
				,'url'=> $action
				);

				
			}elseif(Base::$aRequest["brand"]){
// 				 Form::Error404(true);
			}
			// LNB-57 process filter begin
			$bNoIndexNofollow=false;
			$aFilterParams=String::FilterRequestData(Base::$aRequest['filter']);
			$sWhereParams='';
			if($aFilterParams) foreach ($aFilterParams as $sKey => $sValue) {
				if($sValue!=0) {
					$sWhereParams.=" and pgp.id_".$sKey." in (".$sValue.") ";
				}
				if(count(explode(',',$sValue))>2){
				    $bNoIndexNofollow=true;
				}
			}
			Base::$tpl->assign('bNoIndexNofollow',$bNoIndexNofollow);
			// LNB-57 process filter end
					
			//count products
			$sFullSql=Base::GetSql("Catalog/PriceForCount",array(
					"id_price_group"=>$aRowPriceGroup['id'],
					"childs_id"=>$aIdGroup,
					"where"=>" and p.price>0 ".$sWhereParams,
					'stock_exist'=>1,
					"pgpf"=>1,
					"group_pref"=>1
			));
			$aCnt = Db::GetAssoc($sFullSql);
			
			if($aPriceGroupBrand) foreach ($aPriceGroupBrand as $sBrandKey => $aBrandValue) {
				$aTmpBrand=explode(",", Base::$aRequest['brand']);

				if(in_array(mb_strtolower($aBrandValue['c_name'],'UTF-8'), $aTmpBrand)) {
					$aPriceGroupBrand[$sBrandKey]['checked']=1;
				}
				
				if(Base::$aRequest['brand']) {
					$sBrandWhere=implode("','", $aTmpBrand);
				} else {
					$sBrandWhere=$aBrandValue['c_name'];
				}
				
				/*$sFullSql=Base::GetSql("Catalog/PriceForCount",array(
					"id_price_group"=>$aRowPriceGroup['id'],
					"childs"=>$aChilds,
					"where"=>" and p.price>0 ".$sWhereParams." and upper(c.name)='".mb_strtoupper($aBrandValue['c_name'])."' ",
					"pgpf"=>1
				));
				
				$iCnt = Db::GetOne($sFullSql);*/
				if (($iCnt=$aCnt[mb_strtolower($aBrandValue['c_name'],'UTF-8')]))
					$aPriceGroupBrand[$sBrandKey]['count']=$iCnt;
				else
					unset($aPriceGroupBrand[$sBrandKey]);
			}
			if(count($aTmpBrand)>2){
			    Base::$tpl->assign('bNoIndexNofollow',1);
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

			$aNavigator[count($aNavigator) - 1]['url'] = '';
			
			//AT-1187 begin
			$sGroupChangeTableUrl=$_SERVER['REQUEST_URI'];
			$iQstPos=strpos($sGroupChangeTableUrl, '/g/1');
// 			if(!Base::$aRequest['table'] && $iQstPos!==false) $sGroupChangeTableUrl.='&table=gallery';
// 			elseif(!Base::$aRequest['table'] && $iQstPos===false) $sGroupChangeTableUrl.='?table=gallery';
// 			elseif(Base::$aRequest['table']) {
// 			    $sGroupChangeTableUrl=str_replace("&table=gallery", "", $sGroupChangeTableUrl);
// 			    $sGroupChangeTableUrl=str_replace("?table=gallery&", "?", $sGroupChangeTableUrl);
// 			    $sGroupChangeTableUrl=str_replace("?table=gallery", "", $sGroupChangeTableUrl);
// 			}
			if($iQstPos===false) {
			    $sGroupChangeTableUrl.='/g/1';
			} else {
			    $sGroupChangeTableUrl=str_replace("/g/1", "", $sGroupChangeTableUrl);
			}
			//AT-1187 end

            if($aNavigator) {
                foreach ($aNavigator as $aNavigatorValue) {
                    Base::$oContent->AddCrumb($aNavigatorValue['name'],$aNavigatorValue['url']);
                }
            }
			
			Base::$tpl->assign('aChildNavigator', $aChildNavigator);
			Base::$tpl->assign('sPg_code_name',$sPg_code_name);
			Base::$tpl->assign('aBrand', $aRowCat);
			Base::$tpl->assign('sGroupChangeTableUrl',$sGroupChangeTableUrl);
			Base::$tpl->assign('sGroupTableUrl',$_SERVER['REQUEST_URI']);

			Base::$tpl->assign('aRowPriceGroup', $aRowPriceGroup);
			if ($aRowPriceGroup['description']) {
				Base::$tpl->assign('sSmartyTemplate', $aRowPriceGroup['description']);
				Base::$tpl->assign('sDescription',Base::$tpl->fetch('addon/smarty_template.tpl'));
			}
// 			Base::$sText.=Base::$tpl->fetch($this->sPrefix."/group_brand.tpl");
			
			Base::$aData['template']['sPageH1'] = $aRowPriceGroup['name'];

			if ($aRowPriceGroup['title']) {
				Base::$tpl->assign('sSmartyTemplate', $aRowPriceGroup['title']);
				Base::$aData['template']['sPageTitle'] = Base::$tpl->fetch('addon/smarty_template.tpl');
			}
			if ($aRowPriceGroup['page_description']) {
				Base::$tpl->assign('sSmartyTemplate', $aRowPriceGroup['page_description']);
				Base::$aData['template']['sPageDescription'] = Base::$tpl->fetch('addon/smarty_template.tpl');
			}
			if ($aRowPriceGroup['page_keyword']) {
				Base::$tpl->assign('sSmartyTemplate', $aRowPriceGroup['page_keyword']);
				Base::$aData['template']['sPageKeyword'] = Base::$tpl->fetch('addon/smarty_template.tpl');
			}
			
			if ($aRowPriceGroup['is_product_list_visible']){
    			if(Base::GetConstant('complex_margin_enble','0')) {
        			if (!Base::$aRequest['sort'] || Base::$aRequest['sort'] == 'price')
        				$sOrder = " t.price ";
        			elseif (Base::$aRequest['sort'] == 'term')
        				$sOrder = " t.term ";
        			elseif (Base::$aRequest['sort'] == 'stock')
        				$sOrder = " CONVERT(replace(replace(replace(replace(replace(replace(replace(replace(t.stock,'>',''),'+',''),'++',''),'+++',''),'есть','1'),'X',''),'XX',''),'XXX',''), SIGNED) ";
        			elseif (Base::$aRequest['sort'] == 'make')
        				$sOrder = " t.brand ";
        			elseif (Base::$aRequest['sort'] == 'name')
        				$sOrder = " t.name_translate ";
        			elseif (Base::$aRequest['sort'] == 'code')
        				$sOrder = " t.code ";
    		    } else {
    		        if (!Base::$aRequest['sort'] || Base::$aRequest['sort'] == 'price')
    		            $sOrder = " p.price/cu.value ";
    		        elseif (Base::$aRequest['sort'] == 'make')
    		        $sOrder = " c.title ";
    		        elseif (Base::$aRequest['sort'] == 'provider')
    		        $sOrder = " up.name ";
    		        elseif (Base::$aRequest['sort'] == 'term') {
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
				
				$oTable=new Table();
				$oTable->sStepperType='step_chpu';
				$aDataForTable=Db::GetAll(Base::GetSql("Catalog/Price",array(
				"customer_discount"=>Discount::CustomerDiscount(Auth::$aUser),
				"id_price_group"=>$aRowPriceGroup['id'],
				"pref"=>$aRowCat['pref']?$aRowCat['pref']:$aPref,
				"order"=>$sOrder,
				"childs_id"=>$aIdGroup,
				"where"=>" and p.price>0 ".$sWhereParams,
				"pgpf"=>1
				))." /*limit 1000*/");
				$this->CallParse($aDataForTable);
				$this->CallParseAfter($aDataForTable);
				$oTable->sType='array';
				$oTable->aDataFoTable=$aDataForTable;

				Catalog::GetPriceTableHead($oTable);
				$oTable->iRowPerPage=Language::getConstant('price_group:limit_page_items',25);
				$oTable->iStartStep=1;
				$oTable->bStepperVisible=true;
				
				// macro sort table
				$this->SortTablePriceGroup();
				
				if(Base::$aRequest['table']=='gallery') {
				    $oTable->sTemplateName = 'table/table_list.tpl';
				    $oTable->sDataTemplate='catalog/row_price.tpl';
				    $oTable->iRowPerPage=10;
				} else {
				    $oTable->sTemplateName = 'table/table_thumb.tpl';
				    $oTable->sDataTemplate='catalog/row_price_gallery.tpl';
				    $oTable->iRowPerPage=9;
				}

				// LNB-57 filter fill begin
				$aFilter = array();
				if (MultiLanguage::IsLocale()) {
			        $sIdHandbook=Db::GetOne("select GROUP_CONCAT(pgf.id_handbook) as id_handbook from price_group_filter as pgf where pgf.id_price_group='".$aRowPriceGroup['id']."'");
			        if($sIdHandbook){
    			        $aData=array(
    			            'table'=>'handbook',
    			            'where'=>" and t.id in (".$sIdHandbook.") ",
    			            'order'=>" order by t.number asc "
    			        );
    			        $aFilter=Base::$language->GetLocalizedAll($aData, false);
			        }
				}else {
    				$sSql="select h.* from handbook as h
    						inner join price_group_filter as pgf on pgf.id_handbook=h.id and pgf.id_price_group='".$aRowPriceGroup['id']."' 
    						order by h.number asc";
				    $aFilter=Db::GetAll($sSql);
				}
				/*$sSql="select h.* from handbook as h
						inner join price_group_filter as pgf on pgf.id_handbook=h.id and pgf.id_price_group='".$aRowPriceGroup['id']."' 
						order by h.number asc";
				$aFilter=Db::GetAll($sSql);*/
				if($aFilter) foreach ($aFilter as $sKey => $aValue) {
				    if (MultiLanguage::IsLocale()) {
				        $aData=array(
				            'table'=>$aValue['table_'],
				            'where'=>" and t.visible=1 ",
				            'order'=>" order by t.name "
				        );
				        $aFilter[$sKey]['params']=Base::$language->GetLocalizedAll($aData, false);
				    }else{
					    $aFilter[$sKey]['params']=Db::GetAll("select * from ".$aValue['table_']." where visible=1 order by name");
				    }
					if($aFilter[$sKey]['params']){
    					foreach($aFilter[$sKey]['params'] as $sParamKey => $aParam) {
    						
    						$aSelParams=explode(",", $aFilterParams[mb_strtolower($aValue['table_'],'UTF-8')]);
    						
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
    												." and pgp.id_".$aValue['table_']." in (".$aParam['id'].") ";
    									} else {
    										$sWhereParams.=" and pgp.id_".$sKeyForCount." in (".$aParam['id'].") ";
    									}
    								} else {
    									$sWhereParams.=" and pgp.id_".$sKeyForCount." in (".$sValueCount.") "
    											." and pgp.id_".$aValue['table_']." in (".$aParam['id'].") ";
    								}
    							}
    						} else {
    							$sWhereParams.=" and pgp.id_".$aValue['table_']." in (".$aParam['id'].") ";
    						}
    						
    						$sFullSql=Base::GetSql("Catalog/PriceForCount",array(
    						"id_price_group"=>$aRowPriceGroup['id'],
    						"pref"=>$aRowCat['pref']?$aRowCat['pref']:$aPref,
    						"childs_id"=>$aIdGroup,
    						"where"=>" and p.price>0 ".$sWhereParams,
    						"pgpf"=>1
    						));
    						
    						$aFilter[$sKey]['params'][$sParamKey]['count']=Db::GetOne($sFullSql);
    					}
					}else{
					    $aFilter[$sKey]['params'] = array();
					}
				}
				
				if($aFilter) foreach ($aFilter as $sKey => $aValue) {
				    
				    usort($aFilter[$sKey]['params'], function ($a, $b)
				    {
				        if ($a['count'] == $b['count']) {
				            return 0;
				        }
				        return ($a['count'] > $b['count']) ? -1 : 1;
				    });
				}
				
				
				
				if(Base::$aRequest['brand']){
					$aBrandSelected=explode(",", Base::$aRequest['brand']);
					
					foreach ($aBrandSelected as $sKeyBrand => $sValueBrand) {
					    $aBrandReplace=Db::GetRow("select name as name,title as title from cat where name='".$sValueBrand."'  ");
					    $aBrandReplace['name']=mb_strtolower($aBrandReplace['name']);
					    if($aBrandReplace) $aBrandSelected[$sKeyBrand]=$aBrandReplace;
					}
				}
				
				$sUrl='';
				$aUrl=array();
				if($aFilterParams) foreach ($aFilterParams as $sKey => $sValue) $aUrl[]='filter['.$sKey.']='.$sValue;
				
				if(Base::$aRequest["category"]) $sUrl.='/?action=price_group_filter&category='.Base::$aRequest["category"];
				if(Base::$aRequest["brand"]) $sUrl.="&brand=".Base::$aRequest["brand"];
				if($aUrl) $sUrl.='&'.implode("&", $aUrl);
				Base::$tpl->assign('sUrl',$sUrl);
				
				
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
				// LNB-57 filter fill end

				Base::$sText.=$oTable->GetTable();
			}
			if ($aRowPriceGroup['bottom_text'] && !Base::$aRequest["brand"] && !Base::$aRequest["step"]) {
			    
				Base::$tpl->assign('sSmartyTemplate', $aRowPriceGroup['bottom_text']);
				//Base::$tpl->assign('sBottomText',Base::$tpl->fetch('addon/smarty_template.tpl'));
				Base::$sText.=Base::$tpl->fetch('addon/smarty_template.tpl');
			}
			//Base::$sText.=Base::$tpl->fetch($this->sPrefix."/group_brand.tpl");			
		}
				
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParseAfter(&$aItem)
	{
	if($aItem) foreach ($aItem as $sKey=>$aValue) {
			if($aValue['id_cat_part']){
				$aCatPart[]=$aValue['id_cat_part'];
				$aKeyPart[$aValue['id_cat_part']]=$sKey;
			}
		}
		
		$aCodes=array();
		if($aItem) foreach ($aItem as $sKey=>$aValue) {
		    $aCodes[]=$aValue['code'];
		}
		if($aCodes) $aArtIds=TecdocDb::GetArts($aCodes);
		if($aArtIds) foreach ($aItem as $sKey=>$aValue) {
		    $sArtId='';
		    $sArtId=$aArtIds[$aValue['item_code']];
		    if($sArtId) {
		        $aArtId[]=$sArtId;
		        $aKeyArt[$sArtId]=$sKey;
		        $aItem[$sKey]['art_id']=$sArtId;
		    }
		}

		$aGraphic=TecdocDb::GetImages(array(
		    'aIdGraphic'=>$aArtId,
		    'order'=>'gra_sort desc'
		));
		if($aGraphic){
			foreach ($aItem as $sKey=>$aValue) {
				if (array_key_exists($aValue['item_code'],$aGraphic))
				$aItem[$sKey]['image']=$aGraphic[$aValue['item_code']]['img_path'];
			}
		}

		$aGraphic=TecdocDb::GetImages(array(
		    'aIdCatPart'=>$aCatPart,
		    'order'=>'gra_sort desc'
		));
		if($aGraphic){
			foreach ($aItem as $sKey=>$aValue) {
				if (array_key_exists($aValue['item_code'],$aGraphic))
				$aItem[$sKey]['image']=$aGraphic[$aValue['item_code']]['img_path'];
			}
		}
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
	}
	//-----------------------------------------------------------------------------------------------
	public function CallParse(&$aItem)
	{
	    
	    /*$aCodes=array();
	    if($aItem) foreach ($aItem as $sKey=>$aValue) {
	        $aCodes[]=$aValue['code'];
	    }
	    if($aCodes) $aArtIds=TecdocDb::GetArts($aCodes);
	    if($aArtIds) foreach ($aItem as $sKey=>$aValue) {
	        $sArtId='';
	        $sArtId=$aArtIds[$aValue['item_code']];
	        if($sArtId) {
	            $aArtId[]=$sArtId;
	            $aKeyArt[$sArtId]=$sKey;
	            $aItem[$sKey]['art_id']=$sArtId;
	        }
	    }
	    
	    $aGraphic=TecdocDb::GetImages(array(
	        'aIdGraphic'=>$aArtId,
	        'order'=>'gra_sort desc'
	    ));
	    if($aGraphic){
	        foreach ($aItem as $sKey=>$aValue) {
	            if (array_key_exists($aValue['item_code'],$aGraphic))
	                $aItem[$sKey]['image']=$aGraphic[$aValue['item_code']]['img_path'];
	        }
	    }
	    
	    $aGraphic=TecdocDb::GetImages(array(
	        'aIdCatPart'=>$aCatPart,
	        'order'=>'gra_sort desc'
	    ));
	    if($aGraphic){
	        foreach ($aItem as $sKey=>$aValue) {
	            if (array_key_exists($aValue['item_code'],$aGraphic))
	                $aItem[$sKey]['image']=$aGraphic[$aValue['item_code']]['img_path'];
	        }
	    }*/
	    
		//add sort
		if ($aItem) {
		    foreach ($aItem as $sKey=>$aData) {
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
		    $sType = SORT_STRING;
		    if (!Base::$aRequest['sort'] || Base::$aRequest['sort'] == 'price'
		        || Base::$aRequest['sort'] == 'term' || Base::$aRequest['sort'] == 'term_day'
		        || Base::$aRequest['sort'] == 'stock')
		            $sType = SORT_NUMERIC;
		         
		        if (Base::$aRequest['way'] && Base::$aRequest['way'] == 'down')
		            array_multisort ($aTmpSort, SORT_DESC, $sType, $aItem);
		        else
		            array_multisort ($aTmpSort, SORT_ASC, $sType, $aItem);
		}
		$aTmp=0;
		$aTmpSort=0;
		//end
		
		if($aItem) {
		    $this->PosPriceParse($aItem,false,false);
		}
	}
	//-----------------------------------------------------------------------------------------------
	public function GetTabs(){
// 		$sCache_name .= 'main_tabs';
// 		$sAdd = '';
// 		if (Base::$aRequest['locale'] && Base::$aRequest['locale']!='ru') {
// 			$sCache_name .= '_'.Base::$aRequest['locale'];
// 			$sAdd = '_'.Base::$aRequest['locale'];
// 		}
		
// 		//need refresh after interval
// 		$iNowDate=time();
// 		$iLastRefresh=Base::GetConstant("home:price_group_main_tabs_last_update".$sAdd,time());
// 		if(($iLastRefresh+(Base::GetConstant("home:price_group_main_tabs_interval".$sAdd,"60")*60)) <= $iNowDate) {
// 			//need update
			$aData=array(
					'table'=>'price_group',
					'where'=>" and t.level=0 and t.visible=1 and t.is_menu=1 order by t.name",
			);
			$aGroups=Base::$language->GetLocalizedAll($aData);
// 			FileCache::SetValue('Home', $sCache_name, $aGroups);
// 		}
// 		else{
// 			if(!($aGroups=FileCache::GetValue('Home', $sCache_name))) {
// 				$aData=array(
// 						'table'=>'price_group',
// 						'where'=>" and t.level=0 and t.visible=1 and t.is_menu=1 order by t.name",
// 				);
// 				$aGroups=Base::$language->GetLocalizedAll($aData);
// 		  		FileCache::SetValue('Home', $sCache_name, $aGroups);
// 			}
// 		}	 
			
	    if($aGroups) {
    		foreach ($aGroups as $sBaseKey => $aBaseValue){
    			$aData=array(
    					'table'=>'price_group',
    					'where'=>" and t.level=1 and t.id_parent='".$aBaseValue['id']."' and t.visible=1 order by t.name",
    			);
    			$aGroups[$sBaseKey]['childs']=Base::$language->GetLocalizedAll($aData);
    			if ($aGroups[$sBaseKey]['childs'])
    			foreach ($aGroups[$sBaseKey]['childs'] as $sKey => $aValue){
    				$aData=array(
    						'table'=>'price_group',
    						'where'=>" and t.level=2 and t.id_parent='".$aValue['id']."' and t.visible=1 order by t.name",
    				);
    				$aGroups[$sBaseKey]['childs'][$sKey]['childs']=Base::$language->GetLocalizedAll($aData);
    			}
    		}
	    }
	    
	    $aRubricatorMenu=Rubricator::GetMainMenu();
	    if(!$aRubricatorMenu) $aRubricatorMenu=array();
	    if(!$aGroups) $aGroups=array();
	    
	    $aMerged=array_merge($aRubricatorMenu,$aGroups);
	    $aMerged=self::SortArr($aMerged);
		
		Base::$tpl->assign('aGroups', $aMerged);
	}
	//-----------------------------------------------------------------------------------------------
	public function GetMainGroups(){
// 		$sCache_name .= 'main_groups';
// 		$sAdd = '';
// 		if (Base::$aRequest['locale'] && Base::$aRequest['locale']!='ru') {
// 			$sCache_name .= '_'.Base::$aRequest['locale'];
// 			$sAdd = '_'.Base::$aRequest['locale'];
// 		}
// 		//need refresh after interval
// 		$iNowDate=time();
// 		$iLastRefresh=Base::GetConstant("home:price_group_main_groups_last_update".$sAdd,time());
// 		if(!($aGroups=FileCache::GetValue('Home', $sCache_name)) || ($iLastRefresh+(Base::GetConstant("home:price_group_main_groups_interval".$sAdd,"60")*60)) <= $iNowDate) {
// 	    	//need update
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
    				if($aChildsData) $aGroups[$sKey]['childs']=array_slice($aChildsData,0,3);
    			}
    		}
//     		FileCache::SetValue('Home', $sCache_name, $aGroups);
//     	}
    	
    	$aGroupsRubricator=Rubricator::GetMain();
    	if(!$aGroups) $aGroups=array();
    	if(!$aGroupsRubricator) $aGroupsRubricator=array();
    	
    	$aMerged=array_merge($aGroupsRubricator,$aGroups);
    	//$aMerged=self::SortArr($aMerged);
		
		Base::$tpl->assign('aMainGroups', $aMerged);
	}
	//-----------------------------------------------------------------------------------------------
	public function Filter()
	{
		// filter
	    $sUrl=self::GenerateFilterOldLink(Base::$aRequest);
		Base::Redirect($sUrl);
	}
	//-----------------------------------------------------------------------------------------------
	public function SortTablePriceGroup() {
	    Base::$tpl->assign('sTablePriceSort','price');
	    Base::$tpl->assign('sTablePriceSortWay','asc');
	
	    if (!Base::$aRequest['sort'])
	        Base::$aRequest['sort'] = 'price';
	    	
	    if (!Base::$aRequest['way'])
	        Base::$aRequest['way'] = 'up';
	    	
	    Base::$tpl->assign('sTablePriceSort',Base::$aRequest['sort']);
	    Base::$tpl->assign('sTablePriceSortWay',Base::$aRequest['way']);
	    if (strpos($_SERVER['REQUEST_URI'],'?') !== false) {
	        $iSeoUrlAmp=true;
	    } else {
	        $iSeoUrlAmp=false;
	    }
	    Base::$tpl->assign('iSeoUrlAmp',$iSeoUrlAmp);
	    $sSeoUrl=$_SERVER['REQUEST_URI'];
	    
	    if($iSeoUrlAmp) {
	        $iSortPos=strpos($sSeoUrl,'&sort=');
	        if($iSortPos!==false) {
	            $sTmp=substr($sSeoUrl, $iSortPos);
	             
	            $iNextAmp=strpos($sTmp,'&',2);
	            if($iNextAmp!==false) {
	                $sTmp=substr($sTmp, 0,$iNextAmp);
	            }
	            $sSeoUrl=str_replace($sTmp, '', $sSeoUrl);
	        }
	        $sSeoUrl=str_replace('&way=down','',$sSeoUrl);
	        $sSeoUrl=str_replace('&way=up','',$sSeoUrl);
	    } else {
	        //http://auto.lc/select/smazki/sort=code/way=down
	        
	        $iSortPos=strpos($sSeoUrl,'/sort=');
	        if($iSortPos!==false) {
	            $sTmp=substr($sSeoUrl, $iSortPos);
	             
	            $iNextAmp=strpos($sTmp,'/',2);
	            if($iNextAmp!==false) {
	                $sTmp=substr($sTmp, 0,$iNextAmp);
	            }
	            $sSeoUrl=str_replace($sTmp, '', $sSeoUrl);
	        }
	        $sSeoUrl=str_replace('/way=down','',$sSeoUrl);
	        $sSeoUrl=str_replace('/way=up','',$sSeoUrl);
	        if(Base::$aRequest['action']=='price_group'){
	           $sSeoUrl=str_replace("/o/".Base::$aRequest['sort'].'-'.Base::$aRequest['way'],'',$sSeoUrl);
	           Base::$tpl->assign('sNewSeo',1);
	        }
	    }
	
	    Base::$tpl->assign('sSeoUrl',$sSeoUrl);
	
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
    	        $aPriceGroupBrand[$iKBrand]['url']=mb_strtolower($sTmp);
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
    	        $sTmp.="remove=brand"."&value=".mb_strtolower($aVBrand['name']);
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

	    $sLink=self::GenerateFilterOldLink($aRequestData);
 	    $aLink=parse_url($sLink);
 	    parse_str($aLink['query'],$aRequestData);
 	    $aRequestData['action']=Base::$aRequest['action'];
 	    $aRequestData['category']=Base::$aRequest['category'];
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
	        $sUrl="/".Language::$sLocale."/select/";
	    }else{
	        $sUrl="/select/";
	    }
	    $sUrl.=$aData['category']."/";
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
    	        $aFilteredParams=Db::GetAssoc("select LOWER(table_),id from handbook where table_ in ('".implode("','", array_keys($aData['filter']))."') order by id");
    	        if($aFilteredParams) {
    	            foreach ($aData['filter'] as $sTable => $iIdParam) {
    	                $sTable=mb_strtolower($sTable);
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
	    return str_ireplace("//", "/", $sUrl);
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
	    $sUrl='';
	    $aUrl=array();
	    if($aRequestData["category"]) $sUrl.='/select/'.$aRequestData["category"].'/';
	
	    if(!$aRequestData['remove_all']) {
	        $aFilterParams=String::FilterRequestData($aRequestData['filter']);
	        $sWhereParams='';
	        if($aFilterParams) foreach ($aFilterParams as $sKey => $sValue) {
	            if($sKey==mb_strtolower($aRequestData['add'],'UTF-8')) {
	                $sValue.=','.$aRequestData['value'];
	                $aFilterParams[$sKey]=$sValue;
	            }
	            	
	            if($sKey==mb_strtolower($aRequestData['remove'],'UTF-8')) {
	                $aTmpVal=explode(",", $sValue);
	                if($aTmpVal) foreach ($aTmpVal as $sTmpValKey => $sTmpValVal) {
	                    if($sTmpValVal==mb_strtolower($aRequestData['value'])) unset($aTmpVal[$sTmpValKey]);
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
	                if($sTmpValVal==mb_strtolower($aRequestData['value'])) unset($aTmpVal[$sTmpValKey]);
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
// 	    $sUrl=str_ireplace(",", "_", $sUrl);

	    return str_ireplace("//", "/", $sUrl);
	}
	//-----------------------------------------------------------------------------------------------
	public static function SortArr($aSort){
	
	    usort($aSort, function ($a, $b)
	    {
	        if ($a['sort'] == $b['sort']) {
	            return 0;
	        }
	        return ($a['sort'] < $b['sort']) ? -1 : 1;
	    });
	    //sort by lvl2
	    foreach ($aSort as $sKey1=>$aVal2){
	        if ($aVal2['childs']){
	            usort($aVal2['childs'], function ($a, $b)
	            {
	                if ($a['sort'] == $b['sort']) {
	                    return 0;
	                }
	                return ($a['sort'] < $b['sort']) ? -1 : 1;
	            });
	
	            $aSort[$sKey1]['childs']= $aVal2['childs'];
	
	            if (!$aSort[$sKey1]['id']){
	                unset($aSort[$sKey1]);
	            }
	            //sort by lvl3
	            foreach ($aVal2['childs'] as $sKey3=>$aVal3){
	                if ($aVal3['childs']){
	                    usort($aVal3['childs'], function ($a, $b)
	                    {
	                        if ($a['sort'] == $b['sort']) {
	                            return 0;
	                        }
	                        return ($a['sort'] < $b['sort']) ? -1 : 1;
	                    });
	
	                    $aSort[$sKey1]['childs'][$sKey3]['childs']= $aVal3['childs'];
	                }
	            }
	        }
	    }
	    return $aSort;
	}
	//-----------------------------------------------------------------------------------------------
}
?>