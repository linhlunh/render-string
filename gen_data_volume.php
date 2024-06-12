<?php
// phpinfo(); die;
$listData = readFileData();

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

        // if($data['usa'] == '—') {
        //     continue;
        // }
        $listFields[] = $data['dataField'];
    }
    array_unique($listFields);
    foreach ($listFields as $key => $field1) {
        
        $contentForTool[] = 'ts_delta' . '(' . trim($field1) . ', 1)';
    }
    // $contentForTool = array_splice($contentForTool, 0, 3000);// Chạy 3000 con đầu tiên

    $contentForToolSplit = array_chunk($contentForTool, round(count($contentForTool)/3));

    foreach ($contentForToolSplit as $key => $value) {
        file_put_contents(($key+1).'.txt', implode(PHP_EOL, $value));
    }
}

function readFileData()
{
    $content = file('data_volume.txt');
    
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

function filterSimilarData($listData)
{
    return array_unique($listData);
}

function similarDataField($field1, $field2)
{
    return $field1 == $field2;
}