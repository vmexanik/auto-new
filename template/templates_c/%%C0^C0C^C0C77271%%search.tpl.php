<?php /* Smarty version 2.6.18, created on 2020-04-05 19:55:34
         compiled from mpanel/price_group/search.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'mpanel/price_group/search.tpl', 6, false),)), $this); ?>
<form id="filter_form" name="filter_form" class="form-inline" action="javascript:void(null)" onsubmit="submit_form(this)">

<div class="form-group">
				<label ><?php echo $this->_tpl_vars['oLanguage']->GetdMessage('id'); ?>
:</label>
				<input type=text name=search[id]
					value="<?php echo ((is_array($_tmp=$this->_tpl_vars['aSearch']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" maxlength=50 class="form-control btn-sm">
 </div>
 <div class="form-group">
				<label ><?php echo $this->_tpl_vars['oLanguage']->GetMessage('code'); ?>
:</label>
				<input type=text name=search[code]
					value="<?php echo ((is_array($_tmp=$this->_tpl_vars['aSearch']['code'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" maxlength=50
					class="form-control btn-sm">
 </div>
 <div class="form-group">					
				<label ><?php echo $this->_tpl_vars['oLanguage']->GetdMessage('code_name'); ?>
:</label>
				<input type=text name=search[code_name]
					value="<?php echo ((is_array($_tmp=$this->_tpl_vars['aSearch']['code_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" maxlength=50
					class="form-control btn-sm">
 </div>
 <div class="form-group">					
				<label ><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Name'); ?>
:</label>
				<input type=text name=search[name]
					value="<?php echo ((is_array($_tmp=$this->_tpl_vars['aSearch']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" maxlength=50
					class="form-control btn-sm">
 </div>
 <div class="form-group">					
				<label ><?php echo $this->_tpl_vars['oLanguage']->GetdMessage('level'); ?>
:</label>
				<input type=text name=search[level]
					value="<?php echo ((is_array($_tmp=$this->_tpl_vars['aSearch']['level'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" maxlength=50
					class="form-control btn-sm">
 </div>
 <div class="form-group">					
				<label ><?php echo $this->_tpl_vars['oLanguage']->GetdMessage('id_parent'); ?>
:</label>
				<input type=text name=search[id_parent]
					value="<?php echo ((is_array($_tmp=$this->_tpl_vars['aSearch']['id_parent'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" maxlength=50
					style='width: 90px'>
 </div>
 <div class="form-group">
					<label ><?php echo $this->_tpl_vars['oLanguage']->GetMessage('is_product_list_visible'); ?>
:</label>
					<select name="search[is_product_list_visible]" class="form-control btn-sm">
							<option value='1' <?php if ($this->_tpl_vars['aSearch']['is_product_list_visible'] == '1'): ?> selected <?php endif; ?>
							><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Yes'); ?>
</option>
							<option value='0' <?php if ($this->_tpl_vars['aSearch']['is_product_list_visible'] == '0'): ?> selected <?php endif; ?>
							><?php echo $this->_tpl_vars['oLanguage']->GetMessage('No'); ?>
</option>
							<option value='' <?php if ($this->_tpl_vars['aSearch']['is_product_list_visible'] == ''): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Ignore'); ?>
</option>
						</select>
 </div>
 <div class="form-group">					
					<label ><?php echo $this->_tpl_vars['oLanguage']->GetdMessage('is_menu'); ?>
:</label>
					<select name="search[is_menu]" class="form-control btn-sm">
							<option value='1' <?php if ($this->_tpl_vars['aSearch']['is_menu'] == '1'): ?> selected <?php endif; ?>
							><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Yes'); ?>
</option>
							<option value='0' <?php if ($this->_tpl_vars['aSearch']['is_menu'] == '0'): ?> selected <?php endif; ?>
							><?php echo $this->_tpl_vars['oLanguage']->GetMessage('No'); ?>
</option>
							<option value='' <?php if ($this->_tpl_vars['aSearch']['is_menu'] == ''): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Ignore'); ?>
</option>
						</select>
 </div>
 <div class="form-group">					
					<label ><?php echo $this->_tpl_vars['oLanguage']->GetMessage('is_main'); ?>
:</label>
					<select name="search[is_main]" class="form-control btn-sm">
							<option value='1' <?php if ($this->_tpl_vars['aSearch']['is_main'] == '1'): ?> selected <?php endif; ?>
							><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Yes'); ?>
</option>
							<option value='0' <?php if ($this->_tpl_vars['aSearch']['is_main'] == '0'): ?> selected <?php endif; ?>
							><?php echo $this->_tpl_vars['oLanguage']->GetMessage('No'); ?>
</option>
							<option value='' <?php if ($this->_tpl_vars['aSearch']['is_main'] == ''): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Ignore'); ?>
</option>
						</select>
 </div>
 <div class="form-group">					
					<label ><?php echo $this->_tpl_vars['oLanguage']->GetMessage('visible'); ?>
:</label>
					<select name="search[visible]" class="form-control btn-sm">
							<option value='1' <?php if ($this->_tpl_vars['aSearch']['visible'] == '1'): ?> selected <?php endif; ?>
							><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Yes'); ?>
</option>
							<option value='0' <?php if ($this->_tpl_vars['aSearch']['visible'] == '0'): ?> selected <?php endif; ?>
							><?php echo $this->_tpl_vars['oLanguage']->GetMessage('No'); ?>
</option>
							<option value='' <?php if ($this->_tpl_vars['aSearch']['visible'] == ''): ?> selected <?php endif; ?>><?php echo $this->_tpl_vars['oLanguage']->GetMessage('Ignore'); ?>
</option>
						</select>
 </div>
 <div class="form-group">					
					
					<label ><?php echo $this->_tpl_vars['oLanguage']->GetDMessage('language'); ?>
:</label>
				<input type=text name=search[language]
					value="<?php echo ((is_array($_tmp=$this->_tpl_vars['aSearch']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" maxlength=50
					class="form-control btn-sm">
 </div>
 <div class="form-group">

<input type=button class='btn btn-primary btn-sm' value="<?php echo $this->_tpl_vars['oLanguage']->getDMessage('Clear'); ?>
"
	onclick="xajax_process_browse_url('?<?php echo ((is_array($_tmp=$this->_tpl_vars['sSearchReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
')">
<input type=submit value='Search' class='btn btn-primary btn-sm'>

<input type=hidden name=action value=<?php echo $this->_tpl_vars['sBaseAction']; ?>
_search>
<input type=hidden name=return value="<?php echo ((is_array($_tmp=$this->_tpl_vars['sSearchReturn'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
</div>
</form>