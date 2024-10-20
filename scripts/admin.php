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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

add_action('admin_menu', 'katex_add_admin_menu');
add_action('admin_init', 'katex_settings_init');
add_action('admin_enqueue_scripts', 'katex_admin_enqueue_styles'); // Add enqueue action

function katex_add_admin_menu() {
    add_options_page('KaTeX', 'KaTeX', 'manage_options', 'katex', 'katex_options_page');
}

function katex_settings_init() {
    register_setting(
        'pluginPage',
        'katex_use_jsdelivr'
    );

    register_setting(
        'pluginPage',
        'katex_load_assets_conditionally'
    );

    register_setting(
        'pluginPage',
        'katex_enable_latex_shortcode'
    );

    register_setting(
        'pluginPage',
        'katex_enable_autorender'
    );

    register_setting(
        'pluginPage',
        'katex_autorender_options'
    );

    register_setting(
        'pluginPage',
        'katex_support_ref_label'
    );

    add_settings_section(
        'katex_pluginPage_section',
        __('Main', 'katex'),
        'katex_settings_section_callback',
        'pluginPage'
    );

    add_settings_field(
        'katex_jsdelivr_setting',
        __('Use jsDelivr to load files', 'katex'),
        'katex_jsdelivr_setting_render',
        'pluginPage',
        'katex_pluginPage_section'
    );

    add_settings_field(
        'katex_conditional_assets_setting',
        __('Load KaTeX assets conditionally', 'katex'),
        'katex_conditional_assets_setting_render',
        'pluginPage',
        'katex_pluginPage_section'
    );

    add_settings_field(
        'katex_latex_shortcode_setting',
        __('Enable the [latex] shortcode', 'katex'),
        'katex_latex_shortcode_setting_render',
        'pluginPage',
        'katex_pluginPage_section'
    );

    add_settings_field(
        'katex_enable_autorender_setting',
        __('Enable the KaTeX Auto-render Extension', 'katex'),
        'katex_enable_autorender_setting_render',
        'pluginPage',
        'katex_pluginPage_section'
    );

    add_settings_field(
        'katex_autorender_options_setting',
        __('The autorender option content', 'katex'),
        'katex_autorender_options_setting_render',
        'pluginPage',
        'katex_pluginPage_section'
    );

    add_settings_field(
        'katex_support_ref_label_setting',
        __('Support for \ref and \label', 'katex'),
        'katex_support_ref_label_setting_render',
        'pluginPage',
        'katex_pluginPage_section'
    );
}

function katex_jsdelivr_setting_render() {
    $option_katex_use_jsdelivr = get_option('katex_use_jsdelivr', KATEX__OPTION_DEFAULT_USE_JSDELIVR);
    ?>
    <input
        type='checkbox'
        name='katex_use_jsdelivr'
        <?php checked($option_katex_use_jsdelivr, 1); ?>
        value='1'>
    <?php
    echo __('Using the <a href="http://www.jsdelivr.com" target="_blank">jsDelivr</a> CDN will make KaTeX load faster.', 'katex');
}

function katex_conditional_assets_setting_render() {
    $option_katex_load_assets_conditionally = get_option('katex_load_assets_conditionally', KATEX__OPTION_DEFAULT_LOAD_ASSETS_CONDITIONALLY);
    ?>
    <input
        type='checkbox'
        name='katex_load_assets_conditionally'
        <?php checked($option_katex_load_assets_conditionally, 1); ?>
        value='1'>
    <?php
    echo __('Only load the KaTeX JavaScript and CSS assets when KaTeX is used on the page. This might introduce asset enqueueing compatibility issues with themes or other plugins.', 'katex');
}

function katex_latex_shortcode_setting_render() {
    $option_katex_enable_latex_shortcode = get_option('katex_enable_latex_shortcode', KATEX__OPTION_DEFAULT_ENABLE_LATEX_SHORTCODE);
    ?>
    <input
        type='checkbox'
        name='katex_enable_latex_shortcode'
        <?php checked($option_katex_enable_latex_shortcode, 1); ?>
        value='1'>
    <?php
    echo __('For compatibility with other plugins you can use [latex] shortcodes in addition to [katex].', 'katex');
}

function katex_enable_autorender_setting_render() {
    $option_katex_enable_autorender = get_option('katex_enable_autorender', KATEX__OPTION_DEFAULT_ENABLE_AUTORENDER);
    ?>
    <input
        type='checkbox'
        name='katex_enable_autorender'
        <?php checked($option_katex_enable_autorender, 1); ?>
        value='1'>
    <?php
    echo __('Automatically render all of the math inside of text. Read more: <a href="https://katex.org/docs/autorender" target="_blank">Autorender</a>', 'katex');
}

function katex_autorender_options_setting_render() {
    $option_katex_autorender_options = get_option('katex_autorender_options', KATEX__OPTION_DEFAULT_AUTORENDER_OPTIONS);
    
    $options = !empty($option_katex_autorender_options) ? explode(',', $option_katex_autorender_options) : [];

    ?>
    <div id="autorenderOptionsDiv" style="display: none;">
    <label class="katex-autorender-options" style="display: block;">
        <input type="checkbox" name="katex_autorender_options[single_dollar]" value="single_dollar" <?php in_array('single_dollar', $options) ? 'checked' : ''; ?>>
        $...$
    </label><br>
    <label class="katex-autorender-options" style="display: block;">
        <input type="checkbox" name="katex_autorender_options[double_dollar]" value="double_dollar" <?php in_array('double_dollar', $options) ? 'checked' : ''; ?>>
        $$...$$
    </label><br>
    <label class="katex-autorender-options" style="display: block;">
        <input type="checkbox" name="katex_autorender_options[backslash_parentheses]" value="backslash_parentheses" <?php in_array('backslash_parentheses', $options) ? 'checked' : ''; ?>>
        \(...\)
    </label><br>
    <label class="katex-autorender-options" style="display: block;">
        <input type="checkbox" name="katex_autorender_options[backslash_brackets]" value="backslash_brackets" <?php in_array('backslash_brackets', $options) ? 'checked' : ''; ?>>
        \[...\]
    </label><br>
    <textarea id="delimitersPreview" readonly style="width: 100%; height: 100px; resize: none;"></textarea>
    </div>
    <label id="autorenderNotice" style="color: gray;">Autorender options are only available when Enable the KaTeX Auto-render Extension is activated</label>
    <?php
}

function katex_support_ref_label_setting_render() {
    $option_katex_support_ref_label = get_option('katex_support_ref_label', 0);
    ?>
    <input
        type='checkbox'
        name='katex_support_ref_label'
        <?php checked($option_katex_support_ref_label, 1); ?>
        value='1'>
    <?php
    echo __('Enable support for \ref \eqref and \label in your documents.', 'katex');
}

function katex_settings_section_callback() {
     echo __('', 'katex');
}

function katex_options_page() {
    ?>
    <div class="wrap">
        <h1>KaTeX</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('pluginPage');
            do_settings_sections('pluginPage');
            submit_button();
            ?>
        </form>

    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const autorenderCheckbox = document.querySelector('input[name="katex_enable_autorender"]');
        const autorenderOptionsDiv = document.getElementById('autorenderOptionsDiv');
        const autorenderOptions = document.querySelectorAll('input[name^="katex_autorender_options"]'); // Corrected selector
        // const autorenderField = document.querySelector('.katex-autorender-options');
        const autorenderNotice = document.getElementById('autorenderNotice');
        const delimitersPreview = document.getElementById('delimitersPreview');

        // autorenderNotice.textContent = "";
        // autorenderNotice.style.color = "gray";
        autorenderNotice.style.display = "none";

        function toggleAutorenderOptions() {
            const isEnabled = autorenderCheckbox.checked;
            // autorenderOptions.forEach(option => {
            //     option.closest('label').style.display = isEnabled ? 'block' : 'none';
            // });
            autorenderOptionsDiv.style.display = isEnabled ? 'block' : 'none'; 
            // if (autorenderField) autorenderField.style.display = isEnabled ? 'block' : 'none';
            autorenderNotice.style.display = isEnabled ? 'none' : 'block';
        }

        function updateDelimitersPreview() {
            const delimiters = [];
            if (autorenderCheckbox.checked) {    
                const delimiterMap = {
                    "double_dollar": {left: '$$', right: '$$', display: true},
                    "single_dollar": {left: '$', right: '$', display: false},
                    "backslash_parentheses": {left: '\\(', right: '\\)', display: false},
                    "backslash_brackets": {left: '\\[', right: '\\]', display: true}
                };

                autorenderOptions.forEach(option => {
                    if (option.checked) {
                        let delimiter = delimiterMap[option.value];
                        if (delimiter) {
                            delimiters.push(delimiter);
                        } else {
                            console.error('Not in delimiterMap key = ' + option.value);
                        }
                    }
                });

                console.info('delimiters 1 = ' + JSON.stringify(delimiters));

                // Ensure $$...$$ appears first if checked
                delimiters.sort((a, b) => {
                    if (a.left === '$$' && b.left === '$') return -1;
                    if (a.left === '$' && b.left === '$$') return 1;
                    return 0;
                });

                console.info('delimiters 2 = ' + JSON.stringify(delimiters));
            }
            const delimitersString = delimiters.map(d => `{left: '${d.left}', right: '${d.right}', display: ${d.display}}`).join(',\n');
            delimitersPreview.value = `[${delimitersString}]`;
        }

        autorenderOptions.forEach(option => {
            option.addEventListener('change', updateDelimitersPreview);
        });

        autorenderCheckbox.addEventListener('change', toggleAutorenderOptions);

        toggleAutorenderOptions();
        updateDelimitersPreview();
        
        autorenderField.parentNode.insertBefore(autorenderNotice, autorenderField.nextSibling);
    });
    </script>
    
    <?php
}

// Enqueue admin styles
function katex_admin_enqueue_styles() {
    wp_enqueue_style('katex-admin-styles', KATEX__PLUGIN_URL . 'assets/admin.css');
}
?>