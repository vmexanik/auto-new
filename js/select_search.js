//$(document).ready(function() {
//    $(".select_search").searchable({
//    maxListSize: 50,
//    maxMultiMatch: 25,
//    wildcards: true,
//    ignoreCase: true,
//    latency: 10,
//    warnNoMatch: 'no matches...',
//    zIndex: 'auto'
//    });
//});


$(document).ready(function() {
 $('.select_name_user').select2(
//		 {
//language: 'ru',
//minimumInputLength: 2,
//ajax: {
//  url: "/?action=manager_get_user_select",
//  dataType: 'json',
//	      data: function (term, page) {
//	        return {
//	          data: term
//	        };
//	      },
//	      processResults: function (data) {
//	            return {
//	                results: $.map(data, function (item) {
//	                    return {
//	                        text: item.name,
//	                        id: item.id
//	                    }
//	                })
//	            };
//	        }
//	    }
//	  }
		 );
 $('.select_name_manager').select2();
});					