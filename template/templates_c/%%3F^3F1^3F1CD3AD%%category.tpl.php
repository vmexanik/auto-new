<?php /* Smarty version 2.6.18, created on 2020-08-13 12:20:48
         compiled from rubricator/category.tpl */ ?>
<!-- Каталог товаров -->
<?php $_from = $this->_tpl_vars['aCategory']['childs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItemCategory']):
?>
    <?php if ($this->_tpl_vars['aItemCategory']['childs']): ?>
    <div class="rubric-column">
        <div>
            <p><?php echo $this->_tpl_vars['aItemCategory']['name']; ?>
</p>
            <ul>
                <?php $_from = $this->_tpl_vars['aItemCategory']['childs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItem']):
?>
                <li>
    			     <?php if ($this->_tpl_vars['aItem']['url']): ?><a class="link" href="/rubricator/<?php echo $this->_tpl_vars['aItem']['url']; ?>
/<?php echo $this->_tpl_vars['sAutoPreSelected']; ?>
"><span><?php echo $this->_tpl_vars['aItem']['name']; ?>
</span></a> 
    			     <?php else: ?><span><?php echo $this->_tpl_vars['aItem']['name']; ?>
</span><?php endif; ?>
                </li>
                <?php endforeach; endif; unset($_from); ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
<div class="clear"></div>
<div class="seoshield_content">&nbsp;</div>

<?php echo $this->_tpl_vars['sPriceTable']; ?>