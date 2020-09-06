<td>{$aRow.brand}</td>
<td>{$aRow.code}</td>
<td>{$aRow.name}</td>
<td>
    <a href="/pages/extension_td_tree_rubric?subaction=delete&id={$aRow.id}&data[id_model_detail]={$smarty.request.data.id_model_detail}&is_post=1&data[id_rubric]={$smarty.request.data.id_rubric}"
        onclick="if (!confirm('{$oLanguage->getMessage("Are you sure you want to delete this item?")}')) return false;"
        ><img src="/image/delete.png" border=0 width=16 align=absmiddle /> {$oLanguage->GetMessage('delete')}
    </a>
</td>