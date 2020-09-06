<div class="at-block-popup js-popup-basket" style="display: none;">
   <div class="dark" onclick="popupClose('.js-popup-basket');"></div>
   <div class="block-popup">
       <div class="popup-head">
           <a href="javascript: void(0);" class="close" onclick="popupClose('.js-popup-basket');">&nbsp;</a>
           Корзина
       </div>

       <div class="popup-body">
           <div class="at-popup-basket" id="popup-cart-body">
               {include file="cart/popup.tpl"}
           </div>
       </div>
   </div>
</div>