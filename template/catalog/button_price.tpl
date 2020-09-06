<script type="text/javascript" src="/js/vin_request.js?2720"></script>
{if $aAuthUser.type_=='customer'}
<input type=button class='at-btn' value="{$oLanguage->getMessage("Go to Cart for sending to work")}"
	onclick="location.href='/?action=cart_cart'">
{/if}

{if $aAuthUser.type_=='customer'}

{*if $bAddCartVisible}
<input type=button class='at-btn' style="width: 200px;" value="{$oLanguage->getMessage("Add selected to cart")}"
	onclick="mt.ChangeActionSubmit(this.form,'cart_add_cart_item_checked');">
{/if*}

{/if}

{if $smarty.session.catalog.catalog_return}
<input type=button class='at-btn' value="{$oLanguage->getMessage("Return to catalog")} {$smarty.session.catalog.name}"
	onclick="location.href='{$smarty.session.catalog.catalog_return}'">
{/if}


{if $smarty.get.manager_login}
<input type='hidden' name='manager_login' value='{$smarty.get.manager_login}'>
{/if}