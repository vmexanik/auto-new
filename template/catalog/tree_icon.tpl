<table>
<tr><td>
{foreach item=aItem from=$aTreeIcon} 
<a href="/?action=catalog_assemblage_view&data[id_make]={$smarty.request.data.id_make}&data[id_model]={$smarty.request.data.id_model}&data[id_model_detail]={$smarty.request.data.id_model_detail}&data[id_group_icon]={$aItem.id}&data[sort]={$smarty.request.data.sort}'>
<img src="{$aItem.image}" width="40px">
</a>
{/foreach}
</td>
</tr>
</table>