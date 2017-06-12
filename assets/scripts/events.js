/**
 * Created by frbos on 19/05/2017.
 */
function renderButtons(id) {
    return editButton(id);
}

function editButton(id) {
    return '<button class="btn btn-primary btn-sm" data-toggle="tooltip" onclick="editParty('+id+')" data-placement="top" title="Dettagli festa"><i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></button>';
}

function editParty(id) {
    window.location.href = 'eventdetails?id='+id;
}

$(document).ready(function(){
    $(".nav-tabs a").click(function(){
        $(this).tab('show');
    });

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
    $("#active").addClass('show');

});