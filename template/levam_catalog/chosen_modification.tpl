<div class="at-selected-car">
    <div class="at-mainer">
        <div class="selected-car-inner">
            <div class="selected-car-part first">
                <div class="caption">Для авто</div>
                {*<a href="{if $smarty.request.category}/rubricator/{$smarty.request.category}/?clear_auto=1{else}/pages/catalog/?clear_auto=1{/if}" class="at-link-dashed">Удалить авто</a>*}
                {*<a href="/pages/own_auto_add/?from_car_select=1">Добавить авто в гараж</a>*}
				{*if $aAuthUser.type_=='manager'}<br><br><a class="td_edit_tree" href="/pages/extension_td_tree?data[id_model_detail]={$aModelDetailChosen.id_model_detail}">{$oLanguage->getMessage('edit snap')}</a>{/if*}
            </div>

            <div class="selected-car-part third">
            		{assign var="make" value="Марка"}
            		<b>{$aParts.model_info.$make} {$aParts.client.family} {$aParts.client.model}</b>
            		<br>
                    {foreach key=name item=value from=$aParts.model_info}
                    	{if $name!='Марка'}
                    		{$name}: {$value}<br>
                    	{/if}
                    {/foreach}
                    {*Период выпуска: {$aModelDetailChosen.month_start}.{$aModelDetailChosen.year_start} - {$aModelDetailChosen.month_end}.{$aModelDetailChosen.year_end}<br>
                    {if $aModelDetailChosen.Engines}Двигатели: {$aModelDetailChosen.Engines}<br>{/if}
                    Мощность двигателя: {$aModelDetailChosen.hp_from} л.с. / {$aModelDetailChosen.kw_from} кВт<br>
                    Объем двигателя: {$aModelDetailChosen.ccm} см3 {$aModelDetailChosen.Fuel}<br>
                    {if $aModelDetailChosen.cylinder}Цилиндров: {$aModelDetailChosen.cylinder}<br>{/if}
                    Кузов: {$aModelDetailChosen.body}/{$aModelDetailChosen.Drive}*}
            </div>

            <div class="selected-car-part forth">
                <a href="javascript:;" class="image">
                    {if $aParts.model_image}<img src="{$aParts.model_image}" alt="">
                    {else}<img src="/image/media/no-photo.png" alt="">{/if}
                </a>
            </div>
        </div>
    </div>
</div>