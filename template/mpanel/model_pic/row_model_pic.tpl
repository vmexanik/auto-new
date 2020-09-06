<td>{$aRow.id}</td>
<td>{$aRow.make}</td>
<td><font {if $aRow.model!=$aRow.name}color=red{/if}>{$aRow.model}</font></td>
<td>{$aRow.description}</td>
<td>{if $aRow.image}<img width="100px" src="/imgbank/Image/model/{$aRow.image}" />{else}NA{/if}</td>
<td>{$aRow.size}</td>
<td nowrap>{include file='addon/mpanel/base_row_action.tpl'
sBaseAction=$sBaseAction}</td>
