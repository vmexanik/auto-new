{if $aPaymentType.id=='1'}
	<input type=button class='at-btn' value="{$oLanguage->getMessage("Pay for cartpackage via Beznal")}"
	 	onclick="location.href='/?action=finance_bill_add&amount={if $aCartPackage.price_total>0}{$aCartPackage.price_total}{else}{$smarty.session.current_cart_package.price_total}{/if}'">
{/if}


{*if $aPaymentType.id=='3'}
	<input type=button class='at-btn' value="{$oLanguage->getMessage("Pay for cartpackage via Liqpay")}"
	 	onclick="location.href='/?action=payment_liqpay&amount={$smarty.session.current_cart_package.price_total}'">
{/if*}