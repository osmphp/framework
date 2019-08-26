<?php
/* @var \Osm\Framework\Views\View $view */
use Osm\Samples\Ui\Views\SampleViewUsingSnackBar;
?>
@include(SampleViewUsingSnackBar::new(['alias' => 'sample']))
<footer>
    <a href="{{ osm_url('GET /tests/') }}">{{ osm_t("Back To Test List") }}</a>
</footer>