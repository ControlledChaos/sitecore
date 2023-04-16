tinymce.PluginManager.add('source_code', function(editor, url) {

    function show() {

        var viewPort = tinymce.util.Tools.resolve('tinymce.dom.DOMUtils').DOM.getViewPort();

        var getMinWidth = function(editor) {
            return Math.min(viewPort.w - 200, 600);
        };

        var getMinHeight = function() {
            return Math.min(viewPort.h - 200, 500);
        };

        var getWidth = function(editor) {
            //return viewPort.w - 400;
            return 950;
        };

        var getHeight = function() {
            //return viewPort.h - 200;
            return 600;
        };

        var dialog = editor.windowManager.open({
            title: 'Source Code',
            url: acfe.get('home_url') + '/?acfe_wysiwyg_source_code=1',

            minWidth: getMinWidth(),
            minHeight: getMinHeight(),
            width: getWidth(),
            height: getHeight(),

            resizable: true,
            maximizable: true,
            fullScreen: false,
            buttons: [{
                    text: 'Apply',
                    subtype: 'primary',
                    onclick: function() {
                        var doc = document.querySelectorAll('.mce-container-body > iframe')[0];
                        doc.contentWindow.submit();
                        dialog.close();
                    }
                },
                {
                    text: 'Cancel',
                    onclick: 'close'
                }
            ]
        });

    }

    editor.addButton('source_code', {
        title: 'Source Code',
        icon: 'wp_code',
        onclick: show
    });

    editor.addMenuItem('source_code', {
        icon: 'wp_code',
        text: 'Source Code',
        context: 'tools',
        onclick: show
    });

    document.onkeydown = function(e) {

        e = e || window.event;
        var isEscape = false;

        isEscape = (e.keyCode === 27);

        if ("key" in e) {
            isEscape = (e.key === "Escape" || e.key === "Esc");
        }

        if (isEscape) {
            editor.windowManager.close();
        }

    };

});