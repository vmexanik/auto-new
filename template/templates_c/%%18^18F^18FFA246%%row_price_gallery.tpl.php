<?php /* Smarty version 2.6.18, created on 2020-06-22 17:42:18
         compiled from catalog/row_price_gallery.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lower', 'catalog/row_price_gallery.tpl', 5, false),)), $this); ?>
<li>
<div class="js-thumb-height">
    <div class="at-thumb-element">
        <div class="inner-wrap see">
            <a href="/buy/<?php echo smarty_modifier_lower($this->_tpl_vars['oContent']->Translit($this->_tpl_vars['aRow']['cat_name'])); ?>
_<?php echo smarty_modifier_lower($this->_tpl_vars['aRow']['code']); ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>" class="image">
                <img class="fake" src="/image/plist-thumb-mask.png" alt="<?php echo $this->_tpl_vars['aRow']['name_translate']; ?>
 <?php echo $this->_tpl_vars['aRow']['brand']; ?>
 <?php echo $this->_tpl_vars['aRow']['code']; ?>
" title="<?php echo $this->_tpl_vars['aRow']['name_translate']; ?>
 <?php echo $this->_tpl_vars['aRow']['brand']; ?>
 <?php echo $this->_tpl_vars['aRow']['code']; ?>
">
                <img class="real" src="<?php if ($this->_tpl_vars['aRow']['image']): ?><?php echo $this->_tpl_vars['aRow']['image']; ?>
<?php else: ?>/image/media/no-photo-thumbs.png<?php endif; ?>" alt="<?php echo $this->_tpl_vars['aRow']['name_translate']; ?>
 <?php echo $this->_tpl_vars['aRow']['brand']; ?>
 <?php echo $this->_tpl_vars['aRow']['code']; ?>
" title="<?php echo $this->_tpl_vars['aRow']['name_translate']; ?>
 <?php echo $this->_tpl_vars['aRow']['brand']; ?>
 <?php echo $this->_tpl_vars['aRow']['code']; ?>
">
            </a>

            <div class="name">
                <?php echo $this->_tpl_vars['aRow']['name_translate']; ?>

            </div>

            <div class="brand-name">
                <a href="/buy/<?php echo smarty_modifier_lower($this->_tpl_vars['oContent']->Translit($this->_tpl_vars['aRow']['cat_name'])); ?>
_<?php echo smarty_modifier_lower($this->_tpl_vars['aRow']['code']); ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>"><?php echo $this->_tpl_vars['aRow']['brand']; ?>
 <?php echo $this->_tpl_vars['aRow']['code']; ?>
</a>
            </div>


            <div class="options">
                <a class="at-link-dashed green" href="javascript:void(0);"><?php if ($this->_tpl_vars['aRow']['stock']): ?><?php echo $this->_tpl_vars['aRow']['stock']; ?>
<?php else: ?>-<?php endif; ?> шт</a>
                <a class="at-link-dashed grey" href="javascript:void(0);"><?php if ($this->_tpl_vars['aRow']['term']): ?><?php echo $this->_tpl_vars['aRow']['term']; ?>
<?php else: ?>-<?php endif; ?> дн.</a>
            </div>

            <div class="price">
                <span><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRow']['price']); ?>
</span>
            </div>

                    </div>

        <div class="extend">
            <input type='hidden' name='n[<?php echo $this->_tpl_vars['aRow']['code_provider']; ?>
]' id='number_<?php echo $this->_tpl_vars['aRow']['item_code']; ?>
_<?php echo $this->_tpl_vars['aRow']['id_provider']; ?>
'>
            <input type='hidden' name='r[<?php echo $this->_tpl_vars['aRow']['code_provider']; ?>
]' id='reference_<?php echo $this->_tpl_vars['aRow']['item_code']; ?>
_<?php echo $this->_tpl_vars['aRow']['id_provider']; ?>
' value=''>
            <div class="buy" id='add_link_<?php echo $this->_tpl_vars['aRow']['item_code']; ?>
_<?php echo $this->_tpl_vars['aRow']['id_provider']; ?>
'>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "catalog/link_add_cart.tpl", 'smarty_include_vars' => array('bLabel' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            </div>

            
            <?php if ($this->_tpl_vars['aRow']['childs']): ?>
            <?php $_from = $this->_tpl_vars['aRow']['childs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aRowchild']):
?>
                <div class="inner-wrap">
                    <div class="options">
                        <a class="at-link-dashed green" href="javascript:void(0);"><?php if ($this->_tpl_vars['aRowchild']['stock']): ?><?php echo $this->_tpl_vars['aRowchild']['stock']; ?>
<?php else: ?>-<?php endif; ?> шт</a>
                        <a class="at-link-dashed grey" href="javascript:void(0);"><?php if ($this->_tpl_vars['aRowchild']['term']): ?><?php echo $this->_tpl_vars['aRowchild']['term']; ?>
<?php else: ?>-<?php endif; ?> дн.</a>
                    </div>

                    <div class="price">
                        <span><?php echo $this->_tpl_vars['oCurrency']->PrintPrice($this->_tpl_vars['aRowchild']['price']); ?>
</span>
                    </div>
                </div>

                <input type='hidden' name='n[<?php echo $this->_tpl_vars['aRowchild']['code_provider']; ?>
]' id='number_<?php echo $this->_tpl_vars['aRow']['item_code']; ?>
_<?php echo $this->_tpl_vars['aRowchild']['id_provider']; ?>
'>
                <input type='hidden' name='r[<?php echo $this->_tpl_vars['aRowchild']['code_provider']; ?>
]' id='reference_<?php echo $this->_tpl_vars['aRow']['item_code']; ?>
_<?php echo $this->_tpl_vars['aRowchild']['id_provider']; ?>
' value=''>
                <div class="buy" id='add_link_<?php echo $this->_tpl_vars['aRow']['item_code']; ?>
_<?php echo $this->_tpl_vars['aRowchild']['id_provider']; ?>
'>
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "catalog/link_add_cart.tpl", 'smarty_include_vars' => array('bLabel' => true,'aRow' => $this->_tpl_vars['aRowchild'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </div>
            <?php endforeach; endif; unset($_from); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</li>