<?php /* Smarty version 2.6.18, created on 2019-09-27 16:52:36
         compiled from manager_panel/template/right_order_panel.tpl */ ?>
<div class="col-sm-4">
	<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('cartpackage #'); ?>
 #<?php echo $this->_tpl_vars['aCartPackage']['id']; ?>
 <?php echo $this->_tpl_vars['oLanguage']->getMessage('from_s'); ?>
 <?php echo $this->_tpl_vars['oLanguage']->GetPostDate($this->_tpl_vars['aCartPackage']['post_date']); ?>
</b>
	<a href="/?action=manager_panel_print_order&id=<?php echo $this->_tpl_vars['aCartPackage']['id']; ?>
&return=<?php echo $this->_tpl_vars['sReturn']; ?>
" onclick="xajax_process_browse_url(this.href); return false;"><img src="/image/fileprint.png" border="0" width="16" align="absmiddle" hspace="1" alt="<?php echo $this->_tpl_vars['oLanguage']->getMessage('print_order_article'); ?>
" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('print_order_article'); ?>
"></a>
	<br>
	<a href="/?action=manager_panel_print_order&id=<?php echo $this->_tpl_vars['aCartPackage']['id']; ?>
&no_article=1&return=<?php echo $this->_tpl_vars['sReturn']; ?>
" onclick="xajax_process_browse_url(this.href); return false;"><img src="/image/print_black.png" border="0" width="16" align="absmiddle" hspace="1" alt="<?php echo $this->_tpl_vars['oLanguage']->getMessage('print_order_no_article'); ?>
" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('print_order_no_article'); ?>
"></a>
	<div class="panel panel-default" style="margin-top:5px;">
		<div class="panel-body">
			<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('order status'); ?>
</b>
			<?php if ($this->_tpl_vars['aCartPackage']['order_status_select']): ?>
				<?php echo $this->_tpl_vars['aCartPackage']['order_status_select']; ?>

			<?php else: ?>
				<?php echo $this->_tpl_vars['oContent']->getOrderStatus($this->_tpl_vars['aCartPackage']['order_status']); ?>

			<?php endif; ?>
			<a href="/?action=manager_panel_manager_package_list_view_log&id=<?php echo $this->_tpl_vars['aCartPackage']['id']; ?>
" onclick="xajax_process_browse_url(this.href); return false;">
				<img src="/image/design/clock.png" border="0" width="16" align="absmiddle" style="margin-left:10px;" 
					hspace="1" alt="<?php echo $this->_tpl_vars['oLanguage']->getMessage('history'); ?>
" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('history'); ?>
">
			</a>
			<br><br>
			<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('manager'); ?>
:</b>
			<span id="name_manager"> 
				<?php if ($this->_tpl_vars['aCartPackage']['id_manager']): ?>
					<?php if ($this->_tpl_vars['aCartPackage']['name_manager']): ?>
						<?php echo $this->_tpl_vars['aCartPackage']['name_manager']; ?>

					<?php elseif ($this->_tpl_vars['aCartPackage']['manager_login']): ?>
						<?php echo $this->_tpl_vars['aCartPackage']['manager_login']; ?>

					<?php endif; ?>
				<?php else: ?>
					<a href="/?action=manager_panel_manager_package_list_set_package_own&id=<?php echo $this->_tpl_vars['aCartPackage']['id']; ?>
" onclick="xajax_process_browse_url(this.href); return false;">
						<?php echo $this->_tpl_vars['oLanguage']->getMessage('set_package_own'); ?>

					</a>
				<?php endif; ?>
			</span>
			<?php if ($this->_tpl_vars['aCartPackage']['is_web_order']): ?>
				<span><img src="/image/design/globe_icon.png" border=0 width=16 align=absmiddle></span>
			<?php endif; ?>
			<br><br>
			<span style="color:grey;text-transform:uppercase;font-weight:bold;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('info_client'); ?>
</span>
			<span class="glyphicon glyphicon-edit" aria-hidden="true" style="float:right;"></span>
			<br>
			<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
			<b>ID:</b><?php echo $this->_tpl_vars['aCartPackage']['id_user']; ?>
&nbsp;&nbsp;
			<span class="glyphicon glyphicon-search" aria-hidden="true" 
				title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('history_client_orders'); ?>
" style="cursor:pointer;"
				onclick="xajax_process_browse_url('/?action=manager_panel_manager_package_list&search_id_user=<?php echo $this->_tpl_vars['aCartPackage']['id_user']; ?>
'); return false;"></span>
			&nbsp;
			<a href="/?action=manager_panel_user_edit_car&id_cp=<?php echo $this->_tpl_vars['aCartPackage']['id']; ?>
&id_user=<?php echo $this->_tpl_vars['aCartPackage']['id_user']; ?>
" onclick="xajax_process_browse_url(this.href); return false;">
				<img src="/image/design/car.png" border="0" width="16" align="absmiddle" hspace="1" alt="<?php echo $this->_tpl_vars['oLanguage']->getMessage('car_client'); ?>
" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('car_client'); ?>
">
			</a>
			<div style="padding-left:10px;padding-bottom: 10px;">
				<span style="color:blue;">
				<?php if ($this->_tpl_vars['aCartPackage']['name']): ?>
					<?php echo $this->_tpl_vars['aCartPackage']['name']; ?>

				<?php else: ?>
					<?php echo $this->_tpl_vars['aCartPackage']['login']; ?>

				<?php endif; ?>
				</span>
				<?php if ($this->_tpl_vars['aCartPackage']['email']): ?>
				<br>
				<span style="color:blue;">
					<?php echo $this->_tpl_vars['aCartPackage']['email']; ?>

				</span>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['aCartPackage']['phone']): ?>
					<br>
					<span style="color:blue;">
						<?php echo $this->_tpl_vars['aCartPackage']['phone']; ?>

					</span>
					&nbsp;&nbsp;
					<span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
				<?php endif; ?>
				<br><br>
				<?php echo $this->_tpl_vars['oLanguage']->getMessage('custamount'); ?>
: 
					<span id="balance"><?php echo $this->_tpl_vars['sBalance']; ?>
</span>
				<br>
				<span style="cursor:pointer;" class="glyphicon glyphicon-usd" aria-hidden="true" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('correct balance'); ?>
"
					onclick="xajax_process_browse_url('/?action=manager_panel_user_edit_correct_balance&amp;id_cp=<?php echo $this->_tpl_vars['aCartPackage']['id']; ?>
&amp;id_user=<?php echo $this->_tpl_vars['aCartPackage']['id_user']; ?>
'); return false;">
				</span>
				&nbsp;
				<a href="/?action=manager_panel_user_edit_bill&amp;code_template=order_bill&amp;id_cp=<?php echo $this->_tpl_vars['aCartPackage']['id']; ?>
&amp;id_user=<?php echo $this->_tpl_vars['aCartPackage']['id_user']; ?>
&return=<?php echo $this->_tpl_vars['sReturn']; ?>
"
					onclick="xajax_process_browse_url(this.href); return false;">
					<img src="/image/design/edit.png" border="0" width="16" align="absmiddle" 
						hspace="1" alt="<?php echo $this->_tpl_vars['oLanguage']->getMessage('s_pko'); ?>
" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('s_pko'); ?>
"
						><?php echo $this->_tpl_vars['oLanguage']->getMessage('pko'); ?>

				</a>
				&nbsp;
				<a href="/?action=manager_panel_user_edit_bill&amp;code_template=order_bill_bv&amp;id_cp=<?php echo $this->_tpl_vars['aCartPackage']['id']; ?>
&amp;id_user=<?php echo $this->_tpl_vars['aCartPackage']['id_user']; ?>
&return=<?php echo $this->_tpl_vars['sReturn']; ?>
"
					onclick="xajax_process_browse_url(this.href); return false;">
					<img src="/image/design/edit.png" border="0" width="16" align="absmiddle" 
						hspace="1" alt="<?php echo $this->_tpl_vars['oLanguage']->getMessage('order bill bv'); ?>
" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('order bill bv'); ?>
"
						><?php echo $this->_tpl_vars['oLanguage']->getMessage('bv'); ?>

				</a>
				&nbsp;
				<a href="/?action=manager_panel_user_edit_bill&amp;code_template=order_bill_rko&amp;id_cp=<?php echo $this->_tpl_vars['aCartPackage']['id']; ?>
&amp;id_user=<?php echo $this->_tpl_vars['aCartPackage']['id_user']; ?>
&return=<?php echo $this->_tpl_vars['sReturn']; ?>
"
					onclick="xajax_process_browse_url(this.href); return false;">
					<img src="/image/design/edit.png" border="0" width="16" align="absmiddle" 
						hspace="1" alt="<?php echo $this->_tpl_vars['oLanguage']->getMessage('order bill rko'); ?>
" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('order bill rko'); ?>
"
					><?php echo $this->_tpl_vars['oLanguage']->getMessage('rko'); ?>

				</a>				
				&nbsp;&nbsp;
				<a href="/?action=manager_panel_user_send_sms&amp;id_cp=15&amp;id_user=7" onclick="xajax_process_browse_url(this.href); return false;">
					<img src="/image/design/spech.png" border="0" width="16" align="absmiddle" hspace="1" alt="<?php echo $this->_tpl_vars['oLanguage']->getMessage('send_sms'); ?>
" title="<?php echo $this->_tpl_vars['oLanguage']->getMessage('send_sms'); ?>
">
				</a>
			</div>
			<div style="padding-top: 10px; border-top: 1px solid; border-color: #ddd;">
				<span style="color:grey;text-transform:uppercase;font-weight:bold;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('info_delivery'); ?>
</span>
				<span class="glyphicon glyphicon-edit" aria-hidden="true" style="float:right;"></span>
				<br>
				<span class="glyphicon glyphicon-send" aria-hidden="true"></span>
				&nbsp;<b><?php echo $this->_tpl_vars['oLanguage']->getMessage('type_delivery'); ?>
:</b>&nbsp;<?php echo $this->_tpl_vars['aCartPackage']['delivery_type_name']; ?>

				<br><b style="padding-left:22px;"><?php echo $this->_tpl_vars['oLanguage']->getMessage('address'); ?>
:</b>&nbsp;<?php echo $this->_tpl_vars['aCartPackage']['user_contact_city']; ?>
&nbsp;<?php echo $this->_tpl_vars['aCartPackage']['user_contact_address']; ?>

			</div>
		</div>
	</div>
</div>