<select class="js-select js-select-modification" onchange="send_param(this.options[this.selectedIndex].value,'modif','{$aModif.name}'); return false;">
<option value="">Двигатель</option>
{foreach from=$aCarSelectModifGroup key=sKey item=aCarSelectModif}
	{if $aCarSelectModifGroup|@count >1 }
    	<optgroup label="{$sKey} выпуск {$aCarSelectModif.0.start_end} гг.">
    {/if}
		{foreach from=$aCarSelectModif item=aModif}
			<option value="/?action=car_select{include file='car_select/xajax_link.tpl'}&year={$sCarSelectedYear}&car_select[brand]={$sCarSelectedBrand}&car_select[model]={$sCarSelectedModel}&body={$sCarSelectedBody}&volume={$sCarSelectedVolume}&modification={$aModif.id}">{$aModif.name}</option>
		{/foreach}
	{if $aCarSelectModifGroup|@count >1 }
	   	</optgroup>
	{/if}

{/foreach}
</select>