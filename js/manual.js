/**
* @author Alexander Belogura
*/
var Manual=function (data) {
};

Manual.prototype.ChangeManualCode = function()
{
	var codeInput = document.getElementById('data_code');
	var depSelect = document.getElementById('data_code_manual_category');
	var manNumber = document.getElementById('data_number');
	
	codeInput.value = depSelect.options[depSelect.options.selectedIndex].value+"-"+manNumber.value;
}
var man=new Manual();
