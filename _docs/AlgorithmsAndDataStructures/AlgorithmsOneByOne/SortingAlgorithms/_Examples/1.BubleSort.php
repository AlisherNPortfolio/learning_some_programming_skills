<?php

function BubbleSort(array &$data)
{
    $len = count($data);

    for ($i = 0; $i < $len - 1; ++$i) {
        for ($j = 0; $j < $len - $i - 1; ++$j) {
            if ($data[$j] > $data[$j + 1]) {
                $temp = $data[$j];
                $data[$j] = $data[$j + 1];
                $data[$j + 1] = $temp;
            }
        }
    }
}

$array = [5, 3, 4, 2, 1];
bubbleSort($array);

echo '<pre>';
print_r($array);
echo '</pre>';
