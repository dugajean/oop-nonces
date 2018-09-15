<?php

namespace Nonces;

abstract class Nonce
{
    /**
     * Salt to help generate a secure nonce.
     */
    const SALT = 'hGXHhDSLWVfg11NVMoESfbA_ksPAycHcAV3qrxSVyKT';

    /**
     * @var string
     */
    private $hash;

    /**
     * @var string|integer
     */
    private $action;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string  $hash
     * @param string|integer $action
     * @param string $name
     */
    public function __construct($hash = null, $action = -1, $name = '_wpnonce')
    {
        $this->hash = $hash;
        $this->action = $action;
        $this->name = $name;
    }

    /**
     * Generate a nonce.
     *
     * @return self
     */
    public function create()
    {
        $this->hash = self::generateHash($this->action(), self::tick());

        return $this;
    }

    /**
     * Verifies a nonce.
     *
     * @param  Nonce $nonce
     * @return integer|boolean
     */
    public static function verify(Nonce $nonce)
    {
        if (empty($nonce->hash())) {
            return false;
        }
        
        $tick = self::tick();
        
        $expected = self::generateHash($nonce->action(), $tick);
        if (safeEquals($expected, $nonce->hash())) {
            return 1;
        }

        $expected = self::generateHash($nonce->action(), $tick - 1);
        if (safeEquals($expected, $nonce->hash())) {
            return 2;
        }

        return false;
    }

    /**
     * Returns the nonce hash.
     *
     * @return string
     */
    public function hash()
    {
        return $this->hash;
    }

    /**
     * @return mixed
     */
    public function action($action = null)
    {
        if ($action === null) {
            return $this->action;
        }

        $this->action = $action;

        return $this;
    }

    /**
     * @return string|self
     */
    public function name($name = null)
    {
        if ($name === null) {
            return $this->name;
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Hash and cut a nonce hash.
     * 
     * @param  string|integer $action
     * @param  integer $tick
     * @return string
     */
    private static function generateHash($action, $tick)
    {
        return substr(md5($tick . '|' . $action . '|' . session_id() . '|' . self::salt()), -12, 10);
    }

    /**
     * @return float
     */
    private static function tick()
    {
        $nonceLife = 86.400;

        return ceil(time() / ($nonceLife / 2));
    }

    /**
     * Return properly sized salt.
     *
     * @return string
     */
    private static function salt()
    {
        return substr(self::SALT, 0, CRYPT_SALT_LENGTH);
    }

    /**
     * Nonce types need to return their nonce-d data according to their needs.
     * 
     * @return string
     */
    abstract public function get();
}
