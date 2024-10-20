<?php
/*
 * Copyright (C) 2018  Thomas Churchman
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

include("latex_utils.php");

katex_shortcode_init();
katex_prevent_autorender_latex_init();


function katex_shortcode_init() {
    $option_enable_latex_shortcode = get_option('katex_enable_latex_shortcode', KATEX__OPTION_DEFAULT_ENABLE_LATEX_SHORTCODE);

    add_shortcode('katex', 'katex_display_inline_render');

    if ($option_enable_latex_shortcode) {
        add_shortcode('latex', 'katex_display_inline_render');
    }

    add_filter('no_texturize_shortcodes', 'katex_shortcode_exempt_from_wptexturize');
}

function katex_prevent_autorender_latex_init() {
    add_filter('the_content', 'katex_prevent_autorender_latex');    // Corrected function name here. The user had mistakenly put katex_prevent_autorender_latex_init
}

function katex_display_inline_render($attributes, $content = null) {
    $option_katex_enable_autorender = get_option('katex_enable_autorender', KATEX__OPTION_DEFAULT_ENABLE_AUTORENDER);

    global $katex_resources_required;
    $katex_resources_required = true;

    $attributes = shortcode_atts(
        array(
            'display' => false
        ),
        $attributes,
        'latex'
    );

    // Truthy -> 'true', falsy -> 'false'
    $display = $attributes['display'] ? 'true' : 'false';

    $latex_content  = restore_latex_in_text($content);

    // var_dump($latex_content);

    // $is_display = $latex_content['is_display'];
    // $encoded = $latex_content['content'];

    // $result_text = $latex_content;

    // // 避免autorender 开启的情况下，重复添加了 katex-eq
    // if ($option_katex_enable_autorender === false) {
    //     $result_text = sprintf('<span class="katex-eq" data-katex-display="%s">%s</span>', $display, $latex_content);
    // }

    $result_text = sprintf('<span class="katex-eq" data-katex-display="%s">%s</span>', $display, $latex_content);

    return $result_text;
}


function katex_shortcode_exempt_from_wptexturize($shortcodes) {
    $shortcodes[] = 'katex';
    $shortcodes[] = 'latex';
    return $shortcodes;
}


function katex_prevent_autorender_latex($content) {
    // error_log("Content before regex: " . $content); // Log before regex

    // $content = restore_latex_in_text($content);

    $latex_content  = restore_latex_in_text($content);

    // var_dump($latex_content);

    // $is_display = $latex_content['is_display'];
    // $content = $latex_content['content'];

    $katex_resources_required = true;

    // $decoded = sprintf('<span class="katex-eq" data-katex-display="%s">%s</span>', $display, $encoded);

    // error_log("Content after regex: " . $content);  // Log after regex
    return $latex_content;
}


?>
