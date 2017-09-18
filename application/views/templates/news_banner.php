<?php
$user = User::getCurrent();
if($user->id > 0 and $user->get_last_version() < VERSION) {
    echo "<div class='container deletable'>
<div class=\"alert alert-info alert-dismissible fade show\" role=\"alert\">
    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
        <span aria-hidden=\"true\">&times;</span>
    </button>
    <strong>Aggiornamento:</strong> Dall'ultima visita, il programma è stato aggiornato alla versione più recente. <a href=\"changelog\">Vedi le novità</a>
</div>
</div>";
}