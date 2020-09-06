<?php /* Smarty version 2.6.18, created on 2019-09-20 12:11:41
         compiled from cart/popup.tpl */ ?>
<?php if ($this->_tpl_vars['sTableMessage']): ?>
	<div class="<?php if ($this->_tpl_vars['sTableMessageClass']): ?><?php echo $this->_tpl_vars['sTableMessageClass']; ?>
<?php else: ?>warning_p<?php endif; ?>">
		<?php echo $this->_tpl_vars['sTableMessage']; ?>

	</div>
<?php endif; ?>
<?php $_from = $this->_tpl_vars['aDataCart']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aRow']):
?>
<div class="at-basket-element">
   <div class="element-part brand-part">
       <a class="image-brand" href="/buy/<?php echo $this->_tpl_vars['aRow']['cat_name']; ?>
_<?php echo $this->_tpl_vars['aRow']['code']; ?>
"><?php if ($this->_tpl_vars['aRow']['image_logo']): ?><img src="<?php echo $this->_tpl_vars['aRow']['image_logo']; ?>
" alt=""><?php else: ?><?php echo $this->_tpl_vars['aRow']['brand']; ?>
<?php endif; ?></a>
   </div>

   <div class="element-part code-part">
       <a href="/buy/<?php echo $this->_tpl_vars['aRow']['cat_name']; ?>
_<?php echo $this->_tpl_vars['aRow']['code']; ?>
"><?php echo $this->_tpl_vars['aRow']['code']; ?>
</a>
   </div>

   <div class="element-part photo-part">
   <?php if ($this->_tpl_vars['aRow']['image']): ?>
       <div class="photo">
           <div class="photo-view">
               <i>
                   <img src="<?php echo $this->_tpl_vars['aRow']['image']; ?>
" alt="">
               </i>
           </div>
       </div>
   <?php else: ?>
       <div class="photo nophoto">
       </div>
   <?php endif; ?>
   </div>

   <div class="element-part name-part">
       <a href="/buy/<?php echo $this->_tpl_vars['aRow']['cat_name']; ?>
_<?php echo $this->_tpl_vars['aRow']['code']; ?>
"><?php echo $this->_tpl_vars['aRow']['name_translate']; ?>
</a>
   </div>

   <div class="element-part data-part">
       <table class="at-list-basket-table">
           <tr>
                              <td class="count-cell">
                   <div class="count">
                       <input type="text" value="<?php echo $this->_tpl_vars['aRow']['number']; ?>
" id='cart_<?php echo $this->_tpl_vars['aRow']['id']; ?>
' onKeyUp="xajax_process_browse_url('?action=cart_cart_update_number&id=<?php echo $this->_tpl_vars['aRow']['id']; ?>
&number='+this.value);">
                       <div class="unit">шт x <?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['price']); ?>
</div>
                   </div>
               </td>
                              <td class="price-cell">
                   <div class="price" id='cart_total_<?php echo $this->_tpl_vars['aRow']['id']; ?>
'><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['total']); ?>
</div>

                   <a href="/?action=cart_cart_delete&id=<?php echo $this->_tpl_vars['aRow']['id']; ?>
" onclick="xajax_process_browse_url(this.href); return false;" class="delete"></a>
               </td>
           </tr>
       </table>
   </div>
</div>
<?php endforeach; endif; unset($_from); ?>

<div class="at-basket-element total">
   <table class="at-list-basket-table">
       <tr>
           <td class="mob-hide">
               <a href="/pages/additional_delivery" class="at-link-dashed">Условия доставки и гарантии</a>
           </td>
           <td class="total-caption">
               Итого:
           </td>
                      <td class="price-cell">
               <div class="price-total" id="cart_subtotal"><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aSubtotalCart']['dSubtotal']); ?>
</div>
           </td>
       </tr>
   </table>

   <div class="mob-settings-basket">
       <a href="/pages/additional_delivery" class="at-link-dashed">Условия доставки и гарантии</a>
   </div>
</div>
 
<?php if ($this->_tpl_vars['aRow']['number']): ?>
<div class="basket-buttons">
   <a href="/?action=cart_cart_clear" onclick="if (confirm('<?php echo $this->_tpl_vars['oLanguage']->getMessage("Are you sure you want to clar cart?"); ?>
')) xajax_process_browse_url(this.href); return false;" class="at-btn clear-btn">Очистить корзину</a>
   	  <a href="/pages/cart_onepage_order" class="at-btn makorder">Оформить заказ</a>
   <div class="clear"></div>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'cart/order_by_phone.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>