<td>{$aRow.auto_name}</td>
<td>{$aRow.post_date}</td>
<td>
<a href="{$aRow.tecdoc_url}">
	<img src="/image/icon_ask.gif" border="0" width="16" align="absmiddle" hspace="1/">
	{$oLanguage->getMessage('view_catalog')}
</a>&nbsp;
{if $aAuthUser.id && !$aRow.ua_id}
<a href="/pages/own_auto_add/?log_id={$aRow.id}">
	<img src="/image/design/plus.gif" border="0" width="16" align="absmiddle" hspace="1/">
	{$oLanguage->getMessage('add to garage')}
</a>
{else}
    <span class="add_auto_garage_disable">
	<img src="/image/plus_disable.gif" border="0" width="16" align="absmiddle" hspace="1/">
	{$oLanguage->getMessage('add to garage')}
    </span>
{/if}
&nbsp;
{if $aAuthUser.id}
<a href="/pages/own_auto_del_from_log/?id={$aRow.id}">
	<img src="/image/delete.png" border="0" width="16" align="absmiddle" hspace="1/">
	{$oLanguage->getMessage('delete')}
</a>
{/if}
</td>
