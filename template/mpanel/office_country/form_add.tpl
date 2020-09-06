<!--<form id="main_form" action="javascript:void(null);" onsubmit="submit_form( this, Array( 'data_description' ) );">-->
<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">

<table cellspacing="0" cellpadding="2" class="add_form">
<tr>
	<th>{$oLanguage->getDMessage('Office country')}</th>
</tr>
<tr><td>

	<table cellspacing="2" cellpadding="1">

	<tr>
		<td width="50%">{$oLanguage->getDMessage('Name')}:{$sZir}</td>
		<td><input type="text" name="data[name]" value="{$aData.name|escape}"></td>
	</tr>

	<tr>
		<td width="50%">{$oLanguage->getDMessage('Num')}:</td>
		<td><input type="text" name="data[num]" value="{$aData.num|escape}"></td>
	</tr>

	<tr>
		<td width=50%>{$oLanguage->getDMessage('Visible')}:</td>
		<td>{strip}
			{if $aData.visible==1}
				<input type="hidden" name="data[visible]" value="0">
			{/if}
			<input type="checkbox" name="data[visible]" value="1" {if $aData.visible==1} checked="checked"{/if}>
		{/strip}</td>
	</tr>

	</table>

</td></tr>
</table>

<input type="hidden" name="data[id]" value="{$aData.id|escape}">

{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}

</form>