# Bleach-Http
Client http
```php
use ClearSwitch\BleachHttp\Request;
$request=new Request();
//设置请求的地址
$request->setUrl("url");
//设置请求通道，支持curl
$request->setRequestAisle("curl");
// 可以添加自己的通道分两步1.先注册通道，2设置通道
$request->addAisle('通道名字','回调')
$request->setRequestAisle("通道名字")
//设置请求头
$request->setHeader(['Content-Type'=>'application/json']);
//设置代理
$request->setProxy('host','port');
//设置请求体的格式
//暂时支持 'json', 'xml', 'form_data', 'urlencoded'的body
$request->setContent([],'json');
//如果上传文件的时候， 文件需要打标
$data = [
            'file' => TagParam::file('/Users/daikai/project/php/logisticApi/.env')
        ];
//设置请求方法默认是get
$request->setMethod("post");
//设置请求超时时间 默认10
$request->setTimeOut(300);
//这些设置都是可以链式写发的
$request->setUrl('')->setMethod('post')->send();
//发送请求
$response=$request->send();
```
# 响应
```php
 use ClearSwitch\BleachHttp\Request;
 $request=new Request();
 $response=$request->send();
 //获得响应的数据，帮你转为数组
 $response->getBody();
//获得响应的原样的body
 $response->getRowContent();
//获得cookies
 $response->getCookies();
//获得响应的头，转为数据
 $response->getHeaders();
//原样输出响应头
 $response->getRawHeaders();
//获得响应的code 
 $response->getStatusCode();
//获得相依的完成数据
$response->getRowResponse();
//发送完请求是可以直接链式写的
$request->send()->getBody();
```
#批量请求
```php
use ClearSwitch\BleachHttp\BatchRequest;
use ClearSwitch\BleachHttp\Request;
$batch=new BatchRequest();
$requests = [];

for($i = 0; $i < 100; $i++){
    $request = new Request();
    $request->setUrl('');
    $request->setContent([]);
    $requests[] = $request;
}
//设置批量请求
//参数2是选填的，用来设置并发数，默认的并发是50
$batch->setRequests($requests,3);
//发送批量请求
$responses = $batch->send();
foreach ($responses as $response){
 //获得响应的数据，帮你转为数组
  $response->getBody();
//...获得结果其他方法和上面的request 一致
}
```

