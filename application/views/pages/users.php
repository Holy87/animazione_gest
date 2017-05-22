<div class="container">
    <h1>Gestione utenti</h1>
    <p>In questa pagina puoi gestire tutti gli utenti che possono accedere all'applicazione.</p>
</div>
<div class="container">
    <button type="button" onclick="createUser()" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Aggiungi utente</button>
    <div class="table-responsive">
        <table class="table" id="users-table">
            <thead>
            <tr>
                <td></td>
                <td>Nome</td>
                <td>Username</td>
                <td>Ruolo</td>
                <td>email</td>
                <td></td>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <div class="alert alert-info" id="alert-info" role="alert">
            <i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Caricamento in corso...
        </div>
        <input type="hidden" id="user-id" value="<?php echo User::getCurrent()->id ?>">
    </div>
</div>