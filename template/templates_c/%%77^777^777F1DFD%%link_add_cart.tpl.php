<?php /* Smarty version 2.6.18, created on 2019-09-27 16:20:04
         compiled from catalog/link_add_cart.tpl */ ?>
<?php ob_start(); ?>./<?php echo '?action=cart_add_cart_item&id_provider='; ?><?php echo $this->_tpl_vars['aRow']['id_provider']; ?><?php echo '&item_code='; ?><?php echo $this->_tpl_vars['aRow']['item_code']; ?><?php echo ''; ?><?php if ($_GET['manager_login']): ?><?php echo '&manager_login='; ?><?php echo $_GET['manager_login']; ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?><?php if ($_REQUEST['data']['id_part']): ?><?php echo '&id_part='; ?><?php echo $_REQUEST['data']['id_part']; ?><?php echo ''; ?><?php endif; ?><?php echo ''; ?>
<?php $this->_smarty_vars['capture']['add_link_href'] = ob_get_contents(); ob_end_clean(); ?>

<a href="javascript:;"
onclick="<?php echo 'xajax_process_browse_url(\''; ?><?php echo $this->_smarty_vars['capture']['add_link_href']; ?><?php echo '&xajax_request=1&hilight_code='; ?><?php echo $this->_tpl_vars['aRow']['code']; ?><?php echo '&link_id=add_link_'; ?><?php echo $this->_tpl_vars['aRow']['item_code']; ?><?php echo '_'; ?><?php echo $this->_tpl_vars['aRow']['id_provider']; ?><?php echo '&number=\'+document.getElementById(\'number_'; ?><?php echo $this->_tpl_vars['aRow']['item_code']; ?><?php echo '_'; ?><?php echo $this->_tpl_vars['aRow']['id_provider']; ?><?php echo '\').value+\'&reference=\'+document.getElementById(\'reference_'; ?><?php echo $this->_tpl_vars['aRow']['item_code']; ?><?php echo '_'; ?><?php echo $this->_tpl_vars['aRow']['id_provider']; ?><?php echo '\').value);oCart.AnimateAdd(this);return false;'; ?>
"

	class="at-btn"><?php if ($this->_tpl_vars['bLabel']): ?>Купить<?php else: ?><i></i><?php endif; ?></a>