{capture name=add_link_href}./{strip}
	?action=cart_add_cart_item&id_provider={$aRow.id_provider}&item_code={$aRow.item_code}
	{if $smarty.get.manager_login}
		&manager_login={$smarty.get.manager_login}
	{/if}
	{if $smarty.request.data.id_part}
		&id_part={$smarty.request.data.id_part}
	{/if}
{/strip}{/capture}

<a href="javascript:;"
onclick="{strip}
xajax_process_browse_url('{$smarty.capture.add_link_href}&xajax_request=1&hilight_code={$aRow.code}
&link_id=add_link_{$aRow.item_code}_{$aRow.id_provider}
&number='+document.getElementById('number_{$aRow.item_code}_{$aRow.id_provider}').value+'
&reference='+document.getElementById('reference_{$aRow.item_code}_{$aRow.id_provider}').value);

oCart.AnimateAdd(this);

return false;{/strip}"

	class="at-btn">{if $bLabel}Купить{else}<i></i>{/if}</a>