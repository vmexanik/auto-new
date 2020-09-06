{if $aListOwnAuto}
<div class="search-input-own-auto">
    <table width="99%">
	<tr>
		<td><b>{$oLanguage->getMessage("Select own auto")}:</b></td>
	</tr>
	<tr>
		<td>
			{html_options options=$aListOwnAuto selected=$aData.id_own_auto name="id_own_auto" style="width: 260px;" 
			    onchange="window.location.href='/?action=catalog_view_own_auto&id='+this.options[this.selectedIndex].value;"}
		</td>
	</tr>
    </table>
</div>
{/if}
