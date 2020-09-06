/**
* @author Mikhail Starovojt
*/

var MstarGeneral=function (data) {
};


MstarGeneral.prototype.ChangeProductImage = function(Id,SubId,ImageMiddle,ImageOriginal){
	//alert('Change:'+Id+' '+SubIdm+' '+Image);
	document.getElementById('lupa_link_id').href='./?action=product_preview&id='+Id+'&subId='+SubId+'&second=1';
	//document.getElementById('image_original').value=ImageOriginal;
	document.getElementById('ctl00_ContentMainPage_imgMainImage').src=ImageMiddle;
}

var oMstarGeneral=new MstarGeneral();