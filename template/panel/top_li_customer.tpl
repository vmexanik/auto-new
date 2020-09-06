{if $aAuthUser.price_type=='margin' && !$aAuthUser.id_parent_second && $aAuthUser.has_subcustomer}
	<li class="icon-list"
		><a href='/?action=customer_subuser'
		>{$oLanguage->GetMessage('Subusers')}</a></li>
{/if}

<!--li class="icon-list"></li-->