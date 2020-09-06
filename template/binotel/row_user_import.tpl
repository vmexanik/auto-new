{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}<td nowrap>
{if !$aRow.is_binotel_sync}
<nobr>
    <A href="/?action=binotel_user_add&id={$aRow.id}&import=1&return={$sReturn|escape:"url"}">
    <IMG class=action_image border=0 src="/libp/mpanel/images/small/refresh.png"
    	hspace=3 align=absmiddle>{$oLanguage->getMessage('sync')}</A>
</nobr>
{/if}
</td>
{elseif $sKey=='is_binotel_sync'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_binotel_sync}</td>
{else}<td>{$aRow.$sKey}</td>
{/if}
{/foreach}