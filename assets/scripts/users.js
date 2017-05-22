/**
 * Created by frbos on 19/05/2017.
 */
function add_row(item) {
    var table = document.getElementById("users-table");
    var body = table.getElementsByTagName("tbody")[0];
    //var tr = document.createElement("tr");
    var tr = body.insertRow();
    tr.id = item.id;
    console.log(item.name);
    var userId = parseInt(document.getElementById("user-id").value);
    tr.insertCell(0).innerHTML = '<div class="avatar-little"> <div class="circle-avatar" style="background-image:url('+item.avatar+')"></div></div>';
    tr.insertCell(1).innerHTML = item.name;
    tr.insertCell(2).innerHTML = item.nickname;
    tr.insertCell(3).innerHTML = item.role;
    tr.insertCell(4).innerHTML = '<a href="mailto:'+item.mail+'">'+item.mail+'</a>';
    tr.insertCell(5).innerHTML = editButton(item.id, userId);
}

function createUser() {
    window.location.href = "account?mode=new";
}

function editUser(userId) {
    window.location.href = "account?user_id="+userId;
}

function editButton(id, userId) {
    return '<button class="btn btn-primary" data-toggle="tooltip" data-placement="top" onclick="editUser('+id+')" title="Modifica utente" data-item="'+id+'" '+buttonDisabled(id, userId)+'>' +
        '<i class="fa fa-pencil" aria-hidden="true"></i>' +
        '</button>';
}

function buttonDisabled(id, userId) {
    //noinspection EqualityComparisonWithCoercionJS
    if(id == userId)
        return 'disabled';
    else
        return '';
}

$(document).ready(function() {
    console.log("test");
    $.ajax({
        type: "POST",
        url: "services?action=get_users",
        dataType: "json",
        success: function (response) {
            $("#alert-info").hide();
            response.forEach(add_row);
        }
    });
});