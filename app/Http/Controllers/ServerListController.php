<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column\Rule;

class ServerListController extends Controller
{
    public function getAllServers($params=null){
        
        if(!empty($params)){
            $paramsArr = explode("/",$params);
            if(count($paramsArr) < 2){
                return response()->json(['message' => 'Insufficient API parameters'], 404);
            }
            $filterBy = $paramsArr[0];
            $filterVal = $paramsArr[1];
            if(count($paramsArr) > 2){
                $maxVal = $paramsArr[2];
            }

            $maps = array('location' => 'D', 'harddisk' => 'G', 'ram' => 'F', 'storage' => 'I');
            if(!array_key_exists(strtolower($filterBy), $maps)){
                return response()->json(['message' => 'Invalid API parameters'], 404);
            }
            $col = $maps[strtolower($filterBy)];
        }
        
        //$path = storage_path().'\documents\LeaseWeb_servers_filters_assignment.xlsx';
        $path = storage_path().'LeaseWeb_servers_filters_assignment.xlsx';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);

        $spreadsheet->setActiveSheetIndex(0);
        
        if(isset($col)){
            $spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());
            $autoFilter = $spreadsheet->getActiveSheet()->getAutoFilter();
            $columnFilter = $autoFilter->getColumn($col);
            if(isset($maxVal)){
                $columnFilter->setFilterType(Column::AUTOFILTER_FILTERTYPE_CUSTOMFILTER);
                $columnFilter->createRule()->setRule(Rule::AUTOFILTER_COLUMN_RULE_LESSTHANOREQUAL,$maxVal)->setRuleType(Rule::AUTOFILTER_RULETYPE_CUSTOMFILTER);
                $columnFilter->createRule()->setRule(Rule::AUTOFILTER_COLUMN_RULE_GREATERTHANOREQUAL,$filterVal)->setRuleType(Rule::AUTOFILTER_RULETYPE_CUSTOMFILTER);
                $columnFilter->setJoin(Column::AUTOFILTER_COLUMN_JOIN_AND);
            }else{
                $columnFilter->setFilterType(Column::AUTOFILTER_FILTERTYPE_FILTER);
                $columnFilter->createRule()->setRule(Rule::AUTOFILTER_COLUMN_RULE_EQUAL,$filterVal);
            }
            $autoFilter->showHideRows();
        }
        
        foreach ($spreadsheet->getActiveSheet()->getRowIterator() as $row) {
            if ($spreadsheet->getActiveSheet()->getRowDimension($row->getRowIndex())->getVisible()) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(TRUE);
                $temp = array();
                foreach ($cellIterator as $cell) {
                     $temp[] = $cell->getCalculatedValue();
                }
                $data_detail[] = $temp;
            }
        }

        return array('data' => $data_detail);

    }
}
