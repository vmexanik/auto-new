<h2 class="at-index-brands-title">{$oLanguage->getMessage('selection of spare parts for the car')}</h2>
<div class="mi-parts-cats js-parts-cats">
    {foreach from=$aAllModels item=aBrand key=sKey}
    <div class="grid-item grid-sizer">
        <div class="element">
            <div class="name">
                <a href="{$aBrand.url}">{$oLanguage->getMessage('parts for')} {$aBrand.title}</a>
            </div>

            {foreach from=$aBrand.models item=aGroup key=iKey}
            <div class="item"><a href="{$aGroup.url}">{$aBrand.title} {$aGroup.name}</a></div>
            {/foreach}
        </div>
    </div>
    {/foreach}
</div>