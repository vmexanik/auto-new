<?
function SqlOfficeAccountLogCall($aData)
{
	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and oal.id='".$aData['id']."'";
	}

	if ($aData['order']) {
		$sOrder.=$aData['order'];
	}

	if ($aData['join_account']) {
		$sJoin.="left join account as a on oal.id_subconto1=a.id";
		$sField.=" , a.title as account_title, a.name as account_name";
	}
	if ($aData['id_subconto1']) {
		$sWhere.=" and ual.id_subconto1 = '".$aData['id_subconto1']."'";
	}
	
	if ($aData['sum']) {
		$sSelectedRow = " sum(".$aData['sum'].") as total ";
	}else {
		$sSelectedRow = " oal.*,o.name as office_name";
	}
	$query = "select  ".$sSelectedRow.$sField."
			from office_account_log oal
			left join office as o on o.id=oal.id_office
			".$sJoin;

	if ($aData['join1']) {
		$sqlPart1 = $aData['join1'];
	}

	if ($aData['join2']) {
		$sqlPart2 =" union ".$query." ".$aData['join2']." where 1=1 ".$sWhere;
	}

	$sSql = $query." ".$sqlPart1." where 1=1 ".$sWhere.$sqlPart2." ".$sOrder;

	return $sSql;
}
?>