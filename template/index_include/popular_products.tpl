{if $aPopularProducts}
<h2>{$oLanguage->GetMessage('popular products')}</h2>

<div class="at-product-carousel js-product-carousel">
	<div class="line">
		{foreach from=$aPopularProducts item=aRow}
		<div>
			<div class="at-thumb-element ready">
				<div class="inner-wrap">
					<a href="{$aRow.url}" class="image"> 
						<img class="fake" src="/image/plist-thumb-mask.png" alt="{$aRow.name}" title="{$aRow.name}"> 
						{if $aRow.image}
						<img class="real" src="{$aRow.image}" alt="{$aRow.name}" title="{$aRow.name}"> 
						 {else}
						<img class="real" src="/image/media/no-photo-thumbs.png" alt="{$aRow.name}" title="{$aRow.name}">
						{/if}
						{*<span class="fav"></span>*}
						{*<span class="com"></span> *}
						{if $aRow.bage=='new'}<span class="action new">{$oLanguage->GetMessage("badge new")}</span>{/if}
						{if $aRow.bage=='action'}<span class="action">{$oLanguage->GetMessage("badge action")}</span>{/if}
						{if $aRow.bage=='recommend'}<span class="action recommend">{$oLanguage->GetMessage("badge recommend")}</span>{/if}
					</a>

					<div class="name x3-overflow">{$aRow.name}</div>

					<div class="price">
						<span>{$oCurrency->PrintPrice($aRow.price,0,0,'strong')}</span> {*<span class="cur">грн</span>*}
					</div>
					{if $aRow.old_price>0}
					<div class="price-old">
						<span>{$oCurrency->PrintPrice($aRow.old_price)}</span>
					</div>
					{/if}
				</div>

				<div class="extend">
					<div class="buy">
						<a href="{$aRow.url}" class="at-btn">Купить</a>
					</div>

					{*<div class="extra">
						Вес [кг]: 0.03 <br /> Внутренний диаметр: 25.00 <br /> Длина
						[мм]: 25.00 <br /> Материал: резина <br />
					</div>*}
				</div>
			</div>
		</div>
		{/foreach}
	</div>
</div>
{/if}