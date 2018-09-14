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
     * @param string  $key
     * @param string|integer $action
     */
    public function __construct($key = null, $action = -1)
    {
        $this->key = $key;
        $this->action = $action;
    }

    /**
     * Generate a nonce.
     *
     * @return string
     */
    private function create()
    {
        $token = session_id();
        $tick = $this->tick();
        $hash = crypt($tick . '|' . $this->action . '|' . $token, $this->getSalt());

        $this->key = substr($hash, -12, 10);

        return $this;
    }

    /**
     * @return float
     */
    private function tick()
    {
        $nonceLife = 86.400;

        return ceil(time() / ( $nonceLife / 2 ));
    }

    /**
     * Return properly sized salt.
     *
     * @return string
     */
    private function getSalt()
    {
        return substr(self::SALT, 0, CRYPT_SALT_LENGTH);
    }

    /**
     * Verifies a nonce.
     *
     * @param  Nonce $nonce
     * @return boolean
     */
    public function verify(Nonce $nonce)
    {
        if (empty($nonce->get())) {
            return false;
        }

        $token = session_id();
        $tick = $this->tick();

        $expected = substr(crypt($tick . '|' . $nonce->action() . '|' . $token, $this->getSalt()), -12, 10);
        if ($expected == $nonce->get()) {
            return 1;
        }

        $expected = substr(crypt(($tick - 1) . '|' . $nonce->action() . '|' . $token, $this->getSalt()), -12, 10);
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
     * @return string|integer
     */
    public function action()
    {
        return $this->action;
    }

    /**
     * @param string|integer $action
     */
    private function setAction($action)
    {
        if ($action !== null) {
            $this->action = $action;
        }

        return $this;
    }

    /**
     * Create a nonce url.
     *
     * @param  string $actionUrl
     * @param  string|integer $action
     * @param  string $name
     * @return string
     */
    public function makeUrl($actionUrl, $action = null, $name = '_wpnonce')
    {
        return esc_html(add_query_arg($name, $this->setAction($action)->create()->get(), $actionurl));
    }

    /**
     * Create a nonce HTML form field.
     *
     * @param  string  $action
     * @param  string  $name
     * @param  boolean $referer
     * @param  boolean $echo
     * @return string
     */
    public function makeField($action = null, $name = '_wpnonce', $echo = true)
    {
        $nonceField = '<input type="hidden" id="' . $name . '" name="' . $name . '" value="' . $this->setAction($action)->create()->get() . '" />';

        if ($echo) {
            echo $nonceField;
        }

        return $nonceField;
    }
}
