<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:33
         compiled from cart/order_by_phone.tpl */ ?>
<div class="notice">
   <div class="caption"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('order by phone'); ?>
</div>

   <div class="text">
      <?php echo $this->_tpl_vars['oLanguage']->GetText('order by phone'); ?>

   </div>

   <table>
       <tr>
           <td><input class="phone js-masked-input fast_order_phone" type="text" placeholder="(___) ___ __ __" value="<?php if ($this->_tpl_vars['aAuthUser']['phone']): ?><?php echo $this->_tpl_vars['aAuthUser']['phone']; ?>
<?php endif; ?>" id="user_phone"></td>
           <td>
               <input class="at-btn" type="submit" value="<?php echo $this->_tpl_vars['oLanguage']->getMessage('Order by phone'); ?>
" id="fast_order_button" onclick="check_phone(); return false;" tabindex="1">
           </td>
       </tr>
   </table>
</div>