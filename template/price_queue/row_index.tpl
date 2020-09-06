{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}
<td nowrap>
    {if $aRow.is_processed == 2}
      {if $aRow.sum_errors == 0}
    	<img src="/image/button_ok.png" border=0 width=16 align=absmiddle />{*$oLanguage->getMessage("Work ok")*}
      {else}
      	<img src="/image/button_error.png" border=0 width=16 align=absmiddle />{*$oLanguage->getMessage("Work error")*}
      	<a href="" onclick="xajax_process_browse_url('/?action=price_queue_message_show&id={$aRow.id}');$('#popup_id').show();return false;">
      	<img src="/image/message_icon.gif" border=0 width=16 align=absmiddle />{*$oLanguage->getMessage("Message")*}</a>
      {/if}
    {/if}
    {if $aRow.is_processed == 1}
    <a href="/?action=price_queue_stop&id={$aRow.id}&return={$sReturn|escape:"url"}"
    	onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want stopped")}')) return false;"
    ><img src="/image/stop.png" border=0 width=16 align=absmiddle /></a>
    {else}
    <a href="/?action=price_queue_edit&id={$aRow.id}&return={$sReturn|escape:"url"}">
    <img src="/image/edit.png" border=0 width=16 align=absmiddle /></a>
    	{if $aRow.is_processed == 0 or $aRow.is_processed == 3}
    	<a href="/?action=price_queue_delete&id={$aRow.id}&return={$sReturn|escape:"url"}"
    		onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want delete")}')) return false;"
    	><img src="/image/delete.png" border=0 width=16 align=absmiddle /></a>
    	{/if}
    {/if}
</td>

{elseif $sKey=='pp_name'}
<td>
    <div class="order-num">{$oLanguage->GetMessage('Price profile')}</div>
    <a href="/?action=price_profile_edit&id={$aRow.id_price_profile}&return=action%3Dprice" onclick="StopInterval();">{$aRow.pp_name}</a>
    <br>
    <div class="order-num">{$oLanguage->GetMessage('Source')}</div>
    {$aRow.source}</br>
    <i style="font-size:13px;color:blue;">{$aRow.email}</i>
</td>

{elseif $sKey=='file_path'}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {if $aRow.text_error}
    <span style="white-space: nowrap;">
	<span onmouseover="show_hide('{$aRow.id}_text_error','inline')" onmouseout="show_hide('{$aRow.id}_text_error','none')">
	<img src="/image/button_error.png" border="0" width="16" align="absmiddle">
	<div align="left" style="display: none; " class="tip_div tip_error" id="{$aRow.id}_text_error">{$aRow.text_error}</div>
	</span>
    </span>
    {/if}
    <a href="/imgbank/price/queue/{$aRow.file_name|escape:'url'}" download>{$aRow.file_name_original}</a>
    <br>
    <div class="order-num">{$oLanguage->GetMessage('Date')}</div>
    {$aRow.post_date}
    <br>
    <div class="order-num">{$oLanguage->GetMessage('Provider')}</div>
    <span style="white-space: nowrap;">
    {$aRow.up_name}{$oLanguage->getContextHintProvider($aRow.id_user_provider,$aRow.id)}
    {if $aRow.$sKey}
    	<a onclick="StopInterval();" href="/?action=price_profile_provider_edit&id={$aRow.id_user_provider}&return=action%3Dprice">
    		<img src="/image/edit.png" border="0" width="16">
    	</a>
    {/if}
    </span>
</td>

{else}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {if $sKey=='sum_all' and $aRow.$sKey eq 0}
	{else}
	 {$aRow.$sKey}
	{/if}
</td>
{/if}
{/foreach}
