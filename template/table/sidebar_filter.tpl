{if !$sTopPriceGroup}
{if $aFilter || $aPriceGroupBrand}

<div class="at-filters-selected mob-filter-head">
    <div class="mob-filter-toggle">
        <a href="javascript:void(0);" class="at-btn" onclick="atFiltersMenuOpen();">Фильтры</a>
    </div>
    
 {if $aBrandSelected || $aFilterSelected || $aPriceSelected.url}   
    <div class="caption">Вы выбрали фильтр</div>
    {if $aPriceSelected && $aPriceSelected.url}
    <a class="link" href="{$aPriceSelected.url}" class="del-filter">Цена {$aPriceSelected.min_price} - {$aPriceSelected.max_price} грн</a>
    {/if}
    {foreach from=$aBrandSelected item=aBrand}
        <a class="link" href="{$aBrand.url}">Бренд: {$aBrand.title}</a> <br/>
    {/foreach}
    {foreach from=$aFilterSelected item=aItem}
        <a class="link" href="{$aItem.url}">{$aItem.name}: {$aItem.value}</a> <br/>
    {/foreach}
    
    <div class="clear-filters">
{if $aFilterSelected || $aBrandSelected || $aPriceSelected.url}<a href="{$sUrlRemoveAll}" class="at-link-dashed ">Сбросить фильтры</a>{/if}
    </div>
    {else}
    <div class="caption mob-filter" style="margin:0; text-align:center;">Фильтры</div>
    {/if}
</div>

<div class="at-filters js-mob-filters">
    <div class="mob-filter-head">
        Фильтры
        <a href="javascript:void(0);" class="close" onclick="atFiltersMenuClose();"></a>
    </div>

    <div class="body-filters">
       {*if $aCategory.childs}
        <div class="block-filter">
            <div class="caption">Подкатегории</div>

            <div class="labels-list">
                {foreach item=aItem from=$aCategory.childs}
                <label><a href="/rubricator/{$aItem.url}/{$sAutoPreSelected}">{$aItem.name}</a></label><br>
                 {/foreach}
            </div>
        </div>
        {/if*}

      <div class="block-filter">
            <div class="caption" title="Свернуть/Показать">Цена {$oCurrency->PrintCurrencySymbol('',1)}</div>
                <div class="labels-list">

                    <div class="at-filter-slider">

                        <div id="slider"></div>
                    {literal}
                        <script type="text/javascript">
                            $(function () {
                                jQuery("#slider").slider({
                                	{/literal}
                                    min: {$aPriceForFilter.min_price|strip},
                                    max: {$aPriceForFilter.max_price|strip},
                                    values: [{if $aPriceSelected.min_price}{$aPriceSelected.min_price|strip}{else}{$aPriceForFilter.min_price|strip}{/if}, {if $aPriceSelected.max_price}{$aPriceSelected.max_price|strip}{else}{$aPriceForFilter.max_price|strip}{/if}],
                                    {literal}
                                    range: true,
                                    stop: function (event, ui) {
                                        minVal = ui.values[0];
                                        maxVal = ui.values[1];

                                        jQuery("input#minCost").val(minVal);
                                        jQuery("input#maxCost").val(maxVal);
                                    },
                                    slide: function (event, ui) {
                                        for (var i = 0; i < ui.values.length; ++i) {
                                            $("input.sliderValue[data-index=" + i + "]").val(ui.values[i]);
                                        }
                                    }
                                });

                                $("input.sliderValue").change(function () {
                                    var $this = $(this);
                                    $("#slider").slider("values", $this.data("index"), $this.val());
                                });
                            });
                        </script>
                    {/literal}
                        <div class="fields">
							                            
                          	<div class="cell">
	                            <input value="{if $aPriceSelected.min_price}{$aPriceSelected.min_price}{else}{$aPriceForFilter.min_price}{/if}" id="minCost" class="sliderValue"
	                                                     data-index="0" type="text"/>
							</div>

                            <div class="cell dash"><span></span></div>
                            
	                        <div class="cell">
								    <input value="{if $aPriceSelected.max_price}{$aPriceSelected.max_price}{else}{$aPriceForFilter.max_price}{/if}" id="maxCost" class="sliderValue"
	                                                     data-index="1" type="text"/>
                            </div>
	                        
                            <div class="cell button-cell">
                                <a class="at-btn filters-btn" href="javascript:void(0);" 
                                onclick="var link='{$aPriceSelected.url_2}'; link2=link.replace('min_price', +$('#minCost').val()); link3=link2.replace('max_price', +$('#maxCost').val());location.href=link3"></a>
                            </div>
                    	</div>
                </div>
            </div>
     </div>
    
        <div class="block-filter">
            <div class="caption" title="Свернуть/Показать">Бренд</div>

            <div class="labels-list">
            {assign var=iCount value=0}{assign var=iSum value=0}
            {foreach from=$aPriceGroupBrand key=key item=aItemBrand}{assign var=iSum value=$iSum+1}
                <label {if $key>4} class="hidden hiddened"{assign var=iCount value=$iCount+1}{/if}>
                    <input type="checkbox" class="js-checkbox" {if $aItemBrand.checked}checked="checked"{/if} onclick="document.location='{$aItemBrand.url}'">
                    <a href="{$aItemBrand.url}">{$aItemBrand.c_title} <span>({$aItemBrand.count})</span></a>
               </label><br {if $key>4} class="hidden hiddened"{/if} />
                
            {/foreach}
                {if $iCount}<a class="at-link-dashed alltypes" href="javascript:void(0);"><span id="state_show_all">Показать все</span><span id="state_hide_all" style="display:none;">Скрыть все</span></a>{/if}
            </div>
        </div>
        
        {foreach from=$aFilter item=aItem}
        <div class="block-filter">
            <div class="caption{if $aItem.is_collapsed} collapsed{/if}" title="Свернуть/Показать">{$aItem.name}</div>

            <div class="labels-list{if $aItem.is_collapsed} hide{/if}">
                {assign var=iCount value=0}
	            {foreach from=$aItem.params key=key item=aParam}
                <label {if $key>4} class="hidden hiddened"{assign var=iCount value=$iCount+1}{/if}>
                    <input type="checkbox" class="js-checkbox" {if $aParam.checked}checked="checked"{/if} onclick="document.location='{$aParam.url}'">
                    <a href="{$aParam.url}">{$aParam.name} <span>({$aParam.count})</span></a>
                </label><br {if $key>4} class="hidden hiddened"{/if} />
                
                {/foreach}
                {if $iCount}<a class="at-link-dashed alltypes" href="javascript:void(0);"><span id="state_show_all">Все типы</span><span id="state_hide_all" style="display:none;">Скрыть</span></a>{/if}
            </div>
        </div>
        {/foreach}
    </div>
</div>
{/if}
{/if}
