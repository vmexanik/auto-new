{*if !isset($aUser)}
	{ include file="cart/cart_onepage_user_tabs.tpl" }
{/if*}

<table>
    <tr>
        <td>
    	   <div class="field-name">{$oLanguage->getMessage("Name")}:</div>
        </td>
        <td>
            {html_options name="data[old_name]" options=$aName selected=$aData.old_name id="select_name_user" style="width:260px"}
        </td>
    </tr>
</table>
{literal}
 <script type="text/javascript">    
    $(document).ready(function() {
    	 $('#select_name_user').select2({
		    language: 'ru',
    		    minimumInputLength: 2,
    		    ajax: {
    		      url: "/?action=manager_get_user_select",
    		      dataType: 'json',
    		      data: function (term, page) {
    		        return {
    		          data: term
    		        };
    		      },
    		      processResults: function (data) {
    		            return {
    		                results: $.map(data, function (item) {
    		                    return {
    		                        text: item.name,
    		                        id: item.id
    		                    }
    		                })
    		            };
    		        }
    		    }
    		  });
    });	
 	</script>
{/literal}
{ include file="cart/cart_onepage_delivery.tpl" }
{ include file="cart/cart_onepage_payment.tpl" }