{if count($aChildNavigator)}
<div class="legend-brand">
{$oLanguage->getMessage("Subcategories in this category")}:&nbsp;
{foreach item=aItem from=$aChildNavigator name=childnavi}
{if $aItem.name}<a href="
{if $oLanguage->getConstant('global:url_is_lower',01)}
	{$aItem.url|@lower}
{else}
	{$aItem.url}
{/if}
">{$aItem.name}</a> {if $smarty.foreach.childnavi.last}{else}<span>|</span>{/if} {/if}
{/foreach}
</div>

{/if}

<br>
{$sDescription}
<br>