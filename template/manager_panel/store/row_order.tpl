{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}
<td nowrap>
	<span class="glyphicon glyphicon-file" aria-hidden="true" style="font-size:14px;cursor:pointer"
		onclick="xajax_process_browse_url('/?action=manager_panel_store_view&id={$aRow.id}&return={$sReturn|escape:"url"}'); return false;">
	</span>
	<a href="/?action=manager_panel_print_order&id={$aRow.id}&return={$sReturn}" onclick="xajax_process_browse_url(this.href); return false;">
	<img src="/image/fileprint.png" border="0" width="16" align="absmiddle" hspace="1" style="padding-bottom:3px;">
	</a>
</td>
{elseif $sKey=='id'}
<td style="white-space: nowrap;">
{$oLanguage->getMessage('cartpackage #')} #{$aRow.id}
{if $aRow.is_web_order}<span><img src="/image/design/globe_icon.png" border=0 width=16 align=absmiddle></span>{/if}
</td>
{elseif $sKey=='name'}
<td>
<div style="overflow:overlay;font-size:11px;max-width:130px;word-wrap: break-word;">
<span>
    {if $aRow.order_status=="new" || $oContent->CheckAccessManager(false,'manager_package_edit_detail')}
	<a href="/?action=manager_edit_weight&id_cart={$aRow.id}&item_code={$aRow.item_code}&name={$aRow.name_translate}&return={$sReturn|escape:"url"}"
	><img src="/image/edit.png" border=0 width=16 align=absmiddle /></a>
    {/if}
	{$aRow.name_translate}
	<br><font color="#9B9B9B">{$aRow.customer_comment}</font>
</span>
</div>
</td>
{elseif $sKey=='provider'}
<td>
	{if $aAllowChangeProviderDetailStatus && $aRow.order_status|in_array:$aAllowChangeProviderDetailStatus}
	<a href="/?action=manager_change_provider&id_cart={$aRow.id}&return={$sReturn|escape:"url"}"
		><img src="/image/edit.png" border=0 width=16 align=absmiddle /></a>
	{/if}
	<span onmouseover="$('#provider_{$aRow.id_provider}_{$aRow.id}').toggle();" onmouseout="$('#provider_{$aRow.id_provider}_{$aRow.id}').toggle();">
		<a href="/?action=manager_order&search[id_provider]={$aRow.id_provider}">{$aRow.provider_name}</a>
		<div align="left" style="width:auto;padding: 5px; display: none;" class="tip_div" id="provider_{$aRow.id_provider}_{$aRow.id}">
			<p>Баланс:<br>
				{if $aRow.id_group_provider}
					{if $aRow.provider_group_account_amount>0}
						<b><font color="green">{$aRow.provider_group_account_amount}</font></b> (Предоплата)
					{elseif $aRow.provider_group_account_amount==0}
						<b><font color="black">0,00</font></b>
					{else}
						<b><font color="red">{$aRow.provider_group_account_amount}</font></b> (Долг)
					{/if}			
				{else}
					{if $aRow.provider_account_amount>0}
						<b><font color="green">{$aRow.provider_account_amount}</font></b> (Предоплата)
					{elseif $aRow.provider_account_amount==0}
						<b><font color="black">0,00</font></b>
					{else}
						<b><font color="red">{$aRow.provider_account_amount}</font></b> (Долг)
					{/if}
				{/if}
			</p>
		</div>
	</span>
</td>
{elseif $sKey=='customer_name'}
<td style="white-space:nowrap;">
{*assign var="Id" value=$aRow.id_user|cat:"_"|cat:$aRow.id}
{$oLanguage->AddOldParser('customer_uniq',$Id)*}
	{if $aRow.customer_name}{$aRow.customer_name}{else}{$aRow.login}{/if}
	{if $aRow.phone}<br>({$aRow.phone}){/if}
	{if $aRow.user_is_new}<span class="label label-danger">new</span>{/if}
</td>
{elseif $sKey=='cp_post_date_f'}
<td>{if $aRow.order_status=='return_store' || $aRow.order_status=='return_provider'}
		{$aRow.post|date_format:"%d.%m %H:%S"}
	{else}
		{$aRow.$sKey}
	{/if}
	{*$oLanguage->getDateTime($aRow.post)*}
</td>
{elseif $sKey=='term'}
<td> {*$aRow.term_last*}{$aRow.term}</td>
{elseif $sKey=='buh_balance'}
<td> <a href='/?action=buh_changeling&search[id_buh]=361&search[id_subconto1]={$aRow.id_user}' target=_blank>
	<font color="red">{$oCurrency->PrintPrice($aRow.buh_balance)}</font></a>
</td>
{elseif $sKey=='debt'}
<td> {if $aRow.buh_balance<$aRow.total}{$oCurrency->PrintPrice($aRow.total-$aRow.buh_balance)}{else}0{/if}</td>
{elseif $sKey=='total_original'}
<td> {$oCurrency->PrintPrice($aRow.total_original)}</td>
{elseif $sKey=='profit'}
<td> {$oCurrency->PrintSymbol($aRow.profit)}</td>
{*elseif $sKey=='price_total'}
<td>
	{$oCurrency->PrintPrice($aRow.price_total)}<br>
	{<font color=blue>{$oCurrency->PrintSymbol($aRow.price_original,$aRow.id_currency_provider)}</font>}
</td>*}
{elseif $sKey=='price_total'}
<td>{$oCurrency->PrintPrice($aRow.price_total)}</td>
{elseif $sKey=='created'}
<td style="white-space:nowrap;">
	{$aRow.$sKey}
</td>
{elseif $sKey=='changed'}
<td style="white-space:nowrap;">
	{if $aRow.post_date_changed!='0000-00-00 00:00:00'}{$aRow.$sKey}{else}{$aRow.created}{/if}
</td>
{elseif $sKey=='order_status'}
<td style="white-space:nowrap;">
	{if $aRow.order_status_select}
		{$aRow.order_status_select}
	{else}
		{$oContent->getOrderStatus($aRow.order_status)}
	{/if}
	{if $aRow.history}
	<br><nobr>
	<strong><a href="#" onmouseover="show_hide('history_{$aRow.id}','inline')" onmouseout="show_hide('history_{$aRow.id}','none')"
		onclick="return false"><img src='/image/comment.png' border=0 align=absmiddle hspace=0>
		{$oLanguage->getMessage("History")}</a>&nbsp;</strong></nobr>
	<div style="display: none;width:auto; " align=left class="status_div" id="history_{$aRow.id}">
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
	{*if $aAuthUser.type_=='manager'}
		<br><br>
			<a href="{strip}/?action=manager_panel_manager_package_list_finance_add&code_template=order_bill_rko
			&data[amount]={$oCurrency->PrintPrice($aRow.total_real,'',2,'<none>')}
			&data[id_cart_package]={$aRow.id_cart_package}&data[id_cart]={$aRow.id}
			&data[id_provider]={$aRow.id_provider}
			{/strip}"
				onclick="xajax_process_browse_url(this.href); return false;"><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle />{$oLanguage->getMessage("RKO")}</a>
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
	{/if*}	
</td>
{elseif $sKey=='cat_name'}
<td>{if $aRow.cat_name_changed}{$aRow.cat_name_changed}{else}{$aRow.cat_name}{/if}</td>
{elseif $sKey=='code'}
<td>{if $aRow.code_changed}{$aRow.code_changed}{else}{$aRow.code}{/if}<br><font color=red>ZZZ_{$aRow.zzz_code}</font></td>
{else}<td>{$aRow.$sKey}</td>
{/if}
{/foreach}