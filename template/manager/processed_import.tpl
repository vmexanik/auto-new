<h3>{$oLanguage->getMessage("Parsed order statuses")}:</h3>

<table width="100%" cellspacing=0 cellpadding=5 class="datatable">
<tr>
	<th><nobr>{$oLanguage->getMessage("ID detal")}</th>
	<th><nobr>{$oLanguage->getMessage("Old Status")}</th>
	<th><nobr>{$oLanguage->getMessage("New Status")}</th>
	<th><nobr>{$oLanguage->getMessage("Comment")}</th>
	<th><nobr>{$oLanguage->getMessage("Id Provider Order")}</th>
	<th><nobr>{$oLanguage->getMessage("ProviderPrice")}</th>
	<th><nobr>{$oLanguage->getMessage("Id Provider Invoice")}</th>
	<th><nobr>{$oLanguage->getMessage("CustomValue")}</th>
	<th><nobr>{$oLanguage->getMessage("Message")}</th>
</tr>
{foreach item=aItem from=$aProcessed}
<tr class="{cycle values="even,none"}">
	<td>{$aItem.id}</td>
	<td>{$aItem.old_order_status}</td>
	<td>{$aItem.order_status}</td>
	<td>{$aItem.comment}</td>
	<td>{$aItem.id_provider_order}</td>
	<td>{$aItem.provider_price}</td>
	<td>{$aItem.id_provider_invoice}</td>
	<td>{$aItem.custom_value}</td>
	<td><font color=blue>{$aItem.message}</font></td>
</tr>
{/foreach}
</table>

<br>