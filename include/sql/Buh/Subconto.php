<?
function SqlBuhSubcontoCall($aData)
{
	$aBuhMultiple=Db::GetAssoc("Assoc/Buh",array('multiple'=>1));

	//	if ($aData['order']) {
	//		$sOrder=$aData['order'];
	//	} else {
	//		$sOrder=" order by b.id ";
	//	}

	//	if ($aData['multiple']) {
	//		$sField.=", b.*";
	//	}

	//	if ($aData['visible']) {
	//		$sWhere.=" and b.visible='".$aData['visible']."'";
	//	}
	
	if ($aBuhMultiple[$aData['id_buh']]['subconto1']=='user') {
		$sField=", login as name ";
	} else {
		$sField=", name ";
	}
	
	if ($aBuhMultiple[$aData['id_buh']]['subconto1'] && $aData['id_buh_subconto1']) {
		$sTable=$aBuhMultiple[$aData['id_buh']]['subconto1'];
		$sWhere.=" and id=".$aData['id_buh_subconto1'];
	} else {
		return " select '' as id, '' as name ";
	}

	$sSql=" select t_sub1.id 
	".$sField."
	from ".$sTable." as t_sub1
	where 1=1
	".$sWhere
	.$sOrder;

	return $sSql;
}
?>