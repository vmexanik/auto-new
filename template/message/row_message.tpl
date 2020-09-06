<td>
    <div class="order-num">{$oLanguage->GetMessage('Subject')}</div>
    <div >
    {include file='message/is_starred.tpl' aData=$aRow}
    
    <a href="/?action=message_preview&id={$aRow.id}{if $bDraft}&draft=1{/if}"
    	{if $aRow.is_read}class='normal'{else}class="not-read"{/if}>{if $aRow.subject}{$aRow.subject}{else}{$oLanguage->GetMessage('(no subject)')}{/if}
    	</a>
    </div>
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('From')}</div>
	{if $aRow.id_customer_from && $aAuthUser.type_=='manager'}
		{$oLanguage->AddOldParser('customer',$aRow.id_customer_from)}
	{else}{$aRow.from}{/if}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('To')}</div>
	{if $aRow.id_customer_to && $aAuthUser.type_=='manager'}
		{$oLanguage->AddOldParser('customer',$aRow.id_customer_to)}
	{else}{$aRow.to}{/if}
</td>
<td>
    <div class="order-num">{$oLanguage->GetMessage('Post Date')}</div>
    {$oLanguage->getDateTime($aRow.timestamp)}
</td>
<td nowrap>
<a href="/?action=message_preview&id={$aRow.id}{if $bDraft}&draft=1{/if}"
	{if $aRow.is_read}class='normal'{else}class="not-read"{/if} ><img src="/image/tooloptions.png" border=0 width=16 align=absmiddle
	/>{$oLanguage->getMessage("Preview")}</a>

{if $smarty.session.message.current_folder_id!=4}
<br>
<a href="/?action=message_delete&id={$aRow.id}"
	onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
	{if $aRow.is_read}class='normal'{else}class="not-read"{/if}><img src="/image/delete.png" border=0  width=16 align=absmiddle
	/>{$oLanguage->getMessage("To Archive")}</a>
{/if}
</td>
