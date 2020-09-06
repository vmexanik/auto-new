<FORM id='main_form' action='javascript:void(null);'
	onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th>{$oLanguage->getDMessage('Page')}</th>
	</tr>
	<tr>
		<td>

		<table cellspacing=2 cellpadding=1>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Parent')}:</td>
				<td><select name=data[id_parent]>
					<option value=0>{$oLanguage->getDMessage('Top Level')}</option>
					{foreach from=$aParent item=aRow}
					<option value={$aRow.id}{if $aRow.id==$aData.id_parent} selected{/if} style="padding-left:{$aRow.level*12-12}px">{$aRow.nice_num} {$aRow.name}</option>
					{/foreach}
				</select></td>
			</tr>

			{include file='mpanel/page_manager/form_add_part.tpl'}
		</table>

		</td>
	</tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}">
{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}</FORM>