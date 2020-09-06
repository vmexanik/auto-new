{include file='addon/mpanel/base_sub_menu.tpl' sBaseAction=$sBaseAction not_delete=1}
 <a class=submenu href="?action=customer_clear_test_data"
	onclick="if (confirm('Are you sure?')) xajax_process_browse_url(this.href); return false;">
    <img border=0 src="/libp/mpanel/images/small/delete.png" hspace=3 align=absmiddle>{$oLanguage->getDMessage('Clear Test Data')}</a>