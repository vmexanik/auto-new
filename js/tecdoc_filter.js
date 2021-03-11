$("select[name='data[rubric]']").select2();

function select_rubric(sSelectedIndex) {
    xajax_process_browse_url('/?action=tecdoc_filter&subaction=select_rubric&data[selected_index]='+sSelectedIndex);
}

$('.tree_crit.block_param span').click(function (e) {
    $(this).next('.tree_crit_param').toggleClass('collasped');
});

function delete_parameter(el)
{
    $(el).parent('.row_parameter').remove();
}

function add_parameter(el){
    $count=Number($(el).parent(".row_parameter_all").index(".row_parameter_all"));
    $count_param=Number($(el).parent().find(".row_parameter").last().attr("row_number"))+1;
    if (isNaN($count_param)) $count_param=0;
    xajax_process_browse_url('/?action=tecdoc_filter_get_parameter_row&number_filter='+$count+'&number_param='+$count_param);
}

function add_filter(el){
    $count=Number($(".row_parameter_all").length);
    xajax_process_browse_url('/?action=tecdoc_filter_get_filter&number_filter='+$count);
}

function unbind_filter(index) {
    $selected_rubric=$("[name='data[rubric]']").val();
    xajax_process_browse_url('/?action=tecdoc_filter&subaction=unbind_filter&id_filter='+index+'&selected_rubric='+$selected_rubric);
}

function edit_filter(index) {
    $selected_rubric=$("[name='data[rubric]']").val();
    xajax_process_browse_url('/?action=tecdoc_filter&subaction=edit_filter&id_filter='+index+'&selected_rubric='+$selected_rubric);
}

function delete_filter(index) {
    $selected_rubric=$("[name='data[rubric]']").val();
    xajax_process_browse_url('/?action=tecdoc_filter&subaction=delete_filter&id_filter='+index+'&selected_rubric='+$selected_rubric);
}

function delete_unsave_filter(el) {
    $(el).parent('.filter').remove();
}

function overlay() {
    $('.overlay').toggleClass('display');
}

setTimeout(function() {
    $(".notice_p").slideUp('slow');
    link = window.location.toString();
    link = link.split('?amessage[mt_notice]=')[0];
    window.history.pushState("object or string", "Title", link);
},2000);