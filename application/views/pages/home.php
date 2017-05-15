<!-- Qui ci va il contenuto della Home Page. -->

<div class="container">
    <div class="jumbotron">
        <h1 class="display-3">Ciao, <?php echo User::getCurrent()->friendly_name ?></h1>
        <p class="lead">Programma gestionale per l'Animazione Senza Pensieri</p>
        <hr class="my-4">
        <p>Questo programma è ancora in sviluppo, ma puoi utilizzare le funzioni principali. Puoi visualizzare una guida veloce cliccando in basso.</p>
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="#" role="button">Informazioni</a>
        </p>
    </div>
</div>