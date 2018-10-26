<?php

// Data (change here)
$iterations = 1000 * 100;
$tests = [];

// Populate initial array with random data
$a = [];
$b = [];
$c = [];
for ($i = 0; $i < 50; $i++) {
    $a[] = random_int(1, 100);
    $b[] = random_int(1, 100);
    $c[] = random_int(1, 100);
}

// array_merge() --------------------------------------------------------------
$test = [
    'label' => 'array_merge()',
    'time' => microtime(true)
];
for ($i = 0; $i < $iterations; $i++) {

    // Test loop
    $result = array_merge($a, $b, $c);

}
$test['time'] = microtime(true) - $test['time'];
$tests[] = $test;


// foreach --------------------------------------------------------------------
$test = [
    'label' => 'foreach',
    'time' => microtime(true)
];
for ($i = 0; $i < $iterations; $i++) {

    // Test loop
    $result = $a;
    foreach ($b as $bValue) $result[] = $bValue;
    foreach ($c as $cValue) $result[] = $cValue;

}
$test['time'] = microtime(true) - $test['time'];
$tests[] = $test;


// for ------------------------------------------------------------------------
$test = [
    'label' => 'for',
    'time' => microtime(true)
];

$bCount = count($b);
$cCount = count($c);

for ($i = 0; $i < $iterations; $i++) {

    // Test loop
    $result = $a;
    for ($j = 0; $j < $bCount; $j++) { $result[] = $b[$j]; }
    for ($k = 0; $k < $cCount; $k++) { $result[] = $c[$k]; }

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
}, implode('', [
    "\nArray union comparison",
    "\n======================",
    "\nIterations: {$iterations}\n\n"
]));

// RESULTS
// Array union comparison
// ======================
// Iterations: 100000

// array_merge() : 052 ms : 100%
// foreach       : 535 ms : 1,020%
// for           : 475 ms : 906%
