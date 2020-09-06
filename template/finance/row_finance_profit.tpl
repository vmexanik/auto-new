<td>{$aRow.id}</td>
<td>{$aRow.post_date}</td>
<td>{$oLanguage->AddOldParser('customer',$aRow.id_user)}</td>
<td nowrap>{$oLanguage->PrintPrice($aRow.total_price)}</td>
<td nowrap>{$oLanguage->PrintPrice($aRow.total_price_original)}</td>
<td nowrap>{$oLanguage->PrintPrice($aRow.profit)}</td>