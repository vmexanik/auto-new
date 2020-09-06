<?
/**
 * @author Irina Miroshnichenko
 */
function SqlAccessoryGlassCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and ag.id='{$aData['id']}'";
	}

	if (isset($aData['visible'])) {
		$sWhere.=" and ag.visible='{$aData['visible']}'";
	}

	if ($aData['join']) {
		$sJoin .= $sJoin;
	}

	if ($aData['show_price']) {
		$sField.=" , p.*";
		$sJoin.=" inner join price as p on p.item_code=ag.item_code ";
	}

	//if ($aData['model_name']){
		//$sFieldAvtoModel = " ,cm.name as cm_name ";
		//$sJoin .= " inner join concat('cat__','".$aData['model_name']."') as cm on cm.t_id=ag.id_cat_model ";
	//}

	$sSql="select  c.*, ai.*, c.title as c_producer, c.image as img_provider,
			insert(ag.item_code, 1, locate('_',ag.item_code), '') as ag_code
			".$sField."
			, ag.*
			from accessory_glass as ag
			left join accessory_image ai ON ai.code = ag.code_img
			inner join cat as c on c.id=ag.id_cat and c.visible=1
			".$sJoin."
			where 1=1 ".$sWhere;

	return $sSql;
}
?>