<?php 

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModTableName;

class TableName extends BaseController{
    protected $tablename;
    function __construct(){
        $this->tablename = new ModTableName();
    }
    /**
     * @api {post} /TableName/ TableName-搜尋所有資料
     * @apiName 搜尋所有資料
     * @apiGroup TableName
     * @apiVersion 1.0.0
     * 
     * @apiSampleRequest TableName
     * 
     
     * @apiBody {String} [selectCols] 選擇欄位 
     * @apiBody {String} [sortCols] 排序欄位 
     * @apiBody {String} [whereCols] 條件欄位
     * @apiBody {String} [rangeCols] 範圍欄位
     * @apiBody {Integer} [page] 目前頁數
     * @apiBody {Integer} [items] 每頁筆數
     * 
     * apiParamExample {json} Request-Example:
     * {
     *   "selectCols": [
     *     "work_order_type",
     *      "headquarter_id",
     *      "plate_number",
     *      "mileage",
     *      "total_amount"
     *   ],
     *   "sortCols": [
     *     {
     *       "created_at":"desc"
     *     }
     *   ],
     *   "whereCols": [
     *     {
     *       "work_order_type":"1"
     *     }
     *   ],
     *  "rangeCols":[
     *      {
     *          "repair_date":["2023-07-28","2023-07-28"]
     *    }
     *  ],
     *   "page": 1,
     *   "items": 10
     * }
     * 
     * @apiSuccess {String} sysCode 狀態
     * @apiSuccess {String} sysMsg 訊息
     * @apiSuccess {String} data 資料
     * @apiSuccess {String} data.total 總筆數
     * @apiSuccess {String} data.page 目前頁數
     * @apiSuccess {String} data.items 每頁筆數
     * @apiSuccess {String} data.list 資料
     * 
     */
    function index(){
        $data = $this->request->getJSON(true);
        $page = (int) ($data['page'] ?? 1);
        $items = (int) ($data['items'] ?? 10);
        try {
            $listdata = $this->tablename->paginateArray($data, $page, $items);
            return $this->successResponse($listdata);
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    /**
     * @api {get} /TableName/get/:pkid TableName-取得單筆資料
     * @apiName 取得單筆資料
     * @apiGroup TableName
     * @apiVersion 1.0.0
     * 
     * @apiSampleRequest TableName/get/:pkid
     * 
     * @apiParam {String} pkid 主鍵
     * 
     */
    function get($pkid){
        // 先確認是否有資料
        if(!$this->tablename->exists($pkid)){
            return $this->errorResponse('查無資料', 404);
        }
        try {
            $data = $this->tablename->find($pkid);
            return $this->successResponse($data);
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * @api {post} /TableName/create TableName-新增資料
     * @apiName 新增資料
     * @apiGroup TableName
     * @apiVersion 1.0.0
     * @apiSampleRequest TableName/create
     * 
     * @apiBody {String} [col1] 欄位1
     * @apiBody {String} [col2] 欄位2
     * @apiBody {String} [col3] 欄位3
     * 
     * 
     * 
     */
    function create(){
        $data = $this->request->getJSON(true);
        
        try {
            $this->tablename->insert($data);
            return $this->successResponse();
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    /**
     * @api {post} /TableName/update/:pkid TableName-更新資料
     * @apiName 更新資料
     * @apiGroup TableName
     * @apiVersion 1.0.0
     * @apiSampleRequest TableName/update/:pkid
     * 
     * @apiParam {String} pkid 主鍵
     * 
     * @apiBody {String} [col1] 欄位1
     * @apiBody {String} [col2] 欄位2
     */
    function update($pkid){
        $data = $this->request->getJSON(true);
        // 先確認是否有資料
        if(!$this->tablename->exists($pkid)){
            return $this->errorResponse('查無資料', 404);
        }
        try {
            $this->tablename->update($pkid, $data);
            return $this->successResponse();
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * @api {get} /TableName/delete/:pkid TableName-刪除資料
     * @apiName 刪除資料
     * @apiGroup TableName
     * @apiVersion 1.0.0
     * @apiSampleRequest TableName/delete/:pkid
     * 
     * @apiParam {String} pkid 主鍵
     * 
     */
    function delete($pkid){
        // 先確認是否有資料
        if(!$this->tablename->exists($pkid)){
            return $this->errorResponse('查無資料', 404);
        }
        try {
            $this->tablename->delete($pkid);
            return $this->successResponse();
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}