<?php
/**
 * Created by PhpStorm.
 * User: Gold Service
 * Date: 04/08/2017
 * Time: 10:06
 */

$party = Party::get_party($_GET['party']);
if($party == null) {
    header('location: error');
}

/**
 * @param Party $party
 */
function print_users($party) {
    $outp = '';
    /** @var User $animator */
    foreach($party->get_animators() as $animator) {
        $disabled = '';
        if($animator->phone == null) {
            $disabled = 'disabled';
        }
        $outp.= '<tr>
            <td>
                <div class="avatar-little"> <div class="circle-avatar" style="background-image:url('.$animator->get_avatar_url().')"></div></div>
            </td>
            <td>'.$animator->friendly_name.'</td>
            <td><a class="btn btn-primary" href="tel:'.$animator->phone.'" '.$disabled.'><i class="fa fa-phone" aria-hidden="true"></i></a> </td>
         </tr>';
    }
    echo $outp;
}

/**
 * @param Party $party
 */
function print_items($party) {
    $outp = '';
    /** @var Item $item */
    foreach($party->get_all_items() as $item) {
        $outp.= '<tr>
            <td>'.$item->name.'</td>
            <td>'.$item->floor.'</td>
            <td>'.$party->get_item_number($item->id).'</td>
        </tr>';
    }
    echo $outp;
}

/**
 * @param Party $party
 */
function google_maps_url($party) {
    echo 'https://www.google.it/maps/place/'.str_replace(' ', '+', $party->address);
}
?>

<div class="container">
    <div class="jumbotron">
        <h1 class="display-2">Evento</h1>
        <p class="lead"><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo $party->get_printable_date() ?></p>
        <p class="lead"><i class="fa fa-clock-o" aria-hidden="true"></i> Ora: <?php echo $party->get_printable_hour().' ('.$party->hours.' ore)' ?></p>
        <hr class="my-4">
        <p>Mettiti in contatto con <?php echo $party->customer ?></p>
        <?php
        if(User::getCurrent()->can_edit_events() and $party->phone != null) {
            echo '<p>Telefono: <a href="tel:'.$party->phone.'">'.$party->phone.'</a></p>';
        }
        ?>
        <a href="<?php google_maps_url($party) ?>"><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $party->address ?></a>
    </div>
    <?php
    if($party->get_theme() != null) {
        echo "<h2>Festa a tema ".$party->get_theme()->name."</h2>";
    }
    ?>
    <ul>
        <?php
            if($party->guest_of_honor != null)
                echo "<li>Festeggiato: <strong>".$party->guest_of_honor."</strong></li>";
            if($party->guest_age != null)
                echo "<li>Età festeggiato: ".$party->guest_age." anni</li>";
            if($party->child_number != null)
                echo "<li>Numero bambini: ".$party->child_number."</li>";
            if($party->generic_age != null)
                echo "<li>Età media: ".$party->generic_age." anni</li>";
        ?>
    </ul>
    <h3>Prezzo della festa <strong><?php echo $party->price ?> €</strong></h3>
    <?php
    if($party->fuel != null and $party->fuel > 0)
        echo '<p>(+ rimborso carburante di € '.$party->fuel.')</p>';
    if($party->notes != null and strlen($party->notes) > 0) {
        echo '<p><code>Note: '.$party->notes.'</code></p>';
    }
    ?>
    <p>Partecipanti</p>
    <table class="table table-responsive">
        <?php print_users($party) ?>
    </table>
    <hr>
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#items" aria-expanded="false" aria-controls="items">
        Oggetti da portare
    </button>
    <div class="collapse" id="items">
        <div class="card card-block">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>Oggetto</th>
                        <th>Posizione</th>
                        <th>Quantità</th>
                    </tr>
                </thead>
                <tbody>
                    <?php print_items($party) ?>
                </tbody>
            </table>
        </div>
    </div>
    <p>* Creato da <?php echo $party->get_creator()->friendly_name ?></p>
</div>
