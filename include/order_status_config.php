<?

$aOrderStatusConfig=array(
'new'=>array('new','work','refused', 'accrued','reserve','self_delivery','delivery','redeemed','reclamation','removed'),
'work'=>array('work','confirmed', 'refused', 'accrued','reserve','self_delivery','delivery','redeemed','reclamation','removed'),
'confirmed'=>array('confirmed','road',  'refused',),
'road'=>array('road','store', 'refused'),
'store'=>array('store','end','return_customer'),
'pending'=>array('work','refused'),
'refused'=>array('refused'),
'end'=>array('end','return_customer'),
'return_customer'=>array('return_customer','return_store','return_provider','end'),
'return_provider'=>array('return_provider'),
'return_store'=>array('return_store'),
'reserve'=>array('reserve', 'accrued','self_delivery','delivery','redeemed','reclamation','removed','return_customer', 'return_provider', 'return_provider_ok', 'return_provider_no'),
'accrued'=>array('accrued', 'reserve','self_delivery','delivery','redeemed','reclamation','removed','return_customer', 'return_provider', 'return_provider_ok', 'return_provider_no'),
'self_delivery'=>array('self_delivery','accrued','reserve','delivery','redeemed','reclamation','removed','return_customer', 'return_provider', 'return_provider_ok', 'return_provider_no'),
'delivery'=>array('delivery', 'accrued','reserve','self_delivery','redeemed','reclamation','removed','return_customer', 'return_provider', 'return_provider_ok', 'return_provider_no'),
'redeemed'=>array('redeemed', 'accrued','reserve','self_delivery','delivery','reclamation','removed','return_customer', 'return_provider', 'return_provider_ok', 'return_provider_no'),
'reclamation'=>array('reclamation','accrued','reserve','self_delivery','delivery','redeemed','removed','return_customer', 'return_provider', 'return_provider_ok', 'return_provider_no'),
'removed'=>array('removed', 'accrued','reserve','self_delivery','delivery','redeemed','reclamation','return_customer', 'return_provider', 'return_provider_ok', 'return_provider_no'),							
);


$aUnstateOrderStatus=array('change_price','change_quantity','change_code');

$aAllowChangeProviderDetailStatus=array('pending','new','work','confirmed','road');

$aOrderStatusColor=array(
'new'=>'broun',
'work'=>'green',
'confirmed'=>'#0000A0',
'road'=>'#1589FF',
'store'=>'blue',
'end'=>'black',
'refused'=>'red',
'pending'=>'blue',
'return_customer'=>'black',
'return_provider'=>'black',
'return_store'=>'black',
'assembled'=>'black',
'need_delivery'=>'black',
'need_courier'=>'black',
'in_wait'=>'black',
'shipment' => 'black',
'shipment_2' => 'black',
'delivery' => 'black',
'cover' => 'black',
'return' => 'black',
'archive' => 'black',
'no_answer_phone' => 'black',
'wait_pay' => 'black',
'need_call' => 'black',
'cancel_customer' => 'black',
);

$aPurchaseDetailOrderStatus=array('accrued','reserve','self_delivery','delivery',
	'work','redeemed','reclamation','removed');

$aStoreOrderStatus=array('assembled','end','need_delivery','need_courier',
	'refused','shipment_2','cover','delivery','return');

$aStoreOrderStatusView=array('assembled','need_delivery','need_courier',
	'shipment_2','cover','delivery','return');
?>