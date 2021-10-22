<?php

namespace BusyPHP\apidoc;

use think\Response;
use think\Route;

class Service extends \think\Service
{
    public function boot()
    {
        $this->registerRoutes(function(Route $route) {
            // 资源路由
            $route->rule('assets/plugins/apidoc/<path>', function($path) {
                $parse = parse_url($path);
                $path  = $parse['path'] ?? '';
                $file  = __DIR__ . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'static' . DIRECTORY_SEPARATOR . ltrim($path, '/');
                if (!$path || !is_file($file)) {
                    return Response::create('资源不存在', 'html', 200)->contentType('text/plain');
                }
                
                // 判断类型
                switch (strtolower((string) pathinfo($path, PATHINFO_EXTENSION))) {
                    case 'css':
                        $mimeType = 'text/css';
                    break;
                    case 'js':
                        $mimeType = 'application/x-javascript';
                    break;
                    case 'png':
                        $mimeType = 'image/png';
                    break;
                    default:
                        $mimeType = 'application/octet-stream';
                }
                
                return Response::create(file_get_contents($file), 'html', 200)->contentType($mimeType);
            })->pattern(['path' => '.*']);
        });
    }
}
