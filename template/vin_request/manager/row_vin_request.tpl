<td>
    <div class="order-num">{$oLanguage->GetMessage('#')}</div>
    {$aRow.id}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Customer')}</div>
	{$oLanguage->AddOldParser('customer',$aRow.id_user)}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('vin')}</div>
    {$aRow.vin|upper}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('Order Status')}</div>
    {$oLanguage->getOrderStatus($aRow.order_status)}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('date')}</div>
    {$oLanguage->getPostDate($aRow.post_date)}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Manager Comment')}</div>
    {if $aRow.manager_comment}{$aRow.manager_comment}{/if}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('Remember')}</div>
    {if $aRow.order_status=='refused' || $aRow.order_status=='parsed'}
    <input type='checkbox' сlass='js-checkbox' value='1' {if $aRow.is_remember}checked{/if} 
    	onclick="xajax_process_browse_url('?action=vin_request_manager_remember&id={$aRow.id}&checked='+this.checked);"
    	>{$aRow.remember_text}
    {/if}
</td>
<td nowrap>
    <a href="/?action=vin_request_manager_edit&id={$aRow.id}&return={$sReturn|escape:"url"}"
    	{if !$aRow.id_manager_fixed} style="font-color:green;"{/if}
    	><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle alt="{$oLanguage->getMessage('Preview')}" title="{$oLanguage->getMessage('Preview')}"/>{$oLanguage->getMessage('Preview')}</a>
    
    {if $aRow.order_status=='work'}
    <br>
    <a href="/?action=vin_request_package_create&id_vin_request={$aRow.id}&return={$sReturn|escape:"url"}"
    		><img src="/image/pack2.png" border=0 width=16 align=absmiddle alt="{$oLanguage->getMessage('create package')}" title="{$oLanguage->getMessage('create package')}" />{$oLanguage->getMessage('create package')}</a>
    {/if}
    <br><a href="/?action=vin_request_copy&id={$aRow.id}&id_customer_for={$aRow.id_user}&login_customer_for={$aRow.login}"
    	><img src="/image/redo.png" border=0  width=16 align=absmiddle alt="{$oLanguage->getMessage('copy as new')}" title="{$oLanguage->getMessage('copy as new')}"/>{$oLanguage->getMessage('copy as new')}</a>
</td>