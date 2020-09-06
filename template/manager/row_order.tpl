{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}
<td nowrap>
	{if $aRow.order_status=='pending'}
		<a href="/?action=manager_order_refuse_pending&id={$aRow.id}&return={$sReturn|escape:"url"}"
			onclick="if (!confirm('{$oLanguage->getMessage("Are you sure?")}')) return false;"
			><img src="/image/delete.png" border=0 width=16 align=absmiddle alt="{$oLanguage->getMessage("Refuse pending")}" title="{$oLanguage->getMessage("Refuse pending")}" /> </a>

	{else}
		{if $aAuthUser.is_super_manager}
	<a href="/?action=manager_order_edit&id={$aRow.id}&return={$sReturn|escape:"url"}"
		><img src="/image/edit.png" border=0 width=16 align=absmiddle alt="{$oLanguage->getMessage("Status")}" title="{$oLanguage->getMessage("Status")}" /></a>
		{/if}
	{/if}
</td>
{elseif $sKey=='id_cart_package'}
<td>
    <div class="order-num">{$item.sTitle}</div>
    <a href="/?action=manager_package_edit&id={$aRow.id_cart_package}&return=action%3Dmanager_package_list">{$aRow.id_cart_package}</a>
    <br>
    <div class="order-num">{$oLanguage->GetMessage('date')}</div>
    {$aRow.post_date}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('detail id')}</div>
    {$aRow.id}
    <br>
    <div class="order-num">{$item.sTitle}</div>
    {assign var="Id" value=$aRow.id_user|cat:"_"|cat:$aRow.id}
    {$oLanguage->AddOldParser('customer_uniq',$Id)}
</td>
{elseif $sKey=='name'}
<td>
    <div class="order-num">{$oLanguage->GetMessage('name')}</div>
    <span>
    	<a href="/?action=manager_edit_weight&id_cart={$aRow.id}&item_code={$aRow.item_code}&name={$aRow.name_translate}&return={$sReturn|escape:"url"}"
    	><img src="/image/edit.png" border=0 width=16 align=absmiddle /></a>
    	{$aRow.name_translate}
    	<br><font color="#9B9B9B">{$aRow.customer_comment}</font>
    </span>
    <br>
    <div class="order-num">{$oLanguage->GetMessage('order status')}</div>
	{$oContent->getOrderStatus($aRow.order_status)}
	{if $aRow.history}
	<br><nobr>
	<strong><a href="#" onmouseover="show_hide('history_{$aRow.id}','inline')" onmouseout="show_hide('history_{$aRow.id}','none')"
		onclick="return false"><img src='/image/comment.png' border=0 align=absmiddle hspace=0>
		{$oLanguage->getMessage("History")}</a>&nbsp;</strong></nobr>
	<div style="display: none; " align=left class="status_div" id="history_{$aRow.id}">
		{foreach from=$aRow.history item=aHistory}
			<div>
			 {$oContent->getOrderStatus($aHistory.order_status)} - {$oLanguage->getDateTime($aHistory.post)}<br>
			{$aHistory.comment}
			</div>
		{/foreach}

		{if $aRow.csc_post_date && ($aAuthUser.is_super_manager || $aAuthUser.manager) }
			<div><b>----</b></div>
			<div><b>{$oLanguage->GetMessage('Sticker confirmed')}</b> {$aRow.manager_name}<br>{$aRow.csc_post_date}</div>
			<div><b>{$oLanguage->GetMessage('Box')}</b> {$aRow.cpc_id_cart_packing_box}
			&nbsp;<b>{$oLanguage->GetMessage('Sending')}</b> {$aBoxSending[$aRow.cpc_id_cart_packing_box]}</div>
		{/if}
	</div>
	{/if}
{if $aAuthUser.type_=='manager'}
		<br><br>
		{*if $aRow.price_real<=0}
			<img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("RKO")}<br>
			<img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("BV")}<br>
			<img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("PKO")}
		{else*}
			<a href="{strip}/?action=finance_bill_provider_add&code_template=order_bill_rko
			&data[amount]={$oCurrency->PrintPrice($aRow.total_real,'',2,'<none>')}
			&data[id_cart_package]={$aRow.id_cart_package}&data[id_cart]={$aRow.id}
			&data[id_provider]={$aRow.id_provider}&return_action=manager_order
			{/strip}"
				><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("RKO")}</a>
			<br>
			<a href="{strip}/?action=finance_bill_provider_add&code_template=order_bill_bv
			&data[amount]={$oCurrency->PrintPrice($aRow.total_real,'',2,'<none>')}
			&data[id_cart_package]={$aRow.id_cart_package}&data[id_cart]={$aRow.id}
			&data[id_provider]={$aRow.id_provider}&return_action=manager_order
			{/strip}"
				><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("BV")}</a>
			<br>
			<a href="{strip}/?action=finance_bill_provider_add&code_template=order_bill
			&data[amount]={$oCurrency->PrintPrice($aRow.total_real,'',2,'<none>')}
			&data[id_cart_package]={$aRow.id_cart_package}&data[id_cart]={$aRow.id}
			&data[id_provider]={$aRow.id_provider}&return_action=manager_order
			{/strip}"
			><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("PKO")}</a>
		{*/if*}
	{/if}	
</td>
{elseif $sKey=='provider'}
<td>
    <div class="order-num">{$item.sTitle}</div>
	<a href="/?action=manager_change_provider&id_cart={$aRow.id}&return={$sReturn|escape:"url"}"
		><img src="/image/edit.png" border=0 width=16 align=absmiddle /></a>
	<a href="/?action=manager_order&search[id_provider]={$aRow.id_provider_ordered}">{$aRow.provider_name_ordered}</a>
</td>
{elseif $sKey=='user'}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {assign var="Id" value=$aRow.id_user|cat:"_"|cat:$aRow.id}
    {$oLanguage->AddOldParser('customer_uniq',$Id)}
</td>
{elseif $sKey=='date'}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {$oLanguage->getDateTime($aRow.post_date)}
</td>
{elseif $sKey=='term'}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {$aRow.term}
</td>
{elseif $sKey=='buh_balance'}
<td>
    <div class="order-num">{$item.sTitle}</div>
    <a href='/?action=buh_changeling&search[id_buh]=361&search[id_subconto1]={$aRow.id_user}' target=_blank>
	<font color="red">{$oCurrency->PrintPrice($aRow.buh_balance)}</font></a>
</td>
{elseif $sKey=='debt'}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {if $aRow.buh_balance<$aRow.total}{$oCurrency->PrintPrice($aRow.total-$aRow.buh_balance)}{else}0{/if}
</td>
{elseif $sKey=='total_original'}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {$oCurrency->PrintPrice($aRow.total_original)}
</td>
{elseif $sKey=='total_profit'}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {$oCurrency->PrintPrice($aRow.total_profit)}
</td>
{elseif $sKey=='price'}
<td>
    <div class="order-num">{$item.sTitle}</div>
	{$oCurrency->PrintPrice($aRow.price)}<br>
	<font color=blue>{$oCurrency->PrintSymbol($aRow.price_original,$aRow.id_currency_provider)}</font>
</td>
{elseif $sKey=='total'}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {$aRow.number}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('total')}</div>
    {$oCurrency->PrintSymbol($aRow.total)}
</td>
{elseif $sKey=='cat_name'}
<td>
    <div class="order-num">{$oLanguage->GetMessage('brand')}</div>
    {if $aRow.cat_name_changed}{$aRow.cat_name_changed}{else}{$aRow.cat_name}{/if}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('code')}</div>
    {if $aRow.code_changed}{$aRow.code_changed}{else}{$aRow.code}{/if}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('zzz_code')}</div>
    <font color=red>ZZZ_{$aRow.zzz_code}</font>
</td>
{elseif $sKey=='number'}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {$aRow.$sKey}
    {if $aRow.number > 1}<a href="/?action=manager_separate_cart&id_cart={$aRow.id}&return={$sReturn|escape:"url"}"
		><img src="/image/edit.png" border=0 width=16 align=absmiddle /></a>{/if}
</td>
{else}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {$aRow.$sKey}
</td>
{/if}
{/foreach}