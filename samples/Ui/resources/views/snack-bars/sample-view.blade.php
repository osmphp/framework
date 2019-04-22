<?php
/* @var \Manadev\Samples\Ui\Views\SampleViewUsingSnackBar $view */
use Manadev\Ui\Buttons\Views\Button;
?>
<div id="{{$view->id_}}">
@include(Button::new(['alias' => 'normal', 'title' => m_("Show normal snack bar")]))
@include(Button::new(['alias' => 'modal', 'title' => m_("Show modal snack bar")]))
@include(Button::new(['alias' => 'exception', 'title' => m_("Show exception snack bar")]))
</div>
{!! $view->view_model_script !!}