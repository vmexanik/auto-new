<td style="height:23px;width:20%;" class="logo_brand">
<a href="/cars/{if $oLanguage->getConstant('global:url_is_lower',0)}{$oContent->Translit($aRow.name)|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}{else}{$oContent->Translit($aRow.name)}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}{/if}">
{$aRow.title}</a>
</td>