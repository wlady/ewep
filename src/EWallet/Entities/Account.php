<?php
/**
 * Created by PhpStorm.
 * User: wlady2001
 * Date: 18.08.16
 * Time: 21:23
 */

namespace EWallet\Entities;

use EWallet\BaseContainer;

class Account
{
    use BaseContainer;

    /**
     * @param $user
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function create($user, $params)
    {
        $account = filter_var($params->account, FILTER_SANITIZE_STRING);
        $name = filter_var($params->name, FILTER_SANITIZE_STRING);
        $lastSum = filter_var($params->sum, FILTER_VALIDATE_FLOAT);
        if (empty($account) || empty($name)) {
            throw new \Exception('Wrong parameters');
        }
        $stmt = $this->db->prepare('INSERT INTO accounts(`account`,`name`,`user`) VALUES(?,?,?)');
        $stmt->execute([$account, $name, $user->id]);
        $accountId = $this->db->lastInsertId();
        if ($lastSum) {
            $stmt = $this->db->prepare('INSERT INTO payments(`account`,`sum`) VALUES(?,?)');
            $stmt->execute([$accountId, $lastSum]);
        }
        return $accountId;
    }

    /**
     * @param $user
     * @return mixed
     * @throws \Exception
     */
    public function listAddresses($user)
    {
        $stmt = $this->db->prepare('SELECT * FROM addresses WHERE `account`=?');
        $stmt->execute([$user->accountId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * @param $user
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function addAddress($user, $params)
    {
        $name = filter_var($params->name, FILTER_SANITIZE_STRING);
        $city = filter_var($params->city, FILTER_SANITIZE_STRING);
        $street = filter_var($params->street, FILTER_SANITIZE_STRING);
        $number = filter_var($params->number, FILTER_SANITIZE_STRING);
        $option = filter_var($params->option, FILTER_SANITIZE_STRING);
        $notify = filter_var($params->notify, FILTER_VALIDATE_INT) ? 1 : 0;
        if (empty($name) || empty($city) || empty($street) || empty($number)) {
            throw new \Exception('Wrong parameters');
        }
        $stmt = $this->db->prepare(
            'INSERT INTO addresses(`account`,`name`,`city`, `street`,`number`,`option`,`notify`) 
                VALUES(?,?,?,?,?,?,?)'
        );
        return $stmt->execute([$user->accountId, $name, $city, $street, $number, $option, $notify]);
    }

    /**
     * @param $user
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function deleteAddress($user, $params)
    {
        $id = filter_var($params->id, FILTER_VALIDATE_INT);
        if (empty($id)) {
            throw new \Exception('Wrong parameters');
        }
        $stmt = $this->db->prepare(
            'DELETE FROM addresses WHERE id=? AND `account`=?'
        );
        return $stmt->execute([$id, $user->accountId]);
    }
}
