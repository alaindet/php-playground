<?php

// Data (change here)
$min = 1;
$max = 100;
$numbers = 50;
$iterations = 1000 * 1000;
$tests = [];
$random = [];

// Populate initial array with random data
for ($i = 0; $i < $numbers; $i++) {
    $randomData[] = random_int($min, $max);
}

// While (reverse) ------------------------------------------------------------
$test = [
    'label' => 'While (reverse)',
    'time' => microtime(true),
    'data' => $random
];
for ($i = 0; $i < $iterations; $i++) {

    // Test loop
    $j = count($test['data']);
    while ($j--) {
        $test['data'][$j]++;
    }

}
$test['time'] = microtime(true) - $test['time'];
$tests[] = $test;

// For loop -------------------------------------------------------------------
$test = [
    'label' => 'For loop',
    'time' => microtime(true),
    'data' => $random
];
for ($i = 0; $i < $iterations; $i++) {

    // Test loop
    for ($j = 0; $j < count($test['data']); $j++) {
        $test['data'][$j]++;
    }

}
$test['time'] = microtime(true) - $test['time'];
$tests[] = $test;

// For loop (optimized) -------------------------------------------------------
$test = [
    'label' => 'For loop (cache count)',
    'time' => microtime(true),
    'data' => $random
];
$count = count($test['data']); // Cache the loop count for optimization!
for ($i = 0; $i < $iterations; $i++) {

    // Test loop
    for ($j = 0; $j < $count; $j++) {
        $test['data'][$j]++;
    }

}
$test['time'] = microtime(true) - $test['time'];
$tests[] = $test;


// Foreach --------------------------------------------------------------------
$test = [
    'label' => 'Foreach',
    'time' => microtime(true),
    'data' => $random
];
for ($i = 0; $i < $iterations; $i++) {

    // Test loop
    foreach ($test['data'] as &$random) {
        $random++;
    }

}
$test['time'] = microtime(true) - $test['time'];
$tests[] = $test;

// array_map ------------------------------------------------------------------
$test = [
    'label' => 'Array_map',
    'time' => microtime(true),
    'data' => $random
];
for ($i = 0; $i < $iterations; $i++) {

    // Test loop
    $test['data'] = array_map(
        function ($element) { return $element++; },
        $test['data']
    );

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
// ======================================
// While (reverse)        : 069 ms : 188%
// For loop               : 056 ms : 151%
// For loop (cache count) : 037 ms : 100%
// Foreach                : 073 ms : 197%
// Array_map              : 227 ms : 614%
