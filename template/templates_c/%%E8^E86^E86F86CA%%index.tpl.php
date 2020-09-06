<?php /* Smarty version 2.6.18, created on 2019-09-27 17:14:59
         compiled from addon/print_content/index.tpl */ ?>
<HTML>
<HEAD>
<title>Print</title>
<META http-equiv=content-type content="text/html; charset=<?php echo $this->_tpl_vars['oLanguage']->GetConstant('global:default_encoding','UTF-8'); ?>
">


<?php if ($this->_tpl_vars['bNoFollow'] && ! $this->_tpl_vars['bNoIndex']): ?>
	<meta name="robots" content="noindex, nofollow"/>
<?php elseif ($this->_tpl_vars['bNoFollow'] && $this->_tpl_vars['bNoIndex']): ?>
	<meta name="robots" content="noindex, follow"/>
<?php else: ?>
	<meta name="robots" content="index, follow"/>
<?php endif; ?>
</HEAD>
<BODY <?php echo $this->_tpl_vars['sOnloadPrint']; ?>
>
<?php if ($this->_tpl_vars['iBeforeContentButtonsPrint']): ?>
<?php if (! $this->_tpl_vars['bHideButtonTable']): ?>
<table border=0 width=700 class='noPrint'>
	<TR>
		<TD align=center colspan=3>
			<?php if (! $this->_tpl_vars['sOnloadPrint']): ?>
			<INPUT onclick="javascript:window.print();" style='width=100' type=button class='btn'
				value="<?php echo $this->_tpl_vars['oLanguage']->GetMessage('Print'); ?>
">

			<?php if ($this->_tpl_vars['iCloseButton'] == 1): ?>
			<input type=button class='btn' name=submit value='<?php echo $this->_tpl_vars['oLanguage']->GetMessage('Close'); ?>
'
				style='width=100' onclick="<?php if ($this->_tpl_vars['bCloseButtonAsReturn']): ?>history.back()<?php else: ?>window.close()<?php endif; ?>;">
			<?php endif; ?>

			<?php if ($_GET['return']): ?>
			<input type=button class='btn' name=submit value='<?php echo $this->_tpl_vars['oLanguage']->GetMessage('Return'); ?>
'
				style='width=100' onclick="location.href='/?action=<?php echo $_GET['return']; ?>
'">
			<?php endif; ?>
			<?php endif; ?>
		</TD>

	</TR>
</table>
<?php endif; ?>
<?php endif; ?>
<?php echo $this->_tpl_vars['sContent']; ?>


<?php echo '
<style type="text/css">
@media print {
	.noPrint {
	    display:none;
	}
}
</style>
'; ?>



<?php if (! $this->_tpl_vars['bHideButtonTable']): ?>
<table border=0 width=700 class='noPrint'>
	<TR>
		<TD align=center colspan=3>
			<?php if (! $this->_tpl_vars['sOnloadPrint']): ?>
			<INPUT onclick="javascript:window.print();" style='width=100' type=button class='btn'
				value="<?php echo $this->_tpl_vars['oLanguage']->GetMessage('Print'); ?>
">

			<?php if ($this->_tpl_vars['iCloseButton'] == 1): ?>
			<input type=button class='btn' name=submit value='<?php echo $this->_tpl_vars['oLanguage']->GetMessage('Close'); ?>
'
				style='width=100' onclick="<?php if ($this->_tpl_vars['bCloseButtonAsReturn']): ?>history.back()<?php else: ?>window.close()<?php endif; ?>;">
			<?php endif; ?>

			<?php if ($_GET['return']): ?>
			<input type=button class='btn' name=submit value='<?php echo $this->_tpl_vars['oLanguage']->GetMessage('Return'); ?>
'
				style='width=100' onclick="location.href='/?action=<?php echo $_GET['return']; ?>
'">
			<?php endif; ?>
			<?php endif; ?>
		</TD>

	</TR>
</table>
<?php endif; ?>

</BODY>
</HTML>