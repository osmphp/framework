<?php
/* @var \Osm\Framework\Views\View $view */
?>
<h1>{{ osm_t("Containers") }}</h1>
<h2>{{ osm_t("Page Sections") }}</h2>
<p>{{ osm_t("Centered content, limited width, background with full width, asides") }}</p>
<div class="section -primary">
    <div>Section 1</div>
    <div>Section 1</div>
    <div>Section 1</div>
</div>
<div class="section -narrow -primary -light">
    <div>Section 2</div>
</div>
<div class="section">
    <div>Section 3</div>
</div>
<div class="section -narrow">
    <div>Section 4</div>
</div>
<div class="section">
    <div>Section 5</div>
</div>
<h2>{{ osm_t("Form") }}</h2>
<p>{{ osm_t("One column, some field may have 2 columns") }}</p>
<div class="t-container -form">
    <div>Field 1</div>
    <div>Field 2</div>
    <div>Field 3</div>
    <div>Field 4</div>
    <div>Field 5</div>
</div>
<h2>{{ osm_t("Product Grid") }}</h2>
<p>{{ osm_t("All items of specified width") }}</p>
<div class="t-container -product-grid">
    <div>Product 1</div>
    <div>Product 2</div>
    <div>Product 3</div>
    <div>Product 4</div>
    <div>Product 5</div>
    <div>Product 6</div>
    <div>Product 7</div>
    <div>Product 8</div>
    <div>Product 9</div>
</div>
<hr>
<footer>
    <a href="{{ osm_url('GET /tests/') }}">{{ osm_t("Back To Test List") }}</a>
</footer>