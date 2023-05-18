<?php

function InsertionSort(array &$data)
{
    $len = count($data);

    for ($i = 0; $i < $len; ++$i) {
        $x = $data[$i];

        $j = $i - 1;
        while ($j >= 0 && $data[$j] > $x) {
            $data[$j + 1] = $data[$j];
            $j = $j - 1;
        }

        $data[$j + 1] = $x;
    }
}

$array = [12, 11, 13, 5, 6];

InsertionSort($array);

echo '<pre>';
print_r($array);
echo '</pre>';
