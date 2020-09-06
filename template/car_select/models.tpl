<select class="js-select js-select-model" onchange="send_param(this.options[this.selectedIndex].value,'models','{$aModel.short_name|escape}'); return false;">
<option value="">Выберите модель</option>
{foreach from=$aCarSelectModelGroup item=aCarSelectModel}
{foreach from=$aCarSelectModel item=aModel}
<option value="/?action=car_select{include file='car_select/xajax_link.tpl'}&year={$sCarSelectedYear}&car_select[brand]={$sCarSelectedBrand}&car_select[model]={$aModel.code|escape}">{$aModel.short_name}</option>
{/foreach}
{/foreach}
</select>