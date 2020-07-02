<?php
/* @var \Osm\Samples\Ui\Views\SampleViewUsingSnackBar $view */
use Osm\Ui\Buttons\Views\Button;
?>
<div id="{{$view->id_}}" class="sample-view-using-snackbar">
@include(Button::new(['alias' => 'normal', 'title' => osm_t("Show normal snack bar")]))
@include(Button::new(['alias' => 'modal', 'title' => osm_t("Show modal snack bar")]))
@include(Button::new(['alias' => 'exception', 'title' => osm_t("Show exception snack bar")]))
</div>
