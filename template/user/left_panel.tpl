<div class="at-layer-left">
     <div class="mob-hide">
      <div class="at-auth-menu">
          <div class="auth-menu">
              <table class="user-name">
                  <tr>
                      <td class="icon">
                          <i></i>
                      </td>
                      <td>
                          <a href="javascript:void(0);"><strong>{$aAuthUser.login}</strong></a>
			    {if $aAuthUser.type_=='customer'}
			    <br><br>{$oLanguage->GetMessage('currency_balance')}:<span {if $aAuthUser.amount>0}style="color:green;"{elseif $aAuthUser.amount<0}style="color:red;"}{/if}> {$oCurrency->PrintSymbol($aAuthUser.amount)}</span>
			    {/if}
                      </td>
                  </tr>
              </table>
              
              <ul class="list">
                  {if $aAuthUser.type_=='manager'}
			  		 <li>
			  		 	<a href="/mpanel" target="_blank">Перeйти в MPanel</a>
			   	  	 </li>
			   	  {/if}
                  
                  {foreach from=$aAccountMenu item=aItem}
                  <li>
					<a href="/pages/{if !$aItem.link}{$aItem.code}{else}{$aItem.code}{/if}" 
					{if $aItem.code==$smarty.request.action}class="selected"{/if}					
					>{$aItem.name}</a>
					{if $aAuthUser.type_=='manager'}
						{if $aItem.code=="message"}{if $aTemplateNumber.message_number} <font color="red">({$aTemplateNumber.message_number})</font>{/if}{/if}
						{if $aItem.code=="payment_report_manager"}{if $aTemplateNumber.payment_report_id} <font color="red">({$aTemplateNumber.payment_report_id})</font>{/if}{/if}
						{if $aItem.code=="vin_request_manager"}{if $iNotViewedVins} <font color="red">({$iNotViewedVins})</font>{/if}{/if}
						{if $aItem.code=="manager_package_list"}{if $iNotViewedOrders} <font color="red">({$iNotViewedOrders})</font>{/if}{/if}
						{if $aItem.code=="call_me_show_manager"}{if $aTemplateNumber.resolved} <font color="red">({$aTemplateNumber.resolved})</font>{/if}{/if}
					{/if}
					{if $aAuthUser.type_=='customer'}
						{if $aItem.code=="payment_declaration"}{if $aTemplateNumber.payment_declaration_id} <font color="red">({$aTemplateNumber.payment_declaration_id})</font>{/if}{/if}
						{if $aItem.code=="message_change_current_folder"}{if $aTemplateNumber.message_number} <font color="red">({$aTemplateNumber.message_number})</font>{/if}{/if}
					{/if}
					</li>
				{/foreach}
                  
                  <li class="logout">
                      <a href="/pages/user_logout/">Выход</a>
                  </li>
              </ul>
          </div>
      </div>

{if $aAuthUser.type_=='customer'}
      <div class="at-manager-block">
          <a href="/?action=message_compose&compose_to={$aAuthUser.manager_login}">{$oLanguage->GetMessage('Your personal manager')}</a>
      </div>
{/if}
      
  </div>
</div>