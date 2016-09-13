<?php
/**
 * Created by PhpStorm.
 * User: wlady2001
 * Date: 15.08.16
 * Time: 14:43
 */

namespace EWallet\Mobikassa;

use EWallet\BaseContainer;
use EWallet\Exceptions\SenderException;

class Sender
{
    use BaseContainer;

    /**
     * @param $user
     * @return \stdClass
     * @throws SenderException
     */
    public function sendCreateUser($user)
    {
        $cmd = (new Command($this->container))->createUser($user);
        $params = [
            'func' => 'CreatUser',
            'cmd' => $cmd,
        ];
        $request = $this->container->view->fetch('Request.tpl', ['params' => $params]);
        $response = $this->send($request);
        $data = $this->parseResponse($response);
        if ($data->user) {
            $user->mobikassa_id = (string)$data->user;
        }
        if ($data->{'user-key'}) {
            $user->key = (string)$data->{'user-key'};
        }
        if ($data->{'user-crt'}) {
            $user->crt = (string)$data->{'user-crt'};
        }
        return $user;
    }

    /**
     * @param $user
     * @return string
     * @throws SenderException
     */
    public function sendLoginUser($user)
    {
        $cmd = (new Command($this->container))->loginUser($user);
        $sign = $this->sign($cmd, $user->key, $user->password);
        $params = [
            'func' => 'LoginUser',
            'cmd' => $cmd,
            'sign' => $sign,
            'user_id' => $user->mobikassa_id,
        ];
        $request = $this->container->view->fetch('Request.tpl', ['params' => $params]);
        $response = $this->send($request);
        $data = $this->parseResponse($response);
        // mobikassa session
        return (string)$data->{'pay-id'};
    }

    /**
     * @param $user
     * @param $params
     * @return array
     * @throws SenderException
     */
    public function getRegion($user, $params)
    {
        $cmd = (new Command($this->container))->getRegion($params);
        $sign = $this->sign($cmd, $user->key, $user->password);
        $params = [
            'func' => 'GetRegion',
            'cmd' => $cmd,
            'sign' => $sign,
            'user_id' => $user->mobikassa_id,
        ];
        $request = $this->container->view->fetch('Request.tpl', ['params' => $params]);
        $response = $this->send($request);
        $data = $this->parseResponse($response);
        $regions = [];
        foreach ($data->ListRegion->region as $region) {
            $regions[] = (string)$region;
        }
        return $regions;
    }

    /**
     * @param $user
     * @param $params
     * @return array
     * @throws SenderException
     */
    public function getStreet($user, $params)
    {
        $cmd = (new Command($this->container))->getStreet($params);
        $sign = $this->sign($cmd, $user->key, $user->password);
        $params = [
            'func' => 'GetStreet',
            'cmd' => $cmd,
            'sign' => $sign,
            'user_id' => $user->mobikassa_id,
        ];
        $request = $this->container->view->fetch('Request.tpl', ['params' => $params]);
        $response = $this->send($request);
        $data = $this->parseResponse($response);
        $streets = [];
        foreach ($data->ListStreet->street as $street) {
            $streets[] = (string)$street;
        }
        return $streets;
    }

    /**
     * @param $user
     * @param $params
     * @return array
     * @throws SenderException
     */
    public function offGetCity($user, $params)
    {
        $cmd = (new Command($this->container))->offGetCity($params);
        $sign = $this->sign($cmd, $user->key, $user->password);
        $params = [
            'func' => 'OffGetCity',
            'cmd' => $cmd,
            'sign' => $sign,
            'user_id' => $user->mobikassa_id,
        ];
        $request = $this->container->view->fetch('Request.tpl', ['params' => $params]);
        $response = $this->send($request);
        $data = $this->parseResponse($response);
        $cities = [];
        foreach ($data->{'list-offcities'}->{'offcity-name'} as $city) {
            $cities[] = (string)$city;
        }
        return $cities;
    }

    /**
     * @param $user
     * @param $params
     * @return array
     * @throws SenderException
     */
    public function offGetStreet($user, $params)
    {
        $cmd = (new Command($this->container))->offGetStreet($params);
        $sign = $this->sign($cmd, $user->key, $user->password);
        $params = [
            'func' => 'OffGetStreet',
            'cmd' => $cmd,
            'sign' => $sign,
            'user_id' => $user->mobikassa_id,
        ];
        $request = $this->container->view->fetch('Request.tpl', ['params' => $params]);
        $response = $this->send($request);
        $data = $this->parseResponse($response);
        $streets = [];
        foreach ($data->{'list-street'}->{'street-name'} as $street) {
            $streets[] = (string)$street;
        }
        return $streets;
    }

    /**
     * @param $user
     * @param $params
     * @return array
     * @throws SenderException
     */
    public function offGetAddress($user, $params)
    {
        $cmd = (new Command($this->container))->offGetAddress($params);
        $sign = $this->sign($cmd, $user->key, $user->password);
        $params = [
            'func' => 'OffGetAdress',
            'cmd' => $cmd,
            'sign' => $sign,
            'user_id' => $user->mobikassa_id,
        ];
        $request = $this->container->view->fetch('Request.tpl', ['params' => $params]);
        $response = $this->send($request);
        $data = $this->parseResponse($response);
        return [
            'name' => (string)$data->{'shutdown-parameters'}->{'res-name'},
            'knot' => (string)$data->{'shutdown-parameters'}->{'network-knot'},
            'dateStart' => (string)$data->{'shutdown-parameters'}->{'date-of-shutdown'},
            'timeStart' => (string)$data->{'shutdown-parameters'}->{'time-of-shutdown'},
            'dateEnd' => (string)$data->{'shutdown-parameters'}->{'date-of-inclusion'},
            'timeEnd' => (string)$data->{'shutdown-parameters'}->{'time-of-inclusion'},
            'description' => (string)$data->{'shutdown-parameters'}->{'description-of-shutdown'},
            'type' => (string)$data->{'shutdown-parameters'}->{'shutdown-type'},
            'reason' => (string)$data->{'shutdown-parameters'}->{'shutdown-reason'},
            'status' => (string)$data->{'shutdown-parameters'}->{'status-of-shutdown'},
        ];
    }

    /**
     * @param $content
     * @return string
     * @throws SenderException
     */
    protected function send($content)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, MOBIKASSA_URL);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: text/xml;charset=utf-8',
            'Accept: text/xml',
            'Cache-Control: no-cache',
            'Pragma: no-cache',
            'Content-Length: ' . strlen($content)
        ));
        $result = curl_exec($ch);
        if ($error = curl_error($ch)) {
            throw new SenderException($error);
        }
        // parse response
        $xml = simplexml_load_string($result, null, null, 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('SOAP-ENV', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('SOAP-ENC', 'http://schemas.xmlsoap.org/soap/encoding/');
        $xml->registerXPathNamespace('xmm', 'http://namespace.softwareag.com/entirex/xml/mapping');
        $xml->registerXPathNamespace('xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xml->registerXPathNamespace('xsd', 'http://www.w3.org/2001/XMLSchema');
        $xml->registerXPathNamespace('m', 'urn:com-softwareag-entirex-rpc:CSS-BASE-WSDLVEGA');
        $resObg = $xml->xpath('//m:WSDLVEGAResponse');
        if (count($resObg)) {
            $response = $resObg[0];
            return trim(base64_decode($response->W3VALUE));
        }
        throw new SenderException('SOAP Error: Bad response');
    }

    /**
     * @param $response
     * @return \SimpleXMLElement
     * @throws SenderException
     * @throws \Exception
     */
    public function parseResponse($response)
    {
        $xml = simplexml_load_string($this->convert($response));
        $resObg = $xml->xpath('/Returns');
        if (count($resObg)) {
            $data = $resObg[0];
            if (isset($data->error->attributes()->cod)) {
                $code = (string)$data->error->attributes()->cod;
                if (intval($code) != 0) {
                    throw new SenderException('SOAP Error: ' . $this->describeErrorCode($code));
                }
            }
            return $data;
        } else {
            throw new \Exception('SOAP Error:  ' . SenderException::CANT_PARSE_XML);
        }
    }

    /**
     * @param $content
     * @param $key
     * @param string $password
     * @return mixed
     * @throws SenderException
     */
    protected function sign($content, $key, $password = '')
    {
        $pkeyid = openssl_get_privatekey(base64_decode($key), $password);
        if (!$pkeyid) {
            throw new SenderException('Cant process private key');
        }
        openssl_sign($content, $signature, $pkeyid);
        openssl_free_key($pkeyid);
        return $signature;
    }

    /**
     * @param $code
     * @return string
     */
    protected function describeErrorCode($code)
    {
        switch ($code) {
            case '0111':
                return 'Search is needed 3 or more symbols';
            case '0114':
                return 'No data';
            case '0117':
                return 'Function name is incorrect';
            case '0312':
                return 'Required field is empty';
            case '0314':
                return 'User is already registered';
            case '0315':
                return 'Key generation error';
            case '0316':
                return 'Authentification error';
            default:
                return 'Unknown error';
        }
    }

    /**
     * @param $string
     * @return string
     */
    protected function convert($string)
    {
        if (stristr($string, 'WINDOWS-1251') !== false || stristr($string, 'CP-1251') !== false) {
            $string = iconv('CP1251', 'UTF-8', $string);
            $string = str_ireplace(['CP-1251', 'WINDOWS-1251'], 'UTF-8', $string);
        }
        return $string;
    }
}
