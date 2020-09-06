<table border=0 width=99%>
<tr><td width=70%>
<input type=button class='at-btn' 
{if ($iNeedBoldPref and $iNeedBoldPref == 1)}
style="font-weight: bold;" 
{/if}
value="{$oLanguage->getMessage("Check prefix")}" onclick="location.href='/?action=price_conformity'">
<input type=button class='at-btn' value="{$oLanguage->getMessage("Clear prefix")}" onclick="location.href='/?action=price_clear_pref'">
<input type=button class='at-btn' value="{$oLanguage->getMessage("Clear price")}" onclick="location.href='/?action=price_clear_import'">
<input type=button class='at-btn' value="{$oLanguage->getMessage("Clear providers ")}" onclick="location.href='/?action=price_clear_provider'">
<input type=button class='at-btn' value="{$oLanguage->getMessage("New price item")}" onclick="location.href='/?action=price_add_new'">
<input type=button class='at-btn' value="{$oLanguage->getMessage("Install price")}" onclick="location.href='/?action=price_install&no_del_error=1'">
</td>
</tr>
</table>