<script type="text/javascript" src="/libp/js/table.js"></script>

<table {if $sIdTable!=""}id="{$sIdTable}"{/if} style="width:{$sWidth};border-spacing:{$sCellSpacing};padding:5px;" class="{$sClass}" >
{foreach key=key item=aValue from=$aColumn}
	{strip}
	<th style="{if $bHeaderNobr}white-space:nowrap;{/if}{if $aValue.sWidth}width:{$aValue.sWidth}{/if}" {if $aValue.sHeaderClassSelect}class="{$aValue.sHeaderClassSelect}"{/if}
	{if $aValue.sClass} class="{$aValue.sClass}"{/if}
	{$aValue.sAdditionalHtml}>
	{assign var='sSort' value=$key}
	{if $aValue.sOrderLink}<a href='{if !$bNoneDotUrl}.{/if}/?{$aValue.sOrderLink}' {$title_order_link}>{/if}
	{if !$aValue.nosort}
	{if $key==$sTablePriceSort && $sTablePriceSortWay == 'up'}
		<a class="common-th" href="{$sSeoUrl}{if $iSeoUrlAmp}&sort={$sSort}&way=down{else}/sort={$sSort}/way=down{/if}">
	{elseif $key==$sTablePriceSort && $sTablePriceSortWay == 'down'}
		<a class="common-th" href="{$sSeoUrl}{if $iSeoUrlAmp}&sort={$sSort}&way=up{else}/sort={$sSort}/way=up{/if}">
	{else}
		<a class="common-th" href="{$sSeoUrl}{if $iSeoUrlAmp}&sort={$sSort}&way=up{else}/sort={$sSort}/way=up{/if}">
	{/if}
	{/if}
		{$aValue.sTitle}
	{if !$aValue.nosort}
		</a>
	{/if}
	{if !$aValue.sTitle}&nbsp;{/if}
	{if $aValue.sOrderLink}{if $aValue.sOrderImage}<img src='{$aValue.sOrderImage}' style="margin-right:1px;margin-left:1px;">{/if}
	</a>{/if}
	{if $aValue.sHint}{$oLanguage->GetContextHint($aValue.sHint)}{/if}
	{if $aValue.nosort}
	{else}
		{if $aValue.sort}
			{assign var='sSort' value=$aValue.sort}			
		{/if}
		<div class="sorting-block">
			{if $key==$sTablePriceSort && $sTablePriceSortWay == 'up'}
				<a class="up" href="{$sSeoUrl}{if $iSeoUrlAmp}&sort={$sSort}&way=up{else}/sort={$sSort}/way=down{/if}"></a>
			{elseif $key==$sTablePriceSort && $sTablePriceSortWay == 'down'}
				<a class="down" href="{$sSeoUrl}{if $iSeoUrlAmp}&sort={$sSort}&way=up{else}/sort={$sSort}/way=up{/if}"></a>
			{else}
				<a class="up" href="{$sSeoUrl}{if $iSeoUrlAmp}&sort={$sSort}&way=up{else}/sort={$sSort}/way=up{/if}"></a>
				<a class="down" href="{$sSeoUrl}{if $iSeoUrlAmp}&sort={$sSort}&way=up{else}/sort={$sSort}/way=down{/if}"></a>
			{/if}
		</div>
	{/if}
	</th>
	{/strip}
{/foreach}
</table>
<br>
<div class="gallery_big" >

{assign var="iTr" value="0"}
{section name=d loop=$aItem}
{assign var=aRow value=$aItem[d]}
{assign var=iTr value=$iTr+1}

{include file=$sDataTemplate}

{/section}


{if !$aItem}
<tr>
	<td class="even" colspan="{$aColumn|@count}">
	{if $sNoItem}
		{$oLanguage->getMessage($sNoItem)}
	{else}
		{$oLanguage->getMessage("No items found")}
	{/if}
	</td>
</tr>
{/if}
</div>

{if $sSubtotalTemplate} {include file=$sSubtotalTemplate} {/if}


{if $bShowRowPerPage}
<tr>
	<td colspan="{$aColumn|@count}" style="text-align:right;">
	{$oLanguage->getDMessage('Display #')}
<select id=display_select_id name=display_select style="width: 50px;"
	onchange="{strip}javascript:
location.href='/?{$sActionRowPerPage}&content='+document.getElementById('display_select_id')
	.options[document.getElementById('display_select_id').selectedIndex].value; {/strip}">
	<option value=10 {if $iRowPerPage==10} selected{/if}>10</option>
    <option value=20 {if $iRowPerPage==20 || !$iRowPerPage} selected{/if}>20</option>
    <option value=50 {if $iRowPerPage==50} selected{/if}>50</option>
    <option value=100 {if $iRowPerPage==100} selected{/if}>100</option>
    {if $bShowPerPageAll}<option value=10000 {if $iRowPerPage==10000} selected{/if}>{$oLanguage->getMessage('all')}</option>{/if}
</select>

<span class="stepper_results">{$oLanguage->getDMessage('Results')} {$iStartRow} - {if $iEndRow==10000 && $iAllRow<10000}{$iAllRow}{else}{$iEndRow}{/if} {$oLanguage->getDMessage('of')} {$iAllRow}</span>
	</td>
</tr>
{/if}


<div style="clear: both"></div>
{if $sStepper && !$bStepperOutTable}
<tr class="{$sStepperClass}">
	<td colspan="{$aColumn|@count}" style="text-align:{$sStepperAlign};" class="{$sStepperClassTd}">
	{$sStepper}
	{if $bStepperInfo}
	<span class="{$sStepperInfoClass}">{$oLanguage->getDMessage('showing row')} {$iStartRow+1} - {if ($iEndRow==10000 && $iAllRow<10000) || $iAllRow<$iEndRow}{$iAllRow}{else}{$iEndRow}{/if} of {$iAllRow}</span>
	{/if}
	</td>
</tr>
{/if}

{if $sStepper && $bStepperOutTable}
<div class="{$sStepperClass}">
	{$sStepper}
	{if $bStepperInfo}
	<span class="{$sStepperInfoClass}">{$oLanguage->getDMessage('showing row')} {$iStartRow+1} - {if ($iEndRow==10000 && $iAllRow<10000) || $iAllRow<$iEndRow}{$iAllRow}{else}{$iEndRow}{/if} {$oLanguage->getDMessage('of')} {$iAllRow}</span>
	{/if}
</div>
{/if}

{if $sButtonTemplate} {include file=$sButtonTemplate} {/if}

{if $sAddButton}
<span {if $sButtonSpanClass}class="button"{/if}>
<input type=button class='at-btn' value="{$sAddButton}" onclick="location.href='{if !$bNoneDotUrl}.{/if}/?action={$sAddAction}'" >
</span>
{/if}



{if $bFormAvailable}
<input type="hidden" name="action" id='action' value='{if $sFormAction}{$sFormAction}{else}empty{/if}'>
<input type="hidden" name="return" id='return' value='{$sReturn}'>
</form>
{/if}
