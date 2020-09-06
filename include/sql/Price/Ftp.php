<?
function SqlPriceFtpCall($aData) {

	$sWhere.=$aData['where'];

	if ($aData['id']) {
		$sWhere.=" and pf.id='".$aData['id']."'";
	}

	if ($aData['code']) {
		$sWhere.=" and pf.code='".$aData['code']."'";
	}

	if ($aData['is_download']) {
		$sWhere.=" and if (last_download + interval repeat_day day > now(), 0, 1)='".$aData['is_download']."'";
	}

	if ($aData['order']) {
		$sOrder=$aData['order'];
	}

	$sSql="	select pf.*
	, if (last_download + interval repeat_day day > now(), 0, 1) as is_download
	from price_ftp as pf
	where 1=1
	".$sWhere
	. $sOrder;

	return $sSql;
}
?>