<?php

declare(strict_types=1);

namespace Nonces\Types;

use Nonces\Nonce;

class NonceField extends Nonce
{
    /**
     * @var bool
     */
    private $echo;

    /**
     * @param string  $hash
     * @param string|int $action
     * @param string $name
     * @param bool $echo
     */
    public function __construct(string $hash = null, $action = -1, string $name = '_wpnonce', bool $echo = true)
    {
        parent::__construct($hash, $action, $name);
        $this->echo($echo);
    }

    /**
     * Create a nonce HTML form field.
     *
     * @return string
     */
    public function get(): string
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
     * @param  bool $echo
     * @return bool|self
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
