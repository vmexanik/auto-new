var MessageAttachment=function (data) {
	//this.data=data;
};

MessageAttachment.prototype.AddRow = function (form)
{
	var table=document.getElementById("attachment");
	var a=parseInt(table.rows.length);	
	var b=a+1;
	if (a!=100) {
		table.rows.length=b;
		var tb=document.getElementById("attachment");
		var tr=tb.tBodies[0].rows[0].cloneNode(true);
		tb.tBodies[0].appendChild(tr);
		//tr.cells[0].innerHTML=b+"\n";
	//	tr.cells[1].innerHTML="<input type=text name=\"azpDescript"+b+"\" maxlength=\"100\" style=\"width:330px;\" value=\"\">\n";
		tr.cells[0].innerHTML="<input type=\"file\" size=70 name=\"patch"+b+"\" accept\"image\/*\">\n";
	}
	else window.alert($("#vin_request_less_100lines").val());
};

var maf=new MessageAttachment();
