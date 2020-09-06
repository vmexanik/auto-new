<?
function SqlTranslateCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and t.id='".$aData['id']."'";
	}

	$sSql="select t.*, l.i, l.content
			from translate_message as t
			inner join locale_global l on (t.id=l.id_reference and l.table_name='translate_message'
					and l.locale='".$_SESSION['translate']['current_locale']."')
			where 1=1
				".$sWhere."
			group by t.id";

	return $sSql;
}
?>