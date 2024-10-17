<?php
function findLatex($text) {
    $matches = [];
    $in_math = false;
    $start = 0;
    for ($i = 0; $i < strlen($text); $i++) {
        if ($text[$i] === '$' && $text[$i+1] === '$' && !$in_math) {
            $in_math = true;
            $start = $i;
            $i++;
        } elseif ($text[$i] === '$' && $text[$i+1] === '$' && $in_math) {
            $in_math = false;
            $matches[] = substr($text, $start, $i - $start + 2);
            $i++;
        } elseif ($text[$i] === '$' && !$in_math) {
            $in_math = true;
            $start = $i;
        } elseif ($text[$i] === '$' && $in_math) {
            $in_math = false;
            $matches[] = substr($text, $start, $i - $start + 1);
        } elseif (strpos($text, '\\begin{equation}', $i) === $i) {
            $end_pos = strpos($text, '\\end{equation}', $i);
            if ($end_pos !== false) {
                $matches[] = substr($text, $i, $end_pos - $i + 14);
                $i = $end_pos + 13;
            }
        } elseif (strpos($text, '\[', $i) === $i) {
            $end_pos = strpos($text, '\]', $i);
            if ($end_pos !== false) {
                $matches[] = substr($text, $i, $end_pos - $i + 2);
                $i = $end_pos + 1;
            }
        } elseif ($text[$i] === '[' && $text[$i+1] === '[') {
            $in_math = true;
            $start = $i;
            $i++;
        } elseif ($i + 1 < strlen($text) && $text[$i] === ']' && $text[$i+1] === ']' && $in_math) {
            $in_math = false;
            $matches[] = substr($text, $start, $i - $start + 2);
            $i++;
        }
    }
    return $matches;
}

$text = '这是一个公式 $E=mc^2$ 和一个 display 公式 $$\int_0^1 x^2 dx$$ 以及一个环境公式 \begin{equation} a^2 + b^2 = c^2 \end{equation}  and a new formula \[\sum_{i=1}^n i^2\]';
$matches = findLatex($text);
var_dump($matches);
?>
