<td><b>
{if !$aRow.id_manager_fixed || $aRow.id==$aAuthUser.id_vin_request_fixed}
	<font color=green>
{/if}
	{$aRow.id} </b>
</td>
<td align=center>{$oLanguage->getOrderStatus($aRow.order_status)}</td>
<td>{if $aRow.id_manager_fixed }
	{$oLanguage->AddOldParser('customer',$aRow.id_user)}
{/if}
{if $aRow.phone}<br>{$aRow.phone}{/if}
</td>
<td>{if $aRow.id_manager_fixed }{$aRow.vin}{/if}</td>
<td>{if !$aRow.id_manager_fixed}<font color=green>{/if}{$oLanguage->getDateTime($aRow.post)}</td>
<td>{if $aRow.id_manager_fixed } {$aRow.marka} {/if}</td>
<td>{$aRow.manager_comment}&nbsp;
{if $aRow.order_status=='refused' || $aRow.order_status=='parsed'}
 <br><input type=checkbox value=1 {if $aRow.is_remember}checked{/if}
	onclick=" xajax_process_browse_url('?action=manager_vin_request_remember&id={$aRow.id}&checked='+this.checked);"
	>{$aRow.remember_text}
{/if}
</td>
<td nowrap>
<a href="/?action=manager_vin_request_edit&id={$aRow.id}"
	{if !$aRow.id_manager_fixed} style="font-color:green;"{/if}
	><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Preview")}</a>
</td>
