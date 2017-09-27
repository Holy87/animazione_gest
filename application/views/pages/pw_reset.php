<?php
/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 22/09/2017
 * Time: 11:55
 */
require_once ABS_PATH.'/application/controllers/account_controller.php';

if(isset($_GET['token'])) {
    $token = $_GET['token'];
    $token_valid = AccountController::token_is_valid($token);
}  else
    $token_valid = false;
?>

<div class="container">
    <div class="row justify-content-md-center" <?php if(!$token_valid){echo 'hidden';} ?>>
        <div class="col-md-6">
            <h3>Reimposta la password</h3>
            <p>Inserisci una nuova password per accedere al gestionale.</p>
            <form id="reset">
                <div class="form-group id=password_check">
                    <input type="password" class="form-control" placeholder="Nuova password" name="new-password" id="new-password" required>
                    <input type="password" class="form-control" placeholder="Ripeti password" name="check-password" id="check-password" required>
                    <div class="form-control-feedback" id="feedback-pcheck"></div>
                    <input type="hidden" name="token" value="<?php echo $token ?>">
                    <button type="submit" class="btn btn-primary" id="resetbtn">Cambia</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-md-center" <?php if($token_valid){echo 'hidden';} ?>>
        <div class="col-md-6">
            <div class="alert alert-danger" role="alert">
                <h4>Errore</h4>
                <p>Il link a cui hai avuto accesso non è valido. Se hai bisogno di ripristinare la password, per favore, ripeti di nuovo la <a href="pw_recovery">procedura di recupero password</a>.</p>
            </div>
        </div>
    </div>
</div>
