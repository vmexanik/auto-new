<a href="{strip}
	?action=role_action_exeption_rebuild&return={$sReturn|escape:"url"}
	{/strip}"
	onclick="if (confirm('вы уверены?')) xajax_process_browse_url(this.href); return false;">
	<img border=0 src="/libp/mpanel/images/small/refresh.png"  hspace=3 align=absmiddle
		/>{$oLanguage->GetDMessage('rebuild all actions')}</a>

<a class=submenu href="?action={$sBaseAction}_setexeption&return={$sReturn|escape:"url"}" onclick="

if (confirm('Вы действительно хотите установить исключение на выделенные события?'))
{ldelim}
	update_input('main_form','action','{$sBaseAction}_setexeption');
	update_input('main_form','return','{$sReturn|escape}');
	submit_form();
{rdelim}  return false;">

<IMG border=0 src="/libp/mpanel/images/small/document_check.png" hspace=3 align=absmiddle>{$oLanguage->getDMessage('Set is exeption')}</A>

<a class=submenu href="?action={$sBaseAction}_unsetexeption&return={$sReturn|escape:"url"}" onclick="
if (confirm('Вы действительно хотите снять исключение на выделенных событиях?'))
{ldelim}
	update_input('main_form','action','{$sBaseAction}_unsetexeption');
	update_input('main_form','return','{$sReturn|escape}');
	submit_form();
{rdelim}  return false;">

<IMG border=0 src="/libp/mpanel/images/small/document_plain.png" hspace=3 align=absmiddle>{$oLanguage->getDMessage('unSet is exeption')}</A>

<a class=submenu href="?action={$sBaseAction}_move_permissions_list&return={$sReturn|escape:"url"}" onclick="
if (confirm('Вы действительно переместить выделенные события в список разрешений?'))
{ldelim}
	update_input('main_form','action','{$sBaseAction}_move_permissions_list');
	update_input('main_form','return','{$sReturn|escape}');
	submit_form();
{rdelim}  return false;">

<IMG border=0 src="/libp/mpanel/images/small/clipboard_next.png" hspace=3 align=absmiddle>{$oLanguage->getDMessage('Move permissions list')}</A>

{include file='addon/mpanel/base_sub_menu.tpl' sBaseAction=$sBaseAction not_archive='1'}