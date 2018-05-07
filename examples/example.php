<?php

use vxm\examples\gatewayclients\Gateway;

$responseData = (new Gateway([
    'client' => [
        'username' => 'vxm'
    ]
]))->request('refund', ['amount' => 1000]);

if ($responseData->isOk) {
    print_r($responseData->get());
}