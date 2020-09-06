{if $aData.code_delivery}

<br />
<span class="hov">
<a href="#">{$aRow.code_delivery}

<!--[if IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->

<b id="region{$aData.id_provider}{$sUnique}" style="width:170px">
{$aData.code_delivery_description}
<br>
<a style="text-decotation:underline" href='/?action=delivery_region_preview&id={$aData.id_provider_region}'
	target=_blank>{$oLanguage->getMessage('RegionDetails')}</a>
</b>
<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</span>
</span>{/if}