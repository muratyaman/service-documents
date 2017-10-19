<?php
/**
 * File for hydrator trait
 */

namespace App;

/**
 * Trait HydratorTrait
 * @package App
 */
trait HydratorTrait
{
    /**
     * @param array $data
     */
    function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}