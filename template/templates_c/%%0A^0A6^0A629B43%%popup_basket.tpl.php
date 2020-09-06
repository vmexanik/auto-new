<?php /* Smarty version 2.6.18, created on 2019-09-20 12:11:41
         compiled from index_include/popup_basket.tpl */ ?>
<div class="at-block-popup js-popup-basket" style="display: none;">
   <div class="dark" onclick="popupClose('.js-popup-basket');"></div>
   <div class="block-popup">
       <div class="popup-head">
           <a href="javascript: void(0);" class="close" onclick="popupClose('.js-popup-basket');">&nbsp;</a>
           Корзина
       </div>

       <div class="popup-body">
           <div class="at-popup-basket" id="popup-cart-body">
               <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cart/popup.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
           </div>
       </div>
   </div>
</div>