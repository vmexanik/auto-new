{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}<td nowrap>{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction}</td>
{elseif $sKey=='visible'}<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
{else}<td>{$aRow.$sKey}</td>
{/if}
{/foreach}
