
{$sContactForm}

<div class="at-reg-info">
    <div class="inner-panel">
        <div class="top-part">
			<p>{$oLanguage->GetText('contact_form:address')}</p>
			<p>{$oLanguage->GetText('contact_form:mail')}</p>
			<p>{$oLanguage->GetText('contact_form:phone')}</p>
			<p>{$oLanguage->GetText('contact_form:work_time')}</p>
			<p>{$oLanguage->GetText('contact_form:text')}</p>
        </div>
    </div>
</div>
<div class="clear"></div>

<br>

{$oLanguage->GetText('contact_form:map')}

{literal}
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
		var elementClick = $('.error_message');
	    var destination = $(elementClick).offset().top;
	    $("body,html").animate({ scrollTop: destination }, 800);
	    return false;
	}
});
</script>
{/literal}