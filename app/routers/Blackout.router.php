<?php

use EWallet\Middlewares\AuthMiddleware;

$app->group('/api/blackout', function () {
    /**
     * Get Blackout statistics
     */
    $this->post('/statistics', function ($request, $response) {
        try {
            // TODO: брать реальные данные
            $data = [
                'sudden' => 0,
                'planned' => 2,
                'nearest' => [
                    'name' => 'Офис',
                    'city' => 'ОДЕССА',
                    'street' => 'ГАГАРИНА ПРОСПЕКТ',
                    'number' => '23/2',
                    'dateStart' => '20160422',
                    'timeStart' => '90000',
                    'dateEnd' => '20160422',
                    'timeEnd' => '170000',
                ],
                'order' => [
                    'city' => 'ОДЕССА',
                    'street' => 'ГАГАРИНА ПРОСПЕКТ',
                    'number' => '23/2',
                    'regNumber' => '35363738',
                    'total' => 194.50,
                    'due' => '20160511',
                ]
            ];
            return $response->withJson(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return $response->withJson(['success' => false, 'message' => $e->getMessage()]);
        }
    });
    /**
     * Get Blackout city
     */
    $this->post('/city', function ($request, $response) {
        $user = $this->session->get('user');
        try {
            $params = (object)$request->getParsedBody();
            $data = $this->mobikassa->offGetCity(
                $user,
                ['city' => $params->city, 'session' => $user->session]
            );
            return $response->withJson(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return $response->withJson(['success' => false, 'message' => $e->getMessage()]);
        }
    });
    /**
     * Get Blackout street
     */
    $this->post('/street', function ($request, $response) {
        $user = $this->session->get('user');
        try {
            $params = (object)$request->getParsedBody();
            $data = $this->mobikassa->offGetStreet(
                $user,
                ['city' => $params->city, 'session' => $user->session]
            );
            return $response->withJson(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return $response->withJson(['success' => false, 'message' => $e->getMessage()]);
        }
    });
    /**
     * Get Blackout address
     */
    $this->post('/address', function ($request, $response) {
        $user = $this->session->get('user');
        try {
            $params = (object)$request->getParsedBody();
            $data = $this->mobikassa->offGetAddress(
                $user,
                [
                    'date' => date('d.m.Y', strtotime($params->date)),
                    'city' => $params->city,
                    'street' => $params->street,
                    'number' => $params->number,
                    'session' => $user->session
                ]
            );
            return $response->withJson(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return $response->withJson(['success' => false, 'message' => $e->getMessage()]);
        }
    });
})->add(new AuthMiddleware($app->getContainer()));
