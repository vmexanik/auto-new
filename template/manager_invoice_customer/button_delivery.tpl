<form method=post action="/?action=manager_invoice_customer_delivery_apply">
<input type=hidden name="is_post" value=1>
<input type=submit class='at-btn' value="{$oLanguage->getMessage("Create")}"
	onclick="if (!confirm('{$oLanguage->getMessage("Create Delivery cost?")}'))
	 return false;">
</form>