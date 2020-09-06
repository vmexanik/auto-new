{*<td align="center">{if $aRow.image_logo}<img src="{$aRow.image_logo}" alt="{$aRow.brand}" title="{$aRow.brand}">{/if}</td>*}
<td class="cell-name">
{$aRow.name}
{if $aRow.cri_text}<br>{$aRow.cri_text}{/if}
</td>
<td class="cell-brand">{$aRow.brand}
{if $aAuthUser.type_=='manager' && $aRow.provider_name}<br><span style="color:grey;">{$aRow.provider_name}{/if}</td>
<td class="cell-code">
{if $oLanguage->getConstant('global:url_is_lower',0)}
<a href="/buy/{$aRow.cat_name|@lower}_{$aRow.art_article_nr|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">
{else}
<a href="/buy/{$aRow.cat_name}_{$aRow.art_article_nr}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">
{/if}
{$aRow.art_article_nr}</a>
</td>
<td class="cell-image">{*
{if $aRow.img_path}<a class="thickbox" href="{$aRow.img_path}"><img src="{$aRow.img_path}" style="width:100px;"></a>{/if}<br>*}
 {if $aRow.image.img_path || $aRow.img_path}
 	<a class="thickbox" href="{if $aRow.image.img_path}{$aRow.image.img_path}{elseif $aRow.img_path}{$aRow.img_path}{else}{$aRow.image}{/if}">
	<img style="width:100px;" src="{if $aRow.image.img_path}{$aRow.image.img_path}{elseif $aRow.img_path}{$aRow.img_path}{else}{$aRow.image}{/if}"
	alt="{if $aRow.image.alt}{$aRow.image.alt}{else}{if $aRow.price_group_name}{$aRow.price_group_name} {/if}{if $aRow.name}{$aRow.name} {/if}{if $aRow.brand}{$aRow.brand} {/if}{$oLanguage->GetMessage('art.')} {$aRow.art_article_nr}{/if}" 
	title="{if $aRow.image.title}{$aRow.image.title}{else}{if $aRow.price_group_name}{$aRow.price_group_name} {/if}{if $aRow.name}{$aRow.name} {/if}{if $aRow.brand}{$aRow.brand} {/if}{$oLanguage->GetMessage('art.')} {$aRow.art_article_nr}{/if}">
	</a><br />
 {/if}
</td>
<td class="cell-price" style="white-space:nowrap;">{$oCurrency->PrintPrice($aRow.price)}<br />
{if $aAuthUser.type_=='manager'}<br><span style="color:grey;">{$oCurrency->PrintSymbol($aRow.price_original,$aRow.id_currency)}</span>{/if}

<input type='hidden' name='r[{$aRow.code_provider}]' id='reference_{$aRow.item_code}_{$aRow.id_provider}' value=''>
<input type=text name='n[{$aRow.code_provider}]' id='number_{$aRow.item_code}_{$aRow.id_provider}'
		value="{if $aRow.request_number}{$aRow.request_number}{else}1{/if}" size=4>

{assign var='bAddCartVisible' value=true}

{*if $aAuthUser.type_=='manager'}
{assign var='bAddCartVisible' value=false}
{/if*}

</td>
<td class="cell-action">
{if $bAddCartVisible}
<span id='add_link_{$aRow.item_code}_{$aRow.id_provider}'>

{include file="catalog/link_add_cart.tpl"}

{*if $aRow.is_our_store}<font color=red>{$oLanguage->GetMessage('Our store in price online')}</font>{/if*}
</span>
<br>
{/if}
</td>