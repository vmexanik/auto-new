<td>
    <div class="order-num">{$oLanguage->GetMessage('#')}</div>
    <a href="/?action=manager_package_edit&id={$aRow.id}&return={$sReturn|escape:"url"}">{$aRow.id}</a>
    <br>
    <div class="order-num">{$oLanguage->GetMessage('date')}</div>
    {$oLanguage->getPostDateTime($aRow.post_date)}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('order status')}</div>
    {$oLanguage->getOrderStatus($aRow.order_status)} {if $aRow.is_reclamation}<b>{$oLanguage->getMessage('reclamation')}</b>{/if}
    {if $aRow.is_need_check}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('need check')}</div>
    	<span id="auto_{$aRow.id}" 
        	onclick="set_checked_auto(this,{if ($aRow.is_checked_auto)}'0'{else}'1'{/if})" 
        	onmouseover="$('#tip_auto_{$aRow.id}').show();" 
        	onmouseout="$('#tip_auto_{$aRow.id}').hide();">
        	{if $aRow.is_checked_auto == 0}
        		<a><img src="/image/design/not_sel_chk.png"></img></a>
        	{else}
        		<a><img src="/image/design/sel_chk.png"></img></a>
        	{/if}
        	<div align="left" style="width: 500px;" class="tip_div" id="tip_auto_{$aRow.id}">{$aRow.sAutoInfo}</div>
    	</span>
    {/if}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('customer')}</div>
    {assign var="Id" value=$aRow.id_user|cat:"_"|cat:$aRow.id}
    {$oLanguage->AddOldParser('customer_uniq',$Id)}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('parts')}</div>
    {if $aRow.aCart}
    {foreach key=iCart item=aCart from=$aRow.aCart}
    {if $aCart.history}
    <nobr>
    <strong><a href="#" onmouseover="show_hide('history_{$aCart.id}','inline')" onmouseout="show_hide('history_{$aCart.id}','none')"
    	onclick="return false"><img src='/image/comment.png' border=0 align=absmiddle hspace=0>
    	{*$oLanguage->getMessage("History")*}</a></strong></nobr>
    <div style="display: none; " align=left class="tip_div" id="history_{$aCart.id}">
    	{foreach from=$aCart.history item=aHistory}
    		<div>
    		 {$oLanguage->getOrderStatus($aHistory.order_status)} - {$oLanguage->getDateTime($aHistory.post)}<br>
    		{$aHistory.comment}
    		</div>
    	{/foreach}
    </div>
    {/if}
    {if $aCart.order_status=='refused'}<strike>{/if}
    {$aCart.code} {if $aCart.code_changed}=>({$aCart.code_changed}){/if} <b>{if $aCart.cat_name_changed}{$aCart.cat_name_changed}{else}{$aCart.cat_name}{/if}
    	[ <font color="red">{$aCart.number}</font> ] </b><font color=green>{$aCart.name_translate}</font>
    {if $aCart.order_status=='refused'}</strike>{/if}
    <br>
    {/foreach}
    {/if}
    {if $aRow.order_status=="pending" || $aRow.order_status=="work" && $aRow.is_payed==0}
    <a href="/?action=manager_empty_package_delete&id={$aRow.id}&return={$sReturn|escape:"url"}"
    		onclick="if (!confirm('{$oLanguage->getMessage("Are you sure?")}')) return false;"
    		><img src="/image/delete.png" border=0 width=16 align=absmiddle /> {$oLanguage->getMessage("Delete Package")}</a>
    {/if}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('total without delivery')}</div>
    {$oCurrency->PrintPrice($aRow.price_total-$aRow.price_delivery)}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('delivery price')}</div>
    {$oCurrency->PrintPrice($aRow.price_delivery)}
    {if $aRow.price_additional>0}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('Additional payment')}</div>
    {$oCurrency->PrintPrice($aRow.price_additional)}
    {/if}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('total')}</div>
    {$oCurrency->PrintPrice($aRow.price_total)}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('delivery type')}</div>
    {$aRow.delivery_type_name}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('payment type')}</div>
    {$aRow.payment_type_name}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('Is payed')}</div>
    {include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_payed}
</td>
</tr>
<tr>
    <td colspan="8" style="border-bottom: 3px double #2f86c2; text-align: center;">
    {if $aAuthUser.is_super_manager}
    	{if $aRow.order_status=='pending' || $aRow.order_status=='new' || $aRow.order_status==''}
    	<a href="/?action=manager_package_order&id={$aRow.id}&confirm=1"
    		><img src="/image/apply.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Send Package to Work")}</a>
    	{/if}
    {/if}
    <a href="/?action=manager_order_print&id={$aRow.id}&id_user={$aRow.id_user}" target=_blank
    	><img src="/image/fileprint.png" border=0 width=16 align=absmiddle hspace=1/>{$oLanguage->getMessage("Print")}</a>

	<a href="/?action=manager_order&search[id_cart_package]={$aRow.id}"
    	><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{if $aRow.is_viewed==0}<b>{$oLanguage->getMessage("Browse Detals")}</b>{else}{$oLanguage->getMessage("Browse Detals")}{/if}</a>
    
    <a href="{strip}/?action=finance_bill_add&code_template=order_bill
        &data[amount]={$oCurrency->PrintPrice($aRow.price_total,'',2,'<none>')}
        &data[id_cart_package]={$aRow.id}
        &data[login]={$aRow.login}&return_action=manager_package_list
        {/strip}"
    	><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Order Bill")}</a>
    <a href="{strip}/?action=finance_bill_add&code_template=order_bill_bv
        &data[amount]={$oCurrency->PrintPrice($aRow.price_total,'',2,'<none>')}
        &data[id_cart_package]={$aRow.id}
        &data[login]={$aRow.login}&return_action=manager_package_list
        {/strip}"
    	><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Order Bill BV")}</a>
    <a href="{strip}/?action=finance_bill_add&code_template=order_bill_rko
        &data[amount]={$oCurrency->PrintPrice($aRow.price_total,'',2,'<none>')}
        &data[id_cart_package]={$aRow.id}
        &data[login]={$aRow.login}&return_action=manager_package_list
        {/strip}"
    	><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Order Bill RKO")}</a>
    {if !$aRow.is_payed}
    <a {strip}href="/?action=manager_package_payed&id={$aRow.id}&return={$sReturn|escape:"url"}"
        {/strip}><img src="/image/inbox.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("Set cart package payed")}</a>
    {/if}
    </td>