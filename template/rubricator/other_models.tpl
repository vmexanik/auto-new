{if $sOtherModels}
<div class="container list" style="">
    <h2>{$sSelectedSubcategory} {$oLanguage->getMessage('dla_drugih_modeley')} {$sSelectedBrandTitle}</h2>
    <ul class="at-brands-list">
    {foreach from=$sOtherModels item=aItem}
		<li>
			<a href="{$aItem.seourl}">{$aItem.brand} {$aItem.name}</a>
		</li>
	{/foreach}
    </ul>
</div>
{/if}