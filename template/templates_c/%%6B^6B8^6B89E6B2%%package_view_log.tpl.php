<?php /* Smarty version 2.6.18, created on 2019-09-27 16:53:24
         compiled from manager_panel/manager_package_list/package_view_log.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 'manager_panel/manager_package_list/package_view_log.tpl', 16, false),)), $this); ?>
<div class="row">
	<table class="table" style="margin-bottom:0px;">
	    <thead>
	      <tr>
	        <th><?php echo $this->_tpl_vars['oLanguage']->getMessage('order status'); ?>
</th>
	        <th><?php echo $this->_tpl_vars['oLanguage']->getMessage('manager'); ?>
</th>
	        <th><?php echo $this->_tpl_vars['oLanguage']->getMessage('time'); ?>
</th>
	        <th><?php echo $this->_tpl_vars['oLanguage']->getMessage('Ip_up'); ?>
</th>
	      </tr>
	    </thead>
        <tbody>
        	<?php if ($this->_tpl_vars['aLog']): ?>
        		<?php $_from = $this->_tpl_vars['aLog']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['value']):
?>
        			<tr>
        				<td>        					
        					<?php $this->assign('status', ((is_array($_tmp='status_')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['value']['order_status']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['value']['order_status']))); ?>
        					<?php if ($this->_tpl_vars['value']['order_status'] == 'shipment' || $this->_tpl_vars['value']['order_status'] == 'shipment_2' || $this->_tpl_vars['value']['order_status'] == 'cover' || $this->_tpl_vars['value']['order_status'] == 'no_answer_phone'): ?>
        						<h3 style="margin:2px 0 0 0;"><span class="label label-default"><?php echo $this->_tpl_vars['oLanguage']->getMessage($this->_tpl_vars['status']); ?>
</span></h3>
        					<?php else: ?>
        						<h3 style="margin:2px 0 0 0;"><span class="label label-<?php echo $this->_tpl_vars['value']['order_status']; ?>
"><?php echo $this->_tpl_vars['oLanguage']->getMessage($this->_tpl_vars['status']); ?>
</span></h3>
        					<?php endif; ?>
        				</td>
        				<td><?php if ($this->_tpl_vars['value']['name']): ?><?php echo $this->_tpl_vars['value']['name']; ?>
<?php elseif ($this->_tpl_vars['value']['login']): ?><?php echo $this->_tpl_vars['value']['login']; ?>
<?php endif; ?></td>
        				<td><nobr><?php echo $this->_tpl_vars['oLanguage']->GetPostDateTime($this->_tpl_vars['value']['post_date']); ?>
</nobr></td>
        				<td><?php echo $this->_tpl_vars['value']['ip']; ?>
</td>
        			</tr>
        		<?php endforeach; endif; unset($_from); ?>
        	<?php else: ?>
        		<tr>
        			<td colspan=4><?php echo $this->_tpl_vars['oLanguage']->getMessage('no items found'); ?>
</td>
        		</tr>
        	<?php endif; ?>
        	<tr>
        		<td colspan=4>&nbsp;</td>
        	</tr>
        </tbody>
      </table>
      <button type="button" class="btn btn-default" style="float:right;" onclick="$('.js_manager_panel_popup').hide();">
		<?php echo $this->_tpl_vars['oLanguage']->getMessage('close'); ?>

	  </button>
</div>