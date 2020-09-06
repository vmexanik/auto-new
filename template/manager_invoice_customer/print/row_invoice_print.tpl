{foreach from=$aUserInvoice item=aItem name=ItemList}

<TR>
	<TD STYLE="border: 1px solid #000000" HEIGHT=17 ALIGN=LEFT><FONT SIZE=2> {$smarty.foreach.ItemList.iteration}</FONT></TD>
	<TD STYLE="border: 1px solid #000000" ALIGN=CENTER>{$aItem.id}1111</TD>
	<TD STYLE="border: 1px solid #000000" ALIGN=CENTER>{$aItem.pref}</TD>
	<TD STYLE="border: 1px solid #000000" ALIGN=LEFT>{$aItem.code}</TD>
	<TD STYLE="border: 1px solid #000000" ALIGN=LEFT>{if $aItem.name}{$aItem.name}{else}&nbsp;{/if}</TD>
	<TD STYLE="border: 1px solid #000000" ALIGN=RIGHT>{$aItem.number}</TD>
	<TD STYLE="border: 1px solid #000000" ALIGN=RIGHT>{$oLanguage->Price($aItem.price, $sCurrencyDefault)}</TD>
	<TD STYLE="border: 1px solid #000000" ALIGN=RIGHT VALIGN=MIDDLE BGCOLOR="#FFFFFF">
	<FONT SIZE=2>{$oLanguage->Price($aItem.total_price, $sCurrencyDefault)}</FONT></TD>
	<TD><BR></TD>
</TR>
{/foreach}
