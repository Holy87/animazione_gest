<?php
/**
 * Created by PhpStorm.
 * User: frbos
 * Date: 15/05/2017
 * Time: 13:12
 */
MainView::push_script("<script>
$(document).ready(function(){
    $(\".nav-tabs a\").click(function(){
        $(this).tab('show');
    });
});
</script>");

$events = Party::get_all();
$active_events = [];
$passed_events = [];
/** @var Party $event */
foreach($events as $event)
{
    if($event->is_done())
        array_push($passed_events, $event);
    else
        array_push($active_events, $event);
}
usort($active_events, function($a, $b)
{
    return strcmp($a->date, $b->date);
});

usort($passed_events, function($a, $b)
{
    return strcmp($b->date, $a->date);
});


?>

<div class="container">
    <h1>Feste</h1>
    <?php if(count($events) == 0) {
        echo '
    <div class="alert alert-info" role="alert">
      <strong>Attenzione:</strong> Non risultano feste programmate.
        </div>';
    } ?>
    <a class="btn btn-primary" href="eventdetails"><i class="fa fa-plus" aria-hidden="true"></i>Nuova festa</a>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#menu1">Prossime</a></li>
        <li><a href="#menu2">Passate</a></li>
    </ul>

    <div class="tab-content">
        <div id="menu1" class="tab-pane fade in active">
            <h3>Feste in programma prossimamente</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Indirizzo</th>
                        <th>Animatori</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $content = '<tr>';
                    /** @var Party $event */
                    foreach($active_events as $event) {
                        /** @noinspection HtmlUnknownTarget */
                        $content.='
<tr>
    <td>'.$event->get_printable_date().'</td>
    <td>'.$event->address.'</td>
    <td>'.$event->get_animators_names().'</td>
    <td><a class="btn btn-primary" href="viewparty?partyid='.$event->party_id.'"><i class="fa fa-pencil" aria-hidden="true"></i> Vedi</a> </td>
</tr>';

                        }
                        echo $content;
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="menu2" class="tab-pane fade in">
            <h3>Feste passate</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Indirizzo</th>
                        <th>Animatori</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $content = '<tr>';
                    /** @var Party $event */
                    foreach($passed_events as $event) {
                        /** @noinspection HtmlUnknownTarget */
                        $content.='
<tr>
    <td>'.$event->get_printable_date().'</td>
    <td>'.$event->address.'</td>
    <td>'.$event->get_animators_names().'</td>
    <td><a class="btn btn-primary" href="viewparty?partyid='.$event->party_id.'"><i class="fa fa-pencil" aria-hidden="true"></i> Vedi</a> </td>
</tr>';

                    }
                    echo $content;
                    ?>
                    </tbody>
                </table>
            </div>
        </div>    </div>
</div>
