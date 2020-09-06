{*<div class="at-index-banner-wrap">
    <div class="at-index-banner js-index-banner">
    	{foreach from=$aBanner item=aSingleBanner}
        <div>
        	<a href="{$aRow.url}">
            <img src="{$aRow.image}" alt="">

           <!-- <div class="slide-content">
                <div class="name">
                    {$aRow.name}
                </div>

                <div class="text">
                    {$aRow.text}
                </div>

                <a class="slide-button" href="{$aRow.url}">
                    Подробнее
                </a>
            </div> -->
            
            </a>
        </div>
		{/foreach}
    </div>
</div>*}

<div class="at-index-banner-wrap">
         <div class="at-index-banner js-index-banner">
        	  {foreach from=$aBanner item=aSingleBanner}
               <div>
                   <a href="{$aSingleBanner.link}"><img src="{$aSingleBanner.image}" alt="{$aSingleBanner.name}"></a>
          	   </div>
              {/foreach} 
         </div>
 </div>