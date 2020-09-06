
{foreach from=$aTranslateLanguageList item=aItem}
<A href="?action=translate_change&amp;content={$aItem.code}" onclick="xajax_process_browse_url(this.href);  return false;">
<IMG border=0 src="{$aItem.image}"
		{if $smarty.session.translate.current_locale==$aItem.code}
		width='28' height='20'
		{else}
		width='18' height='12'
		{/if}

		hspace=3 align=absmiddle>

		{if $smarty.session.translate.current_locale==$aItem.code}<font size=+1>{/if}
			{$aItem.name}
		{if $smarty.session.translate.current_locale==$aItem.code}</font>{/if}

			</A>
{/foreach}

{*wtf is this?*}


&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


<a class=submenu href="?action={$sBaseAction}_trash&return={$sReturn|escape:"url"}" onclick="
	update_input('main_form','action','{$sBaseAction}_save');
	update_input('main_form','return','{$sReturn|escape}');
	submit_form();
    return false;">

<IMG border=0 src="/libp/mpanel/images/medium/yast_bootmode.png" hspace=3 align=absmiddle>{$oLanguage->getDMessage('Save')}</A>


<a class=submenu href="?action={$sBaseAction}_export_translation"  onclick="
	update_input('main_form','action','{$sBaseAction}_export_translation');
	submit_form();
    return false;">
	<img border=0 src="/libp/mpanel/images/small/outbox.png"
	hspace=3 align=absmiddle>{$oLanguage->getDMessage('Export translations')}</a>

<a href="?action={$sBaseAction}_import_translation&amp;return={$sReturn|escape:"url"}"
	onclick="xajax_process_browse_url(this.href); return false;" class="submenu">
	<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/inbox.png"/
	>{$oLanguage->GetDMessage('Import tranlsations')}</a>