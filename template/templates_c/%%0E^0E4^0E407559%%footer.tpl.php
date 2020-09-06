<?php /* Smarty version 2.6.18, created on 2019-09-20 12:11:41
         compiled from footer.tpl */ ?>

<?php if ($this->_tpl_vars['aGeneralConf']['IsLive']): ?>
	<?php echo '

	'; ?>

<?php endif; ?>

<?php if ($this->_tpl_vars['aAuthUser']['type_'] == 'manager'): ?>
    <script type="text/javascript">
    <?php echo '
    $(document).ready(function() {
	if ($(\'#select_name_user\').length) {
	    $("#select_name_user").searchable({
	    maxListSize: 50,
	    maxMultiMatch: 25,
	    wildcards: true,
	    ignoreCase: true,
	    latency: 1000,
	    '; ?>
warnNoMatch: '<?php echo $this->_tpl_vars['oLanguage']->getMessage('no matches'); ?>
 ...',<?php echo '
	    zIndex: \'auto\'
	    });
	}
    });
    '; ?>

    </script>
<?php endif; ?>

</body>
</html>