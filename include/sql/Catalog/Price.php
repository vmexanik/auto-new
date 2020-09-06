<?
function SqlCatalogPriceCall($aData)
{
	$sWhere.=$aData['where'];
	
	if ($aData['pref'] && is_array($aData['pref'])) {
		$sWhere.=" and c.pref in('".implode("','",$aData['pref'])."')";
	}

	if ($aData['sId'])
		$sWhere .= " and p.id = '".$aData['sId']."'";
	
	if(!$aData['aCode']) $aData['aCode']=array();
	$inCode = "'".implode("','",$aData['aCode'])."'";

	if(!$aData['aItemCode']) $aData['aItemCode']=array();
	$inItemCode = "'".implode("','",$aData['aItemCode'])."'";

	if (!$aData["customer_discount"]) $aData["customer_discount"]=0;

	if (!$aData['aCode'])
	{
		if (strpos($aData['aItemCode'][0],"ZZZ_")!==false) {
			$sField.=" , 0 as code_visible";
			$sWhere.=" and p.id='".str_replace("ZZZ_","",$aData['aItemCode'][0])."'";
		} else {
			if ($aData['childs']){
				//level 0
				$sInParam=" and pgs.id_price_group in (";
				foreach ($aData['childs'] as $sKey => $aValue){
					$sInParam.=$aValue['id'];
					if (next($aData['childs'])) $sInParam.=",";
				}
				if(isset($aData['id_price_group'])) $sInParam.=",".$aData['id_price_group'];
				$sWhere.=$sInParam.")";
			}
			elseif ($aData['childs_id']) {
				$sInParam=" and pgs.id_price_group in (".implode(",",$aData['childs_id']);
				if (isset($aData['id_price_group']))
					$sInParam.= ','.$aData['id_price_group'];
			
				$sWhere.=$sInParam.")";
			}
			else {
				//level 1
				if (isset($aData['id_price_group']) || $aData['all_price_group']) {
					if (!$aData['all_price_group']){
						if ($aData['id_price_group'] == 0)
							$sWhere.=" and pgs.id_price_group is null";
						else
							$sWhere.=" and pgs.id_price_group='".$aData['id_price_group']."'";
					}
				} elseif ($inItemCode !="''") {
					$sWhere.=" and p.item_code in (".$inItemCode.")";
				}
				elseif (!$aData['is_not_check_item_code'])
					$sWhere .= ' and 0=1';
			}
		}

	} else {
      $sIds='';
      foreach($aData['aCode'] as $aValue) {
         if(strpos($aValue,"ZZZ_")!==false) {
            if($sIds) $sIds.=",".str_replace("ZZZ_","",$aValue);
            else $sIds=str_replace("ZZZ_","",$aValue);  
         }
      }
      if($sIds) {
         $sField.=" , 0 as code_visible";
         $sWhere.=" and p.id in (".$sIds.") ";  
      }
      else {
			if ($aData['is_advance'] && $aData['pref'] && $aData['aCode']) {
				$sWhere.=" and p.item_code like '".$aData['pref']."\_%".$aData['aCode'][0]."%' and p.price>0";
				$sGroup.=" group by p.item_code ";
			} elseif ($inItemCode != "''") {
				$sWhere.=" and (p.code in (".$inCode.") or p.item_code in (".$inItemCode."))";
			}
			else
				$sWhere.=" and p.code in (".$inCode.")";
		}
	}

	if ($aData['id_provider'])
	{
		$sWhere.=" and p.id_provider=".$aData['id_provider'];
	}

	//$dTax=Base::GetConstant("price:tax",0)/100;
	//$dDiscountDefault=Base::GetConstant("price:discount_default",0)/100;
	$dMarginMin=String::GetDecimal(Base::GetConstant("price:margin_min",1));
	if (!$dCatMargin) $dCatMargin=0;

	$iNotChangeRecalc = 0;
	if ($aData['not_change_recalc'])
		$iNotChangeRecalc = $aData['not_change_recalc'];
		
	if (Auth::$aUser['type_'] == 'manager' && $iNotChangeRecalc == 0) {
		$aData["customer_discount"] = 0;
		$aCustomer = Auth::$aUser;
		$aCustomer['type_'] = 'customer';
		$aCustomer['discount_static'] = 0;
		$aCustomer['discount_dynamic'] = 0;
		$aCustomer['group_discount'] = 0;
		if (Auth::$aUser['type_price'] == 'group' || Auth::$aUser['type_price'] == 'none') {
			$aGroup = Db::GetRow(Base::GetSql('CustomerGroup',array('id'=>Auth::$aUser['id_type_price_group'])));
			if (!$aGroup) {
				Auth::$aUser['id_type_price_group'] = Language::getConstant('IdDefaultPriceGroupManager',1);
				$aGroup = Db::GetRow(Base::GetSql('CustomerGroup',array('id'=>Auth::$aUser['id_type_price_group'])));
			}
			if ($aGroup) {
			    $aCustomer['group_discount'] = $aGroup['group_discount'];
		        $aCustomer['cg_visible'] = $aGroup['visible'];
			}
		} elseif (Auth::$aUser['type_price'] == 'user' && Auth::$aUser['id_type_price_user']) {
			$aUser = Base::$db->GetRow( Base::GetSql('Customer',array('id'=>Auth::$aUser['id_type_price_user'])));
			if ($aUser) {
			    $aCustomer = $aUser;
			}
		}
		$aData['customer_discount'] = Discount::CustomerDiscount($aCustomer);
	}
	
	if(Base::GetConstant('complex_margin_enble','0')==1) {
    	$sMarginProvider="if(mc.margin <> 0, mc.margin, pg.group_margin)";
    	$sMarginGroup="if(mg.margin <> 0, mg.margin, ".$sMarginProvider.")";
    	$sMarginPrice="if(mp.margin<>0,mp.margin,pg.group_margin)";
    	
    	$sPriceCalc="p.price/cu.value*(1+".$sMarginPrice."/100+".$dCatMargin.")*(100-".$aData["customer_discount"].")/100";
    	$sPriceMinCalc="(p.price/cu.value)*".$dMarginMin;
    	$sPriceWithMargin="if(".$sPriceCalc.">".$sPriceMinCalc.",".$sPriceCalc.", ".$sPriceMinCalc.")";
    	$sFieldPrice=", round(".$sPriceWithMargin.",2) as price";
	} else {
	    $sPriceCalc="p.price/cu.value*(1+pg.group_margin/100+".$dCatMargin.")*(100-".$aData["customer_discount"].")/100";
	    $sPriceMinCalc="(p.price/cu.value)*".$dMarginMin;
	    $sFieldPrice=", round(if(".$sPriceCalc.">".$sPriceMinCalc.",".$sPriceCalc.", ".$sPriceMinCalc."),2) as price";
	}

	$sField.=$sFieldPrice.$sFieldPrice."_order ";

	if ($aData['sId']!="" || (Base::GetConstant("global:hide_code",1) && !Auth::$aUser['id'])) {
// 		$sField.=" , if(p.code in (".$inCode."),p.code, INSERT(ifnull(cp.code, p.code), 2, 20, '***')) as code
// 		, if(p.code in (".$inCode."),p.item_code,concat('ZZZ_',p.id)) as item_code
// 		, if(p.code in (".$inCode."),concat(p.item_code,'::',up.id_user),concat('ZZZ_',p.id,'::',up.id_user)) as code_provider
// 		, if(p.code in (".$inCode."),0,1) as hide_code";
	} else {
		$sField.=" , p.code as code, concat(p.item_code,'::',up.id_user) as code_provider";
	}

	if (!$aData['sCode']) {
		$aData['sCode']=0;
	}

	if ($aData['pref'] && !is_array($aData['pref'])) {
		if( $aData['pgpf']){
			$sWhere.=" and p.pref='".$aData['pref']."'";
		}
		else {
			if(!is_array($aData['pref'])) $sWhere.=" and p.item_code like '".$aData['pref']."\\_%'";
		}
	}

	if ($aData['term_to']) {
		$sWhere.=" and pg.term_to<=".$aData['term_to'];
	}

	if ($aData['group_pref']) {
		$sGroup.=" group by p.pref ";
	}

	if ($aData['pref_order']) {
		$sField.=" , if(p.pref='".$aData['pref_order']."',0,1) as pref_order ";
	} else {
		$sField.=" , 0 as pref_order ";
	}

	if ($aData['code_order']) {
		$sField.=" , if(p.code='".$aData['code_order']."',0,1) as code_order ";
	} else {
		$sField.=" , 0 as code_order ";
	}

	if($aData['order']) {
	    $sOrder=$aData['order'];
	} else {
	    //default
	    if(Base::GetConstant('complex_margin_enble','0')){
	        $sOrder=" t.price asc ";
	    } else {
	        $sOrder=" p.price asc ";
	    }
	}

	if ($aData['sort_stock_filtered'])
		$sOrder = " ,if ((CONVERT(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(p.stock,'>',''),',','.'),'Н','1'),'-',''),'M','1'), '<',''),'+',''),'++',''),'+++',''),'есть','1'),'X',''),'XX',''),'XXX',''), SIGNED)) > 0,1,0) desc " . $sOrder;

	if ($aData['stock_exist']) {
		$sWhere .=" and CONVERT(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(p.stock,'>',''),',','.'),'Н','1'),'-',''),'M','1'), '<',''),'+',''),'++',''),'+++',''),'есть','1'),'X',''),'XX',''),'XXX',''), SIGNED) > 0 ";
	}

	$sName = "coalesce(if (cp.name_rus is NULL || cp.name_rus='',NULL, cp.name_rus),p.part_rus,'')";
	if (MultiLanguage::IsLocale())
		$sName = "if (coalesce(if (cp.name_".Language::$sLocale." is NULL || cp.name_".Language::$sLocale."='',NULL, cp.name_".Language::$sLocale."),p.part_".Language::$sLocale.",'')<>'',
					coalesce(if (cp.name_".Language::$sLocale." is NULL || cp.name_".Language::$sLocale."='',NULL, cp.name_".Language::$sLocale."),p.part_".Language::$sLocale.",''), 
					coalesce(if (cp.name_rus is NULL || cp.name_rus='',NULL,cp.name_rus),p.part_rus,''))";
	
	if (Base::GetConstant('price:term_from_provider',1)) {
	    $sTermDay=",up.term as term_day,up.term as term ";
	} else {
	    $sTermDay=",p.term as term_day,p.term as term ";
    }
	
	if(MultiLanguage::IsLocale()){
	    $sInformation = ", concat(ifnull(cp.information_".Language::$sLocale.", ''), ' ', ifnull(p.description,'')) as information";
	}else{
	    $sInformation = ", concat(ifnull(cp.information, ''), ' ', ifnull(p.description,'')) as information";
	}
	
	if(Base::GetConstant('complex_margin_enble','0') && Base::GetConstant('complex_margin_async','0')==0) {
	    //margin online
	    $sSql="select * from (select";
	} elseif(Base::GetConstant('complex_margin_enble','0') && Base::GetConstant('complex_margin_async','0')==1) {
	    //margin async
	    $sSql="select * from (select";
	} else {
	    //default
	    $sSql="select ";
	}
	
	if(Base::GetConstant('complex_margin_enble','0')) {
	    $sSql.="
     pgs.id_price_group as dbg_id_price_group,
     mp.id_price_group as dbg_mp_id_price_group,
     p.id_provider as dbg_id_provider,
     mp.id_provider as dbg_mp_id_provider,
     up.id_currency as dbg_id_currency,
     c.id as dbg_id_cat,
     mp.id_cat as dbg_mp_id_cat,
	 mp.id as margin_id,
     if(mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=c.id,80,
     if(mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=0,50,
     if(mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=c.id,60,
     if(mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=c.id,70,
     if(mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=0,40,
     if(mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=0,10,
     if(mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=0,20,
     if(mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=c.id,30,
    
     if(mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=c.id,8,
     if(mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=0,5,
     if(mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=c.id,6,
     if(mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=c.id,7,
     if(mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=0,4,
     if(mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=c.id,3,
     if(mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=0,2,
     if(mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=0,1,			0)))))))) )))))))) as priority
	 ,mp.margin,";
	} else {
	    //default
	}
	
	$sSql.="
	  p.id
	 ,p.item_code
	 ,p.id_provider
	 ,p.code_in
	 ,p.pref
	 ,p.cat
	 ,p.post_date
	 ,p.stock
	 ,p.description
	 ,ifnull(pgs.id_price_group,0)
	 ,p.number_min
	 ,p.is_restored
	 ,cu.value as kurs_currency
	 ,round(p.price/cu.value,2) as price_original_one_currency
	 , p.price as price_original
	 , c.title as make 
	 , c.name as cat_name
	 , c.id_tof as id_brand
	 , c.title as brand
	 , c.id as brand_id
	 , if(c.image!='' and c.is_use_own_logo=1,c.image,if(c.image_tecdoc<>'',concat( '".Base::$aGeneralConf['TecDocUrl']."/imgbank/tcd/' , c.image_tecdoc),c.image)) as image_logo
	 , concat(ifnull(cp.name,''),' ',ifnull(p.part_eng,'')) as name
	 , ".$sName." as name_translate
	 , ifnull(cp.id, '') as id_cat_part
	 , cp.hide_tof_image
	 , p.code as code_
	 , p.item_code as item_code_
	 ".$sInformation."
	 , ifnull(concat('ZZZ_',p.id),'') as zzz_code
	 , if(p.code in (".$aData['sCode']."),0,1) as crsord
	 , up.name as provider
	 , up.name as provider_name
	 , up.code_name as provider_code_name
	 , up.code_delivery
	 , up.is_our_store
	 ".$sTermDay."
	 , up.id_currency
	 , cu.code as code_currency
	 , ifnull(prg.name,'') as price_group_name
	 , ifnull(prg.code_name,'') as price_group_code_name
	 , CONVERT(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(p.stock,'>',''),',','.'),'Н','1'),'-',''),'M','1'), '<',''),'+',''),'++',''),'+++',''),'есть','1'),'X',''),'XX',''),'XXX',''), SIGNED) as stock_filtered
	 , pgs.id_price_group
	 "
	.$sField.
	" from price as p
	 left join price_group_assign as pgs on pgs.item_code=p.item_code
	 left join cat_part as cp on cp.item_code=p.item_code
	 inner join cat as c on p.pref=c.pref and c.visible=1
	 inner join provider_virtual as pv on p.id_provider=pv.id_provider
	 inner join user_provider as up on pv.id_provider_virtual=up.id_user
	 inner join provider_group as pg on up.id_provider_group=pg.id
	 inner join user as u on up.id_user=u.id and u.visible=1
	 inner join currency as cu FORCE INDEX (PRIMARY) on up.id_currency=cu.id 
	 left join price_group prg on prg.id=pgs.id_price_group
	 left join price_group_param as pgp on pgp.item_code=p.item_code
	 ";

     if(Base::GetConstant('complex_margin_enble','0') && Base::GetConstant('complex_margin_async','0')==0) {
         //margin online
    	$sSql.="left join margin_price as mp on 
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=c.id) or 
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=0) or 
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=c.id) or 
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=c.id) or 
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=0) or 
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=0) or 
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=0) or 
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=up.id_currency and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=c.id)
        or
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=c.id) or 
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=p.id_provider and mp.id_cat=0) or 
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=c.id) or 
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=c.id) or 
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=ifnull(pgs.id_price_group,0) and mp.id_provider=0 and mp.id_cat=0) or 
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=0) or 
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=p.id_provider and mp.id_cat=0) or 
        ( (mp.price_before < p.price and mp.price_after>=p.price) and mp.visible=1 and mp.id_currency=0 and mp.id_price_group=0 and mp.id_provider=0 and mp.id_cat=c.id)
		";
	 } elseif(Base::GetConstant('complex_margin_enble','0') && Base::GetConstant('complex_margin_async','0')==1) {
	     //margin async
     	 $sSql.="inner join margin_price as mp on p.id_margin_price=mp.id ";
	 } else {
	     //default price margin
	 }

	$sSql.=" where 1=1
	 ".$sWhere
	.$sGroup;
	
	if(Base::GetConstant('complex_margin_enble','0') && Base::GetConstant('complex_margin_async','0')==0) {
	    //margin online
	    $sSql.=" having 1 order by priority desc, mp.id desc ) as t group by t.id order by ".$sOrder;
    } elseif(Base::GetConstant('complex_margin_enble','0') && Base::GetConstant('complex_margin_async','0')==1) {
        //margin async
	    $sSql.=" having 1 ) as t order by ".$sOrder;
	} else {
	    //default
	    $sSql.=" order by ".$sOrder;
	}
	
//   	Db::Debug($sSql);
    
	return $sSql;
}
?>
