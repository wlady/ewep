<?php

use EWallet\Middlewares\AuthMiddleware;
use EWallet\Entities\User;
use EWallet\Entities\Account;

/**
 * Create account
 */
$app->post('/api/account/create', function ($request, $response) {
    $user = new User($this);
    $success = true;
    $message = '';
    try {
        $params = (object) $request->getParsedBody();
        $data = $user->login($params);
        $account = new Account($this);
        $data->accountId = $account->create($data, $params);
        $this->session->set('user', $data);
    } catch (\Exception $e) {
        $success = false;
        $message = $e->getMessage();
    }
    return $response->withJson(['success'=>$success, 'message'=>$message]);
});

$app->group('/api/account', function () {
    /**
     * List addresses
     */
    $this->post('/address/list', function ($request, $response) {
        $user = $this->session->get('user');
        try {
            $params = (object)$request->getParsedBody();
            $account = new Account($this);
            $data = $account->listAddresses($user);
            return $response->withJson(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return $response->withJson(['success' => false, 'message' => $e->getMessage()]);
        }
    });
    /**
     * Create address
     */
    $this->post('/address/add', function ($request, $response) {
        $user = $this->session->get('user');
        $success = true;
        $message = '';
        try {
            $params = (object)$request->getParsedBody();
            $account = new Account($this);
            $account->addAddress($user, $params);
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        return $response->withJson(['success' => $success, 'message' => $message]);
    });
    /**
     * Create address
     */
    $this->post('/address/delete', function ($request, $response) {
        $user = $this->session->get('user');
        $success = true;
        $message = '';
        try {
            $params = (object)$request->getParsedBody();
            $account = new Account($this);
            $account->deleteAddress($user, $params);
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        return $response->withJson(['success' => $success, 'message' => $message]);
    });
})->add(new AuthMiddleware($app->getContainer()));
