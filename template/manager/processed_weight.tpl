<h3>{$oLanguage->getMessage("Parsed detals with code")}:</h3>

<table width="800px" cellspacing=0 cellpadding=5 class="datatable">
<tr>
	<th><nobr>{$oLanguage->getMessage("Pref")}</th>
	<th><nobr>{$oLanguage->getMessage("Code")}</th>
	<th><nobr>{$oLanguage->getMessage("Name Rus")}</th>
	<th><nobr>{$oLanguage->getMessage("Weight kg")}</th>
</tr>
{foreach item=aItem from=$aProcessed}
<tr class="{cycle values="even,none"}">
	<td>{$aItem.pref}</td>
	<td>{$aItem.code}</td>
	<td>{$aItem.name_rus}</td>
	<td>{$aItem.weight}</td>
</tr>
{/foreach}
</table>

<br>