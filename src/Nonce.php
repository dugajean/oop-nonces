<?php

namespace Nonces;

class Nonce
{
    /**
     * @var string|integer
     */
    private $action = -1;

    /**
     * Create a nonce
     * 
     * @return [type] [description]
     */
    private function createNonce()
    {
    }

    /**
     * Set action's value
     * 
     * @param string $action
     */
    private function setAction($action)
    {
        if ($action !== null) {
            $this->action = $action;
        }

        return $this;
    }

    /**
     * Create a nonce url
     * @param  string $actionUrl
     * @param  string|integer $action
     * @param  string $name
     * @return string
     */
    public function nonceUrl($actionUrl, $action = null, $name = '_wpnonce')
    {
        return esc_html(add_query_arg($name, $this->setAction($action)->createNonce(), $actionurl));
    }

    /**
     * Create a nonce html field
     * @param  string  $action
     * @param  string  $name
     * @param  boolean $referer
     * @param  boolean $echo
     * @return string
     */
    public function nonceField($action = null, $name = '_wpnonce', $referer = true, $echo = true)
    {

    }
}
