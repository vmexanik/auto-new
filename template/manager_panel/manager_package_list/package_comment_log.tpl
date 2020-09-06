<div style="overflow: scroll;max-height:30%;min-height:18%;border: #ddd;border: 1px solid #ddd;padding:5px;">
	{foreach from=$aLogComment item=aItem}
		<div class="panel panel-default" style="margin-bottom: 5px;">
			<div style="padding:5px;">
				<span style="color:blue;"><b>{$aItem.name}</b></span>
				<span style="float:right">{$aItem.created}</span>
				<br>
				{$aItem.comment}
			</div>
		</div>
	{/foreach}
</div>