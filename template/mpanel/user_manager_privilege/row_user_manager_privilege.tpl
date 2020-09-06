<td>
    <b>{$aRow.title}</b><br>
    {$aRow.description}
</td>
<td>
    <ul class="unstyled">
    {if ($aRolePrivilege[$aRow.id]|is_array)}
        {foreach from=$aRoles item=aRole}
            {if ($aRole.id|in_array:$aRolePrivilege[$aRow.id])}
            <li class="role-list-item" onclick="xajax_process_browse_url('?action=user_manager_privilege_unbind_privilege&roleId={$aRole.id}&privilegeId={$aRow.id}&return=action%3Duser_manager_privilege%26mpanel2%3')">
                <span class="icon-remove"></span>{$aRole.name}
            </li>
            {/if}
        {/foreach}
    {/if}
    </ul>
</td>
<td>
    <ul class="unstyled">
        {foreach from=$aRoles item=aRole}
            {if !($aRolePrivilege[$aRow.id]|is_array)||!($aRole.id|in_array:$aRolePrivilege[$aRow.id])}
                <li class="role-list-item" onclick="xajax_process_browse_url('?action=user_manager_privilege_bind_privilege&roleId={$aRole.id}&privilegeId={$aRow.id}&return=action%3Duser_manager_privilege%26mpanel2%3')">
                    <span class="icon-plus-2"></span>{$aRole.name}
                </li>
            {/if}
        {/foreach}
    </ul>
</td>