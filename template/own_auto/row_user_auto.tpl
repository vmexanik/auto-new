<td>
    <div class="order-num">{$oLanguage->GetMessage('brand')}</div>
    {if $aRow.id_make!=''}{$aRow.id_make}{else}-{/if}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('model')}</div>
    {$aRow.id_model_detail}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('vin')}</div>
    {$aRow.vin}
</td>
<td nowrap>
	<a href="/pages/own_auto_edit/?id={$aRow.ua_id}">
		<img src="/image/edit.png" border="0" width="16" align="absmiddle" hspace="1" alt="{$oLanguage->getMessage('edit')}" title="{$oLanguage->getMessage('edit')}"/>
	</a>
	<br>
	<a href="{$aRow.tecdoc_url}">
		<img src="/image/icon_ask.gif" border="0" width="16" align="absmiddle" hspace="1" alt="{$oLanguage->getMessage('view_catalog')}" title="{$oLanguage->getMessage('view_catalog')}"/>
	</a>
	<br />	
	<a href="/pages/vin_request_add_from_garage/?car_id={$aRow.ua_id}">
		<img src="/image/design/plus.gif" border="0" width="16" align="absmiddle" hspace="1" alt="{$oLanguage->getMessage('create_request_vin')}" title="{$oLanguage->getMessage('create_request_vin')}"/>
	</a>
	<br>
	<a href="/pages/own_auto_del/?id={$aRow.ua_id}">
		<img src="/image/delete.png" border="0" width="16" align="absmiddle" hspace="1" alt="{$oLanguage->getMessage('delete')}" title="{$oLanguage->getMessage('delete')}"/>
	</a>
</td>