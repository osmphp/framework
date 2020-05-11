<?php /* @var \Osm\Samples\Ui\Views\Colors $view */ ?>
<table class="__palette">
    <thead>
        <tr>
            @foreach ($view->foreground_colors as $foreground)
                <th>{{ $foreground->title }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($view->background_colors as $background)
            <tr class="{{$background->css}}">
                @foreach ($view->foreground_colors as $foreground)
                    @if (!$foreground->css)
                        <th class="__color">
                            {{ $background->title }}
                        </th>
                    @elseif (!$view->isApplicable($background, $foreground))
                        <td>N/A</td>
                    @else
                        <td class="__color {{$foreground->css}}">
                            OK
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>