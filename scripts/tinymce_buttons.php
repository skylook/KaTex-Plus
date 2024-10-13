<?php
function my_custom_tinymce_buttons($buttons) {
    array_push($buttons, 'latex'); // Add LaTeX button
    return $buttons;
}
add_filter('mce_buttons', 'my_custom_tinymce_buttons');
?>
