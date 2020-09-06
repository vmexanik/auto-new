<a href="?action={$sBaseAction}_remake_pref"
	onclick="xajax_process_browse_url(this.href); return false;" class="submenu">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/reload_page.png"/
	>{$oLanguage->GetDMessage('Remake group pref')}</a>
{include file='addon/mpanel/base_sub_menu.tpl' sBaseAction=$sBaseAction}