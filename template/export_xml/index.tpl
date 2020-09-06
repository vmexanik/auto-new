<table class="datatable">
{* <tr>
	<td width="300px">
	<span id="button_sitemap_html_id">
	<input type="button" value="generate sitemap html" onclick="
			$('#button_sitemap_html_id').toggle(500); $('#image_sitemap_html_id').toggle(500);
			xajax_process_browse_url('/?action=export_xml_gen&amp;section=sitemap_html'); return false;">
	</span>

	<span id="image_sitemap_html_id" style="display: none;">
		<img src="/image/bar90.gif">
	</span>
	</td>

	<td>
		Ссылка на файл:
		<a href="/pages/sitemap/">sitemap html</a>
		<span id="xml_date_sitemap_html_id"></span>
	</td>
</tr> *}
{* ============== Hotline ============== *}
<tr>
	<td width="300px">
	<span id='button_hotline_id' {if $oLanguage->GetConstant('export_xml:hotline_export_running')} style="display: none;" {/if}>
	<input type='button' value="{$oLanguage->GetMessage('Generate hotline xml')}"
		onclick="
			$('#button_hotline_id').toggle(500); $('#image_hotline_id').toggle(500);
			xajax_process_browse_url('/?action=export_xml_gen&section=hotline'); return false;"
		/>
	</span>

	<span id='image_hotline_id' {if !$oLanguage->GetConstant('export_xml:hotline_export_running')} style="display: none;" {/if}>
		<img src='/image/bar90.gif' />
	</span>
	</td>

	<td>
		{$oLanguage->GetMessage('xml file link')}:
		<span id='xml_link_hotline_id' class="text">
			{if $bHotlineXmlLink}
				{include file='export_xml/file_link.tpl' sSection=$sNameFileHotline sExt='xml' bShowSingleXml=0 aRange=$aHotlineRange}
			{/if}
		</span>
		<span id='xml_date_hotline_id'>{if $oLanguage->GetConstant('export_xml:hotline_count')}count {$oLanguage->GetConstant('export_xml:hotline_count')} {/if}{$sHotlineXmlDate}</span>
	</td>
</tr>
<!-- {* ============== Price ============== *}

<tr>
	<td width="300px">
	<span id='button_price_id' {if $oLanguage->GetConstant('export_xml:price_export_running')} style="display: none;" {/if}>
	<input type='button' value="{$oLanguage->GetMessage('Generate price xml')}"
		onclick="
			$('#button_price_id').toggle(500); $('#image_price_id').toggle(500);
			xajax_process_browse_url('/?action=export_xml_gen&section=price'); return false;"
		/>
	</span>

	<span id='image_price_id' {if !$oLanguage->GetConstant('export_xml:price_export_running')} style="display: none;" {/if}>
		<img src='/image/bar90.gif' />
	</span>
	</td>

	<td>
		{$oLanguage->GetMessage('xml file link')}:
		<span id='xml_link_price_id' class="text">
			{if $bPriceXmlLink}
				{include file='export_xml/file_link.tpl' sSection=$sNameFilePrice sExt='xml' bShowSingleXml=0 aRange=$aPriceRange}
			{/if}
		</span>
		<span id='xml_date_price_id'>{if $oLanguage->GetConstant('export_xml:price_count')}count {$oLanguage->GetConstant('export_xml:price_count')} {/if} {$sPriceXmlDate}</span>
	</td>
</tr>
{* ============== Prom ============== *}

 <tr>
	<td width="300px">
	<span id='button_prom_id' {if $oLanguage->GetConstant('export_xml:prom_export_running')} style="display: none;" {/if}>
	<input type='button' value="{$oLanguage->GetMessage('Generate prom xml')}"
		onclick="
			$('#button_prom_id').toggle(500); $('#image_prom_id').toggle(500);
			xajax_process_browse_url('/?action=export_xml_gen&section=prom'); return false;"
		/>
	</span>

	<span id='image_prom_id' {if !$oLanguage->GetConstant('export_xml:prom_export_running')} style="display: none;" {/if}>
		<img src='/image/bar90.gif' />
	</span>
	</td>

	<td>
		{$oLanguage->GetMessage('xml file link')}:
		<span id='xml_link_prom_id' class="text">
			{if $bPromXmlLink}
				{include file='export_xml/file_link.tpl' sSection=$sNameFileProm sExt='xml' bShowSingleXml=0 aRange=$aPromRange}
			{/if}
		</span>
		<span id='xml_date_prom_id'>{if $oLanguage->GetConstant('export_xml:prom_count')}count {$oLanguage->GetConstant('export_xml:prom_count')} {/if} {$sPromXmlDate}</span>
	</td>
</tr>  -->

{* ============== Yandex ============== 

<!--tr>
	<td width="300px">
	<span id='button_yandex_id'>
	<input type='button' value="{$oLanguage->GetMessage('Generate yandex xml')}"
		onclick="
			$('#button_yandex_id').toggle(500); $('#image_yandex_id').toggle(500);
			xajax_process_browse_url('/?action=export_xml_gen&section=yandex'); return false;"
		/>
	</span>

	<span id='image_yandex_id' style="display: none;">
		<img src='/image/bar90.gif' />
	</span>
	</td>

	<td>
		{$oLanguage->GetMessage('xml file link')}:
		<span id='xml_link_yandex_id' class="text">
			{if $bYandexXmlLink}
				{include file='export_xml/file_link.tpl' sSection='market' sExt='yml' bShowSingleXml=1}
			{/if}
		</span>
		<span id='xml_date_yandex_id'>{$sYandexXmlDate}</span> 
		{$oLanguage->GetMessage('count')}: {$oLanguage->GetConstant('export_xml:yandex_i')}
	</td>
</tr-->
*}
{* ============== Google siteindex ============== *}

<tr>
	<td width="300px">
	<span id='button_siteindex_id'>
	<input type='button' value="{$oLanguage->GetMessage('Generate siteindex xml')}"
		onclick="
			$('#button_siteindex_id').toggle(500); $('#image_siteindex_id').toggle(500);
			xajax_process_browse_url('/?action=export_xml_gen&section=siteindex'); return false;"
		/>
	</span>

	<span id='image_siteindex_id' style="display: none;">
		<img src='/image/bar90.gif' />
	</span>
	</td>

	<td>
		{$oLanguage->GetMessage('xml file link')}:
		<br><span id='xml_link_siteindex_id' class="text">
			{* {if $bSiteindexXmlLink} *}
				{include file='export_xml/file_link.tpl' sSection='siteindex' sExt='xml'  bShowSingleXml=1 aSitemapBefore=$aSitemapBeforeRange}
				{include file='export_xml/file_link.tpl' sExt='xml' aSitemap=$aSitemapRange}
			{* {/if} *}
		</span>
		<br><span id='xml_date_siteindex_id'>{$sSiteindexXmlDate}</span>
	</td>
</tr>

{* ============== Ava ============== 

<!-- tr>
	<td width="300px">
	<span id='button_ava_id'>
	<input type='button' value="{$oLanguage->GetMessage('Generate ava xml')}"
		onclick="
			$('#button_ava_id').toggle(500); $('#image_ava_id').toggle(500);
			xajax_process_browse_url('/?action=export_xml_generate&section=ava'); return false;"
		/>
	</span>

	<span id='image_ava_id' style="display: none;">
		<img src='/image/bar90.gif' />
	</span>
	</td>

	<td>
		{$oLanguage->GetMessage('xml file link')}:
		<span id='xml_link_ava_id' class="text">
			{if $bAvaXmlLink}
				{include file='export_xml/file_link.tpl' sSection='ava' sExt='xml' bShowSingleXml=1}
			{/if}
		</span>
		<span id='xml_date_ava_id'>{$sAvaXmlDate}</span>
	</td>
</tr>


 ============== Hotprice ============== 

<tr>
	<td width="300px">
	<span id='button_hotprice_id'>
	<input type='button' value="{$oLanguage->GetMessage('Generate hotprice xml')}"
		onclick="
			$('#button_hotprice_id').toggle(500); $('#image_hotprice_id').toggle(500);
			xajax_process_browse_url('/?action=export_xml_generate&section=hotprice'); return false;"
		/>
	</span>

	<span id='image_hotprice_id' style="display: none;">
		<img src='/image/bar90.gif' />
	</span>
	</td>

	<td>
		{$oLanguage->GetMessage('xml file link')}:
		<span id='xml_link_hotprice_id' class="text">
			{if $bHotpriceXmlLink}
				{include file='export_xml/file_link.tpl' sSection='hotprice' sExt='xml' bShowSingleXml=1}
			{/if}
		</span>
		<span id='xml_date_hotprice_id'>{$sHotpriceXmlDate}</span>
	</td>
</tr>

 ============== Autobazar ============== 

<tr>
	<td width="300px">
	<span id='button_autobazar_id'>
	<input type='button' value="{$oLanguage->GetMessage('Generate autobazar xml')}"
		onclick="
			$('#button_autobazar_id').toggle(500); $('#image_autobazar_id').toggle(500);
			xajax_process_browse_url('/?action=export_xml_generate&section=autobazar'); return false;"
		/>
	</span>

	<span id='image_autobazar_id' style="display: none;">
		<img src='/image/bar90.gif' />
	</span>
	</td>

	<td>
		{$oLanguage->GetMessage('xml file link')}:
		<span id='xml_link_autobazar_id' class="text">
			{if $bAutobazarXmlLink}
				{include file='export_xml/file_link.tpl' sSection='autobazar' sExt='xml' bShowSingleXml=1}
			{/if}
		</span>
		<span id='xml_date_autobazar_id'>{$sAutobazarXmlDate}</span>
	</td>
</tr>
*}


{* ============== vcene ============== 

<!--tr>
	<td width="300px">
	<span id='button_vcene_id'>
	<input type='button' value="{$oLanguage->GetMessage('Generate vcene xml')}"
		onclick="
			$('#button_vcene_id').toggle(500); $('#image_vcene_id').toggle(500);
			xajax_process_browse_url('/?action=export_xml_generate&section=vcene'); return false;"
		/>
	</span>

	<span id='image_vcene_id' style="display: none;">
		<img src='/image/bar90.gif' />
	</span>
	</td>

	<td>
		{$oLanguage->GetMessage('xml file link')}:
		<span id='xml_link_vcene_id' class="text">
			{if $bVceneXmlLink}
				{include file='export_xml/file_link.tpl' sSection='vcene' sExt='yml' bShowSingleXml=1}
			{/if}
		</span>
		<span id='xml_date_vcene_id'>{$sVceneXmlDate}</span>
	</td>
</tr-->*}



</table>