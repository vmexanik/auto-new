<h2>{$oLanguage->getMessage('Select groups')}</h2>

<ul class="at-index-cats">
{foreach item=aItem from=$aMainGroups}
{if $aItem.is_main}
    <li>
	<div class="at-index-cat-thumb"
	    style="background-image: url('{$aItem.image}')">
	    <div class="name">{$aItem.name}</div>
	    {foreach item=aItemsChild from=$aItem.childs}
						    {if $oLanguage->getConstant('global:url_is_lower',0)}
							    <a href="/select/{$aItemsChild.code_name|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItemsChild.name}</a><br/>
						    {else}
							    <a href="/select/{$aItemsChild.code_name}/">{$aItemsChild.name}</a><br/>
						    {/if}
					    {/foreach}
	    <div class="show-more"><a href="/select/{if $oLanguage->getConstant('global:url_is_lower',0)}{$aItem.code_name|@lower}{else}{$aItem.url}{/if}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">Показать все</a></div>
	</div>
    </li>
{elseif $aItem.is_mainpage}
    <li>
	<div class="at-index-cat-thumb"
	    style="background-image: url('{$aItem.image}')">
	    <div class="name">{$aItem.name}</div>
	    {foreach item=aItemsChild from=$aItem.childs}
						    {if $oLanguage->getConstant('global:url_is_lower',0)}
							    <a href="/rubricator/{$aItemsChild.url|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aItemsChild.name}</a><br/>
						    {else}
							    <a href="/rubricator/{$aItemsChild.url}/">{$aItemsChild.name}</a><br/>
						    {/if}
					    {/foreach}
	    <div class="show-more"><a href="/rubricator/{if $oLanguage->getConstant('global:url_is_lower',0)}{$aItem.url|@lower}{else}{$aItem.url}{/if}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">Показать все</a></div>
	</div>
    </li>
{/if}
{/foreach}
</ul>