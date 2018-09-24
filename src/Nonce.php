<?php

declare(strict_types=1);

namespace Nonces;

use Nonces\Exceptions\NonceException;

abstract class Nonce
{
    /**
     * Salt to help generate a secure nonce.
     */
    const SALT = 'hGXHhDSLWVfg11NVMoESfbA_ksPAycHcAV3qrxSVyKT';

    /**
     * The generated nonce.
     *
     * @var string
     */
    protected $hash;

    /**
     * Should contain the action that's being executed.
     * The action string should be as unique as possible, such as "update-post={post id}".
     * Default is: -1
     *
     * @var string|int
     */
    protected $action;

    /**
     * Contains the key name that will be sent to the backend.
     * Default is: _wpnonce
     *
     * @var string
     */
    protected $name;

    /**
     * @param string $hash Nonce hash that you want to set for this instance.
     * @param int|string $action The action or purpose of this nonce.
     * @param string $name The field name for this nonce in order to fetch its value.
     */
    public function __construct(string $hash = null, $action = -1, string $name = '_wpnonce')
    {
        $this->hash = $hash;
        $this->action($action);
        $this->name($name);
    }

    /**
     * Generates an unique nonce.
     *
     * @return $this
     * @throws \Nonces\Exceptions\NonceException
     */
    public function create(): self
    {
        $this->hash = $this->generateHash();

        return $this;
    }

    /**
     * Check whether a nonce is legitimate or not.
     *
     * This will be done by remaking the hash from scratch using a new "tick" function call
     * and then determining if the generated hash matches the one currently stored in the object.
     *
     * @return int|bool Whether the verification failed or passed.
     * @throws \Nonces\Exceptions\NonceException
     */
    public function verify()
    {
        if (empty($this->hash())) {
            return false;
        }
        
        $tick = self::tick();
        
        $expected = $this->generateHash($tick);
        if (self::safeEquals($expected, $this->hash())) {
            return 1;
        }

        $expected = $this->generateHash($tick - 1);
        if (self::safeEquals($expected, $this->hash())) {
            return 2;
        }

        return false;
    }

    /**
     * Hash and cut a nonce hash.
     *
     * @param float $tick Time sensitive number.
     * @return string The salted, hashed and cut string.
     * @throws \Nonces\Exceptions\NonceException
     */
    protected function generateHash(float $tick = null): string
    {
        if (!session_id()) {
            throw new NonceException('Unable to generate a secure hash.');
        }

        $tick = $tick ? : self::tick();

        return substr(md5($tick . '|' . $this->action() . '|' . session_id() . '|' . self::SALT), -12, 10);
    }

    /**
     * Returns the current nonce hash.
     *
     * @return string The current nonce hash.
     */
    public function hash()
    {
        return $this->hash;
    }

    /**
     * Fetch or set a new action for an instance.
     *
     * @param mixed $action Optional. If the argument is missing, it will return the current action.
     *                      If a value is provided then it will set the action to that new value and return $this.
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
     * Fetch or set a new name for this Nonce.
     *
     * @param string|null $name Optional. If the argument is missing, it will return the current name.
     *                          If a value is provided then it will set the name to that new value and return $this.
     * @return string|$this
     */
    public function name(string $name = null)
    {
        if ($name === null) {
            return $this->name;
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Timing attack safe string comparison
     * Compares two strings using the same time whether they're equal or not.
     *
     * Copied from WordPress.
     *
     * @param string $expected Expected string.
     * @param string $actual Actual, user supplied, string.
     * @return bool Whether strings are equal.
     */
    protected static function safeEquals(string $expected, string $actual): bool
    {
        $expectedLen = strlen($expected);
        if ($expectedLen !== strlen($actual)) {
            return false;
        }

        $result = 0;
        for ($i = 0; $i < $expectedLen; $i++) {
            $result |= ord($expected[$i]) ^ ord($actual[$i]);
        }

        return $result === 0;
    }

    /**
     * Get the time-dependent variable for nonce creation.
     *
     * A nonce has a lifespan of two ticks. Nonces in their second tick may be
     * updated, e.g. by autosave.
     *
     * Copied from WordPress.
     *
     * @return float Float value rounded up to the next highest integer.
     */
    protected static function tick(): float
    {
        $nonceLife = 86.400;

        return ceil(time() / ($nonceLife / 2));
    }

    /**
     * Nonce "render" function.
     *
     * This method needs to be implemented in the Types subclasses
     * which will then return their appropriate formats of the nonce.
     *
     * @return string
     */
    abstract public function get(): string;
}
