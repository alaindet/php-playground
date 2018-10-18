<?php

// KEBAB CASE TO PASCAL CASE
// ----------------------------------------------------------------------------
// This test compares two functions to translate PascalCase strings to
// kebab-case strings. The first function uses a regex approach via
// preg_replace_callback(), while the second function uses array splitting and
// gluing via explode() and array_reduce(). Which one's faster?


// Data -----------------------------------------------------------------------
$iterations = 1000 * 100;
$tests = [];
$lorem = 'morbi-congue-tellus-ut-ultricies-congue-suspendisse-potenti-integer-tellus-quam-varius-a-elementum-eu-mattis-vel-ex-aenean-lacus-tellus-hendrerit-vel-ligula-feugiat-gravida-suscipit-magna-vestibulum-purus-nisi-imperdiet-eu-vehicula-at-feugiat-tempus-enim-in-hac-habitasse-platea-dictumst-cras-dapibus-lorem-id-dolor-mollis-sed-facilisis-massa-aliquet-etiam-pellentesque-arcu-nibh-quis-ullamcorper-quam-hendrerit-non-quisque-condimentum-mauris-ac-tortor-elementum-a-mattis-justo-gravida-sed-sollicitudin-condimentum-orci-non-dapibus';


// Regex function -------------------------------------------------------------
function regexKebabToPascal(string $kebab): string
{
    return preg_replace_callback(
        '/(\-|^)([a-z])/',
        function ($matches) { return ucfirst($matches[2]); },
        $kebab
    );
}


// Array split function -------------------------------------------------------
function reduceKebabToPascal(string $kebab): string
{
    return array_reduce(explode('-',$kebab), function ($result, $word) {
        return $result .= ucfirst($word);
    } , '');    
}


// Test preg_replace_callback() -----------------------------------------------
$test = [
    'label' => 'preg_replace_callback()',
    'time' => microtime(true)
];
for ($i = 0; $i < $iterations; $i++) {
    regexKebabToPascal($lorem);
}
$test['time'] = microtime(true) - $test['time'];
$tests[] = $test;


// Test explode() + array_reduce() --------------------------------------------
$test = [
    'label' => 'explode()+array_reduce()',
    'time' => microtime(true)
];
for ($i = 0; $i < $iterations; $i++) {
    reduceKebabToPascal($lorem);
}
$test['time'] = microtime(true) - $test['time'];
$tests[] = $test;


// Results --------------------------------------------------------------------

// Get the best time and the longest label (for presentation purposes)
$bestTime = $tests[0]['time'];
$labelLen = 0;
foreach ($tests as &$test) {
    if ($test['time'] < $bestTime) $bestTime = $test['time'];
    if (strlen($test['label']) > $labelLen) $labelLen = strlen($test['label']);
}

// Output the results
echo array_reduce(
    $tests,
    function ($log, $test) use ($labelLen, $bestTime) {
        $label = str_pad($test['label'], $labelLen, ' ', STR_PAD_RIGHT);
        $time = 1000 * number_format($test['time'], 3);
        $time = str_pad($time, 3, '0', STR_PAD_LEFT) . ' ms';
        $percent = number_format(100*$test['time']/$bestTime,0).'%';
        return $log .= "{$label} : {$time} : {$percent}\n";
    },
    "Kebab case to Pascal case test\n".
    "Iterations: {$iterations}\n".
    "==============================\n"
);

// #1
// Kebab case to Pascal case test
// Iterations: 100000
// ==============================
// preg_replace_callback()  : 3509 ms : 114%
// explode()+array_reduce() : 3089 ms : 100%

// #2
// Kebab case to Pascal case test
// Iterations: 100000
// ==============================
// preg_replace_callback()  : 3955 ms : 110%
// explode()+array_reduce() : 3606 ms : 100%

// #3
// Kebab case to Pascal case test
// Iterations: 100000
// ==============================
// preg_replace_callback()  : 4165 ms : 130%
// explode()+array_reduce() : 3194 ms : 100%

// Average: explode()+array_reduce() is 18% faster than preg_replace_callback()
