<?php
/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 17/05/2017
 * Time: 07:45
 */
$user = User::getCurrent();

function check_disabled() {
    global $user;
    if ($user->can_create_users()) {
        // non fa niente
    } else {
        echo 'disabled';
    }
}
?>

<div class="container">
    <div class="jumbotron">
        <div class="row">
            <div class="col-md-4">
                <div class="circle-avatar" style="background-image:url(<?php echo $user->get_avatar_url(); ?>)">

                </div>
                <!--<img class="rounded-circle img-fluid img-profile" src="">-->
            </div>
            <div class="col-md-8">
                <h1 class="display-3" id="profile-name"><?php echo $user->friendly_name; ?></h1>
                <p>Panoramica del mio profilo</p>
            </div>
        </div>
    </div>
    <div class="container">
        <h2>Modifica il profilo</h2>
        <hr>
        <h3>Informazioni di base</h3>
        <form id="profileedit">
            <div class="form-group row">
                <div class="col-md-4">
                    <input type="hidden" id="user-id" name="id" value="<?php echo $user->id ?>">
                    <label for="user-name" class="form-control-label">Nome utente (per il login)</label>
                    <input id="user-name" type="text" class="form-control" value="<?php echo $user->name ?>" disabled>
                </div>
                <div class="col-md-6">
                    <label for="friendly-name" class="form-control-label">Nome mostrato</label>
                    <div class="input-group">
                        <input id="friendly-name" type="text" name="name" class="form-control" value="<?php echo $user->friendly_name ?>" <?php check_disabled() ?> required>
                        <span class="input-group-btn">
                            <button class="btn btn-primary" id="namebtn" type="submit" <?php check_disabled() ?>>Salva</button>
                        </span>
                    </div>
                </div>
            </div>
        </form>
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <h3>Indirizzo email</h3>
                <form id="mailedit">
                    <input type="hidden" name="id" value="<?php echo $user->id ?>">
                    <div class="form-group" id="mail-input">
                        <label for="user-mail" class="form-control-label">Email</label>
                        <div class="input-group">
                            <input id="user-mail" type="email" name="user-mail" class="form-control" value="<?php echo $user->mail ?>" required>
                            <span class="input-group-btn">
                                <button class="btn btn-primary" id="mailbtn" type="submit">Salva</button>
                            </span>
                        </div>
                        <div class="form-control-feedback" id="mail-state"></div>
                    </div>
                </form>
                <h3>Immagine del profilo</h3>
                <form id="imageedit" enctype="multipart/form-data" method="post" action="services?action=photo-upload">
                    <div class="form-group">
                        <input type="hidden" name="id" value="<?php echo $user->id ?>">
                        <label for="profile-image" class="form-control-label">Carica la tua foto</label>
                        <div class="input-group">
                            <input type="file" accept=".gif,.jpg,.jpeg,.png" id="profile-image" name="fileToUpload" class="file" required>
                            <span class="input-group-btn">
                                <button class="btn btn-primary" id="imgbtn" type="submit">Carica</button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-6">
                <h3>Password</h3>
                <form id="passedit">
                    <input type="hidden" name="id" value="<?php echo $user->id ?>">
                    <div class="form-group" id="password-div">
                        <label for="user-password" class="form-control-label">Vecchia password</label>
                        <input id="user-password" type="password" placeholder="" class="form-control" name="old-password" required>
                        <div class="form-control-feedback" id="pwd-state"></div>
                    </div>
                    <div class="form-group" id="password-check">
                        <label for="new-password" class="form-control-label">Nuova password</label>
                        <input type="password" name="new-password" id="new-password" class="form-control" required>
                        <label for="check-password" class="form-control-label">Conferma password</label>
                        <input type="password" id="check-password" class="form-control" required>
                        <div class="form-control-feedback" id="feedback-pcheck"></div>
                    </div>
                    <button type="submit" id="pwdsave" class="btn btn-primary">Modifica password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modal-message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>