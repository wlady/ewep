<?php
/**
 * Created by PhpStorm.
 * User: wlady2001
 * Date: 15.08.16
 * Time: 15:28
 */
namespace EWallet\Exceptions;

class SenderException extends \Exception
{
    const CANT_PARSE_XML = 'Cant parse XML';
}