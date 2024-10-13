<?php

// Remove TinyMCE hooks and filters
remove_action('init', 'my_tinymce_button');
remove_filter('tiny_mce_version', '__return_false');
remove_filter('mce_external_plugins', 'my_add_tinymce_plugin');
remove_filter('mce_buttons', 'my_register_tinymce_button');
remove_filter('mce_buttons_2', 'my_register_tinymce_button_textmode');

// Add action for media_buttons
add_action('media_buttons', 'add_latex_button');

function add_latex_button() {
    echo '<button type="button" id="insert-latex-button" class="button">Add LaTeX</button>';
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
