# AutoAPI 功能
## 功能說明：  
```
php spark generate:model tablename
```
抓取 table 自動產生對應的 Model  

```
php spark generate:controller tablename
‵‵‵
自動產生 CRUD 的 controller 檔案

## 安裝方式：
將 app 資料夾完整放入

## 前提：
BaseController.php 中要包含 輸出處理 function
```

    protected function successResponse($data = null)
    {
        // 設定允許跨域
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Authorization, Authorize_token, Content-Type');

        header('Access-Control-Allow-Methods: *');
        $response = [
            'sysCode' => 200,
            'sysMsg' => '成功'
        ];
        if ($data !== null) {
            $response['data'] = $data;
        }

        return $this->response->setJSON($response);
    }

    protected function errorResponse($message, $code=500,$data=null)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Authorization, Authorize_token, Content-Type');

        header('Access-Control-Allow-Methods: *');
        $response = [
            'sysCode' => $code,
            'sysMsg' => $message
        ];
        if ($data !== null) {
            $response['data'] = $data;
        }
        return $this->response->setJSON($response);
    }

    protected function validationErrorResponse($errors)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Authorization, Authorize_token, Content-Type');

        header('Access-Control-Allow-Methods: *');
        $response = [
            'sysCode' => 400,
            'sysMsg' => $errors
        ];
       
        return $this->response->setJSON($response);
    }

```
