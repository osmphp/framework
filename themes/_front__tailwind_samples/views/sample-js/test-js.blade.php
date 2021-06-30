<?php
/* @var string $test_url */
/* @var stdClass $options */
?>
<!doctype html>
<html lang="en">
<head>
    <title>JS Tests</title>
    <link rel="stylesheet" href="{{ \Osm\asset('styles.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/mocha/mocha.css">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
</head>
<body>
    <div id="mocha"></div>

    <script src="https://unpkg.com/chai/chai.js"></script>
    <script src="https://unpkg.com/mocha/mocha.js"></script>

    <script class="mocha-init">
        mocha.setup({!! json_encode($options, JSON_PRETTY_PRINT) !!});
    </script>

    <script src="{{ \Osm\asset('scripts.js') }}"></script>
    <script src="{{ $test_url }}"></script>

    <script class="mocha-exec">
        let mochaRunner = mocha.run();
    </script>
</body>
</html>
