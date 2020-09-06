<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:06
         compiled from user/left_panel.tpl */ ?>
<div class="at-layer-left">
     <div class="mob-hide">
      <div class="at-auth-menu">
          <div class="auth-menu">
              <table class="user-name">
                  <tr>
                      <td class="icon">
                          <i></i>
                      </td>
                      <td>
                          <a href="javascript:void(0);"><strong><?php echo $this->_tpl_vars['aAuthUser']['login']; ?>
</strong></a>
			    <?php if ($this->_tpl_vars['aAuthUser']['type_'] == 'customer'): ?>
			    <br><br><?php echo $this->_tpl_vars['oLanguage']->GetMessage('currency_balance'); ?>
:<span <?php if ($this->_tpl_vars['aAuthUser']['amount'] > 0): ?>style="color:green;"<?php elseif ($this->_tpl_vars['aAuthUser']['amount'] < 0): ?>style="color:red;"}<?php endif; ?>> <?php echo $this->_tpl_vars['oCurrency']->PrintSymbol($this->_tpl_vars['aAuthUser']['amount']); ?>
</span>
			    <?php endif; ?>
                      </td>
                  </tr>
              </table>
              
              <ul class="list">
                  <?php if ($this->_tpl_vars['aAuthUser']['type_'] == 'manager'): ?>
			  		 <li>
			  		 	<a href="/mpanel" target="_blank">Перeйти в MPanel</a>
			   	  	 </li>
			   	  <?php endif; ?>
                  
                  <?php $_from = $this->_tpl_vars['aAccountMenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItem']):
?>
                  <li>
					<a href="/pages/<?php if (! $this->_tpl_vars['aItem']['link']): ?><?php echo $this->_tpl_vars['aItem']['code']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aItem']['code']; ?>
<?php endif; ?>" 
					<?php if ($this->_tpl_vars['aItem']['code'] == $_REQUEST['action']): ?>class="selected"<?php endif; ?>					
					><?php echo $this->_tpl_vars['aItem']['name']; ?>
</a>
					<?php if ($this->_tpl_vars['aAuthUser']['type_'] == 'manager'): ?>
						<?php if ($this->_tpl_vars['aItem']['code'] == 'message'): ?><?php if ($this->_tpl_vars['aTemplateNumber']['message_number']): ?> <font color="red">(<?php echo $this->_tpl_vars['aTemplateNumber']['message_number']; ?>
)</font><?php endif; ?><?php endif; ?>
						<?php if ($this->_tpl_vars['aItem']['code'] == 'payment_report_manager'): ?><?php if ($this->_tpl_vars['aTemplateNumber']['payment_report_id']): ?> <font color="red">(<?php echo $this->_tpl_vars['aTemplateNumber']['payment_report_id']; ?>
)</font><?php endif; ?><?php endif; ?>
						<?php if ($this->_tpl_vars['aItem']['code'] == 'vin_request_manager'): ?><?php if ($this->_tpl_vars['iNotViewedVins']): ?> <font color="red">(<?php echo $this->_tpl_vars['iNotViewedVins']; ?>
)</font><?php endif; ?><?php endif; ?>
						<?php if ($this->_tpl_vars['aItem']['code'] == 'manager_package_list'): ?><?php if ($this->_tpl_vars['iNotViewedOrders']): ?> <font color="red">(<?php echo $this->_tpl_vars['iNotViewedOrders']; ?>
)</font><?php endif; ?><?php endif; ?>
						<?php if ($this->_tpl_vars['aItem']['code'] == 'call_me_show_manager'): ?><?php if ($this->_tpl_vars['aTemplateNumber']['resolved']): ?> <font color="red">(<?php echo $this->_tpl_vars['aTemplateNumber']['resolved']; ?>
)</font><?php endif; ?><?php endif; ?>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['aAuthUser']['type_'] == 'customer'): ?>
						<?php if ($this->_tpl_vars['aItem']['code'] == 'payment_declaration'): ?><?php if ($this->_tpl_vars['aTemplateNumber']['payment_declaration_id']): ?> <font color="red">(<?php echo $this->_tpl_vars['aTemplateNumber']['payment_declaration_id']; ?>
)</font><?php endif; ?><?php endif; ?>
						<?php if ($this->_tpl_vars['aItem']['code'] == 'message_change_current_folder'): ?><?php if ($this->_tpl_vars['aTemplateNumber']['message_number']): ?> <font color="red">(<?php echo $this->_tpl_vars['aTemplateNumber']['message_number']; ?>
)</font><?php endif; ?><?php endif; ?>
					<?php endif; ?>
					</li>
				<?php endforeach; endif; unset($_from); ?>
                  
                  <li class="logout">
                      <a href="/pages/user_logout/">Выход</a>
                  </li>
              </ul>
          </div>
      </div>

<?php if ($this->_tpl_vars['aAuthUser']['type_'] == 'customer'): ?>
      <div class="at-manager-block">
          <a href="/?action=message_compose&compose_to=<?php echo $this->_tpl_vars['aAuthUser']['manager_login']; ?>
"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Your personal manager'); ?>
</a>
      </div>
<?php endif; ?>
      
  </div>
</div>