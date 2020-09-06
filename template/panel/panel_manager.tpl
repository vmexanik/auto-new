{foreach from=$aAccountMenu item=aItem}
	<li class="first"><a href="{if !$aItem.link}?action={$aItem.code}{else}{$aItem.code}{/if}">{$aItem.name}</a></li>
{/foreach}