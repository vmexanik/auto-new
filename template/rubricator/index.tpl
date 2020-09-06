{*<!-- Каталог товаров -->
<div class="main-group">
    <div class="at-mainmenu-header-visible">
	<div class="visible-menu">
	
	{foreach item=aSubMenuItems from=$aMainRubric}
		    <div class="category-element">
			<div class="caption-element">
				<a class="caption-element-a" href="/rubricator/{$aSubMenuItems.url}/{$sAutoPreSelected}">{$aSubMenuItems.name}</a></div>
				{foreach item=aItem from=$aSubMenuItems.childs}
				    <a href="/rubricator/{$aItem.url}/{$sAutoPreSelected}" alt="{$aItem.name}">{$aItem.name}</a><br />
				{/foreach}
		    </div>
	{/foreach}

	</div>
    </div>
</div>
<!-- End Каталог товаров на главной -->



<div class="mi-parts-cats js-parts-cats">
	{foreach item=aSubMenuItems from=$aMainRubric}
    <div class="grid-item grid-sizer">
        <div class="element">
            <div class="name">
                <a href="/rubricator/{$aSubMenuItems.url}/{$sAutoPreSelected}">{$aSubMenuItems.name}</a>
            </div>
            {foreach item=aItem from=$aSubMenuItems.childs}
            <div class="item"><a href="/rubricator/{$aItem.url}/{$sAutoPreSelected}">{$aItem.name}</a></div>
            {/foreach}
        </div>
    </div>
    {/foreach}
</div>
*}


<ul class="at-index-cats">
{foreach item=aSubMenuItems from=$aMainRubric}
{if $aSubMenuItems.url}
<li>
	<div class="at-index-cat-thumb"
	    style="background-image: url('{$aSubMenuItems.image}')">
	    <div class="name">
	    	{if $aSubMenuItems.is_price_group}
	    		<a id="catagory_parts" href="/select/{$aSubMenuItems.url}">{$aSubMenuItems.name}</a>
	    	{else}
	    	    <a id="catagory_parts" href="/rubricator/{$aSubMenuItems.url}/{$sAutoPreSelected}">{$aSubMenuItems.name}</a>
	    	{/if}
	    </div>
	    {assign var=iCount value=0}
	    {foreach item=aItem from=$aSubMenuItems.childs}
	    {assign var=iCount value=$iCount+1}
			   <label {if $iCount>5} class="hidden"{/if} style="padding-right:100%;">
				    {if $aSubMenuItems.is_price_group}
			    		<a href="/select/{$aItem.url}">{$aItem.name}</a>
			    	{else}
			    	     <a href="/rubricator/{$aItem.url}/{$sAutoPreSelected}">{$aItem.name}</a>
			    	{/if}
			   </label>
	    {/foreach}
	    {if $aSubMenuItems.is_price_group}
	    {if $iCount}<div class="show-more"><a href="/select/{$aSubMenuItems.url}">Показать все</a></div>{/if}
	    	{else}
	    {if $iCount}<div class="show-more"><a href="/rubricator/{$aSubMenuItems.url}/{$sAutoPreSelected}">Показать все</a></div>{/if}
			{/if}
	</div>
</li>
{/if}
{/foreach}   
 </ul>