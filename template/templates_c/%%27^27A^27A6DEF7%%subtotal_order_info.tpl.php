<?php /* Smarty version 2.6.18, created on 2019-09-27 16:52:36
         compiled from manager_panel/template/subtotal_order_info.tpl */ ?>
	<div class="panel panel-default" style="border:none;box-shadow:none;margin-top:5px;">
		<div class="panel-body">
			<div id="table_error" style=" clear: both; padding-top: 5px;"></div>
			<?php echo $this->_tpl_vars['sOrderItems']; ?>

			<?php echo $this->_tpl_vars['sOrderItemsRefused']; ?>

			<div class="col-lg-7">
				<?php echo $this->_tpl_vars['sCommentLog']; ?>

				<div class="input-group">
			      <input id="manager_comment" type="text" class="form-control" placeholder="<?php echo $this->_tpl_vars['oLanguage']->getMessage('manager comment'); ?>
">
			      <span class="input-group-btn">
			        <button class="btn btn-info" type="button" onclick="xajax_process_browse_url('<?php echo '/?action=manager_panel_manager_package_list_set_manager_comment&id='; ?><?php echo $this->_tpl_vars['aCartPackage']['id']; ?><?php echo '&manager_comment=\'+encodeURIComponent($(\'#manager_comment\').val()));return false;'; ?>
"><?php echo $this->_tpl_vars['oLanguage']->getMessage('write'); ?>

			        </button>
			      </span>
			    </div><!-- /input-group -->
		    </div>
		    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "manager_panel/template/total_order_info_panel.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</div>
	</div>