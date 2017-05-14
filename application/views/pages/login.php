<div class="container">

    <form class="form-signin">
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
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Errore:</strong><br> Nome utente o password errati.
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Entra</button>
    </form>

</div> <!-- /container -->