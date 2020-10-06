BusyPHP ApiDoc 说明
===============

> 本辅助插件主要用于接口文档自动化生成

## 书写规范

### 保留关键字

> `@`、`#`、`*` <br />
> 书写中如果必须使用以上关键字，请转义输入，如：`\@`，`\#`，`\*`

### 控制器

控制器类必须书写注释，且必须写到 `class className {` 之上，书写格式为：

```php
/**
 * 这里写接口分组名称
 * @desc 分组说明，说明这个分组的作用
 */
class TestController extends \BusyPHP\Controller {

}
```

### 方法

API接口方法必须书写注释，且必须写到 `public function functionName() {` 之上

#### 参数说明

> @api 声明接口请求方式，如get 或 post，如：`@api post`<br />
> @param 申明请求参数，可以写多个，格式：`@param 参数类型 参数名称 参数说明 [二级参数..]`，参数前面加 "*" 号代表这个参数是必填的。二级参数格式：`# 参数名称 : 参数说明`<br />
> @return 声明返回类型和结构，格式：`返回类型 [返回结构...]`，目前支持 `Json`返回类型，如：`@return Json` 返回结构格式：`# 参数名称 参数类型 : 参数说明 [多级参数...]` 几个"#"号代表几级参数，只有`array`,`object`参数类型才会解析多级参数
> @example 声明输出示例，可以直接复制测试接口返回的内容
> @desc 接口说明


```php
class TestController extends \BusyPHP\Controller {
    
    // 以下是书写格式

    /**
     * 这里写接口说明，如：获取用户资料
     * @api post
     * @param int *test 参数1说明
     * @param string test2 参数2说明  [
     *     # type_a: 用户ID
     *     # type_b: 用户姓名
     * ]
     * @param string test3 参数3....
     * @return Json [
     *      # list array: 消息集合  [
     *          ## id string: 消息ID
     *          ## title string: 消息标题
     *          ## read boolean: 是否已读
     *          ## operate object: 操作结构 [
     *              ### type int: 操作类型，`1 打开页面`，`2 打开内部浏览器`，`3 打开外部浏览器`
     *              ### value string: 操作内容
     *          ]
     *      ]
     * ]
     * @example {list:[], page: 1}
     * @desc 说明这个接口的功能等
     */
    public function test() {
    
    }
}
```

## 生成文档

在对应要展示文档的控制中使用

```php
class ShowController extends \BusyPHP\Controller {
    
    /**
     * 展示接口文档
     */
    public function doc() {

        $apiDoc = new \BusyPHP\apidoc\ApiDoc('要解析的类文件路径', '要解析的类名等');
        $apiDoc->getScan()->removeSuffix('移除控制器类名指定的后缀', '移除方法名指定的后缀');
    
        $apiDoc->getScan()->removeSuffix(function($name, $class) {
            // 闭包处理控制器类名称
            return '处理后的类名称';
        }, function($name, $class) {
            // 闭包处理方法名称
            return '处理后的方法名称';
        });
    
        $apiDoc->getInfo()->setTitle('文档标题');            
        $apiDoc->getInfo()->setDebugRootUrl('测试模式入口地址');            
        $apiDoc->getInfo()->setReleaseRootUrl('发布模式入口地址');            
        $apiDoc->getInfo()->setDescription('文档说明'); 
    
        // 添加错误代码
        $apiDoc->getInfo()->addErrorCode('错误代码', '说明', '解决方法');

        // 全局请求头参数            
        $apiDoc->getInfo()->addHeader('header_params', true, '请求头参数说明');

        // 添加全局返回结构
        $apiDoc->getInfo()->addReturn(new \BusyPHP\apidoc\structures\ReturnItem('参数', '类型', new \BusyPHP\apidoc\structures\DataStructure('参数说明')));

        // 添加全局参数        
        $apiDoc->getInfo()->addParam(new \BusyPHP\apidoc\structures\ParamItem('param1', 'boolean', true, new \BusyPHP\apidoc\structures\DataStructure('参数说明'))); 
           
        // 添加附录        
        $apiDoc->getInfo()->addAppendix(new \BusyPHP\apidoc\structures\AppendixItem('附录1', '附录1描述'));            

        
        return $apiDoc->renderToHTML('XX项目接口文档');
    }
}
```