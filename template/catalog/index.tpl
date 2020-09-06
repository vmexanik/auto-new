<div class="search_by_auto">
		                 
{foreach key=key item=item from=$aCatalog}
<div class="item">
    <div class="image">

        <a href="/?action=catalog_model_view&cat={$item.name}{include file=catalog/link_add.tpl}">
                                    <img src="{$item.image}" width="50" height="46" alt="{$aItem.name}" title="{$aItem.name}" />
                            </a>
    </div>
    <a href="/?action=catalog_model_view&cat={$item.name}{include file=catalog/link_add.tpl}">{$item.title}</a><br />
    {$item.description}
</div>
{/foreach}
		                        
<div class="clear">&nbsp;</div>
</div>
<br />