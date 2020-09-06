<select class="js-select js-select-brand" onchange="send_param(this.options[this.selectedIndex].value, 'brands','{$aBrand.title}'); return false;">
<option value="">Выберите марку</option>
{foreach from=$aCarSelectBrandGroup item=aCarSelectBrand}
{foreach from=$aCarSelectBrand item=aBrand}
<option value="/?action=car_select{include file='car_select/xajax_link.tpl'}&year={$sCarSelectedYear}&car_select[brand]={$aBrand.name}">{$aBrand.title}</option>
{/foreach}
{/foreach}
</select>