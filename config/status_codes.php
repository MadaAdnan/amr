<?php

return [
    'success' => [
        'created'           => 201,
        'updated'           => 200,
        'ok'                => 200,
        'deleted'           => 204,
        'accepted'          => 202,
        'no_content'        => 204,
    ],

    'client_error' => [
        'bad_request'       => 400,
        'unauthorized'      => 401,
        'forbidden'         => 403,
        'not_found'         => 404,
        'unprocessable'     => 422,
    ],

    'server_error' => [
        'internal_error'    => 500,
        'not_implemented'   => 501,
        'bad_gateway'       => 502,
        'service_unavailable' => 503,
    ]
];