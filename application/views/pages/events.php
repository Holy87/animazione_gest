<?php
/**
 * Created by PhpStorm.
 * User: frbos
 * Date: 15/05/2017
 * Time: 13:12
 */

?>

<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <h1>Feste</h1>
        </div>
            <div class="col-lg-4">
        </div>
        <a class="btn btn-primary sm" href="eventdetails"><i class="fa fa-plus" aria-hidden="true"></i>Nuova festa</a>
    </div>
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#active">Prossime</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#passed">Passate</a></li>
    </ul>

    <div class="tab-content">
        <div id="active" class="tab-pane fade in active">
            <table id="active-parties" class="table table-striped table-bordered" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Data</th>
                    <th>Indirizzo</th>
                    <th>Tema</th>
                    <th>Animatori</th>
                    <th>Azioni</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div id="passed" class="tab-pane fade">
            <table id="passed-parties" class="table table-striped table-bordered" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Data</th>
                    <th>Indirizzo</th>
                    <th>Tema</th>
                    <th>Animatori</th>
                    <th>Azioni</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
