<?php
    require 'Markdown.php';
    print_r(Markdown::defaultTransform(
        '## dd'
    ));