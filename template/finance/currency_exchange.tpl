<table width="500px" cellspacing=0 cellpadding=0 class="datatable" align=left style="margin: 10px;">
<tr>
{foreach item=aItem from=$aCurrency}
	<th style="padding: 5px;">{$aItem.code} - {$aItem.name}</th>
{/foreach}
</tr>
<tr>
{foreach item=aItem from=$aCurrency}
	<td style="padding: 5px;">{$aItem.value}</td>
{/foreach}

</tr>
</table><br><br>