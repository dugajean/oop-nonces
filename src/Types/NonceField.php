<?php

namespace Nonces\Types;

use Nonces\Nonce;

class NonceField extends Nonce
{
    /**
     * @var boolean
     */
    private $echo;

    /**
     * @param string  $hash
     * @param string|integer $action
     * @param string $name
     * @param boolean $echo
     */
    public function __construct($hash = null, $action = -1, $name = '_wpnonce', $echo = true)
    {
        parent::__construct($hash, $action, $name);
        $this->echo($echo);
    }

    /**
     * Create a nonce HTML form field.
     *
     * @return string
     */
    public function get()
    {
        $name = htmlspecialchars($this->name());
        $nonceField = '<input type="hidden" id="' . $name . '" name="';
        $nonceField .= $name . '" value="' . $this->create()->hash() . '" />';

        if ($this->echo()) {
            echo $nonceField;
        }

        return $nonceField;
    }

    /**
     * @param  boolean $echo
     * @return boolean|self
     */
    public function echo($echo = null)
    {
        if ($echo === null) {
            return $this->echo;
        }

        $this->echo = $echo;

        return $this;
    }
}
