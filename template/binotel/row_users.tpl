{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}<td nowrap>
<nobr>
    <A href="/?action=binotel_users_edit&id={$aRow.id}&return={$sReturn|escape:"url"}">
    <IMG class=action_image border=0 src="/libp/mpanel/images/small/edit.png"
    	hspace=3 align=absmiddle>{$oLanguage->getMessage('Edit')}</A>
</nobr>
<nobr>
    <A href="/?action=binotel_users_delete&id={$aRow.id}&return={$sReturn|escape:"url"}" onclick="if (confirm_delete_glg());">
    <IMG border=0 class=action_image src="/libp/mpanel/images/small/del.png"
    		hspace=3 align=absmiddle>{$oLanguage->getMessage('Delete')}</A>
</nobr>
{if !$aRow.is_sync}<nobr>
    <A href="/?action=user_new_account&name={$aRow.name}&email={$aRow.email}&phone={$aRow.numbers[0]}
{if $aRow.numbers[1]}&phone2={$aRow.numbers[1]}{/if}
{if $aRow.numbers[2]}&phone3={$aRow.numbers[2]}{/if}
&id_binotel={$aRow.id}&is_binotel_sync=1">
    <IMG class=action_image border=0 src="/libp/mpanel/images/small/refresh.png"
    	hspace=3 align=absmiddle>{$oLanguage->getMessage('sync')}</A>
</nobr>
{/if}
</td>
{elseif $sKey=='image'}<td><img src='{$aRow.image}' align=left hspace=3 width=40></td>
{elseif $sKey=='visible'}<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
{elseif $sKey=='is_brand'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_brand}</td>
{elseif $sKey=='is_vin_brand'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_vin_brand}</td>
{elseif $sKey=='is_main'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_main}</td>
{elseif $sKey=='numbers'}<td>
{foreach from=$aRow.numbers item=sPhone}
<nobr>
    {$sPhone}

    <A href="/?action=binotel_make_call&internal={$aRow.assignedToEmployeeNumber}&external={$sPhone}"
    onclick="xajax_process_browse_url(this.href); return false;">
    <IMG class=action_image border=0 src="/libp/mpanel/images/small/make_call.png"
    	hspace=3 align=absmiddle></A>

    <A href="/?action=binotel_by_number&number={$sPhone}&is_post=1&return={$sReturn|escape:"url"}">
    <IMG class=action_image border=0 src="/libp/mpanel/images/small/show_calls.png"
    	hspace=3 align=absmiddle></A>
</nobr>
<br>
{/foreach}</td>
{elseif $sKey=='labels'}<td>{foreach from=$aRow.labels item=aLabel}{$aLabel.name}<br>{/foreach}</td>

{elseif $sKey=='is_sync'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_sync}</td>

{elseif $sKey=='extStatus'}<td>{$aRow.extStatus.status}</td>
{else}<td>{$aRow.$sKey}</td>
{/if}
{/foreach}