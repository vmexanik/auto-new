{if $aNews}
<div class="at-index-blog controls">
	<h2>{$oLanguage->GetMessage('news')}</h2>
	<div class="blog-list js-blog-slider">
	{foreach from=$aNews item=aItem}
		<div class="blog-item">
			<a href="/pages/news/{$aItem.id}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}" class="image"
               style="background-image: url('{if $aItem.image}{$aItem.image}{else}/image/media/index-blog-1.png{/if}')">
                <img src="/image/blog-mask.png" alt="">
            </a>

            <a href="/pages/news/{$aItem.id}{if $oLanguage->getConstant('global:url_is_not_last_slash',0)}{else}/{/if}" class="name">{$aItem.name}</a>

            <div class="date">{$oLanguage->GetPostDate($aItem.post_date)}</div>

            <div class="text">
                {$aItem.short}
            </div>
		</div>
	{/foreach}
	</div>
</div>
{/if}