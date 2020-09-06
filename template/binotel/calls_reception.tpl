
	<table class="reception">
		<tr colspan=4>
			<td><span style="font-size:25px;">Входящий звонок</span></td>
		</tr>
		<tr>
			<th>Клиент</th>
			<th>На номер</th>
			<th>Менеджер</th>
			<th>Информация</th>
		</tr>
		<tr>
			<td>{if $aCustomerData.name}<b>{$aCustomerData.name}</b><br>{/if}{$aCustomerData.number}</td>
			<td>{$aCustomerData.srcNumber}</td>
			<td>{$aCustomerData.extNumber}</td>
			<td>{$aCustomerData.description}</td>
		</tr>
	</table>

{literal}	
	<style>
   table.reception { 
    width: 100%;
    border: 4px double black; 
    border-collapse: collapse; 
   }
   table.reception th { 
    text-align: left; 
    background: #ccc; 
    padding: 5px; 
    border: 1px solid black; 
   }
   table.reception td { 
    padding: 5px;
    border: 1px solid black; 
   }
  </style>
  {/literal}