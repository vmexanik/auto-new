<h1>{$aParts.parts.part_name} {$aParts.client.mark} {$aParts.client.family} {$aParts.client.model}</h1>

{include file=levam_catalog/chosen_modification.tpl}

<div class="at-product-view">
    <div class="at-product-top">
        <div class="part image-part js-image-banner">
        	<div class="image">
        		<div class="oneCoordImage">
        			{foreach from=$aCoords item=aCoord}
	        		<div class="map" id="co_{$aCoord.name}" style="margin-top:{$aCoord.margintop}%; margin-left:{$aCoord.marginleft}%; position: absolute; width: {$aCoord.width}%; height: {$aCoord.height}%; border: 2px solid red;" onclick="mark('{$aCoord.name}')"></div>
	        		{/foreach}
	        		
	                <img src="{$aParts.parts.image.0}"  style="width: 100%;">
                </div>
            </div>
		</div>
	</div>
</div>