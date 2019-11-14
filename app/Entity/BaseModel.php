<?php /** @noinspection MissingParameterTypeDeclarationInspection */


namespace App\Entity;


use ReflectionClass;
use ReflectionException;

class BaseModel
{
    /**
     * @param null $object
     * @return array
     * @throws ReflectionException
     */
    public function toArray($object = null): array
    {
        $obj = $object ?? $this;

        $reflectionClass = new ReflectionClass($obj);

        $properties = $reflectionClass->getProperties();

        $array = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($obj);
            if (is_object($value)) {
                $array[$property->getName()] = self::toArray($value);
            } else {
                $array[$property->getName()] = $value;
            }
        }
        return $array;
    }
}
