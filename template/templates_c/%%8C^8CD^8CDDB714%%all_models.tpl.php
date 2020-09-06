<?php /* Smarty version 2.6.18, created on 2020-06-22 17:57:11
         compiled from rubricator/all_models.tpl */ ?>
<h2 class="at-index-brands-title"><?php echo $this->_tpl_vars['oLanguage']->getMessage('selection of spare parts for the car'); ?>
</h2>
<div class="mi-parts-cats js-parts-cats">
    <?php $_from = $this->_tpl_vars['aAllModels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sKey'] => $this->_tpl_vars['aBrand']):
?>
    <div class="grid-item grid-sizer">
        <div class="element">
            <div class="name">
                <a href="<?php echo $this->_tpl_vars['aBrand']['url']; ?>
"><?php echo $this->_tpl_vars['oLanguage']->getMessage('parts for'); ?>
 <?php echo $this->_tpl_vars['aBrand']['title']; ?>
</a>
            </div>

            <?php $_from = $this->_tpl_vars['aBrand']['models']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['iKey'] => $this->_tpl_vars['aGroup']):
?>
            <div class="item"><a href="<?php echo $this->_tpl_vars['aGroup']['url']; ?>
"><?php echo $this->_tpl_vars['aBrand']['title']; ?>
 <?php echo $this->_tpl_vars['aGroup']['name']; ?>
</a></div>
            <?php endforeach; endif; unset($_from); ?>
        </div>
    </div>
    <?php endforeach; endif; unset($_from); ?>
</div>