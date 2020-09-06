<!-- Каталог товаров -->
{foreach item=aItemCategory from=$aCategory.childs}
    {if $aItemCategory.childs}
    <div class="rubric-column">
        <div>
            <p>{$aItemCategory.name}</p>
            <ul>
                {foreach item=aItem from=$aItemCategory.childs}
                <li>
    			     {if $aItem.url}<a class="link" href="/rubricator/{$aItem.url}/{$sAutoPreSelected}"><span>{$aItem.name}</span></a> 
    			     {else}<span>{$aItem.name}</span>{/if}
                </li>
                {/foreach}
            </ul>
        </div>
    </div>
    {/if}
{/foreach}
<div class="clear"></div>
<div class="seoshield_content">&nbsp;</div>

{$sPriceTable}