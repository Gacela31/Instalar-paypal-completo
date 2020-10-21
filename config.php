<?php

require 'paypal/autoload.php';

define('URL_SITIO', 'http://localhost/paypal/');

$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        //ClienteId
        'AafC0UbbDXLc8VewwifTwLIJ0jWuB2wH_RsJY-t1xuFdW-_jl03VKd0Kt-rwvUS2tLeq4r7alu901fIv',
        //Secret
        'ENsHPAE3I9GwaxQNG1edizw_scWs5ag1vKmKD8EXrhdr4uGNgPQ9Ld6uzx-trRS7ijcoFbXwgBTQ634w'
    )
);

