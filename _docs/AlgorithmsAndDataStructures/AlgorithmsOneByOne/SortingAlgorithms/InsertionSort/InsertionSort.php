<?php

function insertionSort($data)
{
    $len = count($data);
    $next = null;

    for ($i = 1; $i < $len; ++$i) { // tashqi loop
        $next = $data[$i];
        for ($j = $i - 1; $j >= 0; --$j) { // ichki loop
            if ($data[$j] > $next) { // kamayish tartibida saralash uchun > ni < ga almashtirish kerak
                $data[$j + 1] = $data[$j];
            } else {
                break;
            }
        }
        $data[$j + 1] = $next; // saralab bo'lingan elementlarning joriy o'rniga keyingi qiymatni qo'yish.
    }

    return $data;
}

$data = [1, 4, 6, -7, 234, 0, -4, -6, 44, 65, 12, -98, -84, 78]; // 14

echo '<pre>';
print_r(insertionSort($data));
echo '</pre>';
