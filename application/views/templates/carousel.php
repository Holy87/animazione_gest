<?php
require_once ABS_PATH.'application/models/CarouselElement.php';
require_once ABS_PATH.'application/models/Carousel.php';

$elements = Carousel::getElements();

function carousel_list() {
    global $elements;
    $code = '';
    $i = 0;
    /** @var CarouselElement $element */
    foreach($elements as $element)
    {
        $code.='<li data-target="#myCarousel" data-slide-to="'.$i.'" class="'.$element->get_class().'"></li>';
    }
    echo $code;
}

function fill_carousel() {
    $code = '';
    global $elements;
    /** @var CarouselElement $element */
    foreach($elements as $element)
    {
        $code.=
            '<div class="carousel-item'.$element->get_class().'">
                <img class="d-block img-fluid" src="'.$element->getImageUrl().'" alt="'.$element->getTitle().'">
                <div class="container">
                    <div class="carousel-caption d-none d-md-block text-left">
                        <h1>'.$element->getTitle().'</h1>
                        <p>'.$element->getDescription().'</p>
                        <p><a class="btn btn-lg btn-primary" href="'.$element->getUrl().'" role="button">'.$element->getButton().'</a> </p>
                    </div>
                </div>
            </div>';
    }
    echo $code;
}
?>

<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <?php carousel_list(); ?>
    </ol>
    <div class="carousel-inner" role="listbox">
        <?php fill_carousel(); ?>
    </div>
    <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>