<?php

namespace Osm\Framework\Encryption\Hashing;

use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Object_;

/**
 * @property string $name @required @part
 * @property int $algorithm @required @part
 * @property array $options @required @part
 */
class Hashing extends Object_
{
    public function encrypt($value) {
        if (($hash = password_hash($value, $this->algorithm, $this->options)) === false) {
            throw new NotSupported(osm_t("':name' hashing not supported.", ['name' => $this->algorithm]));
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