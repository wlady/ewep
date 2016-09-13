<?php
/**
 * Created by PhpStorm.
 * User: wlady2001
 * Date: 14.08.16
 * Time: 17:27
 */
namespace EWallet\Entities;

use EWallet\BaseContainer;
use EWallet\Exceptions\SenderException;

class User
{
    use BaseContainer;

    /**
     * @param \stdClass $params
     * @return mixed
     * @throws \Exception
     */
    public function login(\stdClass $params)
    {
        $phone = filter_var($params->phone, FILTER_SANITIZE_STRING);
        if (empty($phone) || empty($params->password)) {
            throw new \Exception('Wrong parameters');
        }
        $stmt = $this->db->prepare(
            'SELECT u.*, a.id accountId FROM users u LEFT JOIN accounts a ON u.id=a.user WHERE `phone`=?'
        );
        $stmt->execute([$phone]);
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $user = $stmt->fetch();
        if (password_verify($params->password, $user->password)) {
            $user->password = $params->password;
            $user->session = $this->container->mobikassa->sendLoginUser($user);
            return $user;
        } else {
            throw new \Exception('User not found');
        }
    }

    /**
     * @param \stdClass $params
     * @return bool
     * @throws \Exception
     */
    public function register(\stdClass $params)
    {
        $user = new \stdClass();
        $user->email = filter_var($params->email, FILTER_SANITIZE_EMAIL);
        $user->phone = filter_var($params->phone, FILTER_SANITIZE_STRING);
        $password = filter_var($params->password, FILTER_SANITIZE_STRING);
        if (empty($user->email) || empty($user->phone) || empty($password)) {
            throw new \Exception('Wrong parameters');
        }
        $stmt = $this->db->prepare('SELECT `id`,`key`,`crt` FROM users WHERE `phone`=:phone OR `email`=:email');
        $stmt->execute((array)$user);
        if (($row = $stmt->fetch(\PDO::FETCH_OBJ)) !== false) {
            throw new \Exception('User is already registered');
        }
        $user->password = $password;
        $user = $this->container->mobikassa->sendCreateUser($user);
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            'INSERT INTO users(`phone`,`email`,`password`,`mobikassa_id`,`key`,`crt`) 
            VALUES(:phone,:email,:password,:mobikassa_id,:key,:crt)'
        );
        return $stmt->execute((array)$user);
    }

    /**
     * @param \stdClass $params
     * @return bool
     * @throws \Exception
     */
    public function testRegister(\stdClass $params)
    {
        $user = new \stdClass();
        $user->email = filter_var($params->email, FILTER_SANITIZE_EMAIL);
        $user->phone = filter_var($params->phone, FILTER_SANITIZE_STRING);
        $password = filter_var($params->password, FILTER_SANITIZE_STRING);
        if (empty($user->email) || empty($user->phone) || empty($password)) {
            throw new \Exception('Wrong parameters');
        }
        $stmt = $this->db->prepare('SELECT * FROM users WHERE `phone`=?');
        $stmt->execute(['0000000007']);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
