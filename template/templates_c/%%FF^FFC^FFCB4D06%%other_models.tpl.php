<?php /* Smarty version 2.6.18, created on 2020-06-22 17:57:19
         compiled from rubricator/other_models.tpl */ ?>
<?php if ($this->_tpl_vars['sOtherModels']): ?>
<div class="container list" style="">
    <h2><?php echo $this->_tpl_vars['sSelectedSubcategory']; ?>
 <?php echo $this->_tpl_vars['oLanguage']->getMessage('dla_drugih_modeley'); ?>
 <?php echo $this->_tpl_vars['sSelectedBrandTitle']; ?>
</h2>
    <ul class="at-brands-list">
    <?php $_from = $this->_tpl_vars['sOtherModels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItem']):
?>
		<li>
			<a href="<?php echo $this->_tpl_vars['aItem']['seourl']; ?>
"><?php echo $this->_tpl_vars['aItem']['brand']; ?>
 <?php echo $this->_tpl_vars['aItem']['name']; ?>
</a>
		</li>
	<?php endforeach; endif; unset($_from); ?>
    </ul>
</div>
<?php endif; ?>