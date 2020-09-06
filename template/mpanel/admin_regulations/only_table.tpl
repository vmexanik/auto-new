<table>
{foreach key=key item=aValue from=$aDataColumn}
	{strip}
	<th {if $aValue.sHeaderClassSelect}class="{$aValue.sHeaderClassSelect}"{/if}
	{if $aValue.sClass} class="{$aValue.sClass}"{/if}
	{if $aValue.sWidth} width="{$aValue.sWidth}"{/if} {$aValue.sAdditionalHtml}>
	{if $aValue.sOrderLink}<a href='{if !$bNoneDotUrl}.{/if}/?{$aValue.sOrderLink}' {$title_order_link}>{/if}
	{if $bHeaderNobr}<nobr>{/if}{$aValue.sTitle}{if !$aValue.sTitle}&nbsp;{/if}
	{if $aValue.sOrderLink}{if $aValue.sOrderImage}<img src='{$aValue.sOrderImage}' border="0" hspace="1">{/if}
	</a>{/if}{if $bHeaderNobr}</nobr>{/if}
	{if $aValue.sHint}{$oLanguage->GetContextHint($aValue.sHint)}{/if}
	</th>
	{/strip}
{/foreach}

{foreach key=key item=aValue from=$aData}
<tr>
    {foreach key=key2 item=aValue2 from=$aDataColumn}
	<td>{$aValue.$key2}</td>
    {/foreach}
</tr>
{/foreach}

{*
{section name=d loop=$aData}
{assign var=aRowData value=$aData[d]}
<tr id="tr{$aData[d].iTr}" {if $bHideTr} pn="{$aData[d].iHideTr}"{/if}
{include file=$sDataTemplateName}
</tr>
{/section}
*}

{if !$aData}
<tr>
	<td class="even" colspan="20">
	{if $sNoItem}
		{$oLanguage->getDMessage($sNoItem)}
	{else}
		{$oLanguage->getDMessage("No items found")}
	{/if}
	</td>
</tr>
{/if}

</table>
