<?
function SqlCatalogPriceForCountCall($aData)
{
	$sWhere.=$aData['where'];
	
	if ($aData['pref'] && is_array($aData['pref'])) {
		$sWhere.=" and c.pref in('".implode("','",$aData['pref'])."')";
	}

	if($aData['aCode']) $inCode = "'".implode("','",$aData['aCode'])."'";

	if($aData['aItemCode']) $inItemCode = "'".implode("','",$aData['aItemCode'])."'";

	if (!$aData["customer_discount"]) $aData["customer_discount"]=0;

	if (!$aData['aCode'])
	{
		if (strpos($aData['aItemCode'][0],"ZZZ_")!==false) {
			//$sField.=" , 0 as code_visible";
			$sWhere.=" and p.id='".str_replace("ZZZ_","",$aData['aItemCode'][0])."'";
		} else {
			if ($aData['childs']){
				//level 0
				$sInParam=" and pgs.id_price_group in (";
				foreach ($aData['childs'] as $sKey => $aValue){
					if (is_array($aValue))
						$sInParam.=$aValue['id'];
					else 
						$sInParam.=$aValue;
					if (next($aData['childs'])) $sInParam.=",";
				}
				if (isset($aData['id_price_group']))
					$sInParam.= ','.$aData['id_price_group'];
				
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
					if (!$aData['all_price_group']) $sWhere.=" and pgs.id_price_group='".$aData['id_price_group']."'";
				} else {
					if ($inItemCode) $sWhere.=" and p.item_code in (".$inItemCode.")";
				}
			}
		}
	} else {
		if ($aData['is_advance'] && $aData['pref'] && $aData['aCode'] && !is_array($aData['pref'])) {
			$sWhere.=" and p.item_code like '".$aData['pref']."\_%".$aData['aCode'][0]."%' and p.price>0";
			$sGroup.=" group by p.item_code ";
		} else {
			if($inCode) $sCodeWhere="p.code in (".$inCode.")";
			if($inItemCode) $sItemCodeWhere="p.item_code in (".$inItemCode.")";
			
			if($sCodeWhere && !$sItemCodeWhere) $sWhere.=" and ( ".$sCodeWhere." )";
			if(!$sCodeWhere && $sItemCodeWhere) $sWhere.=" and ( ".$sItemCodeWhere." )";
			if($sCodeWhere && $sItemCodeWhere) $sWhere.=" and (".$sCodeWhere." or ".$sItemCodeWhere.")";
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

	/*$sFieldPrice=", round(p.price/cu.value,2) as price";

	$sField.=$sFieldPrice.$sFieldPrice."_order ";
	if ($aData['sId']!="" || (Base::GetConstant("global:hide_code",1) && !Auth::$aUser['id'])) {
		$sField.=" , if(p.code in (".$inCode."),p.code, INSERT(ifnull(cp.code, p.code), 2, 20, '***')) as code
		, if(p.code in (".$inCode."),p.item_code,concat('ZZZ_',p.id)) as item_code
		, if(p.code in (".$inCode."),concat(p.item_code,'::',up.id_user),concat('ZZZ_',p.id,'::',up.id_user)) as code_provider
		, if(p.code in (".$inCode."),0,1) as hide_code";
	} else {
		$sField.=" , p.code as code, concat(p.item_code,'::',up.id_user) as code_provider";
	}*/

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
		$sFieldAdd="lower(c.name) as c_name,";
	}
	
	/*if ($aData['pref_order']) {
		$sField.=" , if(p.pref='".$aData['pref_order']."',0,1) as pref_order ";
	} else {
		$sField.=" , 0 as pref_order ";
	}

	if ($aData['code_order']) {
		$sField.=" , if(p.code='".$aData['code_order']."',0,1) as code_order ";
	} else {
		$sField.=" , 0 as code_order ";
	}*/

	/*if ($aData['order']) {
		$sOrder=" ,".$aData['order']." ";
	}
	*/
	if ($aData['inIdProvider']) {
		$sWhere.=" and p.id_provider in('".implode("','",$aData['inIdProvider'])."')";
	}
	if ($aData['join']) {
		$sJoin.=$aData['join'];
	}
	/*if ($aData['field']) {
		$sField.=$aData['field'];
	}*/
	
	if ($aData['stock_exist']) {
		$sWhere .=" and CONVERT(replace(replace(replace(replace(replace(replace(replace(replace(replace(p.stock,'>',''),'<',''),'+',''),'++',''),'+++',''),'есть','1'),'X',''),'XX',''),'XXX',''), SIGNED) > 0 ";
	}

	if (!$aData['bRubricatorFilter']){
		$sJoinPGA="	 left join price_group_assign as pgs on pgs.item_code=p.item_code
		  left join price_group prg on prg.id=pgs.id_price_group
		 ";
	}
	
	$sSql="select ".$sFieldAdd."count(distinct(p.item_code))
	 from price as p
	 ".$sJoinPGA."
	 inner join cat as c on p.pref=c.pref and c.visible=1
	 inner join user_provider as up on p.id_provider=up.id_user
	 inner join user as u on up.id_user=u.id and u.visible=1
	 inner join currency as cu on up.id_currency=cu.id 
	 inner join provider_group as pg on up.id_provider_group=pg.id
	 left join price_group_param as pgp on pgp.item_code=p.item_code
	 
	 where 1=1
	 ".$sWhere
	.$sGroup;

	return $sSql;
}
?>
