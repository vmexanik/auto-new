<div class="at-product-name">
    {*<h1>{if $aRowPrice.name_translate}{$aRowPrice.name_translate}{else}{$aPartInfo.name}{/if}</h1>
    <div class="brand">{$aPartInfo.brand} - {$aPartInfo.code}</div>*}
    {if $aAuthUser.type_=='manager'}
		<a href="/?action=catalog_manager_edit_name&data[zzz_code]={$aRowPrice.id}&data[art_id]={$aPartInfo.art_id}&data[item_code]={if $aRowPrice.item_code}{$aRowPrice.item_code}{else}{$aPartInfo.item_code}{/if}&return={$sReturn|escape:"url"}"><img src="/image/edit.png" title="edit"></a>
	{/if}
</div>

<div class="at-product-view">
    <div class="at-product-top">
        <div class="part image-part js-image-banner">
        {if $aGraphic}
            {foreach key=key item=item from=$aGraphic}
            <div class="image">
                <a href="{$item.img_path}" class="colorbox">
                    <img src="{$item.img_path}" alt="{if $aRowPrice.price_group_name}{$aRowPrice.price_group_name} {/if}{if $aRowPrice.name_translate}{$aRowPrice.name_translate} {else}{$aPartInfo.name} {/if}{if $aRowPrice.brand}{$aRowPrice.brand} {/if}{$oLanguage->GetMessage('art.')} {$aRowPrice.code}" 
		              title="{if $aRowPrice.price_group_name}{$aRowPrice.price_group_name} {/if}{if $aRowPrice.name_translate}{$aRowPrice.name_translate} {else}{$aPartInfo.name} {/if}{if $aRowPrice.brand}{$aRowPrice.brand} {/if}{$oLanguage->GetMessage('art.')} {$aRowPrice.code}">
                </a>
            </div>
            {/foreach}

            {foreach key=key item=item from=$aPdf}
    			<a target="_blank" href="{$item.img_path}"><img src="/image/design/pdf.png" title="{$oLanguage->GetMessage('aditional pdf info ')}"></a> &nbsp;
    		{/foreach}
        {else}    
        <div class="image">
                <a href="/image/media/no-photo-thumbs.png" class="colorbox">
                    <img src="/image/media/no-photo-thumbs.png" alt="{if $aRowPrice.price_group_name}{$aRowPrice.price_group_name} {/if}{if $aRowPrice.name_translate}{$aRowPrice.name_translate} {else}{$aPartInfo.name} {/if}{if $aRowPrice.brand}{$aRowPrice.brand} {/if}{$oLanguage->GetMessage('art.')} {$aRowPrice.code}" 
		              title="{if $aRowPrice.price_group_name}{$aRowPrice.price_group_name} {/if}{if $aRowPrice.name_translate}{$aRowPrice.name_translate} {else}{$aPartInfo.name} {/if}{if $aRowPrice.brand}{$aRowPrice.brand} {/if}{$oLanguage->GetMessage('art.')} {$aRowPrice.code}">
                </a>
            </div>
        {/if}
		</div>
        
        {literal}
        <script type="text/javascript">
        $('.js-image-banner').slick({
      	  slidesToShow: 1,
    	  slidesToScroll: 1,
    	  arrows: false,
    	  dots: true,
    	  fade: true,
    	});
        $(".colorbox").colorbox({
            rel:'colorbox',
            maxWidth:'80%'
        });
    	</script>
        {/literal}
        
        <div class="part data-part">
            {*<div class="at-product-price-select">
                <table>
                    <tr class="active">
                        <td><input id="c1" name="choosePrice" type="radio" class="js-radio" checked></td>
                        <td><label for="c1">Доставка 1 день</label></td>
                        <td>250 грн</td>
                    </tr>
                </table>
            </div>*}

            <div class="at-product-count">
                <span>Доступно {$aRowPrice.stock} шт.</span>

                <div class="at-count">
                    <input type="text" value="1" name='n[{$aRowPrice.code_provider}]' id='number_{if $aRowPrice.item_code}{$aRowPrice.item_code}{else}{$aPartInfo.item_code}{/if}_{$aRowPrice.id_provider}'> шт
                    <input type='hidden' name='r[{$aRowPrice.code_provider}]' id='reference_{if $aRowPrice.item_code}{$aRowPrice.item_code}{else}{$aPartInfo.item_code}{/if}_{$aRowPrice.id_provider}' value=''>
                </div>
                <div class="clear"></div>
            </div>

            <div class="at-product-buyblock">
                <div>Ваша цена:</div>
                    <span class="price">{$oCurrency->GetPriceWithoutSymbol($aRowPrice.price)}</span>
                	<span class="cur">{$oCurrency->PrintSymbol('')}</span>

                <div class="buy-at-btn" id='add_link_{if $aRowPrice.item_code}{$aRowPrice.item_code}{else}{$aPartInfo.item_code}{/if}_{$aRowPrice.id_provider}_2'>
{capture name=add_link_href}./{strip}
	?action=cart_add_cart_item&id_provider={$aRowPrice.id_provider}&item_code={if $aRowPrice.item_code}{$aRowPrice.item_code}{else}{$aPartInfo.item_code}{/if}
	{if $smarty.get.manager_login}
		&manager_login={$smarty.get.manager_login}
	{/if}
	{if $smarty.request.data.id_part}
		&id_part={$smarty.request.data.id_part}
	{/if}
{/strip}{/capture}

<a href="javascript:;"
onclick="{strip}
xajax_process_browse_url('{$smarty.capture.add_link_href}&xajax_request=1&hilight_code={$aRowPrice.code}
&link_id=add_link_{if $aRowPrice.item_code}{$aRowPrice.item_code}{else}{$aPartInfo.item_code}{/if}_{$aRowPrice.id_provider}_2
&number='+document.getElementById('number_{if $aRowPrice.item_code}{$aRowPrice.item_code}{else}{$aPartInfo.item_code}{/if}_{$aRowPrice.id_provider}').value+'
&reference='+document.getElementById('reference_{if $aRowPrice.item_code}{$aRowPrice.item_code}{else}{$aPartInfo.item_code}{/if}_{$aRowPrice.id_provider}').value);
oCart.AnimateAdd(this);
return false;{/strip}"

	class="at-btn">Купить</a>
                </div>
            </div>

            <div class="clear"></div>
            <div class="tablet-link">
				<a href="/pages/payment">Оплата</a>
				<br>
                <a href="/pages/additional_delivery">Условия доставки и гарантии</a>
            </div>
        </div>
        
        <div class="part payment-part">
            <div class="at-product-info">
                {$oLanguage->GetText('buy:delivery_info')}
				{$oLanguage->GetText('buy:payment_info')}
				{$oLanguage->GetText('buy:warranty_info')}
			</div>
        </div>
    </div>

    <div class="at-product-description">
        <div class="part-left">
            <div class="caption">
            {if $aAuthUser.type_=='manager'}
        		<a href="/?action=catalog_manager_edit_pic&data[art_id]={$aPartInfo.art_id}&data[item_code]={if $aRowPrice.item_code}{$aRowPrice.item_code}{else}{$aPartInfo.item_code}{/if}&return={$sReturn|escape:"url"}"><img src="/image/edit.png" title="edit"></a>
    		{/if}
            Характеристики</div>
            {$sTableCriteria}

            {if $aRowPrice.information}
            <div class="caption">Описание</div>

            <div class="text">
                {$aRowPrice.information}
            </div>
            {/if}
        </div>

        <div class="part-right">
            <div class="caption">Оригинальные номера</div>

            {$sTableOriginal}
            {if $aAuthUser.type_=='manager'}
			<a href="/?action=catalog_cross_add&item_code={if $aRowPrice.item_code}{$aRowPrice.item_code}{else}{$aPartInfo.item_code}{/if}&return={$sReturn|escape:"url"}"><img src="/image/edit.png" title="edit"></a>
			{/if}

            <div class="links">
                {if $bEnableAdvanceOriginal}<a href="{$sAdvanceOriginaLink|@lower}">{$oLanguage->GetMessage('all original >>')}</a> <br />{/if}
                <a href="/model_for/{$aPartInfo.cat_name|@lower}_{$aPartInfo.code|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$oLanguage->getMessage("Use in auto")}</a>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>

{$sTablePrice}