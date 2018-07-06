<?php

$dir = dirname(__FILE__) . DS . 'app' . DS . 'views' . DS;
define('BASE', '');
define('BOWER', 'public/bower_components/');
define('CSS', 'public/css/');
define('IMG', 'public/img/');
define('JS', 'public/js/');

$view_title = 'ADUV';
$add_js = $add_css = '';

include $dir . 'header.phtml';
include $dir . 'partials' . DS . '_incompatible.phtml';
include $dir . 'footer.phtml';