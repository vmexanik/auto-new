{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}<td nowrap>{include file='addon/mpanel/base_row_edit.tpl' sBaseAction=$sBaseAction}
	<a href="{strip}
		?action=user_change_password&id={$aRow.id}&call_action={$sBaseAction}&return={$sReturn|escape:"url"}
	{/strip}"
	onclick="xajax_process_browse_url(this.href); return false;">
	<img border=0 src="/libp/mpanel/images/small/copy.png"  hspace=3 align=absmiddle
		/>{$oLanguage->getDMessage('Change password')}</a>
		
	<a href="{strip}
		?action=provider_clear&id={$aRow.id}&return={$sReturn|escape:"url"}
	{/strip}"
	onclick="if (confirm('вы уверены?')) xajax_process_browse_url(this.href); return false;">
	<img border=0 src="/libp/mpanel/images/small/del.png"  hspace=3 align=absmiddle
		/>{$oLanguage->GetDMessage('clear provider')}</a>
</td>
{elseif $sKey=='visible'}<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
{elseif $sKey=='name'}
<td>{$aRow.$sKey}
{if $aRow.last_date_work}<br><span style="color:green;">{$aRow.last_date_work}</span>{/if}
</td>
{else}<td>{$aRow.$sKey}</td>
{/if}
{/foreach}

