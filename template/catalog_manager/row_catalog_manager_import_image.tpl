{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}<td>{*include file='addon/table/row_action.tpl' sBaseAction=price_grp*}</td>
{elseif $sKey=='provider'}<td>{$aUserProvider[$aRow.id_user_provider]}</td>
{else}<td>{$aRow.$sKey}</td>
{/if}
{/foreach}