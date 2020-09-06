<?
function SqlCalculatorItemCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ci.id='".$aData['id']."'";
	}

	$sSql="select ci.*
				,u.login , concat(pr.code,' - ',prw.name) as provider_region_name
			from calculator_item as ci
			inner join user as u on ci.id_user=u.id
     		inner join provider_region as pr on ci.id_provider_region = pr.id
     		inner join provider_region_way as prw on pr.id_provider_region_way=prw.id
			where 1=1
				".$sWhere."
			group by ci.id";

	return $sSql;
}
?>