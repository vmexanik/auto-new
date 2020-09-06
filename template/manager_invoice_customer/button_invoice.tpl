{*if $smarty.request.search.id_language}
<p>
	{if $smarty.request.search.id_language=='1'}
	<input type='button' class='at-btn' value="{$oLanguage->getMessage("Export filtered invoices to excel")}"
		onclick="mt.ChangeActionSubmit(this.form,'manager_invoice_customer_get_invoice_excel_filtered');" />

	<input type='button' class='at-btn' value="{$oLanguage->getMessage("Export all invoices to excel")}"
		onclick="if (confirm('{$oLanguage->getMessage("Are you sure?")}'))
		 mt.ChangeActionSubmit(this.form,'manager_invoice_customer_get_invoice_excel_all');" />
	{else}
	<input type='button' class='at-btn' value="{$oLanguage->getMessage("Export filtered invoices to zip")}"
		onclick="mt.ChangeActionSubmit(this.form,'manager_invoice_customer_get_invoice_facture_excel_filtered');" />

	<input type='button' class='at-btn' value="{$oLanguage->getMessage("Export all invoices to zip")}"
		onclick="if (confirm('{$oLanguage->getMessage("Are you sure?")}'))
		 mt.ChangeActionSubmit(this.form,'manager_invoice_customer_get_invoice_facture_excel_all');" />
	{/if}
</p>
{/if}
	<input type='button' class='at-btn' value="{$oLanguage->getMessage("Export list invoices to excel")}"
		onclick="mt.ChangeActionSubmit(this.form,'manager_invoice_customer_get_invoice_list_excel');" />
{if $smarty.request.search.is_travel_sheet}
<p>
	<input type='button' class='at-btn' value="{$oLanguage->getMessage("Create invoice travel sheet filtered")}"
		onclick="mt.ChangeActionSubmit(this.form,'manager_invoice_customer_create_invoice_travel_sheet');" />
</p>
{/if*}