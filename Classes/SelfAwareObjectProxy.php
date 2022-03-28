<?php

namespace MhsDesign\EelSelfAwareObjects;

use Neos\Eel\ProtectedContextAwareInterface;

class SelfAwareObjectProxy implements \ArrayAccess, ProtectedContextAwareInterface
{
    private function __construct(
        private array $array
    ) {
    }

    /** @return self|mixed */
    public static function fromAssosiativeArray(array $array)
    {
        $selfAwareObjectProxy = new self($array);
        if ($selfAwareObjectProxy->allowsCallOfMethod('init')) {
            // could trow and ArgumentCountError, but that's okay.
            return $selfAwareObjectProxy->init();
        }
        return $selfAwareObjectProxy;
    }

    public function __call(string $name, array $args)
    {
        if ($this->offsetExists($name) === false && str_starts_with($name, 'get')) {
            // probably misdirected call.
            // https://github.com/neos/flow-development-collection/issues/2785
            $getterRemoved = substr($name, 3);
            if ($this->offsetExists($getterRemoved)) {
                return $this->offsetGet($getterRemoved);
            }
            $getterRemovedLower = lcfirst($getterRemoved);
            if ($this->offsetExists($getterRemovedLower)) {
                return $this->offsetGet($getterRemovedLower);
            }
            return null;
        }
        
        return ($this->array[$name])($this, ...$args);
    }

    public function allowsCallOfMethod($methodName)
    {
        return isset($this->array[$methodName])
            && $this->array[$methodName] instanceof \Closure;
    }

    public function offsetGet($offset)
    {
        return $this->array[$offset];
    }

    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    public function offsetSet($offset, $value)
    {
       throw new \BadMethodCallException('Not supported.');
    }
    
    public function offsetUnset($offset)
    {
       throw new \BadMethodCallException('Not supported.');
    }
}
