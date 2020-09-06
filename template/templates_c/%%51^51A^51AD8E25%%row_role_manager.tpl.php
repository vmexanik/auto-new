<?php /* Smarty version 2.6.18, created on 2020-04-05 19:55:47
         compiled from mpanel/role_manager/row_role_manager.tpl */ ?>
<td><?php echo $this->_tpl_vars['aRow']['id']; ?>
</td>
<td><?php echo $this->_tpl_vars['aRow']['name']; ?>
</td>
<td><?php echo $this->_tpl_vars['aRow']['description']; ?>
</td>
<td>
    <?php if (! $this->_tpl_vars['aRow']['iassigned_permissions']): ?>
		<a onclick="javascript: xajax_process_browse_url('?action=role_manager&id=<?php echo $this->_tpl_vars['aRow']['id']; ?>
' );  return false;">
		<img hspace="3" border="0" align="absmiddle" src="/libp/mpanel/images/small/del.png"> Удалить
		</a>
    <?php endif; ?>
</td>