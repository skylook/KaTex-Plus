<?php

add_action('media_buttons', 'add_latex_button');

function add_latex_button() {
    $icon_url = KATEX__PLUGIN_URL . 'assets/images/square-root-variable-solid.svg';
    echo '<button type="button" id="insert-latex-button" class="button"><img src="' . $icon_url . '" class="latex-icon dashicons-before dashicons-media-code" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px;"> Add LaTeX</button>';
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('#insert-latex-button').click(function() {
            var latexCode = prompt('Enter LaTeX code:', '');
            if (latexCode !== null) {
                wp.media.editor.insert('[latex]' + latexCode + '[/latex]');
            }
        });
    });
    </script>
    <?php
}

?>
