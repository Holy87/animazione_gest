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
    $party = new Party(0, null, null, null, null, null, null, 0);

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
            <div class="container">

            </div>
        </div>
    </div>
</div>
