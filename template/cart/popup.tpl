{if $sTableMessage}
	<div class="{if $sTableMessageClass}{$sTableMessageClass}{else}warning_p{/if}">
		{$sTableMessage}
	</div>
{/if}
{foreach from=$aDataCart item=aRow}
<div class="at-basket-element">
   <div class="element-part brand-part">
       <a class="image-brand" href="/buy/{$aRow.cat_name}_{$aRow.code}">{if $aRow.image_logo}<img src="{$aRow.image_logo}" alt="">{else}{$aRow.brand}{/if}</a>
   </div>

   <div class="element-part code-part">
       <a href="/buy/{$aRow.cat_name}_{$aRow.code}">{$aRow.code}</a>
   </div>

   <div class="element-part photo-part">
   {if $aRow.image}
       <div class="photo">
           <div class="photo-view">
               <i>
                   <img src="{$aRow.image}" alt="">
               </i>
           </div>
       </div>
   {else}
       <div class="photo nophoto">
       </div>
   {/if}
   </div>

   <div class="element-part name-part">
       <a href="/buy/{$aRow.cat_name}_{$aRow.code}">{$aRow.name_translate}</a>
   </div>

   <div class="element-part data-part">
       <table class="at-list-basket-table">
           <tr>
               {*<td class="days-cell">
                   <a class="at-link-dashed days-link" href="#">
                       0 дн.
                       <span class="tip">Дней на доставку</span>
                   </a>
               </td>*}
               <td class="count-cell">
                   <div class="count">
                       <input type="text" value="{$aRow.number}" id='cart_{$aRow.id}' onKeyUp="xajax_process_browse_url('?action=cart_cart_update_number&id={$aRow.id}&number='+this.value);">
                       <div class="unit">шт x {$oCurrency->PrintPrice($aRow.price)}</div>
                   </div>
               </td>
               {*<td class="weight-cell mob-hide">
                   0,00кг
               </td>*}
               <td class="price-cell">
                   <div class="price" id='cart_total_{$aRow.id}'>{$oCurrency->PrintPrice($aRow.total)}</div>

                   <a href="/?action=cart_cart_delete&id={$aRow.id}" onclick="xajax_process_browse_url(this.href); return false;" class="delete"></a>
               </td>
           </tr>
       </table>
   </div>
</div>
{/foreach}

<div class="at-basket-element total">
   <table class="at-list-basket-table">
       <tr>
           <td class="mob-hide">
               <a href="/pages/additional_delivery" class="at-link-dashed">Условия доставки и гарантии</a>
           </td>
           <td class="total-caption">
               Итого:
           </td>
           {*<td class="weight-cell">
               0,00кг
           </td>*}
           <td class="price-cell">
               <div class="price-total" id="cart_subtotal">{$oCurrency->PrintPrice($aSubtotalCart.dSubtotal)}</div>
           </td>
       </tr>
   </table>

   <div class="mob-settings-basket">
       <a href="/pages/additional_delivery" class="at-link-dashed">Условия доставки и гарантии</a>
   </div>
</div>
 
{if $aRow.number}
<div class="basket-buttons">
   <a href="/?action=cart_cart_clear" onclick="if (confirm('{$oLanguage->getMessage("Are you sure you want to clar cart?")}')) xajax_process_browse_url(this.href); return false;" class="at-btn clear-btn">Очистить корзину</a>
   	  <a href="/pages/cart_onepage_order" class="at-btn makorder">Оформить заказ</a>
   <div class="clear"></div>
</div>
{include file='cart/order_by_phone.tpl'}
{/if}