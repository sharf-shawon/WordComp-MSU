<?php

declare(strict_types=1);


use Jfcherng\Diff\DiffHelper;
use Jfcherng\Diff\Factory\RendererFactory;

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>jfcherng/php-diff - Examples</title>

        <!-- Prism -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs@1/themes/prism-okaidia.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs@1/plugins/line-numbers/prism-line-numbers.min.css" />

        <style type="text/css">
            html {
                font-size: 13px;
            }
            .token.coord {
                color: #6cf;
            }
            .token.diff.bold {
                color: #fb0;
                font-weight: normal;
            }

            <?= DiffHelper::getStyleSheet(); ?>
        </style>
    </head>
    <body>
        <a href="{{$url}}">Omni site</a>

        <?php
        echo $result;
        ?>

        <!-- Prism -->
        <script src="https://cdn.jsdelivr.net/npm/prismjs@1/prism.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/prismjs@1/components/prism-diff.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/prismjs@1/components/prism-json.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/prismjs@1/plugins/line-numbers/prism-line-numbers.min.js"></script>
    </body>
</html>
