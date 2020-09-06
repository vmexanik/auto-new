		   	<div class="col-lg-5">
		   		<b>{$oLanguage->getMessage('total')}:</b>
		   		<b style="float:right">{$oCurrency->PrintSymbol($aCartPackage.price_total_no_delivery)}</b>
		   		<br>
		   		<b>{$oLanguage->getMessage('delivery price')}:</b>
		   		<b style="float:right">{$oCurrency->PrintSymbol($aCartPackage.price_delivery)}</b>
		   		<br><br>
		   		<b style="font-size:18px;">{$oLanguage->getMessage('subtotal')}:</b>
		   		<b style="font-size:18px;float:right">{$oCurrency->PrintPrice($aCartPackage.price_total)}</b>
		   		<br><br>
		   		<b>{$oLanguage->getMessage('Created')}:</b>
		   		<b style="float:right">{$oLanguage->GetPostDate($aCartPackage.post_date)}</b>
		   		<br>
		   		<b>{$oLanguage->getMessage('Change')}:</b>
		   		<b style="float:right">{$oLanguage->GetPostDate($aCartPackage.change)}</b>
		    </div>
