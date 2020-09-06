<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:03
         compiled from form/date.tpl */ ?>
<?php if ($this->_tpl_vars['aInput']['checkbox']): ?><input type=checkbox name=search[date] value=1 <?php if ($_REQUEST['search']['date']): ?>checked<?php endif; ?> class="js-checkbox"><?php endif; ?>

<input 
<?php if ($this->_tpl_vars['aInput']['id']): ?>id='<?php echo $this->_tpl_vars['aInput']['id']; ?>
'<?php endif; ?>  
name='<?php echo $this->_tpl_vars['aInput']['name']; ?>
' 
type='text' 
style='width:100% !important; <?php if ($this->_tpl_vars['aInput']['checkbox']): ?>max-width: 122px;<?php else: ?>max-width: 145px;<?php endif; ?>'
<?php if ($this->_tpl_vars['aInput']['readonly']): ?>readonly<?php endif; ?> 
value='<?php echo $this->_tpl_vars['aInput']['value']; ?>
'
<?php if ($this->_tpl_vars['aInput']['onclick']): ?>onclick="<?php echo $this->_tpl_vars['aInput']['onclick']; ?>
"<?php endif; ?>
>