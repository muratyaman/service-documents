<?php
/**
 * File for document controller
 */

namespace App\Http\Api;

use App\DocumentManager;
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

    const STATUS_OK                 = 200;
    const STATUS_CREATED            = 201;
    const STATUS_BAD_REQUEST        = 400;
    const STATUS_UNAUTHORIZED       = 401;
    const STATUS_PAYMENT_REQUIRED   = 402;
    const STATUS_FORBIDDEN          = 403;
    const STATUS_NOT_FOUND          = 404;
    const STATUS_METHOD_NOT_ALLOWED = 405;
    const STATUS_SERVER_ERROR       = 500;
    const STATUS_NOT_IMPLEMENTED    = 501;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var DocumentManager
     */
    private $documentMgr;

    /**
     * DocumentController constructor.
     * @param Container $container
     */
    public function __construct(Container $container) {
        $this->container = $container;
        //TODO: read config
        //$dir = $this->get('upload_directory');
        $this->documentMgr = new DocumentManager();//$dir);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    function index(Request $request, Response $response, $args)
    {
        //TODO: search/prepare file list
        $data = [
            'TODO',
        ];
        return $response->withStatus(static::STATUS_NOT_IMPLEMENTED)
            ->withJson($data);
    }

    /**
     * Store uploaded file, create a record and return it
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    function create(Request $request, Response $response, $args)
    {
        $output = ['error' => 'Bad request'];
        $status = static::STATUS_BAD_REQUEST;

        $files  = $request->getUploadedFiles();
        $meta   = $request->getParam('meta');
        if (isset($files['file'])) {
            /**
             * @var UploadedFile
             */
            $file            = $files['file'];
            $output['data']  = $this->documentMgr->storeUploadedFile($file, $meta);
            $output['error'] = null;
            $status          = static::STATUS_CREATED;
        } else {
            $output = ['error' => 'File is required'];
        }

        return $response->withStatus($status)
            ->withJson($output);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    function retrieve(Request $request, Response $response, $args)
    {
        if (empty($args['id'])) {
            return $response->withStatus(static::STATUS_BAD_REQUEST)
                ->withJson(json_encode(['error' => 'Missing document ID']));
        }
        $id = $args['id'];
        $doc = $this->documentMgr->readInfoFile($id);
        return $response->withStatus(static::STATUS_OK)
            ->withJson($doc);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    function download(Request $request, Response $response, $args)
    {
        //TODO: use $args['id']
        //TODO: return file
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
            ->withJson($data);
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
            ->withJson($data);
    }


}