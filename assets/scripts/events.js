/**
 * Created by frbos on 19/05/2017.
 */
function renderButtons(data) {
    return data;
}

$(document).ready(function(){
        $("#active-parties").dataTable({
        "ajax": "services?action=get_active_parties",

        "columns": [
            {"data": "date"},
            {"data": "address"},
            {"data": "theme"},
            {"data": "animators"},
            {"data": "id", "searchable": false, "orderable": false, "type": "html", "render": function(data){return renderButtons(data)}}
        ]
    });

    $("#passed-parties").dataTable({
        "ajax": "services?action=get_passed_parties",

        "columns": [
            {"data": "date"},
            {"data": "address"},
            {"data": "theme"},
            {"data": "animators"},
            {"data": "id", "searchable": false, "orderable": false, "type": "html", "render": function(data){return renderButtons(data)}}
        ]
    });

    $(".nav-tabs a").click(function(){
        $(this).tab('show');
    });
});