<?php

namespace BusyPHP\apidoc\test;

use BusyPHP\App;
use BusyPHP\exception\AppException;
use BusyPHP\helper\net\Http;
use BusyPHP\helper\util\Str;
use think\response\Html;

/**
 * 测试
 * @author busy^life <busy.life@qq.com>
 * @copyright (c) 2015--2019 ShanXi Han Tuo Technology Co.,Ltd. All rights reserved.
 * @version $Id: 2020/11/7 下午12:07 上午 Test.php $
 */
class Test
{
    /**
     * 执行测试
     */
    public function exec()
    {
        $request = request();
        $method  = $request->post('__api_doc_method__');
        $root    = $request->post('__api_doc_root__');
        $headers = $request->post('__api_doc_headers__');
        $globals = $request->post('__api_doc_globals__');
        $params  = $request->post('__api_doc_params__');
        $path    = $request->post('__api_doc_path__');
        
        
        $url     = $root . $path;
        $params  = is_array($params) ? $params : [];
        $globals = is_array($globals) ? $globals : [];
        $headers = is_array($headers) ? $headers : [];
        $params  = array_merge($params, $globals);
        
        $http = new Http();
        if ($headers) {
            $http->setHeaders($headers);
        }
        
        // 上传文件
        if (isset($_FILES['__api_doc_params__'])) {
            $files    = $_FILES['__api_doc_params__'];
            $fileList = [];
            foreach ($_FILES['__api_doc_params__']['name'] as $key => $val) {
                $fileList[$key] = [
                    'name'     => $val,
                    'type'     => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error'    => $files['error'][$key],
                    'size'     => $files['size'][$key],
                ];
                
                if (!is_uploaded_file($files['tmp_name'][$key])) {
                    abort(404, '上传文件异常');
                }
                if ($files['error'][$key] != 0) {
                    abort(404, '上传文件失败');
                }
            }
            
            $httpFiles = [];
            $httpPath  = App::runtimeUploadPath('api_doc' . DIRECTORY_SEPARATOR . Str::uuid());
            if (!is_dir($httpPath)) {
                if (!mkdir($httpPath, 0775, true)) {
                    abort(404, '没有写入权限' . $httpPath);
                }
            }
            
            foreach ($fileList as $key => $file) {
                $httpFiles[$key] = $httpPath . $file['name'];
                if (!move_uploaded_file($file['tmp_name'], $httpFiles[$key])) {
                    abort(404, '移动文件失败' . $httpPath[$key]);
                }
            }
            
            foreach ($httpFiles as $key => $file) {
                $http->addFile($key, $file);
            }
        }
        
        try {
            switch (strtolower($method)) {
                // post json
                case 'postjson':
                case 'post_json':
                case 'json':
                    $result = Http::postJSON($url, $params, $http);
                break;
                
                // post xml
                case 'postxml':
                case 'post_xml':
                case 'xml':
                    $result = Http::postXML($url, $params, $http);
                break;
                
                // post
                case 'post':
                    $result = Http::post($url, $params, $http);
                break;
                
                // get
                default:
                    $result = Http::get($url, $params, $http);
            }
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }
        
        Html::create($result)->send();
        exit;
    }
}