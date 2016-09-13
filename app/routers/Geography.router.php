<?php

use EWallet\Middlewares\AuthMiddleware;

$app->group('/api/geography', function () {
    /**
     * Get Region
     */
    $this->post('/region', function ($request, $response) {
        $user = $this->session->get('user');
        try {
            $params = (object)$request->getParsedBody();
            $data = $this->mobikassa->getRegion(
                $user,
                ['region' => $params->region, 'session' => $user->session]
            );
            return $response->withJson(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return $response->withJson(['success' => false, 'message' => $e->getMessage()]);
        }
    });
    /**
     * Get Street
     */
    $this->post('/street', function ($request, $response) {
        $user = $this->session->get('user');
        try {
            $params = (object)$request->getParsedBody();
            $data = $this->mobikassa->getStreet(
                $user,
                ['region' => $params->region, 'street' => $params->street, 'session' => $user->session]
            );
            return $response->withJson(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return $response->withJson(['success' => false, 'message' => $e->getMessage()]);
        }
    });
})->add(new AuthMiddleware($app->getContainer()));
