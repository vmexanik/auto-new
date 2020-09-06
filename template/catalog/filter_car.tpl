    <div class="filter-inner">
        <span class="span_h2 at-caption">{$oLanguage->GetMessage('search by car model')}</div>
        
        <label>
        	<span>{$oLanguage->GetMessage('Brand search')}: </span>
        	<input name="action" value="catalog_assemblage_view" type="hidden" />
			<select name="data[make]" id="id_make_r" class="searcher_select" style="width:400px;"
				onchange="location='{$sUrlMake}'+this.options[this.selectedIndex].value;">
				{html_options options=$aMakeName selected=$aPartData.make_url}
			</select>
        </label>
        <br />
        <label>
        	<span>{$oLanguage->GetMessage('Model')}: </span>
            <span id="select_model">
				<select name=data[id_model] id="id_model_r" onchange="location='{$sUrlModel}'+this.options[this.selectedIndex].value;" style="width: 400px;">
				{html_options options=$aModel selected=$aPartData.model_url}
				</select>
			</span>
        </label>
        <label>
            <span>{$oLanguage->GetMessage('Year')}: </span>
        	<span id="select_model_detail">
				<select name=data[id_model_detail] id="id_model_detail_r" class="searcher_select" 
					onchange="location='{$sUrlModelDetail}'+this.options[this.selectedIndex].value;" style="width: 400px;">
				{html_options options=$aModelDetail selected=$aPartData.model_detail_url}
				</select>
			</span>
        </label>
        <div class="clear"></div>
     </div>
       