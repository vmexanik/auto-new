<?php /* Smarty version 2.6.18, created on 2020-04-05 17:44:43
         compiled from index_include/byauto.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lower', 'index_include/byauto.tpl', 13, false),)), $this); ?>
<h2 class="at-index-brands-title"><?php echo $this->_tpl_vars['oLanguage']->getMessage('Select parts by auto'); ?>
</h2>

                   <div class="at-index-brands">
                       <div class="at-toggler js-brands-lists-toggle">
                           <a class="selected" href="javascript:void(0);" data-type="thumbs"></a>
                           <a href="javascript:void(0);" data-type="list"></a>
                           <div class="clear"></div>
                       </div>

                       <div class="container thumbs">
                       <?php $_from = $this->_tpl_vars['aCatalog']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItem']):
?>
                       		<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_lower',0)): ?>
						       <a class="at-brand-thumb" href="/rubricator/c/<?php echo smarty_modifier_lower($this->_tpl_vars['oContent']->Translit($this->_tpl_vars['aItem']['c_name'])); ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>">
								<?php else: ?>
						       <a class="at-brand-thumb" href="/rubricator/c/<?php echo $this->_tpl_vars['oContent']->Translit($this->_tpl_vars['aItem']['c_name']); ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>">
								<?php endif; ?>
                               <span class="image"><i><img src="<?php echo $this->_tpl_vars['aItem']['image']; ?>
" alt="<?php echo $this->_tpl_vars['aItem']['name']; ?>
" title="<?php echo $this->_tpl_vars['aItem']['name']; ?>
"></i></span>
                               <span class="caption"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('spareparts'); ?>
</span>
                               <span class="brand"><?php echo $this->_tpl_vars['aItem']['name']; ?>
</span>
                           </a>
						<?php endforeach; endif; unset($_from); ?>
                           <div class="at-brand-thumb empty"></div>
                           <div class="at-brand-thumb empty"></div>
                           <div class="at-brand-thumb empty"></div>
                           <div class="at-brand-thumb empty"></div>
                           <div class="at-brand-thumb empty"></div>
                           <div class="at-brand-thumb empty"></div>
						<?php if ($_REQUEST['action'] != 'catalog'): ?>
                           <div class="show-more">
                               <a href="/rubricator" class="at-btn"><?php echo $this->_tpl_vars['oLanguage']->GetMessage('all brands'); ?>
</a>
                           </div>
                       <?php endif; ?>    
                       </div>

                       <div class="container list" style="display: none">
                           <ul class="at-brands-list">
                           <?php $_from = $this->_tpl_vars['aCatalog']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['aItem']):
?>
                           <?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_lower',0)): ?>
						       <li><a href="/rubricator/c/<?php echo smarty_modifier_lower($this->_tpl_vars['oContent']->Translit($this->_tpl_vars['aItem']['c_name'])); ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>">
								<?php else: ?>
						       <li><a href="/rubricator/c/<?php echo $this->_tpl_vars['oContent']->Translit($this->_tpl_vars['aItem']['c_name']); ?>
<?php if ($this->_tpl_vars['oLanguage']->getConstant('global:url_is_not_last_slash',0)): ?><?php else: ?>/<?php endif; ?>">
								<?php endif; ?>
                               <?php echo $this->_tpl_vars['oLanguage']->GetMessage('spareparts'); ?>
 <?php echo $this->_tpl_vars['aItem']['name']; ?>

                               </a></li>
                           <?php endforeach; endif; unset($_from); ?>    
                           </ul>
							<?php if ($_REQUEST['action'] != 'catalog'): ?>
                           <div class="show-more">
                               <a href="/rubricator" class="at-btn">Показать все</a>
                           </div>
                           <?php endif; ?> 
                       </div>
                   </div>