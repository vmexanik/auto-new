{foreach key=sKey item=item from=$oTable->aColumn}
{if $sKey=='action'}<td>
{if $oLanguage->getConstant('global:url_is_lower',0)}
	<a href="/price/{$oContent->Translit($aRow.cat)|@lower}_{$aRow.code|@lower}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$oLanguage->GetMessage("Search")}&nbsp;>></a>
{else}
	<a href="/price/{$oContent->Translit($aRow.cat)}_{$aRow.code}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}">{$oLanguage->GetMessage("Search")}&nbsp;>></a>
{/if}
</td>
{else}
<td>
    <div class="order-num">{$item.sTitle}</div>
    {$aRow.$sKey}
</td>
{/if}
{/foreach}
