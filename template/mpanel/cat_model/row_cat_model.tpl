{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}<td nowrap>{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction not_delete=1}</td>
{elseif $sKey=='visible'}<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
{elseif $sKey=='description'}<td>{$aRow.description}</td>
{elseif $sKey=='image'}<td>{if $aRow.image}<img width="100px" src="{$aRow.image}" />{else}NA{/if}</td>
{else}<td>{$aRow.$sKey}</td>
{/if}
{/foreach}