<a href="?action={$sBaseAction}_generate&amp;return={$sReturn|escape:"url"}"
	onclick="xajax_process_browse_url(this.href); return false;" class="submenu">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/reload_page.png"
>{$oLanguage->GetDMessage('generate css')}</a>
<a href="?action={$sBaseAction}_set_default&amp;return={$sReturn|escape:"url"}"
	onclick="xajax_process_browse_url(this.href); return false;" class="submenu">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/del.png"
>{$oLanguage->GetDMessage('set default')}</a>