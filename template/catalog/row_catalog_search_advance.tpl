{if $aRow.separator}<div class="separator" colspan="10"><b>{$aRow.separator_header}</b></div>
{else}
<div class="at-list-element">
    <div class="element-part brand-part">
        <a class="image-brand" href="javascript:void(0);" rel="nofollow" onclick="xajax_process_browse_url('/?action=catalog_view_brand&amp;pref={$aRow.pref}');$('#popup_id').show();return false;">
            {if $aRow.image_logo}<img src="{$aRow.image_logo}" alt="">{else}{$aRow.brand}{/if}
        </a>
    </div>

    <div class="element-part code-part">
        <a href="/buy/{$oContent->Translit($aRow.cat_name)|@lower}_{$aRow.code|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aRow.code}</a>
    </div>

    <div class="element-part photo-part">
    {*if $aRow.image}
        <div class="photo">
            <div class="photo-view">
                <i>
                    <img src="{$aRow.image}" alt="{if $aRow.price_group_name}{$aRow.price_group_name} {/if}{if $aRow.name_translate}{$aRow.name_translate} {/if}{if $aRow.brand}{$aRowPrice.brand} {/if}{$oLanguage->GetMessage('art.')} {$aRow.code}" title="{if $aRow.price_group_name}{$aRow.price_group_name} {/if}{if $aRow.name_translate}{$aRow.name_translate} {/if}{if $aRow.brand}{$aRowPrice.brand} {/if}{$oLanguage->GetMessage('art.')} {$aRow.code}">
                </i>
            </div>
        </div>
    {else}
        <div class="photo nophoto"></div>
    {/if*}
    </div>

    <div class="element-part name-part">
        <a href="/buy/{$oContent->Translit($aRow.cat_name)|@lower}_{$aRow.code|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$aRow.name_translate}</a>
    </div>

    <div class="element-part data-part" {if $aAuthUser.type_=='manager'}style="width: 350px"{/if}>
        <table class="at-list-data-table">
            <tr>
                {if $aAuthUser.type_=='manager'}
                <td style="width: 30px;">
                    <div class="element-part photo-part">
                        <div class="photo" style="background-position: -149px -423px;">
                            <div class="photo-view" style="height: 50px;">
                                {$aRow.provider}
                                <br><span style="color:red;">{$aRow.zzz_code}</span>
                                <br>margin_id: {$aRow.margin_id}</span>
                            </div>
                        </div>
                    </div>
                </td>
                {/if}
                
                <td class="amount-cell">
                    <a href="javascript:void(0);" class="at-link-dashed amount-link">
                        {if $aRow.stock}{$aRow.stock}{else}-{/if} шт
                        <span class="tip">Кол-во на складе</span>
                    </a>
                </td>
                <td class="days-cell">
                    <a class="at-link-dashed days-link" href="javascript:void(0);">
                        {if $aRow.term}{$aRow.term}{else}-{/if} дн.
                        <span class="tip">Дней на доставку</span>
                    </a>
                </td>
                <td>
                    <div class="price">
                        {if $aRow.price>0}{if $aRow.user_view}<span style="color:green">{/if}{$oCurrency->PrintPrice($aRow.price)}{if $aRow.user_view}</span>{/if}{else}---{/if}
                        {if $aAuthUser.type_=='manager'}<br><span style="color:grey;">{$oCurrency->PrintSymbol($aRow.price_original,$aRow.id_currency)}{/if}
                    </div>
                </td>
                <td class="count-cell">
                    <div class="count">
                        <input type="text" value="1" name='n[{$aRow.code_provider}]' id='number_{$aRow.item_code}_{$aRow.id_provider}'>
                        <input type='hidden' name='r[{$aRow.code_provider}]' id='reference_{$aRow.item_code}_{$aRow.id_provider}' value=''>
                        <div class="unit">шт</div>
                    </div>
                </td>
                <td class="btn-cell">
                    <div class="buy" id='add_link_{$aRow.item_code}_{$aRow.id_provider}'>
                        {include file="catalog/link_add_cart.tpl"}
                    </div>
                </td>
            </tr>
            {if $aRow.childs}
            {foreach from=$aRow.childs item=aItem}
            <tr>
                {if $aAuthUser.type_=='manager'}
                <td style="width: 30px;">
                    <div class="element-part photo-part">
                        <div class="photo" style="background-position: -149px -423px;">
                            <div class="photo-view" style="height: 50px;">
                                {$aItem.provider}
                                <br><span style="color:red;">{$aItem.zzz_code}</span>
            	                <br>margin_id: {$aItem.margin_id}
        	                </div>
    	                </div>
	                </div>
                </td>
                {/if}
                
                <td class="amount-cell">
                    <a href="javascript:void(0);" class="at-link-dashed amount-link">
                        {if $aItem.stock}{$aItem.stock}{else}-{/if} шт
                        <span class="tip">Кол-во на складе</span>
                    </a>
                </td>
                <td class="days-cell">
                    <a class="at-link-dashed days-link" href="javascript:void(0);">
                        {if $aItem.term_day}{$aItem.term_day}{else}-{/if} дн.
                        <span class="tip">Дней на доставку</span>
                    </a>
                </td>
                <td>
                    <div class="price">
                        {if $aItem.price>0}{if $aItem.user_view}<span style="color:green">{/if}{$oCurrency->PrintPrice($aItem.price)}{if $aItem.user_view}</span>{/if}{else}---{/if}
                        {if $aAuthUser.type_=='manager'}<br><span style="color:grey;">{$oCurrency->PrintSymbol($aItem.price_original,$aItem.id_currency)}{/if}
                    </div>
                </td>
                <td class="count-cell">
                    <div class="count">
                        <input type="text" value="1" name='n[{$aItem.code_provider}]' id='number_{$aItem.item_code}_{$aItem.id_provider}'>
                        <input type='hidden' name='r[{$aItem.code_provider}]' id='reference_{$aItem.item_code}_{$aItem.id_provider}' value=''>
                        <div class="unit">шт</div>
                    </div>
                </td>
                <td class="btn-cell">
                    <div class="buy" id='add_link_{$aItem.item_code}_{$aItem.id_provider}'>
                        {include file="catalog/link_add_cart.tpl" aRow=$aItem}
                    </div>
                </td>
            </tr>
            {/foreach}
            {/if}
        </table>
    </div>
</div>
{/if}