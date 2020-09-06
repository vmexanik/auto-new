<td>{$aRow.id}</td>
<td>{$aRow.name|truncate:50:""}</td>
<td>{$aRow.short|truncate:50:""}</td>
<td>{$aRow.full|strip_tags|truncate:80:""}</td>
<td>{if $aRow.post_date != ''}{$aRow.post_date|date_format:"%d-%m-%Y"}{/if}</td>
<td><img src='{$aRow.image}' align=left hspace=3 width=40></td>
<td>{include file='addon/mpanel/visible.tpl' aRow=$aRow}</td>
<td>{$aRow.num}</td>
<td nowrap>{include file='addon/mpanel/base_lang_select.tpl'}</td>
<td nowrap>{include file='addon/mpanel/base_row_action.tpl'
sBaseAction=$sBaseAction}</td>
