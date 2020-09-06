<?php /* Smarty version 2.6.18, created on 2020-07-27 10:43:04
         compiled from form/main.tpl */ ?>
<?php if ($this->_tpl_vars['sTitle'] || $this->_tpl_vars['sAdditionalTitle']): ?>
<div class="at-user-details">
    <div class="header">
        <?php echo $this->_tpl_vars['sTitle']; ?>
<?php echo $this->_tpl_vars['sAdditionalTitle']; ?>
<?php echo $this->_tpl_vars['sHint']; ?>

    </div>
</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['sFormError'] || $_GET['form_error']): ?><div class=error_message><?php echo $this->_tpl_vars['sFormError']; ?>

	<?php echo $this->_tpl_vars['oLanguage']->getMessage($_GET['form_error']); ?>
</div><?php endif; ?>

<?php if ($this->_tpl_vars['sFormMessage']): ?><div class="<?php echo $this->_tpl_vars['sFormMessageClass']; ?>
"><?php echo $this->_tpl_vars['sFormMessage']; ?>
</div><?php endif; ?>

<FORM <?php echo $this->_tpl_vars['sHeader']; ?>
>
<?php echo $this->_tpl_vars['sHidden']; ?>


<div class="at-block-form" style="background-color: #ffffff;box-shadow: 0 0 10px #cadae2;margin: 0 0 20px 0;">
<?php echo $this->_tpl_vars['sBeforeContent']; ?>

	<?php echo $this->_tpl_vars['sContent']; ?>

<?php echo $this->_tpl_vars['sAfterContent']; ?>

</div>

<?php if ($this->_tpl_vars['sButtonDivClass']): ?><div class="<?php echo $this->_tpl_vars['sButtonDivClass']; ?>
"><?php endif; ?>

<?php if ($this->_tpl_vars['sReturnButton'] && ! $this->_tpl_vars['bReturnAfterSubmit']): ?>
<span <?php if ($this->_tpl_vars['sButtonSpanClass']): ?>class="button"<?php else: ?> style="padding:<?php echo $this->_tpl_vars['sButtonsPadding']; ?>
px 0 0 0;" <?php endif; ?>>
<input type=button class='<?php echo $this->_tpl_vars['sReturnButtonClass']; ?>
' value="<?php echo $this->_tpl_vars['sReturnButton']; ?>
"
	onclick="location.href='<?php if (! $this->_tpl_vars['bNoneDotUrl']): ?>.<?php endif; ?>/<?php if (! $this->_tpl_vars['bAutoReturn']): ?>?action=<?php endif; ?><?php echo $this->_tpl_vars['sReturnAction']; ?>
'" >
</span>
<?php endif; ?>


<span <?php if ($this->_tpl_vars['sButtonSpanClass'] && $this->_tpl_vars['sSubmitButton']): ?>class="button"<?php else: ?> style="padding:<?php echo $this->_tpl_vars['sButtonsPadding']; ?>
px 0 0 0;" <?php endif; ?>>
<?php if ($this->_tpl_vars['sSubmitButton']): ?>
<input type=submit class='<?php echo $this->_tpl_vars['sSubmitButtonClass']; ?>
' value="<?php echo $this->_tpl_vars['sSubmitButton']; ?>
"
	<?php if ($this->_tpl_vars['bConfirmSubmit']): ?>
		onclick="if (!confirm('<?php echo $this->_tpl_vars['oLanguage']->getMessage($this->_tpl_vars['sConfirmText']); ?>
')) return false;"
	<?php endif; ?>
	 >
<?php endif; ?>

<?php if ($this->_tpl_vars['sReturnButton'] && $this->_tpl_vars['bReturnAfterSubmit']): ?>
<span <?php if ($this->_tpl_vars['sButtonSpanClass']): ?>class="button"<?php else: ?> style="padding:<?php echo $this->_tpl_vars['sButtonsPadding']; ?>
px 0 0 0;" <?php endif; ?>>
<input type=button class='<?php echo $this->_tpl_vars['sReturnButtonClass']; ?>
' value="<?php echo $this->_tpl_vars['sReturnButton']; ?>
"
	onclick="location.href='<?php if (! $this->_tpl_vars['bNoneDotUrl']): ?>.<?php endif; ?>/<?php if (! $this->_tpl_vars['bAutoReturn']): ?>?action=<?php endif; ?><?php echo $this->_tpl_vars['sReturnAction']; ?>
'" >
</span>
<?php endif; ?>




<?php if ($this->_tpl_vars['sSubmitAction']): ?><input type=hidden name=action value='<?php echo $this->_tpl_vars['sSubmitAction']; ?>
'><?php endif; ?>

<?php if ($this->_tpl_vars['bAutoReturn']): ?>
	<input type=hidden name=return value='<?php echo $this->_tpl_vars['sReturnAction']; ?>
'>
<?php endif; ?>

<?php if ($this->_tpl_vars['sAdditionalButtonTemplate']): ?> <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['sAdditionalButtonTemplate'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> <?php endif; ?>

<?php echo $this->_tpl_vars['sAdditionalButton']; ?>

</span>

<?php if ($this->_tpl_vars['bIsPost']): ?>
<input type=hidden name=is_post value='1'>
<?php endif; ?>


<?php if ($this->_tpl_vars['sButtonDivClass']): ?></div><?php endif; ?>

<?php if ($this->_tpl_vars['bShowBottomForm']): ?>
</FORM>
<?php endif; ?>

<br>