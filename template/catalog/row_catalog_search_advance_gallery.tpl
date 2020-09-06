<div class="item">
    <div class="img-holder"><img src="{if $aRow.image}{$aRow.image}{else}/imgbank/Image/no_picture.gif{/if}" alt="{$aRow.name_translate}"></div>
    <span class="title">{$aRow.name_translate}</span>
    {if $aRow.cat_name}
    <span class="title">
		{if $oLanguage->getConstant('global:url_is_lower',0)}
			<a href="/buy/{$aRow.cat_name|@lower}_{$aRow.code|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}" style="text-align: center;">{$aRow.brand} {$aRow.code}</a>
		{else}
			<a href="/buy/{$aRow.cat_name}_{$aRow.code}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}" style="text-align: center;">{$aRow.brand} {$aRow.code}</a>
		{/if}
	</span>
	{/if}
	<span class="name">{$oLanguage->GetMessage('stock')}: {$aRow.stock}</span>
    <span class="name">{$oLanguage->GetMessage('term')}: {$aRow.term}</span>
    {if $aAuthUser.type_=='manager'}<span class="name" style="color:gray;">{$aRow.provider}</span>{/if}
    <span class="price">{$oCurrency->PrintPrice($aRow.price)}
    {if $aRow.price>0}
        <input type='hidden' name='r[{$aRow.code_provider}]' id='reference_{$aRow.item_code}_{$aRow.id_provider}' value=''>
        <input type=text name='n[{$aRow.code_provider}]' id='number_{$aRow.item_code}_{$aRow.id_provider}'
			value="{if $aRow.request_number}{$aRow.request_number}{else}1{/if}" size=4 style="width:30px">
		<span id='add_link_{$aRow.item_code}_{$aRow.id_provider}'>
		{include file="catalog/link_add_cart.tpl"}
		</span>
	{/if}
    </span>
    {*<a href="#" class="block-link">&nbsp;</a>*}
</div>