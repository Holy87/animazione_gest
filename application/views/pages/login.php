<?php /** @noinspection JSUnusedGlobalSymbols */
$script = '
<script type="text/javascript">
    $(document).ready(function() {
        $("#loginform").submit(function(e) {
            $(".btn").attr(\'disabled\', \'disabled\');
			$(".btn").html("<i class=\'fa fa-circle-o-notch fa-spin\'></i> Invio in corso...");
            //var user = $("#inputUser").val();
            //var password = $("#inputPassword").val();
            $.ajax({
                type: "POST",
                url: "services?action=login",
                data: $("#loginform").serialize(),
                dataType: "json",
                success: function(response) {
                    if(response.ok) {
                        $(".btn").html("Login confermato");
                        window.location.href = "home";
                    } else {
                        $(".alert").prop("hidden", false);
                        $(".btn").html("Entra");
                        $(".btn").removeAttr(\'disabled\')
                    }
                }
            });
            $("#errordiv").prop("hidden", false);
            e.preventDefault();
        });
    });
</script>';
MainView::push_script($script);
?>

<!--suppress JSUnusedGlobalSymbols -->
<div class="container">

    <form id="loginform" class="form-signin">
        <br>
        <h2 class="form-signin-heading">Effettua il Login</h2>
        <label for="inputUser" class="sr-only">Nome Utente</label>
        <input id="inputUser" name="user" class="form-control" placeholder="Nome utente" required="" autofocus="" type="text">
        <label for="inputPassword" class="sr-only">Password</label>
        <input id="inputPassword" name="password" class="form-control" placeholder="Password" required="" type="password">
        <div class="checkbox">
            <label>
                <input value="remember-me" type="checkbox"> Ricordami
            </label>
        </div>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" hidden>
            <button id="sendb" type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Errore:</strong><br> Nome utente o password errati.
        </div>
        <button id="sendb" class="btn btn-lg btn-primary btn-block" type="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Verifica...">Entra</button>
    </form>

</div> <!-- /container -->