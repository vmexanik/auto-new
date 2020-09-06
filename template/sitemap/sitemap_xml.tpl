<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{foreach from=$aSiteindex item=iItem}
  <sitemap>
     <loc>{$oLanguage->GetConstant('global:project_url')}/sitemap{$iItem}.xml</loc>
     <lastmod>{$sSiteindexDate}</lastmod>
  </sitemap>
{/foreach}
</sitemapindex>