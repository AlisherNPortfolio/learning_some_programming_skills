<?php

function bubbleSort(&$data)
{
    $len = count($data);

    for ($i = 0; $i < $len; ++$i) {
        // if ($i > 0) {
        //     echo '<br/>';
        // }
        // echo '$i = '.$i.' ---------------------------------------<br/>';
        for ($j = 0; $j < $len - $i - 1; ++$j) {
            // if ($j > 0) {
            //     echo '<br/>';
            // }
            // echo '&nbsp; $j = '.$j.'<br/>';

            if ($data[$j] > $data[$j + 1]) {
                $temp = $data[$j];
                $data[$j] = $data[$j + 1];
                $data[$j + 1] = $temp;
            }

            // echo '&nbsp; &nbsp;'."$data[$j] > {$data[$j + 1]} ?".'&nbsp;[';
            // for ($k = 0; $k < $len; ++$k) {
            //     if ($data[$k] == $data[$j] || $data[$k] == $data[$j + 1]) {
            //         echo "<b>$data[$k]</b>";
            //     } else {
            //         echo "$data[$k]";
            //     }
            //     if ($k != $len - 1) {
            //         echo ',&nbsp;';
            //     }
            // }
            // echo ']<br/>';
        }
    }
}

$data = [1, 4, 6, -7, 234, 0, -4, -6, 44, 65, 12, -98, -84, 78]; // 14
echo '<pre>';
print_r($data);
echo '</pre>';
bubbleSort($data);
echo '<pre>';
print_r($data);
echo '</pre>';

/*
 * algoritm qadamlari
 *
 * $i = 0
 *  $j = 0
 *      1 > 4 ? [1, 4, ...]
 *
 * $i = 0;
 *  $j = 1
 *      4 > 6 ? [1, 4, 6, ...]
 *
 * $i = 0;
 *  $j = 2;
 *      6 > -7 ? [1, 4, -7, 6, ...]
 *
 * $i = 0;
 *  $j = 3;
 *      6 > 234 ? [1, 4, -7, 6, 234, ...]
 *
 * $i = 0;
 *  $j = 4;
 *      234 > 0 ? [1, 4, -7, 6, 0, 234, ...]
 *
 * $i = 0;
 *  $j = 5;
 *      234 > -4 ? [1, 4, -7, 6, 0, -4, 234, ...]
 *
 */
