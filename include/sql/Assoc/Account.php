<?
function SqlAssocAccountCall($aData)
{
	if ($aData['order']) {
		$sOrder=$aData['order'];
	} else {
		$sOrder=" order by a.name ";
	}

	if ($aData['multiple']) {
		$sField.=", a.*";
	}
	if ($aData['visible']) {
		$sWhere.=" and a.visible='".$aData['visible']."'";
	}
	if ($aData['in_use_bv']) {
		$sWhere.=" and a.in_use_bv='".$aData['in_use_bv']."'";
	}
	if ($aData['in_use_pko']) {
		$sWhere.=" and a.in_use_pko='".$aData['in_use_pko']."'";
	}
	if ($aData['in_use_rko']) {
		$sWhere.=" and a.in_use_rko='".$aData['in_use_rko']."'";
	}
		
	$sSql="select a.id , a.title
		".$sField."
	from account as a
	where 1=1 /*and visible=1*/ 
	".$sWhere
	.$sOrder;

	return $sSql;
}
?>