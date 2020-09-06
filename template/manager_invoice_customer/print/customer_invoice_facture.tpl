<p>{literal} <style type="text/css">
.bordered td, .bordered_td {
	border: 1px solid rgb(150, 150, 150);
}
</style>
{/literal}
</p>
<table rules="none" cellspacing="0" border="1" frame="void">
    <tbody>
       <tr>
            <td width="25" height="13" align="left" colspan="10">&nbsp;</td>
        </tr>

        <tr>
            <td valign="top" height="17" align="left" colspan="3"><b><u><font size="2"
				>{$oLanguage->GetMessage('Fucture provider')}</font></u></b></td>
            <td valign="top" align="left" colspan="7"><b><font size="2">{$aInvoiceAccount.holder_name}</font></b></td>
        </tr>
        <tr>
            <td valign="top" align="left" colspan="3"><b><u><font size="1"
				>{$oLanguage->GetMessage('Fucture Address')}</font></u></b></td>
            <td valign="top" align="left" colspan="7"><font size="2">{$aInvoiceAccount.bank_address}</font></td>
        </tr>
        <tr>
            <td valign="top" align="left" colspan="3"><b><u><font size="1"
				>{$oLanguage->GetMessage('Fucture Registration number')}</font></u></b></td>
            <td valign="top" align="left" colspan="7"><font size="2">{$aInvoiceAccount.holder_code}</font></td>
        </tr>
        <tr>
            <td valign="top" align="left" colspan="3"><b><u><font size="1"
				>{$oLanguage->GetMessage('Fucture Bank Name')}</font></u></b></td>
            <td valign="top" align="left" colspan="7"><font size="2">{$aInvoiceAccount.bank_name}</font></td>
        </tr>
        <tr>
            <td valign="top" align="left" colspan="3"><b><u><font size="1"
				>{$oLanguage->GetMessage('Fucture Account number')}</font></u></b></td>
            <td valign="top" align="left" colspan="7"><font size="2">{$aInvoiceAccount.account_id}</font></td>
        </tr>
        <tr>
            <td valign="top" align="left" colspan="3"><b><u><font size="1"
				>{$oLanguage->GetMessage('Fucture Swift code')}</font></u></b></td>
            <td valign="top" align="left" colspan="7"><font size="2">{$aInvoiceAccount.swift}</font></td>
        </tr>


        <tr>
            <td height="5" align="left" colspan="10">&nbsp;</td>
		</tr>
        <tr>
            <td valign="top" height="17" align="left" colspan="3"><b><u><font size="2"
				>{$oLanguage->GetMessage('Fucture receiver')}</font></u></b></td>
            <td valign="top" align="left" colspan="7"><b><font size="2">{$aInvoice.sName}</font></b></td>
        </tr>
        <tr>
            <td height="16" align="left" colspan="3">&nbsp;</td>
            <td align="left" colspan="7"><font size="2"
				>{$oLanguage->GetMessage('Fucture phone')} {$aInvoice.sPhone} </font></td>
        </tr>
        <tr>
            <td height="21" align="left" colspan="3">
            	<b><u><font size="2">{$oLanguage->GetMessage('Fucture payerer')}</font></u></b></td>
            <td align="left" colspan="7"><font size="2">{$oLanguage->GetMessage('Fucture the same')}</font></td>
        </tr>
        <tr>
            <td height="16" align="left" colspan="3">
            	<b><u><font size="2">{$oLanguage->GetMessage('Fucture condition')}:</font></u></b></td>
            <td align="left" colspan="7"><font size="2">{$oLanguage->GetMessage('Fucture condition value')}</font></td>
        </tr>
        <tr>
            <td height="13" align="left" colspan="10">&nbsp;</td>
        </tr>

        <tr>
            <td height="19" align="center" colspan="9"><b><font size="3">
            	{$oLanguage->GetMessage('Fucture #')} {$aInvoice.sNum}</font></b></td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr>
            <td height="22" align="center" colspan="9"><b><font size="3">{$aInvoice.date}</font></b></td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr class="bordered">
            <td valign="middle" height="39" align="center"  width="2%">
            	<b><font size="2">{$oLanguage->GetMessage('facture #')}</font></b></td>
            <td valign="middle" align="center" width="2%"><b>{$oLanguage->GetMessage('facture id')}</b></td>
            <td valign="middle" align="center" width="2%"><b>{$oLanguage->GetMessage('facture brand')}</b></td>
            <td valign="middle" align="center"><b>{$oLanguage->GetMessage('facture code')}</b></td>
            <td valign="middle" align="center"><b>{$oLanguage->GetMessage('facture name')}</b></td>
            <td valign="middle" align="center"><b>{$oLanguage->GetMessage('facture q-ty')}</b></td>
            <td valign="middle" align="center"><b>{$oLanguage->GetMessage('facture unit price')}</b></td>
            <td valign="middle" align="center" colspan="2"><b>{$oLanguage->GetMessage('facture tax per unit')}</b></td>
            <td valign="middle" align="center" colspan="2"><b>{$oLanguage->GetMessage('facture weight cost')}</b></td>
            <td valign="middle" align="center" colspan="2"><b>{$oLanguage->GetMessage('facture amount')}</b></td>
        </tr>

    {assign var='iIdCartPackagePrevious' value=''}
	{assign var='iIdCartPackageCurrent' value=''}

{foreach from=$aUserInvoice item=aItem name=ItemList}

    {assign var='iIdCartPackagePrevious' value=$iIdCartPackageCurrent}
	{assign var='iIdCartPackageCurrent' value=$aItem.id_cart_package}

	{if $iIdCartPackageCurrent!=$iIdCartPackagePrevious || $iIdCartPackagePrevious==''}
	<tr>
		<td colspan="10"><b>{$oLanguage->GetMessage('facture cart package')} {$aItem.id_cart_package}</b></td>
	</tr>
	{/if}

        <tr class="bordered">
            {if $smarty.capture.sElseContent ne '' && $iCountRows > 1}
                <td align="left"  rowspan="{$iCountRows+1}">
            {else}
                <td align="left">
            {/if}

            <font size="2"> {$smarty.foreach.ItemList.iteration}</font></td>
            <td height="17" align="center">{$sStyle}{if $aItem.id}{$aItem.id}{else}&nbsp;{/if}</td>
            <td align="center">{if $aItem.pref}{$aItem.pref}{else}&nbsp;{/if}</td>
            <td align="left">{if $aItem.code}{$aItem.code}{else}&nbsp;{/if}</td>
            <td align="left">{if $aItem.name}{$aItem.name}{else}&nbsp;{/if}
            	<!--font color=red size=-2>{$aItem.customer_id}</font>
				<font color=red size=-2>{$aItem.customer_comment}</font-->
            </td>
            <td align="right">{if $aItem.number}{$aItem.number}{else}&nbsp;{/if}</td>
            <td align="right">{$oLanguage->Price($aItem.price_unit, 'USD')}</td>
            <td valign="middle" bgcolor="#ffffff" align="right" colspan="2">{$sStyle}
            	<font size="2">{$oLanguage->Price($aItem.price_tax+$aItem.price_fee, 'USD')}</font></td>
            <td valign="middle" bgcolor="#ffffff" align="right" colspan="2">{$sStyle}
            	<font size="2">{$oLanguage->Price($aItem.weight_delivery_cost, 'USD')}</font></td>
            <td valign="middle" bgcolor="#ffffff" align="right" colspan="2">{$sStyle}
            	<font size="2">{$oLanguage->Price($aItem.number*$aItem.price, 'USD')}</font></td>
        </tr>
        {/foreach}

        <tr bgcolor="silver">
            <td align="right" colspan="11"><b><font size="2">{$oLanguage->GetMessage('facture SUB TOTAL')}:</font></b></td>
            <td align="right" class="bordered_td">
            	<b><font size="2">{$aFactureRight.subtotal}</font></b></td>
        </tr>
       <tr>
            <td align="right" colspan="11"><b><font size="2">{$oLanguage->GetMessage('facture Tax Total')}:</font></b></td>
            <td align="right" class="bordered_td">
            	<b><font size="2">{$aFactureRight.tax_total}</font></b></td>
        </tr>
        <tr>
            <td align="right" colspan="11"><b><font size="2">{$oLanguage->GetMessage('facture Weight Delivery Total')}:
            </font></b></td>
            <td align="right" class="bordered_td">
            	<b><font size="2">{$aFactureRight.weight_delivery_cost_total}</font></b></td>
        </tr>
        <tr>
            <td align="right" colspan="11"><b><font size="2">{$oLanguage->GetMessage('facture  Parcel delivery cost')}:</font>
            </b></td>
            <td align="right" class="bordered_td">
            	<b><font size="2">{$aFactureRight.total_price_place}</font></b></td>
        </tr>
        <tr>
            <td align="right" colspan="11"><b><font size="2">
            	{$oLanguage->GetMessage('facture Adjusting the cost of delivery')}:</font></b></td>
            <td align="right" class="bordered_td">
            	<b><font size="2">{$aFactureRight.total_additional_payment}</font></b></td>
        </tr>
        <tr>
            <td align="right" colspan="11"><b><font size="3">{$oLanguage->GetMessage('facture TOTAL')}:</font></b></td>
            <td align="right" class="bordered_td">
            	<b><font size="2">{$aFactureRight.total}</font></b></td>
        </tr>
        <tr>
            <td height="16" align="left" colspan="10"><b>{$oLanguage->GetMessage('facture cost of order')}<br>
				{$aFactureRight.total_text}</b></td>
        </tr>
        <tr>
            <td height="16" align="left" colspan="10">&nbsp;</td>
        </tr>
        <tr>
            <td height="18" align="left" colspan="4">{$oLanguage->GetMessage('facture account amount')}:</td>
            <td align="left"  colspan="6">{$oLanguage->PrintPrice($aInvoice.sAmount)}</td>
        </tr>

    </tbody>
</table>
<p>&nbsp;</p>