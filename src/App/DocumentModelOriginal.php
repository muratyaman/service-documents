<?php
/**
 * File for original document info class
 */

namespace App;

/**
 * Class DocumentModelOriginal
 * @package App
 */
class DocumentModelOriginal
{

    use HydratorTrait;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var int
     */
    public $size;

    /**
     * @var mixed
     */
    public $meta;

    /**
     * DocumentInfo constructor.
     * @param string $name
     * @param string $type
     * @param int    $size
     * @param mixed  $meta
     */
    function __construct($name = null, $type = null, $size = null, $meta = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->size = $size;
        $this->meta = $meta;
    }

}