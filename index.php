<?php
$f3 = require('vendor/bcosca/fatfree/lib/base.php');

//main page
$f3->route('GET /',
    function() {
        include 'main.php';
    }
);

// Get the product lists
$f3->route('GET /example/',
    function() {
        include 'example1.php';
    }
);

//add a new product to all stores
$f3->route('GET|POST /new',
    function() {
        include 'newproducts.php';
    }
);

//update the stock of a given product
$f3->route('GET|POST /products/*',
    function() {
        include 'update.php';
    }
);

$f3->route('GET /brew/@count',
    function($f3,$params) {
        echo $params['count'].' bottles of beer on the wall.';
    }
);

$f3->run();