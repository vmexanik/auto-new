<div class="notice">
   <div class="caption">{$oLanguage->GetMessage('order by phone')}</div>

   <div class="text">
      {$oLanguage->GetText('order by phone')}
   </div>

   <table>
       <tr>
           <td><input class="phone js-masked-input fast_order_phone" type="text" placeholder="(___) ___ __ __" value="{if $aAuthUser.phone}{$aAuthUser.phone}{/if}" id="user_phone"></td>
           <td>
               {*<input class="at-btn" type="button" value="{$oLanguage->getMessage("Order by phone")}" id="fast_order_button" onclick="check_phone(); return false;" tabindex="1">*}
               <a href="#" class="at-btn" onclick="check_phone(); return false;">{$oLanguage->getMessage("Order by phone")}</a>
           </td>
       </tr>
   </table>
</div>