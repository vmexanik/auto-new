<div class="row">
	<table class="table" style="margin-bottom:0px;">
	    <thead>
	      <tr>
	        <th>{$oLanguage->getMessage('order status')}</th>
	        <th>{$oLanguage->getMessage('manager')}</th>
	        <th>{$oLanguage->getMessage('time')}</th>
	        <th>{$oLanguage->getMessage('Ip_up')}</th>
	      </tr>
	    </thead>
        <tbody>
        	{if $aLog}
        		{foreach key=name item=value from=$aLog}
        			<tr>
        				<td>        					
        					{assign var=status value='status_'|cat:$value.order_status}
        					{if $value.order_status=='shipment' || $value.order_status=='shipment_2' 
        						|| $value.order_status=='cover' || $value.order_status=='no_answer_phone'}
        						<h3 style="margin:2px 0 0 0;"><span class="label label-default">{$oLanguage->getMessage($status)}</span></h3>
        					{else}
        						<h3 style="margin:2px 0 0 0;"><span class="label label-{$value.order_status}">{$oLanguage->getMessage($status)}</span></h3>
        					{/if}
        				</td>
        				<td>{if $value.name}{$value.name}{elseif $value.login}{$value.login}{/if}</td>
        				<td><nobr>{$oLanguage->GetPostDateTime($value.post_date)}</nobr></td>
        				<td>{$value.ip}</td>
        			</tr>
        		{/foreach}
        	{else}
        		<tr>
        			<td colspan=4>{$oLanguage->getMessage('no items found')}</td>
        		</tr>
        	{/if}
        	<tr>
        		<td colspan=4>&nbsp;</td>
        	</tr>
        </tbody>
      </table>
      <button type="button" class="btn btn-default" style="float:right;" onclick="$('.js_manager_panel_popup').hide();">
		{$oLanguage->getMessage('close')}
	  </button>
</div>