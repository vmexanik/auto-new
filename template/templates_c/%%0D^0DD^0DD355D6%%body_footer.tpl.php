<?php /* Smarty version 2.6.18, created on 2019-09-20 12:11:41
         compiled from index_include/body_footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'index_include/body_footer.tpl', 62, false),)), $this); ?>
<div class="at-fWrapper">
       <div class="wrapper-cell">
           <div class="at-footer">
               <div class="at-mainer">
                   <div class="mob-call">
                       <a href="tel:<?php echo $this->_tpl_vars['oLanguage']->GetConstant('global:project_phone'); ?>
" class="at-btn call-at-btn">
                           <span><?php echo $this->_tpl_vars['oLanguage']->GetConstant('global:project_phone'); ?>
</span>
                       </a>
                   </div>

                   <div class="footer-wrapper">
                       <div class="foot-part part-info">
                           <div class="at-footer-info">
                                                              <?php echo $this->_tpl_vars['oLanguage']->GetText('bottom_graphik'); ?>

                           </div>
                       </div>

                       <div class="foot-part part-cats">
                           <div class="at-foot-cats">
                                                              <?php echo $this->_tpl_vars['oLanguage']->GetText('bottom_links1'); ?>

                           </div>
                       </div>

                       <div class="foot-part part-soc">
                                                      <?php echo $this->_tpl_vars['oLanguage']->GetText('bottom_links2'); ?>

                       </div>

                       <div class="foot-part part-pay">
							<?php echo $this->_tpl_vars['oLanguage']->GetText('accepted_for_payment'); ?>

							<?php echo $this->_tpl_vars['oLanguage']->GetText('site_counters'); ?>

                           <div class="at-copy">
                               <?php echo $this->_tpl_vars['oLanguage']->GetMessage('copyright'); ?>
 - <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
<br/>
                               <div class="dev-logo">
                     		  	 <a rel="nofollow" title="Разработка сайта автозапчастей" href="http://www.mstarproject.com/?action=tecdoc_mysql_site">
                       	   		   <img src="/image/dev-logo.png" alt="Разработка сайта автозапчастей">
                       			 </a>
                    		  </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>