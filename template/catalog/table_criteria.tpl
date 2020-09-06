<script type="text/javascript" src="/libp/js/table.js"></script>


{if $sHeader}
	{if $bHeaderType=='table'}
<table style="width:{$sWidth};margin: 0 0 5px;">
<tr>
<td style="width:100%;">
	<table style="width:100%;"><tr>
		<td class="red_box">{$sHeader}{if $sHint}{$sHint}{/if}</td>
		<td class="red_box" style="text-align:right;">&nbsp;{$sHeaderRight}</td>
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

<table {if $sIdTable!=""}id="{$sIdTable}"{/if} style="width:{$sWidth};border-spacing:{$sCellSpacing};padding:0px;" class="{$sClass}" >

{if $bHeaderVisible}
<tr>
	{if $bCheckVisible}<th {if $bCheckAllVisible}style="white-space:nowrap;"{/if}>{if $bCheckAllVisible}<label><input type=checkbox class="{$sCheckAllClass}" name=check_all
		onclick="mt.SetCheckboxes(this.form,this.checked);{if $sCheckAllAction!=''}{$sCheckAllAction};{/if}"
		 {if $bDefaultChecked}checked{/if} >{/if}{if $sMarkAllText}&nbsp;{$sMarkAllText}{/if}
		 </label></th>
	{/if}


{if $sTitleOrderLink}
	{assign var=title_order_link value=" title='$sTitleOrderLink' "}
{/if}

{foreach key=key item=aValue from=$aColumn}
	{strip}
	<th style="{if $bHeaderNobr}white-space:nowrap;{/if}{if $aValue.sWidth}width:{$aValue.sWidth}{/if}" {if $aValue.sHeaderClassSelect}class="{$aValue.sHeaderClassSelect}"{/if}
	{if $aValue.sClass} class="{$aValue.sClass}"{/if}
	 {$aValue.sAdditionalHtml}>
	{if $aValue.sOrderLink}<a href='{if !$bNoneDotUrl}.{/if}/?{$aValue.sOrderLink}' {$title_order_link}>{/if}
	{$aValue.sTitle}{if !$aValue.sTitle}&nbsp;{/if}
	{if $aValue.sOrderLink}{if $aValue.sOrderImage}<img src='{$aValue.sOrderImage}' style="margin-right:1px;margin-left:1px;">{/if}
	</a>{/if}
	{if $aValue.sHint}{$oLanguage->GetContextHint($aValue.sHint)}{/if}
	</th>
	{/strip}
{/foreach}
	{if $bCheckRightVisible}<th style="{if $bCheckAllVisible}white-space:nowrap;{/if}">{if $bCheckAllVisible}<label><input type=checkbox name=check_all class="{$sCheckAllClass}" 
		onclick="mt.SetCheckboxes(this.form,this.checked);{if $sCheckAllAction!=''}{$sCheckAllAction};{/if}"
		 {if $bDefaultChecked}checked{/if} >{/if}{if $sMarkAllText}&nbsp;{$sMarkAllText}{/if}
		 </label></th>
	{/if}
</tr>
{elseif $bHeaderGroupVisible}
<tr>
	{if $bCheckVisible}<th style="{if $bCheckAllVisible}white-space:nowrap;{/if}">{if $bCheckAllVisible}<label><input type=checkbox name=check_all class="{$sCheckAllClass}"
		onclick="mt.SetCheckboxes(this.form,this.checked);{if $sCheckAllAction!=''}{$sCheckAllAction};{/if}"
		 {if $bDefaultChecked}checked{/if} >{/if}{if $sMarkAllText}&nbsp;{$sMarkAllText}{/if}
		 </label></th>
	{/if}
{foreach key=key item=aValue from=$aColumn}
	{if !$aValue.bGroup}
	<th style="white-space:nowrap;{if $aValue.sWidth}width:{$aValue.sWidth};{/if}" {if $aValue.sHeaderClassSelect}class="{$aValue.sHeaderClassSelect}"{/if}
	 rowspan="2"
	 {$aValue.sAdditionalHtml}>
	{if $aValue.sOrderLink}<a href='{if !$bNoneDotUrl}.{/if}/?{$aValue.sOrderLink}'>{/if}
	{$aValue.sTitle}{if !$aValue.sTitle}&nbsp;{/if}
	{if $aValue.sOrderLink}{if $aValue.sOrderImage}<img src='{$aValue.sOrderImage}' style="margin-right:1px;margin-left:1px;">{/if}
	</a>{/if}
	</th>
	{else}
	{$aValue.sGroupTitle}
	{/if}
{/foreach}
	{if $bCheckRightVisible}<th style="{if $bCheckAllVisible}white-space:nowrap;{/if}">{if $bCheckAllVisible}<label><input type=checkbox name=check_all class="{$sCheckAllClass}"
		onclick="mt.SetCheckboxes(this.form,this.checked);{if $sCheckAllAction!=''}{$sCheckAllAction};{/if}"
		 {if $bDefaultChecked}checked{/if} >{/if}{if $sMarkAllText}&nbsp;{$sMarkAllText}{/if}
		 </label></th>
	{/if}
</tr>
<tr>
{foreach key=key item=aValue from=$aColumn}
	{if $aValue.bGroup}
	<th style="white-space:nowrap;{if $aValue.sWidth}width:{$aValue.sWidth};{/if}" {if $aValue.sHeaderClassSelect}class="{$aValue.sHeaderClassSelect}"{/if}
	 {$aValue.sAdditionalHtml}>
	{$aValue.sTitle}{if !$aValue.sTitle}&nbsp;{/if}
	</th>
	{/if}
{/foreach}
</tr>
{/if}


{if $sStepper && $bTopStepper}
<tr class="{$sStepperClass}">
	<td colspan="{$aColumn|@count}" style="text-align:{$sStepperAlign};">
	{$sStepper}
	</td>
</tr>
{/if}

{if $sSubtotalTemplateTop} {include file=$sSubtotalTemplateTop} {/if}

{assign var="iTr" value="0"}
{section name=d loop=$aItem}
{assign var=aRow value=$aItem[d]}
{assign var=iTr value=$iTr+1}
<tr id="tr_c{$iTr}" {if $bHideTr} pn="{$aItem[d].iHideTr}"{/if}
	{if $aItem[d].class_tr}class="{$aItem[d].class_tr}{else}class="{cycle values="none,even"}{/if}
	{if $bDefaultChecked} {$aRow.sClassCheckTr}{elseif $aRow.bCheckTr} {$aRow.sClassCheckTr}{/if}
	"
	{if $aItem[d].hide_tr=='1'}style="display: none;"{elseif $aItem[d].style_tr}style="{$aItem[d].style_tr}"{/if}
	{if $bCheckVisible && $bCheckOnClick}onclick="var ch=getCookie('checkbox'); setCookie('checkbox','0',1);if(ch=='1') return true; var c=$('#row_check_{$smarty.section.d.index}');c.prop('checked', !c.prop('checked')); return false;"{/if}
	{if $aItem[d].js_tr}{$aItem[d].js_tr}{/if}>

	{if $bCheckVisible}<td>{if !$aRow.bCheckHide}<label><input type=checkbox name=row_check[] class="{$sCheckAllClass}"
	id='row_check_{$smarty.section.d.index}' value='{$aRow.$sCheckField}'
	{if $bDefaultChecked} checked{elseif $aRow.bCheckTr} checked{/if}
	{if $bCheckVisible && $bCheckOnClick}onclick="setCookie('checkbox','1',1);"{/if}
	{if $sCheckAction!=''}onchange="{$sCheckAction}"{/if}>{/if}</label></td>
	{/if}
{include file=$sDataTemplate}
	{if $bCheckRightVisible}<td>{if !$aRow.bCheckHide}<input type=checkbox name=row_check[] class="{$sCheckAllClass}"
	id='row_check_{$smarty.section.d.index}' value='{$aRow.$sCheckField}'
	{if $bDefaultChecked} checked{elseif $aRow.bCheckTr} checked{/if}
	{if $bCheckVisible && $bCheckOnClick}onclick="setCookie('checkbox','1',1);"{/if}
	{if $sCheckAction!=''}onchange="{$sCheckAction}"{/if}>{/if}</td>
	{/if}
</tr>
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


{if $sSubtotalTemplate} {include file=$sSubtotalTemplate} {/if}

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