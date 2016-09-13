<?php

use EWallet\Middlewares\AuthMiddleware;
use EWallet\Reporter;

$app->group('/api/reports', function () {
    /**
     * Alarm
     */
    $this->post('/alarm', function ($request, $response) {
        $user = $this->session->get('user');
        $success = true;
        $message = '';
        try {
            $params = (object)$request->getParsedBody();
            $reporter = new Reporter($this);
            $reporter->alarm($user, $params);
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        return $response->withJson(['success' => $success, 'message' => $message]);
    });
    /**
     * Complain
     */
    $this->post('/complain', function ($request, $response) {
        $user = $this->session->get('user');
        $success = true;
        $message = '';
        try {
            $params = (object)$request->getParsedBody();
            $reporter = new Reporter($this);
            $reporter->complain($user, $params);
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        return $response->withJson(['success' => $success, 'message' => $message]);
    });
})->add(new AuthMiddleware($app->getContainer()));
