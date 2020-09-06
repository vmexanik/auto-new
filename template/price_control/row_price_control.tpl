{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}
<td nowrap>
	<a href="/?action=price_control&subaction=delete&id={$aRow.id}&return={$sReturn|escape:"url"}"
			onclick="if (!confirm('{$oLanguage->getMessage("Are you sure?")}')) return false;"
			><img src="/image/delete.png" style="min-width: 16px;" border=0 width=16 align=absmiddle alt="{$oLanguage->getMessage("Delete error item")}" title="{$oLanguage->getMessage("Delete error item")}" /> </a>
</td>
{elseif $sKey=='id'}
<td>
	<div class="order-num">{$item.sTitle}</div>
	{$aRow.id}<strong>
		<a href="#" onmouseover="show_hide('info_{$aRow.id}','inline')" onmouseout="show_hide('info_{$aRow.id}','none')"
			onclick="return false"><img src='/image/helpicon16.png' border=0 align=absmiddle hspace=0>
		</a>&nbsp;</strong></nobr>
		<div style="display: none; margin: 0 0 0 0;width: auto;" align=left class="status_div" id="info_{$aRow.id}">
			<div><b>{$oLanguage->GetMessage('Id_price_queue')}</b>: {$aRow.id_price_queue}</div>
			<div><b>{$oLanguage->GetMessage('post_date_queue')}</b>: {$oLanguage->getPostDateTime($aRow.pq_post_date)}</div>
			<div><b>{$oLanguage->GetMessage('file_queue')}</b>: {$aRow.file_name_original}</div>
			<div><b>{$oLanguage->GetMessage('provider')}</b>: {$aRow.login_provider}</div>
			<div><b>{$oLanguage->GetMessage('name_price_profile')}</b>: {$aRow.name_price_profile}</div>
			<div><b>{$oLanguage->GetMessage('source')}</b>: {$aRow.pq_source}</div>
			<div><b>{$oLanguage->GetMessage('sum_all_queue')}</b>: {$aRow.pq_sum_all}</div>
			<div><b>{$oLanguage->GetMessage('sum_errors_queue')}</b>: <span style="color:red;">{$aRow.pq_sum_errors}</span></div>
			<div><b>{$oLanguage->GetMessage('status_queue')}</b>: {if $aRow.pq_is_processed==3}<span style="color:red">{$oLanguage->getMessage('stopped')}</span>
				{elseif $aRow.pq_is_processed==1}<span style="color:blue">{$oLanguage->getMessage('loaded')}</span>
				{elseif $aRow.pq_is_processed==2}{$oLanguage->getMessage('ended')}{/if}</div>
	</div>
</td>
{elseif $sKey=='login_provider_buffer'}
<td><div class="order-num">{$item.sTitle}</div>
    {if $aRow.login_store}
    	{$aRow.login_store}
	{elseif $aRow.$sKey}
		{$aRow.login_provider_buffer}
	{else}
		<select name='provider_sel_{$aRow.id}' id='provider_sel_{$aRow.id}' class="js-select">
			{html_options options=$aProvider}
		</select>
		<img src="/image/apply.png" style="cursor:pointer" width=16 title="{$oLanguage->getMessage('Save')}"
		onclick="{strip}if (confirm('{$oLanguage->getMessage("Are you sure set selected provider?")}'))
		xajax_process_browse_url('?action=price_control_set_provider
		&id_provider='+$('#provider_sel_{$aRow.id}').val()
		+'&id={$aRow.id}');
		{/strip}">
	{/if}
</td>
{elseif $sKey=='code'}
<td><div class="order-num">{$item.sTitle}</div>
	{if $aRow.code_in && $aRow.code_in!=$aRow.code}
		<span style="color:grey">({$aRow.code_in})</span><br>
	{/if}
	{if $aRow.$sKey}<span style="color:{if $aRow.is_code_ok==0 && $aRow.is_checked_code==1}red{else}black{/if}">{$aRow.$sKey}</span><br>{/if}
	{if !$aRow.$sKey}
		<input type="text" value="" style="width: 100px;" id="code_sel_{$aRow.id}">
		<img src="/image/apply.png" style="cursor:pointer" width=16 title="{$oLanguage->getMessage('Save')}"
			onclick="{strip}if (confirm('{$oLanguage->getMessage("Are you sure set this code?")}'))
			xajax_process_browse_url('?action=price_control_set_code
			&code='+$('#code_sel_{$aRow.id}').val()
			+'&id={$aRow.id}');
			{/strip}">
	{/if}
	{if $aRow.is_code_ok==0 && $aRow.is_checked_code==1}
		<div style="display: inline-flex;padding: 5px;">
		<a href="/pages/price_control_change_code_pi/?id={$aRow.id}&return_action={$smarty.server.REQUEST_URI|urlencode}" style="padding-right:5px;"><img src="/image/design/change.png" width=16 title="{$oLanguage->getMessage('replace_code')}"></a>
		{if $aRow.pref}
			<a href="/pages/price_control_edit_code_pi/?id={$aRow.id}&return_action={$smarty.server.REQUEST_URI|urlencode}" style="padding-right:5px;"><img src="/image/edit.png" width=16 title="{$oLanguage->getMessage('edit_code')}"></a>
		{/if}
		{if !$aRow.id_tof}
			<a href="javascript:;" onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want add product non tecdoc?")}'))
			{literal}{{/literal}add_product({$aRow.id}){literal}}{/literal} else {literal}{{/literal}return false;}">
			<img src="/image/plus.png" title="{$oLanguage->getMessage('create_product_non_tecdoc')}" width=16></a>
		{else}
			<a href="javascript:;" onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want add product tecdoc?")}'))
			{literal}{{/literal}add_product({$aRow.id}){literal}}{/literal} else {literal}{{/literal}return false;}">
			<img src="/image/plus.png" title="{$oLanguage->getMessage('create_product_tecdoc')}" width=16></a>
		{/if}
		</div>
	{/if}
</td>
{elseif $sKey=='price'}
<td><div class="order-num">{$item.sTitle}</div>
	{if $aRow.$sKey}{$aRow.price}<br>{/if}
	{if $aRow.$sKey<=0}
		<input type="text" value="" id="price_sel_{$aRow.id}">
		<img src="/image/apply.png" style="cursor:pointer" width=16 title="{$oLanguage->getMessage('Save')}"
			onclick="{strip}if (confirm('{$oLanguage->getMessage("Are you sure set this price?")}'))
			xajax_process_browse_url('?action=price_control_set_price
			&price='+$('#price_sel_{$aRow.id}').val()
			+'&id={$aRow.id}');
			{/strip}">
	{/if}
</td>
{elseif $sKey=='stock'}
<td style=" white-space: nowrap;"><div class="order-num">{$item.sTitle}</div>
	{if !$aRow.store_stock}
    	{$aRow.$sKey}
    {else}
    	{foreach key=name item=value from=$aRow.store_stock}
    		{$value}
    	{/foreach}
    {/if}
</td>
{elseif $sKey=='cat'}
<td><div class="order-num">{$item.sTitle} {if $aRow.id_tof}(TecDoc){elseif !$aRow.pref}(нет на сайте){else}(не TecDoc){/if}</div>
   	{if $aRow.$sKey}<span style="color:{if !$aRow.pref}red{else}black{/if}">{$aRow.$sKey}</span><br>{/if}
   	{if !$aRow.$sKey || !$aRow.pref}
		<select name='brand_sel_{$aRow.id}' id='brand_sel_{$aRow.id}' class="js-select">
			{html_options options=$aBrands}
		</select>
		<img src="/image/apply.png" style="cursor:pointer" width=16 title="{$oLanguage->getMessage('Save')}"
			onclick="{strip}if (confirm('{$oLanguage->getMessage("Are you sure set selected brand?")}'))
				xajax_process_browse_url('?action=price_control_set_brand
				&id_brand='+$('#brand_sel_{$aRow.id}').val()
				+'&id={$aRow.id}');
				{/strip}">
		{if $aRow.$sKey}
			<img src="/image/design/plus.gif" style="cursor:pointer" width=16 title="{$oLanguage->getMessage('Create new')}"
			onclick="{strip}if (confirm('{$oLanguage->getMessage("Are you sure create new brand?")}'))
				set_new_brand({$aRow.id});
				{/strip}">
		{/if}   		
   	{/if}
</td>
{elseif $sKey=='part_rus'}
<td class="word-break"><div class="order-num">{$item.sTitle}</div>
   	{if $aRow.$sKey}{$aRow.$sKey}<br>{/if}
   	{if !$aRow.$sKey}
		<input type="text" value="" id="name_sel_{$aRow.id}">
		<img src="/image/apply.png" style="cursor:pointer" width=16 title="{$oLanguage->getMessage('Save')}"
			onclick="{strip}if (confirm('{$oLanguage->getMessage("Are you sure set this name?")}'))
			xajax_process_browse_url('?action=price_control_set_name
			&name='+$('#name_sel_{$aRow.id}').val()
			+'&id={$aRow.id}');
			{/strip}">
	{/if}   	
</td>
{else}
<td><div class="order-num">{$item.sTitle}</div>
    {$aRow.$sKey}
</td>
{/if}
{/foreach}