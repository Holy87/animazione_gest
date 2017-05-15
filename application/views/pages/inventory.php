<?php
/**
 * Created by PhpStorm.
 * User: frbos
 * Date: 16/05/2017
 * Time: 00:13
 */

$items = Item::get_all();
$user = User::getCurrent();

function print_table($items) {
    $rows = '';
    foreach($items as $item) {
        $rows .= print_row($item);
    }
    echo $rows;
}

/**
 * @param Item $item
 * @return string
 */
function print_row($item) {
    return '';
}

function edit_button($item) {

}

function delete_button($item) {

}
?>

<div class="container">
    <h1>Inventario</h1>
    <a class="btn btn-primary" href="eventdetails"><i class="fa fa-plus" aria-hidden="true"></i> Nuovo materiale</a>
    <div class="table-responsive">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <td>Nome</td>
                    <td>Quantità</td>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
