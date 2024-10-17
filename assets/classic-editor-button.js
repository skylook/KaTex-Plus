(function($) {
    'use strict';

    console.log('Classic Editor Button script loading...');

    tinymce.PluginManager.add('latex_button', function(editor) {
        console.log('LaTeX button plugin registered.');

        editor.addButton('latex_button', {
            text: 'LaTeX',
            icon: false,
            onclick: function() {
                console.log('LaTeX button clicked.');

                editor.windowManager.open({
                    title: 'Insert LaTeX',
                    body: [
                        {
                            type: 'textbox',
                            name: 'latex_code',
                            label: 'LaTeX Code'
                        },
                        {
                            type: 'checkbox',
                            name: 'is_equation',
                            label: 'Equation',
                            checked: false // Default to not an equation
                        },
                        {
                            type: 'textbox',
                            name: 'label',
                            label: 'Label (optional)',
                            value: '' // Default to no label
                        }
                    ],
                    onsubmit: function(e) {
                        var latexCode = e.data.latex_code;
                        var isEquation = e.data.is_equation;
                        var label = e.data.label;

                        var shortcode = '[latex';
                        if (isEquation) {
                            shortcode += ' display="true"';
                        }
                        if (label) {
                            shortcode += ' label="' + label + '"';
                        }
                        shortcode += ']' + latexCode + '[/latex]';

                        editor.insertContent(shortcode);
                    }
                });
            }
        });
    });

    console.log('Classic Editor Button script loaded.');
})(jQuery);
