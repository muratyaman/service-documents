<?php
/**
 * File for document model
 */

namespace App;

/**
 * Class DocumentModel
 * @package App
 */
class DocumentModel
{
    use HydratorTrait {
        hydrate as hydrateOfTrait;
    }

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var DocumentModelOriginal
     */
    public $original;

    /**
     * DocumentInfo constructor.
     * @param string                $id
     * @param string                $name
     * @param DocumentModelOriginal $original
     */
    function __construct($id = null, $name = null, DocumentModelOriginal $original = null)
    {
        $this->id       = $id;
        $this->name     = $name;
        $this->original = $original;
    }

    /**
     * @param array $data
     */
    function hydrate(array $data)
    {
        if (isset($data['original'])) {
            $original = new DocumentModelOriginal();
            $original->hydrate($data['original']);
            $data['original'] = $original;
        }
        $this->hydrateOfTrait($data);
    }
}