<div id="list_auto">
<script type="text/javascript" src="/libp/js/table.js"></script>
{if $sHeader}
	{if $bHeaderType=='table'}
<table width="{$sWidth}"  style="margin: 0 0 5px;" cellspacing="0" cellpadding="0" border="0">
<tr>
<td width="100%">
	<table width="100%" cellpadding="0" cellspacing="0"><tr>
		<td class="red_box">{$sHeader}{if $sHint}{$sHint}{/if}</td>
		<td class="red_box"align=right>&nbsp;{$sHeaderRight}</td>
		</tr>
	</table>
</td>

</tr></table>
	{else}
	<div class="hrey_hd">{$sHeader}{if $sHint}{$sHint}{/if}</div>
	{/if}
{/if}

{if $smarty.get.table_error}
<div class="error_message">{$oLanguage->getMessage($smarty.get.table_error)}</div>
{/if}

{if $sTableMessage}<div class="{$sTableMessageClass}">{$sTableMessage}</div>{/if}


{if $bFormAvailable}<form id="table_form" {$sFormHeader}>{/if}
{if $sPanelTemplateTop} {include file=$sPanelTemplateTop} {/if}

<table width="{$sWidth}" cellspacing="{$sCellSpacing}" cellpadding="5" class="{$sClass}" >

{if $bHeaderVisible}
<tr>
	{if $bCheckVisible}<th>{if $bCheckAllVisible}<label><nobr><input type=checkbox name=check_all
		onclick="mt.SetCheckboxes(this.form,this.checked);{if $sCheckAllAction!=''}{$sCheckAllAction};{/if}"
		 {if $bDefaultChecked}checked{/if} >{/if}{if $sMarkAllText}&nbsp;{$sMarkAllText}{/if}</nobr>
		 </label></th>
	{/if}

{foreach key=key item=aValue from=$aColumn}
	<th {if $aValue.sHeaderClassSelect}class="{$aValue.sHeaderClassSelect}"{/if}
	{if $aValue.sClass} class="{$aValue.sClass}"{/if} 
	{if $aValue.sWidth} width="{$aValue.sWidth}"{/if} {$aValue.sAdditionalHtml}>
	{if $aValue.sOrderLink}<a href='{if !$bNoneDotUrl}.{/if}/?{$aValue.sOrderLink}'>{/if}
	{if $bHeaderNobr}<nobr>{/if}{$aValue.sTitle}{if !$aValue.sTitle}&nbsp;{/if}
	{if $aValue.sOrderLink}{if $aValue.sOrderImage}<img src='{$aValue.sOrderImage}' border="0" hspace="1">{/if}
	</a>{/if}{if $bHeaderNobr}</nobr>{/if}
	</th>

{/foreach}
</tr>
{elseif $bHeaderGroupVisible}
<tr>
	{if $bCheckVisible}<th>{if $bCheckAllVisible}<label><nobr><input type=checkbox name=check_all
		onclick="mt.SetCheckboxes(this.form,this.checked);{if $sCheckAllAction!=''}{$sCheckAllAction};{/if}"
		 {if $bDefaultChecked}checked{/if} >{/if}{if $sMarkAllText}&nbsp;{$sMarkAllText}{/if}</nobr>
		 </label></th>
	{/if}
{foreach key=key item=aValue from=$aColumn}
	{if !$aValue.bGroup}
	<th {if $aValue.sHeaderClassSelect}class="{$aValue.sHeaderClassSelect}"{/if}
	 rowspan="2"
	{if $aValue.sWidth} width="{$aValue.sWidth}"{/if} {$aValue.sAdditionalHtml}>
	{if $aValue.sOrderLink}<a href='{if !$bNoneDotUrl}.{/if}/?{$aValue.sOrderLink}'>{/if}
	<nobr>{$aValue.sTitle}{if !$aValue.sTitle}&nbsp;{/if}
	{if $aValue.sOrderLink}{if $aValue.sOrderImage}<img src='{$aValue.sOrderImage}' border="0" hspace="1">{/if}
	</a>{/if}</nobr>
	</th>
	{else}
	{$aValue.sGroupTitle}
	{/if}
{/foreach}
</tr>
<tr>
{foreach key=key item=aValue from=$aColumn}
	{if $aValue.bGroup} 
	<th {if $aValue.sHeaderClassSelect}class="{$aValue.sHeaderClassSelect}"{/if}
	{if $aValue.sWidth} width="{$aValue.sWidth}"{/if} {$aValue.sAdditionalHtml}>
	<nobr>{$aValue.sTitle}{if !$aValue.sTitle}&nbsp;{/if}</nobr>
	</th>
	{/if}
{/foreach}
</tr>
{/if}


{if $sStepper && $bTopStepper}
<tr class="{$sStepperClass}">
	<td colspan="20" align="{$sStepperAlign}">
	{$sStepper}
	</td>
</tr>
{/if}

{if $sSubtotalTemplateTop} {include file=$sSubtotalTemplateTop} {/if}

{section name=d loop=$aItem}
{assign var=aRow value=$aItem[d]}
<tr  onclick="select_auto('{$aRow.id_make}, {$aRow.id_model}, {$aRow.month} {$aRow.year}','{$aRow.id}');" id="tr{$aItem[d].iTr}" {if $bHideTr} pn="{$aItem[d].iHideTr}"{/if}
	{if $aItem[d].class_tr}class="{$aItem[d].class_tr}{else}class="{cycle values="none,even"}{/if}
	{if $bDefaultChecked} {$aRow.sClassCheckTr}{elseif $aRow.bCheckTr} {$aRow.sClassCheckTr}{/if}
	"
	{if $aItem[d].hide_tr=='1'}style="display: none;"{elseif $aItem[d].style_tr}style="{$aItem[d].style_tr}"{/if}
	{if $bCheckVisible && $bCheckOnClick}onclick="var ch=getCookie('checkbox'); setCookie('checkbox','0',1);if(ch=='1') return true; var c=$('#row_check_{$smarty.section.d.index}');c.prop('checked', !c.prop('checked')); return false;"{/if}
	{if $aItem[d].js_tr}{$aItem[d].js_tr}{/if}>

	{if $bCheckVisible}<td><input type=checkbox name=row_check[]
	id='row_check_{$smarty.section.d.index}' value='{$aRow.$sCheckField}'
	{if $bDefaultChecked} checked{elseif $aRow.bCheckTr} checked{/if}
	{if $bCheckVisible && $bCheckOnClick}onclick="setCookie('checkbox','1',1);"{/if}
	{if $sCheckAction!=''}onchange="{$sCheckAction}"{/if}></td>
	{/if}
{include file=$sDataTemplate}
</tr>
{/section}


{if !$aItem}
<tr>
	<td class="even" colspan="20">
	{if $sNoItem}
		{$oLanguage->getMessage($sNoItem)}
	{else}
		{$oLanguage->getMessage("No items found")}
	{/if}
	</td>
</tr>
{/if}


{if $sSubtotalTemplate} {include file=$sSubtotalTemplate} {/if}

{if $sStepper && !$bStepperOutTable}
{literal}
<style>
.list{
	font-family: Arial, Helvetica, sans-serif;
	/*color: 5977A5;*/
	text-decoration: none;
	/*font-size: 11px;
	font-weight: bold;*/
}
</style>
{/literal}
<tr class="{$sStepperClass}">
	<td colspan="20" align="{$sStepperAlign}">
	{$sStepper}
	{if $bStepperInfo}
	<span class="{$sStepperInfoClass}">{$oLanguage->getDMessage('showing row')} {$iStartRow+1} - {if ($iEndRow==10000 && $iAllRow<10000) || $iAllRow<$iEndRow}{$iAllRow}{else}{$iEndRow}{/if} of {$iAllRow}</span>
	{/if}
	</td>
</tr>
{/if}
{if $bShowRowPerPage}
<tr>
	<td colspan="20" align="right">
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

</table>

{if $sStepper && $bStepperOutTable}
<div class="{$sStepperClass}">
	{$sStepper}
	{if $bStepperInfo}
	<span class="{$sStepperInfoClass}">{$oLanguage->getDMessage('showing row')} {$iStartRow+1} - {if ($iEndRow==10000 && $iAllRow<10000) || $iAllRow<$iEndRow}{$iAllRow}{else}{$iEndRow}{/if} {$oLanguage->getDMessage('of')} {$iAllRow}</span>
	{/if}
</div>
{/if}

<div style="padding: 5px;">
{if $sButtonTemplate} {include file=$sButtonTemplate} {/if}

{if $sAddButton}
<span {if $sButtonSpanClass}class="button"{/if}>
<input type=button class='at-btn' value="{$sAddButton}" onclick="location.href='{if !$bNoneDotUrl}.{/if}/?action={$sAddAction}'" >
</span>
{/if}
</div>


{if $bFormAvailable}
<input type="hidden" name="action" id='action' value='{if $sFormAction}{$sFormAction}{else}empty{/if}'>
<input type="hidden" name="return" id='return' value='{$sReturn}'>
</form>
{/if}

<input class="at-btn" value="{$oLanguage->getMessage("Add")}" onclick="add_auto_form();" type="button">
</div>