{assign var="print_language_prefix" value=$oLanguage->GetConstant('global:print_language_prefix','ua')}
<p>{literal} <style type="text/css">
.bordered td, .bordered_td {
	border: 1px solid rgb(150, 150, 150);
}
tr.bordered {
	border: 2px solid rgb(150, 150, 150);
}

</style>
{/literal}
</p>
<table rules="" cols="9" cellspacing="0" border="0" frame="void">
    <colgroup><col width="25" /><col width="86" /><col width="39" /><col width="101" /><col width="256" /><col width="46" />
    <col width="75" /><col width="103" /><col width="61" /></colgroup>
    <tbody>
        <tr>
            <td width="25" height="13" align="left">&nbsp;</td>
            <td width="86" align="left">&nbsp;</td>
            <td width="39" align="left">&nbsp;</td>
            <td width="101" align="left">&nbsp;</td>
            <td width="256" align="left">&nbsp;</td>
            <td width="46" align="left">&nbsp;</td>
            <td width="75" align="left">&nbsp;</td>
            <td width="103" align="left">&nbsp;</td>
            <td width="61" align="left">&nbsp;</td>
        </tr>
{if ! $bNoFopData}
        <tr>
            <td valign="top" height="17" align="left"><b><u><font size="2">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice provider"}
			{$oLanguage->GetMessage($variable_lang)}</font></u></b></td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left" colspan="6"><b><font size="2">{$aInvoiceAccount.name}
            	</font></b></td>
            <td valign="top" align="left">&nbsp;</td>
        </tr>{/if}
        <tr style="display:none">
            <td height="22" align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left" colspan="6">

	<textarea name="textarea" cols="100" rows="4" style="background-color: #FFFFFF; border-color: #CCCCCC;
border-style: none; border-width: 1px; " id="id_text_area_essentials" readonly>{$aInvoiceAccount.description|strip_tags strip}
</textarea>

<input type="button" value="{$oLanguage->GetMessage('Apply')}" id="id_button_test" style="display:none;"
onclick="var obj = document.getElementById('id_text_area_essentials');
obj.readOnly=true;
obj.style.borderStyle='none'; this.style.display='none';">


            </td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr>
            <td height="5" align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr>
            <td valign="top" height="17" align="left"><b><u><font size="2">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice customer"}
			{$oLanguage->GetMessage($variable_lang)}</font></u></b></td>
            <td valign="top" align="left">&nbsp;</td>
            <td valign="top" align="left" colspan="6"><b><font size="2">{$aInvoice.sName}</font></b></td>
            <td valign="top" align="left">&nbsp;</td>
        </tr>
        <tr>
            <td height="16" align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left" colspan="6"><font size="2">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice phone"}
			{$oLanguage->GetMessage($variable_lang)} {$aInvoice.sPhone} </font></td>
            <td align="left">&nbsp;</td>
        </tr>
        
        <tr style="display:none">
            <td height="21" align="left"><b><u><font size="2">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice payer"}
			{$oLanguage->GetMessage($variable_lang)}</font></u></b></td>
            <td align="left">&nbsp;</td>
            <td align="left"><font size="2">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice same"}
			{$oLanguage->GetMessage($variable_lang)}</font></td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr>
            <td height="16" align="left"><b><u><font size="2">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice condition"}
			{$oLanguage->GetMessage($variable_lang)}:</font></u></b></td>
            <td align="left">&nbsp;</td>
            <td align="left" style="white-space:nowrap;"><font size="2">{$aInvoiceAccount.name}
            {*$aInvoice.id_account*}
            <!--{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice condition value"}
			{$oLanguage->GetMessage($variable_lang)}</font>-->
	    </td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr>
            <td height="13" align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr style="">
            <td height="21" align="left"><b><u><font size="2">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice dostavka"}
			{$oLanguage->GetMessage($variable_lang)}</font></u></b></td>
            <td align="left">&nbsp;</td>
            <td align="left" colspan=7><font size="2">
            {$aInvoice.comment}
            </font></td>

        </tr>
        <tr>
            <td height="13" align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
        </tr>

        <tr>
            <td height="19" align="center" colspan="8"><b><font size="3">
           	{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice number"}
			{$oLanguage->GetMessage($variable_lang)}
            	{$aInvoice.sNum}</font></b></td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr>
            <td height="22" align="center" colspan="8"><b><font size="3">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice from"}
            {assign var="variable_lang2" value=$print_language_prefix|cat:"_doc_print::invoice year"}
			{$oLanguage->GetMessage($variable_lang)}
            	{$aInvoice.date} {$oLanguage->GetMessage($variable_lang2)}</font></b></td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr class="bordered">
            <td valign="middle" height="39" align="center"><b><font size="2">№ </font></b></td>
            <td valign="middle" align="center"><b>
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice id"}
			{$oLanguage->GetMessage($variable_lang)}</b></td>
            <td valign="middle" align="center"><b>
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice make"}
			{$oLanguage->GetMessage($variable_lang)}</b></td>
            <td valign="middle" align="center"><b>
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice code"}
			{$oLanguage->GetMessage($variable_lang)}</b></td>
            <td valign="middle" align="center"><b>
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice name"}
			{$oLanguage->GetMessage($variable_lang)}</b></td>
            <td valign="middle" align="center"><b>
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice count"}
			{$oLanguage->GetMessage($variable_lang)}</b></td>
            <td valign="middle" align="center"><b>
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice price single"}
			{$oLanguage->GetMessage($variable_lang)}</b></td>
            <td valign="middle" align="center" colspan="2"><b>
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice price total"}
			{$oLanguage->GetMessage($variable_lang)}</b></td>
        </tr>

    {assign var='iIdCartPackagePrevious' value=''}
	{assign var='iIdCartPackageCurrent' value=''}

{foreach from=$aUserInvoice item=aItem name=ItemList}

    {assign var='iIdCartPackagePrevious' value=$iIdCartPackageCurrent}
	{assign var='iIdCartPackageCurrent' value=$aItem.id_cart_package}

	{if $iIdCartPackageCurrent!=$iIdCartPackagePrevious || $iIdCartPackagePrevious==''}
	<tr>
		<td colspan="9" class="bordered_td"><b>
		{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice cart package"}
		{$oLanguage->GetMessage($variable_lang)} {$aItem.id_cart_package}</b></td>
	</tr>
	{/if}

	{if in_array($aItem.order_status,array('refused','store_refused'))}
		{assign var='sStyle' value="<strike><font color=red>"}
	{else}
		{assign var='sStyle' value=""}
	{/if}

	   {assign var="iCountRows" value=1}
	   {assign var="iSummOther" value=0}
       {capture name=sElseContent}
            {foreach from=$aAdditionalItem item=aItem2}
                {if $aItem.id==$aItem2.custom_id}
                <tr>
                    <td align="right" colspan="7">
                        {$aItem2.post_date} - {$aItem2.description}
                    </td>
                    <td align="right" colspan="2" >
                        <font size="2">
                            {$oCurrency->Price($aItem2.amount, $sCurrencyDefault, false, true)}
                        </font>
                    </td>
                </tr>
                {assign var="iCountRows" value=$iCountRows+1}
                {assign var="iSummOther" value=$iSummOther+$aItem2.amount}
                {/if}
            {/foreach}
        {/capture}

        <tr class="bordered">
            {if $smarty.capture.sElseContent ne '' && $iCountRows > 1}
                <td align="left"  rowspan="{$iCountRows+1}">
            {else}
                <td align="left">
            {/if}

            <font size="2"> {$smarty.foreach.ItemList.iteration}</font></td>
            <td height="17" align="center">{$sStyle}{if $aItem.id}{$aItem.id}{else}&nbsp;{/if}</td>
            <td align="center">{if $aItem.pref}{if $aItem.pref_changed}{$aItem.pref_changed}{else}{$aItem.pref}{/if}{else}&nbsp;{/if}</td>
            <td align="left">{if $aItem.code}{if $aItem.code_changed}{$aItem.code_changed}{else}{$aItem.code}{/if}{else}&nbsp;{/if}</td>
            <td align="left">{if $aItem.russian_name}{$aItem.russian_name}{else}&nbsp;{/if}
            	<font color=red size=-2>{$aItem.customer_id}</font>
				<font color=red size=-2>{$aItem.customer_comment}</font>
            </td>
            <td align="right">{if $aItem.number}{$aItem.number}{else}&nbsp;{/if}</td>
            <td align="right">{$oCurrency->PrintPrice($aItem.single_price, $iCurrencyDefault)}</td>
            <td valign="middle" bgcolor="#ffffff" align="right" colspan="2">{$sStyle}
            	<font size="2">{$oCurrency->PrintPrice($aItem.total_price, $iCurrencyDefault)}</font></td>
        </tr>
        {if $smarty.capture.sElseContent ne '' && $iCountRows > 1}
            {$smarty.capture.sElseContent}
             {math equation="x - y" x=$aItem.total_price y=$iSummOther assign="iTotal"}
            <tr>
                <td colspan="9" align="right" >
                    <font size="2">
                        <b>{$oCurrency->PrintPrice($iTotal, $iCurrencyDefault)}</b>
                    </font>
                </td>
            </tr>
        {/if}
{/foreach}



        <tr>
            <td height="18" align="left"><font size="2"><br />
            </font></td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="right"><b><font size="2">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice razom bez pdv"}
			{$oLanguage->GetMessage($variable_lang)}: </font></b></td>
            <td align="right" sdnum="1049;0;0,00" sdval="54,07"
			class="bordered_td" colspan="2"><b><font size="2">{$aInvoice.fTotalSum}</font></b></td>
        </tr>
        <tr>
            <td height="18" align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left"><font size="2"><br />
            </font></td>
            <td align="left"><font size="2"><br />
            </font></td>
            <td align="right"><b><font size="2">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice pdv"}
			{$oLanguage->GetMessage($variable_lang)}: </font></b></td>
            <td align="right" class="bordered_td" colspan="2">
            	<b><font size="2">0,00</font></b></td>
        </tr>
        
      {*  <tr>
            <td height="18" align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left"><font size="2"><br />
            </font></td>
            <td align="left"><font size="2"><br />
            </font></td>
            <td align="right"><b><font size="2">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice vsogo z pdv"}
			{$oLanguage->GetMessage($variable_lang)}: </font></b></td>
            <td align="right" sdnum="1049;0;0,00" sdval="54,07" class="bordered_td" colspan="2">
            <b><font size="2">{$aInvoice.fTotalSum}</font></b></td>
        </tr>*}
        <tr>
            <td height="18" align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left"><font size="2"><br />
            </font></td>
            <td align="left"><font size="2"><br />
            </font></td>
            <td align="right"><b><font size="2">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice dostavka"}
			{$oLanguage->GetMessage($variable_lang)}: </font></b></td>
            <td align="right" sdnum="1049;0;0,00" sdval="54,07" class="bordered_td" colspan="2">
            <b><font size="2">{$aInvoice.fTotalDostavka}</font></b></td>
        </tr>
         <tr>
            <td height="18" align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left"><font size="2"><br />
            </font></td>
            <td align="left"><font size="2"><br />
            </font></td>
            <td align="right"><b><font size="2">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice vsego"}
			{$oLanguage->GetMessage($variable_lang)}: </font></b></td>
            <td align="right" sdnum="1049;0;0,00" sdval="54,07" class="bordered_td" colspan="2">
            <b><font size="2">{$aInvoice.fTotalTotal}</font></b></td>
        </tr>
    <!--/table>


	<table-->
        <tr>
            <td height="16" align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr>
            <td height="16" align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
        </tr>

        <tr>
            <td height="18" align="left" colspan="3"><font size="2">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice vsogo na sumu"}
			{$oLanguage->GetMessage($variable_lang)}:</font></td>
            <td align="left"><font size="2"><br />
            </font></td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr>
            <td height="18" align="left" colspan="8"><b><font size="2" >{$aInvoice.sTotalSumText}
            {if $aInvoice.fTotalDostavka}
             {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::vtcinvoice dostavka"}
			{$oLanguage->GetMessage($variable_lang)} {$aInvoice.fTotalDostavka}
			{/if}
            </font></b></td>
            <td align="left"><font size="2"><br />
            </font></td>
        </tr>
        <tr>
            <td height="18" align="left"><font size="2">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice without vat"}
			{$oLanguage->GetMessage($variable_lang)}</font></td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
        </tr>
      {*  <tr>
            <td height="13" align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
        </tr>
        <tr>
            <td valign="bottom" height="16" align="left">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice shipped"}
			{$oLanguage->GetMessage($variable_lang)}</td>
            <td valign="top" align="right" >&nbsp;</td>
            <td align="left" >
            <p><font size="2">                                </font></p>
            <p>&nbsp;</p>
            </td>
            <td colspan="2">&nbsp;{$aInvoiceAccount.name}</td>
            <td valign="bottom" align="right"><font size="2">
            {assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::invoice received"}
			{$oLanguage->GetMessage($variable_lang)}</font></td>
            <td align="left" >&nbsp;</td>
            <td align="left" >&nbsp;</td>
            <td align="left">&nbsp;</td>
        </tr>*}
         <tr>
            <td colspan=9><hr color='#000000' size='2px'>

<div style="position: relative; height: 140px;">
	<div style="position: absolute; z-index: 10; font-family: Arial; font-size: 10pt; font-style: normal;">
		<br><br><br>
		{assign var="variable_lang" value=$print_language_prefix|cat:"_doc_print::Руководитель"}
		{assign var="variable_lang2" value=$print_language_prefix|cat:"_doc_print::одержав"}
		{assign var="variable_lang3" value=$print_language_prefix|cat:"_doc_print::претензия"}
		{$oLanguage->GetMessage($variable_lang)} _______________________ <!--({$aActiveAccount.holder_sign})-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$oLanguage->GetMessage($variable_lang2)}_______________________
		<div style="position: absolute;right:0px;z-index: 10; font-family: Arial; font-size: 10pt; font-style: normal;">
		<br>{$oLanguage->GetMessage($variable_lang3)}
		</div>
	</div>

		<!--img src="{$aActiveAccount.holder_stamp}" style="position: absolute; z-index: 5; left: 80px; top: 0px;"-->
</div>
	<div style="position: relative; height: 100px;"></div>
            </td>
            
        </tr>

    </tbody>
</table>


