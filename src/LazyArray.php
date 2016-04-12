<?php
namespace TheCodingMachine\LazyArray;

class LazyArray implements \ArrayAccess
{
    /**
     * The array with lazy values
     * @var array
     */
    private $lazyArray;

    /**
     * The array with constructed values
     * @var array
     */
    private $constructedArray = [];

    /**
     * @param array $lazyArray The array with lazy values
     */
    public function __construct(array $lazyArray = [])
    {
        $this->lazyArray = $lazyArray;
    }

    /**
     * @param string|object $className The FQCN or the instance to put in the array
     * @param array ...$params The parameters passed to the constructor.
     * @return int The key in the array
     */
    public function push($className, ...$params) {
        if (is_object($className)) {
            $this->lazyArray[] = $className;
        } else {
            $this->lazyArray[] = [ $className, $params ];
        }
        end($this->lazyArray);
        return key($this->lazyArray);
    }


    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->lazyArray[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        if (isset($this->constructedArray[$offset])) {
            return $this->constructedArray[$offset];
        } else {
            $item = $this->lazyArray[$offset];
            if (is_array($item)) {
                $className = $item[0];
                $params = $item[1] ?? [];
            } else {
                $className = $item;
                $params = [];
            }
            $this->constructedArray[$offset] = new $className(...$params);
            return $this->constructedArray[$offset];
        }
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        throw new \LogicException('Not implemented yet');
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->lazyArray[$offset]);
        unset($this->constructedArray[$offset]);
    }
}