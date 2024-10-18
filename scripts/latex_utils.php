<?php
function revert_latex_math($latex_math) {
    // echo "latex_math = ". $latex_math ."\n";
    // Remove br
    $latex_math = preg_replace("|<br\s*/?>|", "\n", $latex_math);

    // echo "latex_math = ". $latex_math ."\n";
    
    // Decode special HTML entities
    $decoded_spatialchars = htmlspecialchars_decode($latex_math, ENT_QUOTES);
    $decoded_entity = html_entity_decode($decoded_spatialchars, ENT_QUOTES, "UTF-8");

    // echo "Decoded spatialchars: " . $decoded_spatialchars . "\n";
    // echo "Decoded entity: " . $decoded_entity . "\n";
        

    return $decoded_entity;
}

function restore_latex_in_text($text) {
    // echo 'text = ' . $text .'\n';
    // Updated pattern to match begin and end with the same identifier
    $pattern = '/(\$[^$]+\$|\$\$[^$]+\$\$|\\\\begin{([^}]+)}.*?\\\\end{\2}|\\\\\[.*?\\\\\])/s';

    // Replace each LaTeX match with its restored version
    $restored_text = preg_replace_callback($pattern, function($matches) {
        // echo '$matches[0] = '. $matches[0] .'\n';

        $latex = revert_latex_math($matches[0]);

        // echo "latex = " . $latex ."\n";
        
        // Corrected regex pattern for display mode detection
        // $is_display = preg_match('/\$\$|\\\\\\\\\[|\\\\begin{.+}/', $matches[0]);

        // Use sprintf to style differently based on display or inline
        // return sprintf('<span class="katex-eq" data-katex-display="%s">%s</span>', $is_display ? 'true' : 'false', $latex);
        return $latex;
    }, $text);

    return $restored_text;
}
?>
