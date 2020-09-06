<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>

<td width="450" valign="top">

{$sForm}

</td>

<td valign="top" style="padding:0 15px">
{*<p>
	<a href="/?action=manager_invoice_customer_invoice&search[is_travel_sheet]=1"
		><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle
		/>{$oLanguage->getMessage("Browse Is Travel Sheet")}</a>: {$iIsTravelSheetCount};<br>

{if $iIsTravelSheetCount>0}
	<a href="/?action=manager_invoice_customer_is_travel_sheet_clear"
		><img src="/image/delete.png" border=0 width=16 align=absmiddle hspace=1
		/>{$oLanguage->getMessage("Is Travel Sheet Clear")}</a>

	<a href="/?action=manager_invoice_customer_create_invoice_travel_sheet"
		onclick="if (!confirm('{$oLanguage->getMessage("Are you sure?")}')) return false;"
		><img src="/image/apply.png" border=0 width=16 align=absmiddle
		/>{$oLanguage->getMessage("Create Invoice Travel Sheet")}</a>
{/if}
</p>*}
</td>

</tr>
</table>




