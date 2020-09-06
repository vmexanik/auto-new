<?php /* Smarty version 2.6.18, created on 2019-10-07 11:29:50
         compiled from contact_form/template.tpl */ ?>

<?php echo $this->_tpl_vars['sContactForm']; ?>


<div class="at-reg-info">
    <div class="inner-panel">
        <div class="top-part">
			<p><?php echo $this->_tpl_vars['oLanguage']->GetText('contact_form:address'); ?>
</p>
			<p><?php echo $this->_tpl_vars['oLanguage']->GetText('contact_form:mail'); ?>
</p>
			<p><?php echo $this->_tpl_vars['oLanguage']->GetText('contact_form:phone'); ?>
</p>
			<p><?php echo $this->_tpl_vars['oLanguage']->GetText('contact_form:work_time'); ?>
</p>
			<p><?php echo $this->_tpl_vars['oLanguage']->GetText('contact_form:text'); ?>
</p>
        </div>
    </div>
</div>
<div class="clear"></div>

<br>

<?php echo $this->_tpl_vars['oLanguage']->GetText('contact_form:map'); ?>


<?php echo '
<style>
@media screen and (max-width: 769px) {
.at-reg-info {
	 display: block;
     width: 100%;
	 padding: 0;
	 margin-top: 20px;
    }
}

</style>


<script type="text/javascript">
$(document).ready(function () {
	if($( "div" ).find( ".error_message" ).length>0) {
		var elementClick = $(\'.error_message\');
	    var destination = $(elementClick).offset().top;
	    $("body,html").animate({ scrollTop: destination }, 800);
	    return false;
	}
});
</script>
'; ?>