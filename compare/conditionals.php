<?php

$tests = [];
$data = [];
$iterations = 1000 * 1000; // Change if needed

// Fill-in the test value with lots of useless data!
for ($i = 0; $i < $iterations; $i++) $data[$i] = $i;

// Ternary operator -----------------------------------------------------------
$test = [
    'label' => 'Ternary operator',
    'time' => microtime(true)
];
for ($i = 0; $i < $iterations; $i++) {
    $useless = isset($data[$i]) ? $data : false;
}
$test['time'] = microtime(true) - $test['time'];
$tests[] = $test;

// If statement ---------------------------------------------------------------
$test = [
    'label' => 'If statement',
    'time' => microtime(true)
];
for ($i = 0; $i < $iterations; $i++) {
    if (isset($data[$i])) $useless = $data;
    else $useless = false;
}
$test['time'] = microtime(true) - $test['time'];
$tests[] = $test;

// RESULTS --------------------------------------------------------------------

// Get the best time and the longest label (for presentation purposes)
$bestTime = $tests[0]['time'];
$labelLen = 0;
foreach ($tests as &$test) {
    if ($test['time'] < $bestTime) $bestTime = $test['time'];
    if (strlen($test['label']) > $labelLen) $labelLen = strlen($test['label']);
}

// Output the results
echo array_reduce($tests, function ($log, $test) use ($labelLen, $bestTime) {
    $label = str_pad($test['label'], $labelLen, ' ', STR_PAD_RIGHT);
    $time = 1000*number_format($test['time'], 3);
    $time = str_pad($time, 3, '0', STR_PAD_LEFT) . ' ms';
    $percent = number_format(100*$test['time']/$bestTime,0).'%';
    return $log .= "{$label} : {$time} : {$percent}\n";
}, '');

// RESULTS
// =======
// Ternary operator : 073 ms : 142%
// If statement     : 052 ms : 100%
