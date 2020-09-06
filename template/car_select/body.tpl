<select class="js-select js-select-body" onchange="send_param(this.options[this.selectedIndex].value, 'cuzove','{$aBody.body}'); return false;">
<option value="">Тип кузова</option>
{foreach from=$aCarSelectBodyGroup item=aCarSelectBody}
{foreach from=$aCarSelectBody item=aBody}
<option value="/?action=car_select{include file='car_select/xajax_link.tpl'}&year={$sCarSelectedYear}&car_select[brand]={$sCarSelectedBrand}&car_select[model]={$sCarSelectedModel|escape}&body={$aBody.body}">{$aBody.body}</option>
{/foreach}
{/foreach}
</select>