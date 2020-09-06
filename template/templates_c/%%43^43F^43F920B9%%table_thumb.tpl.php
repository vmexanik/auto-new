<?php /* Smarty version 2.6.18, created on 2019-09-27 16:20:04
         compiled from table/table_thumb.tpl */ ?>
<h1 class="at-plist-header">&nbsp;</h1>

<div class="at-plist-tools">
    <div class="at-sort">
         <select class="js-selectbox" onchange="javascript:window.location.href=($(this).val())">
                <?php $_from = $this->_tpl_vars['aSortArray']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aSortItem']):
?>
                <option value="<?php echo $this->_tpl_vars['sGroupTableUrl']; ?>
/sort=<?php echo $this->_tpl_vars['aSortItem']['sort']; ?>
/way=<?php echo $this->_tpl_vars['aSortItem']['way']; ?>
"><?php echo $this->_tpl_vars['oLanguage']->getMessage($this->_tpl_vars['aSortItem']['name']); ?>
</option>
                <?php endforeach; endif; unset($_from); ?>
          </select>
    </div>

    <div class="at-toggler">
        <?php if ($_REQUEST['table'] != 'gallery'): ?>
          <a href='<?php echo $this->_tpl_vars['sGroupChangeTableUrl']; ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>' class="selected" data-type="thumbs"></a>
          <a href='javascript:void(0);' data-type="list"></a>
        <?php else: ?>  
          <a href='javascript:void(0);' data-type="thumbs"></a>
          <a href='<?php echo $this->_tpl_vars['sGroupChangeTableUrl']; ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>' class="selected" data-type="list"></a>
        <?php endif; ?>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<div class="clear"></div>

<div class="at-layer-left">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'table/sidebar_filter.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

<div class="at-layer-mid">
    <ul class="at-plist-thumbs">
        <?php $this->assign('iTr', '0'); ?>
		<?php unset($this->_sections['d']);
$this->_sections['d']['name'] = 'd';
$this->_sections['d']['loop'] = is_array($_loop=$this->_tpl_vars['aItem']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['d']['show'] = true;
$this->_sections['d']['max'] = $this->_sections['d']['loop'];
$this->_sections['d']['step'] = 1;
$this->_sections['d']['start'] = $this->_sections['d']['step'] > 0 ? 0 : $this->_sections['d']['loop']-1;
if ($this->_sections['d']['show']) {
    $this->_sections['d']['total'] = $this->_sections['d']['loop'];
    if ($this->_sections['d']['total'] == 0)
        $this->_sections['d']['show'] = false;
} else
    $this->_sections['d']['total'] = 0;
if ($this->_sections['d']['show']):

            for ($this->_sections['d']['index'] = $this->_sections['d']['start'], $this->_sections['d']['iteration'] = 1;
                 $this->_sections['d']['iteration'] <= $this->_sections['d']['total'];
                 $this->_sections['d']['index'] += $this->_sections['d']['step'], $this->_sections['d']['iteration']++):
$this->_sections['d']['rownum'] = $this->_sections['d']['iteration'];
$this->_sections['d']['index_prev'] = $this->_sections['d']['index'] - $this->_sections['d']['step'];
$this->_sections['d']['index_next'] = $this->_sections['d']['index'] + $this->_sections['d']['step'];
$this->_sections['d']['first']      = ($this->_sections['d']['iteration'] == 1);
$this->_sections['d']['last']       = ($this->_sections['d']['iteration'] == $this->_sections['d']['total']);
?>
		<?php $this->assign('aRow', $this->_tpl_vars['aItem'][$this->_sections['d']['index']]); ?>
		<?php $this->assign('iTr', $this->_tpl_vars['iTr']+1); ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['sDataTemplate'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endfor; endif; ?>
    </ul>
    
    <?php if (! $this->_tpl_vars['aItem']): ?>

        <?php if ($this->_tpl_vars['sNoItem']): ?>
    		<?php echo $this->_tpl_vars['oLanguage']->getMessage($this->_tpl_vars['sNoItem']); ?>

    	<?php else: ?>
    		<?php echo $this->_tpl_vars['oLanguage']->getMessage('No items found'); ?>

    	<?php endif; ?>
    	
    <?php endif; ?>

    <?php if ($this->_tpl_vars['sStepper']): ?>
		<?php echo $this->_tpl_vars['sStepper']; ?>

	<?php endif; ?>
</div>
<div class="clear"></div>
<br>