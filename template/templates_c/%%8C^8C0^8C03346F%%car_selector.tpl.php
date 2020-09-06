<?php /* Smarty version 2.6.18, created on 2020-07-27 10:37:50
         compiled from car_select/car_selector.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'upper', 'car_select/car_selector.tpl', 35, false),)), $this); ?>
<div class="at-car-picker <?php if ($_REQUEST['action'] == '' || $_REQUEST['action'] == 'home'): ?>home-margin<?php endif; ?>">
   <div class="at-mainer">
       <h3><?php echo $this->_tpl_vars['oLanguage']->getMessage("Подбор по модели автомобиля"); ?>
</h3>
       <div class="light-text">
       <?php echo $this->_tpl_vars['oLanguage']->getText("Выбрав автомобиль вы сможете получить список запчастей подходящих к вашему автомобилю."); ?>

       </div>
		<form>
       <div class="selector">
           <div class="part" style="width: 153px;">
               <div class="at-custom-select-wrap js-select-year">
                   <div class="js-select-custom-drop">
                       <select class="js-select">
                           <option value="0"><?php echo $this->_tpl_vars['oLanguage']->getMessage('Choose year','','Выберите год'); ?>
</option>
                       </select>
                   </div>

                   <div class="select-drop">
                       <div class="select-drop-inner">
                           <table>
                          <?php $_from = $this->_tpl_vars['aCarSelectYear']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sYearGroupName'] => $this->_tpl_vars['aYearGroup']):
?>
                           <tr>
	                       <td class="year"><?php echo $this->_tpl_vars['sYearGroupName']; ?>
-е</td>
							<?php $_from = $this->_tpl_vars['aYearGroup']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['sYear']):
?>
								<td><a rel="nofollow" href="/?action=car_select<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'car_select/xajax_link.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>&year=<?php echo $this->_tpl_vars['sYear']; ?>
" onclick="send_param(this.href, 'year','<?php echo $this->_tpl_vars['sYear']; ?>
'); return false;"><?php echo $this->_tpl_vars['sYear']; ?>
</a></td>
	                        <?php endforeach; endif; unset($_from); ?>
	                       </tr>
	                      <?php endforeach; endif; unset($_from); ?>
	                       </table>
                       </div>
                   </div>
               </div>
           </div>
           <div class="part" id="car_selected_brand_selector">
               <select class="js-select" disabled>
                   <option value=""><?php if ($_REQUEST['cat']): ?><?php echo ((is_array($_tmp=$_REQUEST['cat'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
<?php else: ?><?php echo $this->_tpl_vars['oLanguage']->getMessage('select brand','','Выберите марку'); ?>
<?php endif; ?></option>
               </select>
           </div>
           <div class="part" id="car_selected_model_selector">
               <select class="js-select" disabled>
                   <option value=""><?php if ($this->_tpl_vars['model_preselected']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['model_preselected'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
<?php else: ?>Выберите модель<?php endif; ?></option>
               </select>
           </div>
           <div class="part" id="car_selected_body_selector">
               <select class="js-select" disabled>
                   <option value="">Тип кузова</option>
               </select>
           </div>
           <div class="part" id="car_selected_volume_selector">
               <select class="js-select" disabled>
                   <option value="">Объем</option>
               </select>
           </div>
           <div class="part" id="car_selected_modif_selector">
               <select class="js-select" disabled>
                   <option value="">Двигатель</option>
               </select>
           </div>
       </div>
       </form>
   </div>
</div>