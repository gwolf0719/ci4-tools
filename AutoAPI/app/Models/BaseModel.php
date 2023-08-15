<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    public function paginateArray($data, int $page = 1, int $items = 10,$pager=true)
    {
        // 確認 $items 並且確保它不為0，否則拋出異常
        if ($items <= 0) {
            throw new \InvalidArgumentException("Items per page must be greater than 0");
        }
        // 確保 $data 是一個陣列
        if (!is_array($data)) {
            $data = [];
        }
        // 轉換鍵為底線式命名
        
        $data = array_combine(
            array_map(function ($k) {
                return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $k))));
            }, array_keys($data)),
            $data
        );
        // 將 data 陣列中的 key 換成小寫
        $data = array_change_key_case($data, CASE_LOWER);

        $selectCols = $data['selectcols'] ?? '*';
        $sortCols = $data['sortcols'] ?? [];
        $whereCols = $data['wherecols'] ?? [];
        $whereInCols = $data['whereincols'] ?? [];
        $rangeCols = $data['rangecols'] ?? [];
        $offset = ($page - 1) * $items;
        $query = $this;

        // 檢查是否存在deleted_at欄位，如果存在則過濾掉deleted_at有值的內容
        $columns = $this->db->getFieldNames($this->table);
        if (in_array('deleted_at', $columns)) {
            $query = $query->where('deleted_at', null);
        }

        // 對每個可能的數據庫操作，添加異常處理
        try {
            if ($selectCols) {
                $query = $query->select($selectCols);
            }
            if ($sortCols) {
                foreach ($sortCols as $sortCol) {

                    foreach ($sortCol as $k => $v) {
                        $query = $query->orderBy($k, $v);
                    }
                }
            }
            if ($whereCols) {
                foreach ($whereCols as $whereCol) {
                    foreach ($whereCol as $k => $v) {
                        $query = $query->where($k, $v);
                    }
                }
            }
            if ($whereInCols) {
                
                foreach ($whereInCols as $whereInCol) {
                    foreach ($whereInCol as $k => $v) {
                        $query = $query->whereIn($k, $v);
                    }
                }
            }

            if($rangeCols){
                foreach ($rangeCols as $rangeCol) {
                    foreach ($rangeCol as $k => $v) {
                        $query = $query->where($k.' >=', $v[0]);
                        $query = $query->where($k.' <=', $v[1]);
                    }
                }
            }
            // 如果 pager 為 false，則不使用分頁
            if (!$pager) {
                $list = $query->findAll();
                return $list;
            } else {
                $total = $query->countAllResults(false);
                $list = $query->findAll($items, $offset);
            }
            
            // 印出SQL語法
            // echo $this->db->getLastQuery();
        } catch (\Exception $e) {
            // 如果遇到異常，拋出一個新的，具有更多信息的異常
            throw new \Exception("An error occurred while querying the database: " . $e->getMessage());
        }

        $pageTotal = ceil($total / $items);

        return [
            'list' => $list,
            'total' => $total,
            'items' => $items,
            'pageTotal' =>   $pageTotal,
            'page' => $page
        ];
    }
}
