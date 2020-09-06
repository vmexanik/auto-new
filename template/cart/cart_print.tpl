<table width="800">
<tr>
<td>

<div align=center><h3>{$oLanguage->GetConstant('global:project_name','ProjectName')}
	({$oLanguage->GetConstant('global:project_url','http://project.com')})</h3></div>

<p><b>{$oLanguage->getMessage("ProviderCart")}: </b> {$oLanguage->GetConstant('global:project_name','ProjectName')}
<p><b>{$oLanguage->getMessage("CustomerCart")}: </b> {$aAuthUser.name} (login: {$aAuthUser.login})

<br>

<div align=center><h3>{$oLanguage->getMessage("Cart items")}:</h3></div>

<table width="99%" cellspacing=0 cellpadding=5 class="datatable">
<tr>
	<th><nobr>{$oLanguage->getMessage("CatName")}</th>
	<th><nobr>{$oLanguage->getMessage("CartCode")}</th>
	<th><nobr>{$oLanguage->getMessage("NameCart")}</th>
	<th><nobr>{$oLanguage->getMessage("TermCart")}</th>
	<th><nobr>{$oLanguage->getMessage("NumberCart")}</th>
	<th><nobr>{$oLanguage->getMessage("PriceCart")}</th>
	<th><nobr>{$oLanguage->getMessage("TotalCart")}</th>
</tr>
{foreach item=aItem from=$aCart}
<tr class="{cycle values="even,none"}">
	<td>{$aItem.cat_name}</td>
	<td>{if $aItem.code_visible} {$aItem.code} {else}<i>{$oLanguage->getMessage("cart_invisible")}</i>{/if} </td>
	<td>{$oContent->PrintPartName($aItem)} <font color=red>{$aItem.customer_id}</font>	</td>
	<td>{$aItem.term}</td>
	<td>{$aItem.number}</td>
	<td>{$oCurrency->PrintPrice($aItem.price)}</td>
	<td>{$oCurrency->PrintPrice($aItem.number*$aItem.price)}</td>
</tr>
{/foreach}
<tr class="even">
	<td colspan=6 align=right>{$oLanguage->getMessage('SubtotalCart')}:</td>
	<td><b>{$oCurrency->PrintPrice($dSubtotal)}</b></td>
</tr>
</table>

<p><b>{$oLanguage->getMessage("Current Date Cart")}: </b> {$smarty.now|date_format:"%d.%m.%Y"}



</td>
</tr>
</table>