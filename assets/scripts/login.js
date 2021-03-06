/**
 * Created by frbos on 22/05/2017.
 */
$(document).ready(function() {
    $(".form-control").on("focus", function() {$(".alert").prop("hidden", true)});
    $("#loginform").submit(function(e) {
        var btn = $(".btn-primary");
        btn.attr('disabled', 'disabled');
        btn.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Invio in corso...');
        //var user = $("#inputUser").val();
        //var password = $("#inputPassword").val();
        $.ajax({
            type: "POST",
            url: "services?action=login",
            data: $("#loginform").serialize(),
            dataType: "json",
            success: function(response) {
                if(response.ok) {
                    $(".btn-primary").html("Login confermato");
                    var action = $("#old_url").val();
                    if(action !== null && action.length > 0)
                        window.location.href = action;
                    else
                        window.location.href = "home";
                } else {
                    $(".alert").prop("hidden", false);
                    var btn = $(".btn-primary");
                    btn.html("Entra");
                    btn.removeAttr('disabled')
                }
            }
        });
        $("#errordiv").prop("hidden", false);
        e.preventDefault();
    });
});