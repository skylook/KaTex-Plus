<?php

add_action('media_buttons', 'add_latex_button');

function add_latex_button() {
    $icon_url = KATEX__PLUGIN_URL . 'assets/images/square-root-variable-solid.svg';
    echo '<button type="button" id="insert-latex-button" class="button"><img src="' . $icon_url . '" class="latex-icon dashicons-before dashicons-media-code" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 5px;"> Add LaTeX</button>';
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('#insert-latex-button').click(function() {
            var dialog = $('<div></div>').attr('title', 'Insert LaTeX').addClass('latex-dialog').appendTo('body'); // Added latex-dialog class
            var latexCodeField = $('<textarea></textarea>').attr('id', 'latex-code').css({'width': '100%', 'height': '100px', 'display': 'block'}).appendTo(dialog); // Added display: block
            var modeSelect = $('<select></select>').attr('id', 'latex-mode').css({'width': '100%', 'margin-top': '10px'}).appendTo(dialog); // Added width and margin
            $('<option></option>').attr('value', 'inline').text('Inline').appendTo(modeSelect);
            $('<option></option>').attr('value', 'block').text('Block').appendTo(modeSelect);


            dialog.dialog({
                modal: true,
                buttons: {
                    'Insert': {
                        text: 'Insert',
                        class: 'insert-latex-button', // Added class to the button
                        click: function() {
                            
                            var latexCode = $('#latex-code').val();
                            var mode = $('#latex-mode').val();
                            var shortcode = '[latex' + (mode === 'block' ? ' display="true"]' : ']') + latexCode + '[/latex]';
                            wp.media.editor.insert(shortcode);
                            $(this).dialog('close');

                        }
                    },
                    'Cancel': function() {
                        $(this).dialog('close');
                    }
                },
                close: function() {
                    $(this).remove();
                }
            });
        });
    });
    </script>
    <?php
}

?>
