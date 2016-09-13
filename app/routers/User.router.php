<?php

use EWallet\Entities\User;

/**
 * Login
 */
$app->post('/api/login', function ($request, $response) {
    $user = new User($this);
    $success = true;
    $message = '';
    try {
        $params = (object) $request->getParsedBody();
        $data = $user->login($params);
        $this->session->set('user', $data);
    } catch (\Exception $e) {
        $success = false;
        $message = $e->getMessage();
    }
    return $response->withJson(['success'=>$success, 'message'=>$message]);
})->setName('login');

/**
 * Create user
 */
$app->post('/api/user', function ($request, $response) {
    $user = new User($this);
    $success = true;
    $message = '';
    try {
        $user->register((object) $request->getParsedBody());
    } catch (\Exception $e) {
        $success = false;
        $message = $e->getMessage();
    }
    return $response->withJson(['success'=>$success, 'message'=>$message]);
});

/**
 * Create test local user
 */
$app->post('/api/testuser', function ($request, $response) {
    $user = new User($this);
    $success = true;
    $message = '';
    try {
        $data = $user->testRegister((object) $request->getParsedBody());
        $data->password = 'test';
        $data = $user->login($data);
        $this->session->set('user', $data);
    } catch (\Exception $e) {
        $success = false;
        $message = $e->getMessage();
    }
    return $response->withJson(['success'=>$success, 'message'=>$message]);
});

