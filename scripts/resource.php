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

define('KATEX_JS_VERSION', '0.15.1');


add_action('init', 'katex_resources_init');
add_action('wp_footer', 'katex_enable');


function katex_resources_init() {
    $option_use_jsdelivr = get_option('katex_use_jsdelivr', KATEX__OPTION_DEFAULT_USE_JSDELIVR);
    $option_load_assets_conditionally = get_option('katex_load_assets_conditionally', KATEX__OPTION_DEFAULT_LOAD_ASSETS_CONDITIONALLY);

    if ($option_use_jsdelivr) {
        wp_register_script(
            'katex',
            '//cdn.jsdelivr.net/npm/katex@' . KATEX_JS_VERSION . '/dist/katex.min.js',
            array(), // No dependencies.
            false, // No versioning.
            true // In footer.
        );
        wp_register_script(
            'katex_auto_render',
            '//cdn.jsdelivr.net/npm/katex@' . KATEX_JS_VERSION . '/dist/contrib/auto-render.min.js',
            array(), // No dependencies.
            false, // No versioning.
            true // In footer.
        );
       wp_register_style(
            'katex',
            '//cdn.jsdelivr.net/npm/katex@' . KATEX_JS_VERSION . '/dist/katex.min.css'
        );
    } else {
        wp_register_script(
            'katex',
            KATEX__PLUGIN_URL . 'assets/katex-' . KATEX_JS_VERSION . '/katex.min.js',
            array(), // No dependencies.
            false, // No versioning.
            true // In footer.
        );
        wp_register_script(
            'katex_auto_render',
            KATEX__PLUGIN_URL . 'assets/katex-' . KATEX_JS_VERSION . '/contrib/auto-render.min.js',
            array(), // No dependencies.
            false, // No versioning.
            true // In footer.
        );
        wp_register_style(
            'katex',
            KATEX__PLUGIN_URL . 'assets/katex-' . KATEX_JS_VERSION . '/katex.min.css'
        );
    }

    if ($option_load_assets_conditionally) {
        add_action('loop_end', 'katex_resources_enqueue_conditionally');
        add_action('admin_footer', 'katex_resources_enqueue_conditionally');
    } else {
        // add_action('wp_enqueue_scripts', 'katex_resources_enqueue');
        add_action('wp_enqueue_scripts', 'katex_resources_enqueue');
        add_action('admin_enqueue_scripts', 'katex_resources_enqueue');
    }
}


function katex_resources_enqueue() {
    wp_enqueue_script('katex');
    wp_enqueue_script('katex_auto_render', array(), false, false);
    wp_enqueue_style('katex');
}


function katex_resources_enqueue_conditionally() {
    global $katex_resources_required;

    if ($katex_resources_required) {
        katex_resources_enqueue();
    }
}


function katex_enable() {
    global $katex_resources_required;

    $option_load_assets_conditionally = get_option('katex_load_assets_conditionally', KATEX__OPTION_DEFAULT_LOAD_ASSETS_CONDITIONALLY);
    $option_katex_enable_autorender = get_option('katex_enable_autorender', KATEX__OPTION_DEFAULT_ENABLE_AUTORENDER);
    $option_katex_autorender_options = get_option('katex_autorender_options', KATEX__OPTION_DEFAULT_AUTORENDER_OPTIONS);

    if ($katex_resources_required || !$option_load_assets_conditionally) {
        ?>
        <script>

            function _katexRender(rootElement) {
                const eles = rootElement.querySelectorAll(".katex-eq:not(.katex-rendered)");
                for(let idx=0; idx < eles.length; idx++) {
                    const ele = eles[idx];
                    ele.classList.add("katex-rendered");
                    try {
                        katex.render(
                            ele.textContent,
                            ele,
                            {
                                displayMode: ele.getAttribute("data-katex-display") === 'true',
                                throwOnError: false
                            }
                        );
                    } catch(n) {
                        ele.style.color="red";
                        ele.textContent = n.message;
                    }
                }
            }

            function katexRender() {
                _katexRender(document);
            }

            document.addEventListener("DOMContentLoaded", function() {
                katexRender();


                // Perform a KaTeX rendering step when the DOM is mutated.
                const katexObserver = new MutationObserver(function(mutations) {
                    [].forEach.call(mutations, function(mutation) {
                        if (mutation.target && mutation.target instanceof Element) {
                            _katexRender(mutation.target);
                        }
                    });
                });

                const katexObservationConfig = {
                    subtree: true,
                    childList: true,
                    attributes: true,
                    characterData: true
                };

                katexObserver.observe(document.body, katexObservationConfig);

                <?php
        if ($option_katex_enable_autorender) {
            ?>
            // const katex = require('katex');

            renderMathInElement(document.body, {
              // customised options
              // • auto-render specific keys, e.g.:
              delimiters: [
                  {left: '$$', right: '$$', display: true},
                  {left: '$', right: '$', display: false},
                  {left: '\\(', right: '\\)', display: false},
                  {left: '\\[', right: '\\]', display: true}
              ],
              // • rendering keys, e.g.:
              throwOnError : false
            });
        <?php
        }
        ?>
            });




        </script>
        <?php
    }
}
