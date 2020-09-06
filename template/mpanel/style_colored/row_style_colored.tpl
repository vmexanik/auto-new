{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}<td nowrap>
{include file='addon/mpanel/base_row_action.tpl' sBaseAction=$sBaseAction not_delete=1}
</td>
{elseif $sKey=='image'}<td><img src='{$aRow.image}' align=left hspace=3 width=40></td>
{elseif $sKey=='image_tecdoc'}<td><img src='{if $aRow.image_tecdoc}{$sTecDocUrl}{$aRow.image_tecdoc}{/if}' align=left hspace=3 width=40></td>
{elseif $sKey=='visible'}<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
{elseif $sKey=='is_brand'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_brand}</td>
{elseif $sKey=='is_vin_brand'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_vin_brand}</td>
{elseif $sKey=='is_main'}<td>{include file='addon/mpanel/yes_no.tpl' bData=$aRow.is_main}</td>
{else}<td>{$aRow.$sKey}</td>
{/if}
{/foreach}