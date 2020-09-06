{foreach from=$aAccountMenu item=aItem}
	<li><a href="{if !$aItem.link}?action={$aItem.code}{else}{$aItem.code}{/if}">{$aItem.name}</a></li>
{/foreach}