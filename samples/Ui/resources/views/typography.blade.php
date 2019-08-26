<?php
/* @var \Osm\Framework\Views\View $view */
$text = <<<EOT
Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit
anim id est laborum
EOT;
?>
<h1>{{ osm_t("Heading :n", ['n' => 1]) }}</h1>
<p>{{ $text }}</p>
<p>{{ $text }}</p>
<p>{{ $text }}</p>
<h2>{{ osm_t("Heading :n", ['n' => 2]) }}</h2>
<p>{{ $text }}</p>
<h3>{{ osm_t("Heading :n", ['n' => 3]) }}</h3>
<p>{{ $text }}</p>
<h4>{{ osm_t("Heading :n", ['n' => 4]) }}</h4>
<p>{{ $text }}</p>
<h5>{{ osm_t("Heading :n", ['n' => 5]) }}</h5>
<p>{{ $text }}</p>
<h6>{{ osm_t("Heading :n", ['n' => 6]) }}</h6>
<p>{{ $text }}</p>
<footer>
    <a href="{{ osm_url('GET /tests/') }}">{{ osm_t("Back To Test List") }}</a>
</footer>