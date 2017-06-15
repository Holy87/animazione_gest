<?php
/**
 * Created by PhpStorm.
 * User: frbos
 * Date: 09/06/2017
 * Time: 00:44
 */

$party = null;
if(isset($_GET['id']))
    $party = Party::get_party($_POST['id']);
else
    $party = Party::get_party(0);

/**
 * @param Party $party
 */
function hidden_message($party) {
    if($party->party_id != 0)
        echo 'hidden';
}

/**
 * @param Party $party
 */
function hidden_otion($party) {
    if($party->party_id == 0)
        echo 'hidden';
}


?>

<div class="container">
    <h1>Dettagli festa</h1>
    <div class="row">
        <div class="col-md-6">
            <form id="edit-form">

            </form>
        </div>
        <div class="col-md-6">
            <div class="alert alert-info" role="alert" <?php hidden_message($party) ?>>
                <h4 class="alert-heading"><i class="fa fa-info-circle" aria-hidden="true"></i> Informazioni</h4>
                <hr>
                <p>Nella creazione della festa non puoi inserire oggetti e animatori.</p>
                <p class="mb-0">Crea prima la festa, quindi inserisci gli oggetti.</p>
            </div>
            <div class="container" <?php hidden_otion($party) ?>>
                <h5>Animatori</h5>
                <div class="form-group">
                    <label for="add-user">Aggiungi oggetto</label>
                    <select id="add-user" class="form-control" name="user-id" required>
                        <!-- JSON users -->
                    </select>
                    <button type="submit" id="add-btn" class="btn btn-primary">Aggiungi</button>
                </div>
            </div>
        </div>
    </div>
</div>
