<?php
/* @var \Manadev\Framework\Views\View $view */
use Manadev\Samples\Ui\Views\SampleViewUsingSnackBar;
?>
@include(SampleViewUsingSnackBar::new(['alias' => 'sample']))
<footer>
    <a href="{{ m_url('GET /tests/') }}">{{ m_("Back To Test List") }}</a>
</footer>