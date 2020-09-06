{strip}
&cat={if $smarty.request.cat}{$smarty.request.cat}{else}{$smarty.request.data.make}{/if}
&model_group={$smarty.request.model_group}
&data[id_model]={$smarty.request.data.id_model}
&car_select[model]={$smarty.request.model_group}
&category={$smarty.request.category}
&subcategory={$smarty.request.subcategory}
{/strip}