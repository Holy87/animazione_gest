<?php
/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 22/05/2017
 * Time: 08:05
 */

function buttonDisabled() {
    global $user;
    if($user->can_edit_events())
        return '';
    else
        return 'disabled';
}

?>

<br>
<div class="container">
    <h1>Temi feste</h1>
    <div class="container"><button type="button" class="btn btn-primary btn-sm"" data-toggle="modal" data-target="#editModal" data-item="0" <?php echo buttonDisabled(); ?>><i class="fa fa-plus" aria-hidden="true"></i> Nuovo tema</button></div>
    <br>
    <div class="table-responsive">
        <input type="hidden" value="<?php buttonDisabled() ?>" id="btn-disabled">
        <table id="themes" class="table table-striped table-bordered" width="100%" cellspacing="0">
            <thead>
            <tr>
                <th>Tema</th>
                <th>Descrizione</th>
                <th>Prezzo</th>
                <th>Articoli</th>
                <th>Azioni</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>