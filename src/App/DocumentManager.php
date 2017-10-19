<?php
/**
 * File for document class
 */

namespace App;

use Slim\Http\UploadedFile;

/**
 * Class Document
 * @package App
 */
class DocumentManager
{

    private $errors = [
        //UPLOAD_ERR_OK       => 'There is no error, the file uploaded with success', // 0
        UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive in php.ini', // 1
        UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', //2
        UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded', // 3
        UPLOAD_ERR_NO_FILE    => 'No file was uploaded', //4
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder. Introduced in PHP 5.0.3.', //6
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk. Introduced in PHP 5.1.0', //7
        UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.', //8
    ];

    /**
     * @var string
     */
    private $dir;

    /**
     * Document constructor.
     * @param string $dir
     */
    function __construct($dir = null)
    {
        $this->dir = ($dir ? $dir : (__DIR__ . '/../../storage/'));
    }

    /**
     * Moves the uploaded file to the upload directory and assigns it a unique name
     * to avoid overwriting an existing uploaded file.
     *
     * @param UploadedFile $uploadedFile
     * @param mixed        $meta
     * @return DocumentModel
     * @throws DocumentError
     */
    function storeUploadedFile(UploadedFile $uploadedFile, $meta = null)
    {
        $errId = $uploadedFile->getError();
        if (UPLOAD_ERR_OK !== $errId) {
            $errMsg = isset($this->errors[$errId]) ? $this->errors[$errId] : 'Unknown error';
            throw new DocumentError($errMsg, 400);
        }

        $file = $uploadedFile->getClientFilename();
        $ext  = pathinfo($file, PATHINFO_EXTENSION);
        if (empty($ext)) {
            throw new DocumentError('File has no extension', 400);
        }
        $id   = $this->generateId(64);
        $name = sprintf('%s.%s', $id, $ext);
        $path = $this->dir . '/' . $name;

        //TODO: check storage path is writable
        $uploadedFile->moveTo($path);

        $doc = $this->writeInfoFile($uploadedFile, $id, $name, $meta);
        return $doc;
    }

    /**
     * @param string $id
     * @return string
     */
    private function getInfoFilePath($id)
    {
        return $this->dir . '/' . sprintf('%s.%s', $id, '.info.json');
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param string       $id
     * @param mixed        $meta
     * @return DocumentModel
     * @throws DocumentError
     */
    function writeInfoFile(UploadedFile $uploadedFile, $id, $name, $meta = null)
    {
        $info = new DocumentModelOriginal(
            $uploadedFile->getClientFilename(),
            $uploadedFile->getClientMediaType(),
            $uploadedFile->getSize(),
            $meta
        );
        $doc  = new DocumentModel($id, $name, $info);
        $json = json_encode($doc);
        $path = $this->getInfoFilePath($id);
        $done = file_put_contents($path, $json);
        if (!$done) {
            throw new DocumentError('Failed to save information file', 500);
        }
        return $doc;
    }

    /**
     * @param string $id
     * @return DocumentModel
     * @throws DocumentError
     */
    function readInfoFile($id)
    {
        $path = $this->getInfoFilePath($id);
        // check file exists
        if (!file_exists($path)) {
            throw new DocumentError('File not found', 404);
        }
        $json = file_get_contents($path);
        // check json
        if (empty($json)) {
            throw new DocumentError('File empty', 500);
        }
        $data = json_decode($json, $assoc = true);
        // check json decoded
        if (empty($data)) {
            throw new DocumentError('Invalid file content: ' . json_last_error_msg(), 500);
        }
        $info = new DocumentModel();
        $info->hydrate($data);
        return $info;
    }

    /**
     * @param int $len
     * @return string
     */
    function generateId($len)
    {
        $str   = '';
        $chars = array_merge(
            range('a','z'),
            range('0','9')
        );
        $max = count($chars) - 1;
        for ($i = 0; $i < $len; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $chars[$rand];
        }
        return $str;
    }
}