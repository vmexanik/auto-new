<select class="js-select js-select-volume"
                 onchange="send_param(this.options[this.selectedIndex].value,'engine','{$aVolume.volume}'); return false;">
    <option value="">{$oLanguage->GetMessage('Volume')}</option>
    {foreach from=$aCarSelectVolumeGroup item=aCarSelectVolume}
        {foreach from=$aCarSelectVolume item=aVolume}
            <option value="/?action=car_select{include file='car_select/xajax_link.tpl'}&year={$sCarSelectedYear}&car_select[brand]={$sCarSelectedBrand}&car_select[model]={$sCarSelectedModel|escape}&body={$sCarSelectedBody}&volume={$aVolume.volume}">{$aVolume.new_name}</option>
        {/foreach}
    {/foreach}
</select>