<td><ul class="news"><li>
	<label>{$aRow.post_date|date_format:"%d.%m.%Y"}</label>
	{$aRow.short}
	&nbsp;

	{if $aRow.has_full_link}
		<a href="/pages/news/{$aRow.id}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}"><b>{$oLanguage->getMessage("News Preview")}&raquo;</b></a>
	{/if}
</li></ul></td>