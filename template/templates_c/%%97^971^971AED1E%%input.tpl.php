<?php /* Smarty version 2.6.18, created on 2019-10-07 10:44:38
         compiled from form/input.tpl */ ?>
<?php if ($this->_tpl_vars['aInput']['checkbox']): ?><input type=checkbox name=search[amount] value='1' <?php if ($_REQUEST['search']['amount']): ?>checked<?php endif; ?> class="js-checkbox"><?php endif; ?>

<input 
type='text' 
<?php if ($this->_tpl_vars['aInput']['name']): ?>name='<?php echo $this->_tpl_vars['aInput']['name']; ?>
'<?php endif; ?> 
value='<?php echo $this->_tpl_vars['aInput']['value']; ?>
'
<?php if ($this->_tpl_vars['aInput']['style']): ?>style="<?php echo $this->_tpl_vars['aInput']['style']; ?>
"
<?php else: ?>
 style='<?php if ($this->_tpl_vars['aInput']['checkbox']): ?>max-width: 870px;<?php else: ?>max-width: 100%;<?php endif; ?>'
<?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['id']): ?>id='<?php echo $this->_tpl_vars['aInput']['id']; ?>
'<?php endif; ?> 
<?php if ($this->_tpl_vars['aInput']['onclick']): ?>onclick="<?php echo $this->_tpl_vars['aInput']['onclick']; ?>
"<?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['placeholder']): ?>placeholder="<?php echo $this->_tpl_vars['aInput']['placeholder']; ?>
"<?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['class']): ?>class="<?php echo $this->_tpl_vars['aInput']['class']; ?>
"<?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['onfocus']): ?>onfocus="<?php echo $this->_tpl_vars['aInput']['onfocus']; ?>
"<?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['autocomplete']): ?>autocomplete='<?php echo $this->_tpl_vars['aInput']['autocomplete']; ?>
'<?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['onblur']): ?>onblur="<?php echo $this->_tpl_vars['aInput']['onblur']; ?>
"<?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['maxlength']): ?>maxlength="<?php echo $this->_tpl_vars['aInput']['maxlength']; ?>
"<?php endif; ?>
<?php if ($this->_tpl_vars['aInput']['readonly']): ?> readonly <?php endif; ?> 
>