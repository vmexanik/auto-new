<div class="at-crumbs">
<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
<a itemprop="url" href="/"><span itemprop="title">{$oLanguage->GetMessage('main_page')}</span></a>
</span>
{if $iNotUseCatalogStep == 1}
{else}
    {if $aNavigator}
	<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
	    <a itemprop="url" href="/pages/catalog{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}"><span itemprop="title">{$oLanguage->GetMessage(catalog)}</span></a>
	</span>
    {else}
	{$oLanguage->GetMessage(catalog)}
    {/if}
{/if}
{foreach item=aImem from=$aNavigator}
	{if $aImem.action || $aImem.url}
	    <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
		<a itemprop="url" href="{if $aImem.url}
			{if $oLanguage->getConstant('global:url_is_lower',0)}
				{$aImem.url|@lower}
			{else}
				{$aImem.url}
			{/if}
			{else}/?action={$aImem.action}{/if}"><span itemprop="title">{$aImem.name}</span></a>
	    </span>
	{else}
		{$aImem.name}
	{/if}
{/foreach}
{$sBranch}
</div><br>
