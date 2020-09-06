<a href="?action={$sBaseAction}_restore&return={$sReturn|escape:"url"}"
	onclick="if (confirm_delete_glg())
	{ldelim}
		update_input('main_form','action','{$sBaseAction}_restore');
		update_input('main_form','return','{$sReturn|escape}');
		submit_form();
	{rdelim}   return false;" class="submenu">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/reload_page.png"/
	>{$oLanguage->GetDMessage('Restore')}</a>
	
<a href="?action={$sBaseAction}_delete&return={$sReturn|escape:"url"}"
	onclick="if (confirm_delete_glg())
	{ldelim}
		update_input('main_form','action','{$sBaseAction}_delete');
		update_input('main_form','return','{$sReturn|escape}');
		submit_form();
	{rdelim}   return false;" class="submenu">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/delete.png"/
	>{$oLanguage->GetDMessage('Delete')}</a>