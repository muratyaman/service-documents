<?php
/**
 * File for document controller
 */

namespace App\Http;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

/**
 * Class DocumentController
 * @package App\HttpControllers
 */
class DocumentController
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * DocumentController constructor.
     * @param Container $container
     */
    public function __construct(Container $container) {
        $this->container = $container;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    function index(Request $request, Response $response, $args)
    {
        //TODO: prepare file list
        $data = [
            'TODO',
        ];
        return $response->withStatus(200)
            ->write(json_encode($data));
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    function create(Request $request, Response $response, $args)
    {
        //TODO: store uploaded file, create a record and return it

        $uploadedFiles = $request->getUploadedFiles();

        // handle single input with single file upload
        $uploadedFile = $uploadedFiles['file'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile($uploadedFile);
            $response->write('uploaded ' . $filename . '<br/>');
        }


        $data = [
            'id'   => 111,
            'name' => 'file1.doc',
            'url'  => '',
        ];
        return $response->withStatus(200)
            ->write(json_encode($data));
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    function retrieve(Request $request, Response $response, $args)
    {
        //TODO: use $args['id']
        $data = [
            'TODO',
        ];
        return $response->withStatus(200)
            ->write(json_encode($data));
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    function update(Request $request, Response $response, $args)
    {
        //TODO: use $args['id']
        $data = [
            'TODO',
        ];
        return $response->withStatus(200)
            ->write(json_encode($data));
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    function delete(Request $request, Response $response, $args)
    {
        //TODO: use $args['id']
        $data = [
            'deleted' => true,
        ];
        return $response->withStatus(200)
            ->write(json_encode($data));
    }

    /**
     * Moves the uploaded file to the upload directory and assigns it a unique name
     * to avoid overwriting an existing uploaded file.
     *
     * @param UploadedFile $uploadedFile
     * @return string
     */
    private function moveUploadedFile(UploadedFile $uploadedFile)
    {
        $dir  = $this->get('upload_directory');
        $ext  = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $id   = $this->randStr(64);
        $name = sprintf('%s.%s', $id, $ext);

        $uploadedFile->moveTo($dir . '/' . $name);

        $this->writeMetaFile($uploadedFile, $id);

        return $name;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param string       $id
     * @return bool|int
     */
    private function writeMetaFile(UploadedFile $uploadedFile, $id)
    {
        $dir      = $this->get('upload_directory');
        $meta     = sprintf('%s.%s', $id, '.meta.json');
        $metaData = [
            'name'       => $uploadedFile->getClientFilename(),
            'media_type' => $uploadedFile->getClientMediaType(),
            'size'       => $uploadedFile->getSize(),
        ];
        $json   = json_encode($metaData);
        $result = file_put_contents($dir . '/' . $meta, $json);
        return $result;
    }

    /**
     * @param string $id
     * @return array
     */
    private function readMetaFile($id)
    {
        $directory = $this->get('upload_directory');
        $meta      = sprintf('%s.%s', $id, '.meta.json');
        $json      = file_get_contents($directory . '/' . $meta);
        $metaData  = json_decode($json, $assoc = true);
        return $metaData;
    }

    /**
     * @param int $len
     * @return string
     */
    private function randStr($len)
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