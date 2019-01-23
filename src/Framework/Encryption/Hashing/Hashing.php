<?php

namespace Manadev\Framework\Encryption\Hashing;

use Manadev\Core\Exceptions\NotSupported;
use Manadev\Core\Object_;

/**
 * @property string $name @required @part
 * @property int $algorithm @required @part
 * @property array $options @required @part
 */
class Hashing extends Object_
{
    public function encrypt($value) {
        if (($hash = password_hash($value, $this->algorithm, $this->options)) === false) {
            throw new NotSupported(m_("':name' hashing not supported.", ['name' => $this->algorithm]));
        }

        return $hash;

    }

    public function verify($value, $hash) {
        if (strlen($hash) === 0) {
            return false;
        }

        return password_verify($value, $hash);
    }

    public function needsRehash($hash) {
        return password_needs_rehash($hash, $this->algorithm, $this->options);
    }
}