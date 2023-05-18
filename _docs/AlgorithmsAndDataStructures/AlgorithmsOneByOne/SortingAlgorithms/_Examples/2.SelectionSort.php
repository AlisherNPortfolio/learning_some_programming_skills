<?php

function SelectionSort(array &$data)
{
    $len = count($data);

    for ($i = 0; $i < $len; ++$i) {
        $low = $i;
        for ($j = $i + 1; $j < $len; ++$j) {
            if ($data[$j] < $data[$low]) {
                $low = $j;
            }

            if ($data[$i] > $data[$low]) {
                $temp = $data[$low];
                $data[$low] = $data[$i];
                $data[$i] = $temp;
            }
        }
    }
}

$array = [25, 22, 27, 15, 19];

SelectionSort($array);

echo '<pre>';
print_r($array);
echo '</pre>';
