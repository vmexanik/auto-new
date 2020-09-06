<div class="header">
    {$oLanguage->getMessage("My order")}

    <a href="/pages/cart_cart" class="at-link-dashed edit-order">
        <i></i>
        {$oLanguage->GetMessage('Edit order')}
    </a>
    <div class="clear"></div>
</div>

<table class="plist-table">
    {foreach item=aItem from=$aUserCart}
    <tr>
        <td class="image-cell">
            {if $aItem.image_logo}
            <i>
                <img src="{$aItem.image_logo}" alt="" style="max-width: 75px; max-height: 65px;">
            </i>
            {else}
            {$aItem.brand}
            {/if}
        </td>
        <td class="data-cell">
            <a href="/buy/{$aItem.cat_name}_{$aItem.code}">{$aItem.brand} {$aItem.code}</a>
            <div class="name">
                {$oContent->PrintPartName($aItem)}
            </div>
        </td>
        <td class="mob-ib mob-count">
            <div class="unit">{$aItem.number} шт.</div>
            <div class="multiplayer">х {$oCurrency->PrintPrice($aItem.price)}</div>
            <div class="price">{$oCurrency->PrintSymbol($aItem.number_price)}</div>
        </td>
    </tr>
    {/foreach}
</table>

<div class="footer">
    <table>
        <tr>
            <td colspan="3">
                {$oLanguage->getMessage('Subtotal')}:
            </td>
            <td>
                {$oCurrency->PrintSymbol($dSubtotal)}
            </td>
        </tr>

        <tr>
            <td colspan="3">
                {$oLanguage->getMessage('Shipment Included')}:
            </td>
            <td id='price_delivery'>{$oCurrency->PrintPrice($smarty.session.current_cart.price_delivery)}
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <strong>{$oLanguage->getMessage('Total')}:</strong>
            </td>
            <td>
                <div class="price-total" id='price_total'>{$oCurrency->PrintSymbol($dTotal)}</div>
            </td>
        </tr>
    </table>
    <div class="clear"></div>
</div>