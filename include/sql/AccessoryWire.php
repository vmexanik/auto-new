<?
/**
 * @author Irina Miroshnichenko
 */
function SqlAccessoryWireCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and aw.id='{$aData['id']}'";
	}

	if (isset($aData['visible'])) {
		$sWhere.=" and aw.visible='{$aData['visible']}'";
	}

	if ($aData['show_price']) {
		$sField.=" , p.*";
		$sJoin.=" inner join price as p on p.item_code=aw.item_code ";
	}


	$sSql="select c.*, awt.*, aws.*, aws.name as aws_name, ai.*, awt.name as avto_type,c.name as c_producer
			, insert(aw.item_code, 1, locate('_',aw.item_code), '') as aw_code
			".$sField."
			, aw.*
			from accessory_wire as aw
			inner join accessory_wire_type as awt on aw.id_wire_type=awt.id
			inner join accessory_wire_season as aws on aw.id_season_type=aws.id
			left join accessory_image ai ON ai.code = aw.code_img
			inner join cat as c on c.id=aw.id_cat and c.visible=1
			".$sJoin."
			where 1=1 ".$sWhere;

	return $sSql;
}
?>