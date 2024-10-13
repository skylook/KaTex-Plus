(function($) {
    console.log('Classic Editor Button script loading...');

    tinymce.PluginManager.add('latex_button', function(editor, url) {
        console.log('LaTeX button plugin registered.');

        editor.addButton('latex_button', {
            text: 'LaTeX',
            icon: false,
            onclick: function() {
                console.log('LaTeX button clicked.');
                editor.windowManager.open({
                    title: 'Insert LaTeX',
                    body: {
                        type: 'textbox',
                        name: 'latex_code',
                        label: 'LaTeX Code'
                    },
                    onSubmit: function(e) { // Use onSubmit instead of buttons array
                        var latexCode = e.data.latex_code; // Access latex_code directly from e.data
                        editor.insertContent('[latex]' + latexCode + '[/latex]');
                    }
                });
            }
        });
    });

    console.log('Classic Editor Button script loaded.');
})(jQuery);
