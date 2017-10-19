<?php
/**
 * File for document error class
 */

namespace App;

/**
 * Class DocumentError
 * @package App
 */
class DocumentError extends \Exception
{
    public $httpStatus = 500;

    /**
     * @param int $status
     * @return DocumentError
     */
    function setHttpStatus($status)
    {
        $this->httpStatus = $status;
        return $this;
    }
}