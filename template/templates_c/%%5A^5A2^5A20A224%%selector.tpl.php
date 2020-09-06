<?php /* Smarty version 2.6.18, created on 2020-04-05 19:55:28
         compiled from mpanel/hbparams_editor/selector.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'mpanel/hbparams_editor/selector.tpl', 5, false),)), $this); ?>
<form id="main_form" action="javascript:void(null);" onsubmit="submit_form(this)">
	<table>
		<tr>
			<td width="100%"><?php echo $this->_tpl_vars['oLanguage']->GetDMessage('Select table'); ?>
:</td>
			<td><?php echo smarty_function_html_options(array('name' => "data[table_]",'options' => $this->_tpl_vars['aTables'],'selected' => $this->_tpl_vars['sSelectedTable']), $this);?>
</td>
			<td><input type="submit" class="bttn" value="Выбрать"></td>
		</tr>
	</table>
	<input type="hidden" name="action" value="hbparams_editor">
	<input type="hidden" name="is_post" value="1">
</form>