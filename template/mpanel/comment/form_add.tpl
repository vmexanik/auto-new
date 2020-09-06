<FORM id='main_form' action='javascript:void(null);'
	onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th>{$oLanguage->getDMessage('Comment')}</th>
	</tr>
	<tr>
		<td>

		<table cellspacing=2 cellpadding=1>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Section')}:{$sZir}</td>
				<td><input type=text name=data[section]
					value="{$aData.section|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Ref Id')}:</td>
				<td><input type=text name=data[ref_id] value="{$aData.ref_id|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Name')}:{$sZir}</td>
				<td><input type=text name=data[name] value="{$aData.name|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Site')}:</td>
				<td><input type=text name=data[site] value="{$aData.site|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Content')}:{$sZir}</td>
				<td><textarea name=data[content]>{$aData.content}</textarea></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Post Date')}:</td>
				<td><input type=text name=data[post_date]
					value="{$aData.post_date|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('IP')}:</td>
				<td><input type=text name=data[ip] value="{$aData.ip|escape}"></td>
			</tr>
			{include file='addon/mpanel/form_visible.tpl' aData=$aData}
		</table>

		</td>
	</tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}"> 
{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}</FORM>