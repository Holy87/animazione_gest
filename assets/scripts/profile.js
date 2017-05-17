/**
 * Created by Gold Service on 17/05/2017.
 */
function checkMailAlreadyUsed() {
    $.ajax({
        type: "POST",
        url: "services?action=check_mail",
        data: $("#mailedit").serialize(),
        dataType: "json",
        success: function(response) {
            if(response.ok) {

            }
        }
    })
}

$(document).ready(function() {
    $("#user-mail").on("blur", checkMailAlreadyUsed);
});