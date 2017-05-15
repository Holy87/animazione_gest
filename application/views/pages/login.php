<!--suppress JSUnusedGlobalSymbols -->
<div class="container">

    <form id="loginform" class="form-signin" method="post">
        <br>
        <h2 class="form-signin-heading">Effettua il Login</h2>
        <label for="inputUser" class="sr-only">Nome Utente</label>
        <input id="inputUser" class="form-control" placeholder="Nome utente" required="" autofocus="" type="text">
        <label for="inputPassword" class="sr-only">Password</label>
        <input id="inputPassword" class="form-control" placeholder="Password" required="" type="password">
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
        <button class="btn btn-lg btn-primary btn-block" type="submit">Entra</button>
    </form>

</div> <!-- /container -->

<script type="text/javascript">
    $(document).ready(function() {
        alert("ok");
        $("#loginform").submit(function(e) {
            $("#sendb").prop({"disabled":true, "value":"<span class=\"glyphicon glyphicon-refresh glyphicon-refresh-animate\">Attendere..."});
            //var user = $("#inputUser").val();
            //var password = $("#inputPassword").val();
            $.ajax({
                type: "POST",
                url: "services?action=login",
                data: $("#loginform").serialize(),
                dataType: "html",
                success: function(response) {
                    if(response.ok) {
                        $("#sendb").prop("value","Messaggio inviato");
                    } else {
                        $("#sendb").prop({"value":"Entra","disabled":false});
                        $("#errordiv").prop("hidden", false);
                    }
                }
            });
            e.preventDefault();
        });
    });
</script>