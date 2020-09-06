<a href="?action={$sBaseAction}_generate_groups"
	onclick="xajax_process_browse_url(this.href); return false;" class="submenu">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/reload_page.png"/
	>{$oLanguage->GetDMessage('Remake model groups')}</a>
{include file='addon/mpanel/base_sub_menu.tpl' sBaseAction=$sBaseAction}
{*include file='addon/mpanel/sub_menu_archive.tpl' sBaseAction=$sBaseAction}
{include file='addon/mpanel/sub_menu_unarchive.tpl' sBaseAction=$sBaseAction*}