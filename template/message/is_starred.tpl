{if !$bXajaxRequest}<span id='{$aData.id}_is_starred_span_id'>{/if}
<a href="/?action=message_change_starred_message&id_message={$aData.id}}"
	onclick="xajax_process_browse_url(this.href);return false;"
	><img src="/image/starred_{if $aData.is_starred}on{else}off{/if}.png" align="absmiddle" /></a>
{if !$bXajaxRequest}</span>{/if}