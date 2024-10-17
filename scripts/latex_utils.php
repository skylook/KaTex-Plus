<?php
function revert_latex_math($latex_math) {
    // Remove br
    $latex_math = preg_replace("|<br\s*/?>|", "\n", $latex_math);
    
    // Decode special HTML entities
    $decoded = htmlspecialchars(html_entity_decode($latex_math));

    return $decoded;
}

function restore_latex_in_text($text) {
    // Updated pattern to match begin and end with the same identifier
    $pattern = '/(\$[^$]+\$|\$\$[^$]+\$\$|\\\\begin{([^}]+)}.*?\\\\end{\2}|\\\\\[.*?\\\\\])/s';

    // Replace each LaTeX match with its restored version
    $restored_text = preg_replace_callback($pattern, function($matches) {
        return revert_latex_math($matches[0]);
    }, $text);

    return $restored_text;
}
?>