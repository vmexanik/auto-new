{foreach key=sKey item=item from=$oTable->aColumn}

{if $sKey=='action'}<td nowrap>
	<nobr>
		<A href="/?action=binotel_comment_edit&id={$aRow.generalCallID}&return={$sReturn|escape:"url"}">
		<IMG class=action_image border=0 src="/libp/mpanel/images/small/edit.png"
		hspace=3 align=absmiddle>{$oLanguage->getDMessage('Edit')}</A>
	</nobr>
	{if $aRow.record_url}<a href="{$aRow.record_url}">
		<img class=action_image border=0 hspace=3 align=absmiddle src="/image/binotel/record.png" /></a>{/if}
	</td>
{elseif $sKey=='image'}<td><img src='{$aRow.image}' align=left hspace=3 width=40></td>
{elseif $sKey=='visible'}<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
{elseif $sKey=='is_brand'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_brand}</td>
{elseif $sKey=='is_vin_brand'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_vin_brand}</td>
{elseif $sKey=='is_main'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_main}</td>
{elseif $sKey=='numbers'}<td>{foreach from=$aRow.numbers item=sPhone}{$sPhone}<br>{/foreach}</td>
{elseif $sKey=='labels'}<td>{foreach from=$aRow.labels item=aLabel}{$aLabel.name}<br>{/foreach}</td>
{elseif $sKey=='extStatus'}<td>{$aRow.extStatus.status}</td>
{else}<td>
	{if $sKey=='disposition' && ($aRow.$sKey=='ANSWER' || $aRow.$sKey=='TRANSFER')}
		{if $aRow.callType=='0'}<img src='/image/binotel/incoming-success-call.png' />
		{elseif $aRow.callType=='1'}<img src='/image/binotel/outgoing-success-call.png' />{/if}
	{elseif $sKey=='disposition' && ($aRow.$sKey=='BUSY' || $aRow.$sKey=='CHANUNAVAIL' || $aRow.$sKey=='NOANSWER' || $aRow.$sKey=='CANCEL' || $aRow.$sKey=='CONGESTION')}
		{if $aRow.callType=='0'}<img src='/image/binotel/incoming-failed-call.png' />
		{elseif $aRow.callType=='1'}<img src='/image/binotel/outgoing-failed-call.png' />{/if}
		
	{elseif $sKey=='externalNumber'}
		{if $aRow.customerData.name}<b>{$aRow.customerData.name}</b><br>{$aRow.externalNumber}
		{else}<b>{$aRow.externalNumber}</b>{/if}
	{elseif $sKey=='internalNumber'}
		<b>{if $aRow.internalAdditionalData}{$aRow.internalAdditionalData}{/if}
		{if $aRow.employeeName}{$aRow.employeeName}</b><br>{$aRow.internalNumber}{else}{$aRow.internalNumber}</b>{/if}
	{else}{$aRow.$sKey}
{/if}
</td>
{/if}
{/foreach}
