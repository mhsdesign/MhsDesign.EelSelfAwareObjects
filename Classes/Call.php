<?php
declare(strict_types=1);

namespace MhsDesign\EelSelfAwareObjects;

class Call
{
    public static function call(\Closure|array $subject, ...$args)
    {
        if ($subject instanceof \Closure) {
            return $subject(...$args);
        }

        if (is_array($subject) && isset($subject['__call__'])) {
            $selfAwareObject = self::getSelfAwareObject($subject);
            return ($selfAwareObject['__call__'])(...$args);
        }

        throw new \InvalidArgumentException("Subject: " . get_debug_type($subject) . " cannot be 'called'.");
    }
    
    protected static function getSelfAwareObject(array $selfAwareObject)
    {
        return new class($selfAwareObject) implements \ArrayAccess {

            public function __construct(
                private array $object
            ) {
            }

            public function offsetExists($offset)
            {
                return isset($this->object[$offset]);
            }

            public function offsetGet($offset)
            {
                $item = $this->object[$offset];
                if ($item instanceof \Closure) {
                    // prepare `methods` to auto-include `self` argument like in python.
                    return fn (...$args) => $item($this, ...$args);
                }
                return $item;
            }

            public function offsetSet($offset, $value) {}
            public function offsetUnset($offset) {}
        };
    }
}
