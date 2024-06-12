<?php
// phpinfo(); die;
$listData = readFileData();
$listData = filterSimilarData($listData, 6);
renderFileForTool($listData);
print_r('Thành công!');
die;

function renderFileForTool($listData)
{
    $contentForTool = [];

    $listFields = [];
    foreach ($listData as $data) {
        if($data['delay'] != '1') {
            continue;
        }
    
        if($data['type'] != 'matrix') {
            continue;
        }

        if($data['usa'] == '—') {
            continue;
        }
        $listFields[] = $data['dataField'];
    }
    $max = 30;
    foreach ($listFields as $key => $field1) {
        
        foreach ($listFields as $field2) {
            print_r($max);
            if($max <= 0) {
                $max = 30;
                break;
            }
            if($field1 != $field2){
                $max--;
                $contentForTool[] = 'rank(ts_corr(' . trim($field1) . ', ' . trim($field2) . ', xxxxxxxx))';
                
            }
        }
        
        // for ($i=1; $i <= 2; $i++) { 
        //     $contentForTool[] = 'ts_skewness' . '(' . trim($field1) .', 60)';
        // }
        // unset($listFields[$key]);

        // $contentForTool[] = 'group_zscore' . '(' . trim($field1) .', industry)';
    }
    $contentForTool = array_splice($contentForTool, 0, 3000);// Chạy 3000 con đầu tiên

    $contentForToolSplit = array_chunk($contentForTool, round(count($contentForTool)/3));

    foreach ($contentForToolSplit as $key => $value) {
        file_put_contents(($key+1).'.txt', implode(PHP_EOL, $value));
    }
}

function readFileData()
{
    $content = file('data.txt');
    
    $listData = [];
    $index = 0;
    //
    foreach ($content as $key => $value) {
        $value = trim($value);
        if ($key%6 == 0) {
            $index++;
            $listData[$index]['dataField'] = $value;
            continue;
        }
        if ($key%6 == 2) {
            $listData[$index]['delay'] = $value;
            continue;
        }
        if ($key%6 == 3) {
            $listData[$index]['type'] = $value;
            continue;
        }
        if ($key%6 == 4) {
            $listData[$index]['usa'] = $value;
            continue;
        }
    }

    return $listData;
}

function filterSimilarData($listData, $rate)
{
    //Chưa xử lý theo yêu cầu
    foreach ($listData as $key1 => $value1) {
        foreach ($listData as $key2 => $value2) {
            if(similarDataField($value1['dataField'], $value2['dataField'], $rate) && $key1 != $key2){
                unset($listData[$key2]);
            }
        }
    }

    return $listData;
}

function similarDataField($field1, $field2, $rate)
{
    return $field1 == $field2;
}