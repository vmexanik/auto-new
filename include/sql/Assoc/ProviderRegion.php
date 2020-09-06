<?
function SqlAssocProviderRegionCall($aData) {

	if ($aData['all']) {
		$sWhere.=" ";
	} else {
		$sWhere.=" and pr.visible=1";
	}

	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by pr.name ";
	}

	if (!$aData['field']) {
		$sField.=" , pr.name ";
	}
	else $sField.=$aData['field'];

	if ($aData['multiple']) {
		$sField.=", pr.*";
	}


	$sSql="
	select  pr.id
		".$sField."
	from provider_region as pr
	inner join provider_region_way as prw on pr.id_provider_region_way=prw.id
	where 1=1
		".$sWhere
	.$sOrder;

	return $sSql;
}
?>