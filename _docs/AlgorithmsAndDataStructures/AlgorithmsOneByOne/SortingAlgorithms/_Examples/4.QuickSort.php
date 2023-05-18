<?php

function pr($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre><br/>';
    // die;
}
function Partition(array $data, int $low, int $high)
{
    $pivot = $data[$high];

    $i = $low - 1;
    for ($j = $low; $j <= $high - 1; ++$j) {
        if ($data[$j] < $pivot) {
            ++$i;
            $temp = $data[$i];
            $data[$i] = $data[$j];
            $data[$j] = $temp;
        }
    }

    $temp2 = $data[$i + 1];
    $data[$i + 1] = $data[$high];
    $data[$high] = $temp2;

    return $i + 1;
}

function QuickSort(array &$data, int $low, int $high)
{
    if ($low >= $high || $low < 0) {
        return;
    }

    $middle = Partition($data, $low, $high);
    // pr($middle);
    QuickSort($data, $low, $middle - 1);
    QuickSort($data, $middle + 1, $high);
}

$array = [14, 21, 5, 2, 3, 19];
$len = count($array);
QuickSort($array, 0, $len - 1);

pr($array);
