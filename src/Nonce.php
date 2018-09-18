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
     * @var string
     */
    protected $hash;

    /**
     * @var string|int
     */
    protected $action;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param string  $hash
     * @param string|int $action
     * @param string $name
     */
    public function __construct(string $hash = null, $action = -1, string $name = '_wpnonce')
    {
        $this->hash = $hash;
        $this->action($action);
        $this->name($name);
    }

    /**
     * Generate a nonce.
     *
     * @return self
     * @throws \Nonces\Exceptions\NonceException
     */
    public function create(): self
    {
        $this->hash = $this->generateHash();

        return $this;
    }

    /**
     * Verifies a nonce.
     *
     * @return int|boolean
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
     * @param float $tick
     *
     * @return string
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
     * Returns the nonce hash.
     *
     * @return string
     */
    public function hash()
    {
        return $this->hash;
    }

    /**
     * @param null $action
     *
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
     * @param string|null $name
     *
     * @return string|self
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
     * Copied from Wordpress.
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
     * @return float
     */
    protected static function tick(): float
    {
        $nonceLife = 86.400;

        return ceil(time() / ($nonceLife / 2));
    }

    /**
     * Nonce types need to return their nonce-d data according to their needs.
     *
     * @return string
     */
    abstract public function get(): string;
}
