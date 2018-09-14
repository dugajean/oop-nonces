<?php

namespace Nonces;

class Nonce
{
    /**
     * Salt to help generate a secure nonce.
     */
    const SALT = 'hGXHhDSLWVfg11NVMoESfbA_ksPAycHcAV3qrxSVyKT';

    /**
     * @var string
     */
    private $key;

    /**
     * @var string|integer
     */
    private $action;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string  $key
     * @param string|integer $action
     */
    public function __construct($key = null, $action = -1, $name = '_wpnonce')
    {
        $this->key = $key;
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
        $token = session_id();
        $tick = self::tick();
        $hash = crypt($tick . '|' . $this->action() . '|' . $token, self::salt());

        $this->key = substr($hash, -12, 10);

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
        if (empty($nonce->get())) {
            return false;
        }

        $token = session_id();
        $tick = self::tick();
        $salt = self::salt();

        $expected = substr(crypt($tick . '|' . $nonce->action() . '|' . $token, $salt), -12, 10);
        if ($expected == $nonce->get()) {
            return 1;
        }

        $expected = substr(crypt(($tick - 1) . '|' . $nonce->action() . '|' . $nonce, $salt), -12, 10);
        if ($expected == $nonce->get()) {
            return 2;
        }
    }

    /**
     * Returns the nonce.
     *
     * @return string
     */
    public function get()
    {
        return $this->key;
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
     * @return float
     */
    public static function tick()
    {
        $nonceLife = 86.400; // ??? To be checked...

        return ceil(time() / ( $nonceLife / 2 ));
    }

    /**
     * Return properly sized salt.
     *
     * @return string
     */
    public static function salt()
    {
        return substr(self::SALT, 0, CRYPT_SALT_LENGTH);
    }
}
