<?php /* Smarty version 2.6.18, created on 2019-09-20 12:11:41
         compiled from index_include/body_content.tpl */ ?>
<div class="at-cWrapper">
   <div class="wrapper-cell">
       <div class="js-width-sync">
       <?php if ($_REQUEST['action'] == 'home' || ! $_REQUEST['action']): ?>
           <?php if ($this->_tpl_vars['oLanguage']->getConstant('main_page:visible_action_block','0')): ?>
               <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "index_include/baner.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
           <?php endif; ?>
       <?php endif; ?>  
           
		<?php if (! $_COOKIE['id_model_detail']): ?>
			<?php echo $this->_tpl_vars['sShowCarSelect']; ?>

		<?php else: ?>
			<?php echo $this->_tpl_vars['sShowCarSelected']; ?>

		<?php endif; ?>  
           
           <div class="at-mainer">
                <?php if ($_REQUEST['action'] != '' && $_REQUEST['action'] != 'home'): ?>
                <?php if ($this->_tpl_vars['aCrumbs']): ?>
                <div class="at-crumbs">
                   <?php $_from = $this->_tpl_vars['aCrumbs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['crumb_ar'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['crumb_ar']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['aItem']):
        $this->_foreach['crumb_ar']['iteration']++;
?>
		                <a class="mob-link" href="<?php echo $this->_tpl_vars['aItem']['link']; ?>
"><?php echo $this->_tpl_vars['aItem']['name']; ?>
</a>
		                <div><a href="<?php echo $this->_tpl_vars['aItem']['link']; ?>
"><?php echo $this->_tpl_vars['aItem']['name']; ?>
</a></div>
				   <?php endforeach; endif; unset($_from); ?>
               </div>
               <?php endif; ?>
               <?php endif; ?>
               
               <?php if ($this->_tpl_vars['sIndexMessage']): ?><div class="<?php echo $this->_tpl_vars['sIndexMessageClass']; ?>
"><?php echo $this->_tpl_vars['sIndexMessage']; ?>
</div><?php endif; ?>
               <?php if ($this->_tpl_vars['template']['sPageH1']): ?><h1 <?php if ($_REQUEST['action'] == 'rubricator_category' || $_REQUEST['action'] == 'price_group'): ?>class="js-at-plist-header"<?php endif; ?>><?php echo $this->_tpl_vars['template']['sPageH1']; ?>
</h1><?php endif; ?>
               
               <?php if ($this->_tpl_vars['aAuthUser']['id'] && ! ( $this->_tpl_vars['oContent']->IsChangeableLogin($this->_tpl_vars['aAuthUser']['login']) ) && $this->_tpl_vars['oContent']->CheckDashboard($_REQUEST['action'])): ?>
                   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'user/left_panel.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>               
                   <div class="at-layer-mid">
                   <?php echo $this->_tpl_vars['sText']; ?>

                   </div>
               <?php else: ?>
                   <?php echo $this->_tpl_vars['sText']; ?>

               <?php endif; ?>

               <div class="at-seo">
                 <?php if ($_REQUEST['action'] == '' || $_REQUEST['action'] == 'home'): ?>
                    <?php echo $this->_tpl_vars['oLanguage']->GetText('home bottom text'); ?>

                 <?php else: ?>
                    <?php if ($this->_tpl_vars['template']['sDescription'] && $this->_tpl_vars['template']['sDescription'] != "&nbsp;"): ?><?php echo $this->_tpl_vars['template']['sDescription']; ?>
<?php endif; ?>
                 <?php endif; ?>
              </div>
              
           </div>
		
       </div>
   </div>
</div>