<?php

// KEBAB CASE TO PASCAL CASE
// ----------------------------------------------------------------------------
// This test compares several functions to translate PascalCase strings to
// kebab-case strings. Which one's faster?

// Data -----------------------------------------------------------------------
$iterations = 1000 * 100;
$tests = [];
$lorem = 'morbi-congue-tellus-ut-ultricies-congue-suspendisse-potenti-integer-tellus-quam-varius-a-elementum-eu-mattis-vel-ex-aenean-lacus-tellus-hendrerit-vel-ligula-feugiat-gravida-suscipit-magna-vestibulum-purus-nisi-imperdiet-eu-vehicula-at-feugiat-tempus-enim-in-hac-habitasse-platea-dictumst-cras-dapibus-lorem-id-dolor-mollis-sed-facilisis-massa-aliquet-etiam-pellentesque-arcu-nibh-quis-ullamcorper-quam-hendrerit-non-quisque-condimentum-mauris-ac-tortor-elementum-a-mattis-justo-gravida-sed-sollicitudin-condimentum-orci-non-dapibus';

// Test ucwords() -------------------------------------------------------------
$test = [
    'label' => 'ucwords()',
    'time' => microtime(true)
];

function ucwordsKebabToPascal(string $kebab): string
{
    return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $kebab)));
}

for ($i = 0; $i < $iterations; $i++) ucwordsKebabToPascal($lorem);
$test['time'] = microtime(true) - $test['time'];
$tests[] = $test;

// Test preg_replace_callback() -----------------------------------------------
$test = [
    'label' => 'preg_replace_callback()',
    'time' => microtime(true)
];

function regexKebabToPascal(string $kebab): string
{
    return preg_replace_callback(
        '/(\-|^)([a-z])/',
        function ($matches) { return ucfirst($matches[2]); },
        $kebab
    );
}

for ($i = 0; $i < $iterations; $i++) regexKebabToPascal($lorem);
$test['time'] = microtime(true) - $test['time'];
$tests[] = $test;


// Test explode() + array_reduce() --------------------------------------------
$test = [
    'label' => 'explode()+array_reduce()',
    'time' => microtime(true)
];

function reduceKebabToPascal(string $kebab): string
{
    return array_reduce(explode('-',$kebab), function ($result, $word) {
        return $result .= ucfirst($word);
    } , '');
}

for ($i = 0; $i < $iterations; $i++) reduceKebabToPascal($lorem);
$test['time'] = microtime(true) - $test['time'];
$tests[] = $test;

// Test for loop --------------------------------------------------------------
$test = [
    'label' => 'for loop on letters',
    'time' => microtime(true)
];

function forLoopKebabToPascal(string $kebab): string
{
    $uppercase = ['a'=>'A','b'=>'B','c'=>'C','d'=>'D','e'=>'E','f'=>'F','g'=>'G','h'=>'H','i'=>'I','j'=>'J','k'=>'K','l'=>'L','m'=>'M','n'=>'N','o'=>'O','p'=>'P','q'=>'Q','e'=>'E','s'=>'S','t'=>'T','u'=>'U','v'=>'V','w'=>'W','x'=>'X','y'=>'Y','z'=>'Z'];
    $result = $uppercase[$kebab[0]];
    for ($i = 1, $len = strlen($kebab); $i < $len; $i++) {
        if ($kebab[$i] === '-') {
            $i++;
            $result .= $uppercase[$kebab[$i]];
        } else {
            $result .= $kebab[$i];
        }
    }
    return $result;
}

for ($i = 0; $i < $iterations; $i++) reduceKebabToPascal($lorem);
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
// ucwords()                : 1183 ms : 100%
// preg_replace_callback()  : 4182 ms : 353%
// explode()+array_reduce() : 2612 ms : 221%
// for loop on letters      : 2566 ms : 217%

// #2
// Kebab case to Pascal case test
// Iterations: 100000
// ==============================
// ucwords()                : 1173 ms : 100%
// preg_replace_callback()  : 3716 ms : 317%
// explode()+array_reduce() : 2782 ms : 237%
// for loop on letters      : 3116 ms : 266%

// #3
// Kebab case to Pascal case test
// Iterations: 100000
// ==============================
// ucwords()                : 1338 ms : 100%
// preg_replace_callback()  : 3662 ms : 274%
// explode()+array_reduce() : 2903 ms : 217%
// for loop on letters      : 2518 ms : 188%

// #4
// Kebab case to Pascal case test
// Iterations: 100000
// ==============================
// ucwords()                : 1119 ms : 100%
// preg_replace_callback()  : 3618 ms : 323%
// explode()+array_reduce() : 2551 ms : 228%
// for loop on letters      : 2478 ms : 221%
