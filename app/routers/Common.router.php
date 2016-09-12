<?php

/**
 * Home Page
 */
$app->get('/', function ($request, $response) {
    return $response->write('Welcome to EWEP.');
});

$app->post('/api/test', function ($request, $response) {
    $requestBody = $request->getParsedBody();
    return $response->withJson($requestBody);
});
