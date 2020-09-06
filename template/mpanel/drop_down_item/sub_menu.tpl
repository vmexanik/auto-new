<a class=submenu href="?action=drop_down" onclick="xajax_process_browse_url(this.href); return false;">
<img border=0 height=32 src="/libp/mpanel/images/small/restore_f2.png" width=32 hspace=3 align=absmiddle>{$oLanguage->getDMessage('Back')}</a>

<a href="?action={$sBaseAction}_add&amp;id_parent={$aRequest.id_parent}&amp;return={$sReturn|escape:"url"}"
	onclick="xajax_process_browse_url(this.href); return false;" class="submenu">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/new.png"/
	>{$oLanguage->GetDMessage('Add new')}</a>

<a href="?action={$sBaseAction}_delete&id_parent={$aRequest.id_parent}&amp;return={$sReturn|escape:"url"}"
	onclick="if (confirm_delete_glg())
	{ldelim}
		update_input('main_form','action','{$sBaseAction}_delete');
		update_input('main_form','return','{$sReturn|escape}');
		submit_form();
	{rdelim}   return false;" class="submenu">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/delete.png"/
	>{$oLanguage->GetDMessage('Delete')}</a>

<a class=submenu href="?action={$sBaseAction}_trash&id_parent={$aRequest.id_parent}&amp;return={$sReturn|escape:"url"}" onclick="

if (confirm_delete_glg())
{ldelim}
	update_input('main_form','action','{$sBaseAction}_trash');
	update_input('main_form','return','{$sReturn|escape}');
	submit_form();
{rdelim}  return false;">

<IMG border=0 height=32 src="/libp/mpanel/images/small/trashcan_empty.png" width=32 hspace=3 align=absmiddle>{$oLanguage->getDMessage('Move to Trash')}</A>