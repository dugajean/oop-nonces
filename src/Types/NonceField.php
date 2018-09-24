<?php

declare(strict_types=1);

namespace Nonces\Types;

use Nonces\Nonce;

class NonceField extends Nonce
{
    /**
     * Determine whether the get method should echo out
     * the field in addition to returning it.
     *
     * @var bool
     */
    private $echo;

    /**
     * @inheritdoc
     * @param bool $echo Whether to echo out the field or not.
     */
    public function __construct(string $hash = null, $action = -1, string $name = '_wpnonce', bool $echo = true)
    {
        parent::__construct($hash, $action, $name);

        $this->echo($echo);
    }

    /**
     * Return a HTML hidden input field containing the nonce.
     *
     * E.g: Given the name is "secure_nonce" and a hash with value "4grh349dbll",
     * the resulting field would look like this:
     * <input type="hidden" id="secure_nonce" name="secure_nonce" value="4grh349dbll" />
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
     * Fetch or set whether the get method should echo out the field or not.
     *
     * @param  bool $echo Optional. If the argument is missing, it will return the current echo value.
     *                    If a value is provided then it will set echo to that new value and return $this.
     * @return bool|$this
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
