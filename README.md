BusyPHP ApiDoc 说明
===============

> 本辅助插件主要用于接口文档自动化生成

## 书写规范

### 控制器

控制器类必须书写注释，且必须写到 `class className {` 之上，书写格式为：

```php
/**
 * 这里写接口分组说明
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
     * @example {list:[], page: 1} 这里是输出示例，可以复制测试结果的JSON字符串至此
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
        
        return $apiDoc->renderToHTML('XX项目接口文档');
    }
}
```