<FORM id='main_form' action='javascript:void(null);'
	onsubmit="submit_form(this)">

<table cellspacing=0 cellpadding=2 class=add_form>
	<tr>
		<th>{$oLanguage->getDMessage('Cart History')}</th>
	</tr>
	<tr>
		<td>

		<table cellspacing=2 cellpadding=1>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Code')}:</td>
				<td><input type=text name=data[code]	value="{$aData.code|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Make')}:</td>
				<td><input type=text name=data[make]	value="{$aData.make|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Id Provider')}:</td>
				<td><input type=text name=data[id_provider] value="{$aData.id_provider|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('OrderStatus')}:</td>
				<td><input type=text name=data[order_status] value="{$aData.order_status|escape}"></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Comment')}:</td>
				<td><textarea name=data[comment]>{$aData.comment}</textarea></td>
			</tr>
			<tr>
				<td width="100%">{$oLanguage->getDMessage('Post Date')}:</td>
				<td><input type=text name=data[post_date]
					value="{$aData.post_date|escape}"></td>
			</tr>
		</table>

		</td>
	</tr>
</table>

<input type=hidden name=data[id] value="{$aData.id|escape}"> 
{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}</FORM>