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
        <div class="alert alert-danger fade show" role="alert" hidden>
            <!--<button id="sendb" type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>-->
            <strong>Errore:</strong><br> Nome utente o password errati.
        </div>
        <button id="sendb" class="btn btn-lg btn-primary btn-block" type="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Verifica...">Entra</button>
    </form>
    <!-- Per precaricare la ruota di attesa -->
    <p hidden><i class="fa fa-circle-o-notch fa-spin fa-fw"></i></p>
</div> <!-- /container -->