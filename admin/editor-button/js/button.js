(function($) {
    $(function() {
        $('body').on('grav-editor-ready', function() {
            var Instance = Grav.default.Forms.Fields.EditorField.Instance;
            Instance.addButton({
                youtube: {
                    identifier: 'amazon-products',
                    title: 'Amazon Products',
                    label: '<i class="fa fa-fw fa-amazon"></i>',
                    modes: ['gfm', 'markdown'],
                    action: function(_ref) {
                        var codemirror = _ref.codemirror,
                            button = _ref.button,
                            textarea = _ref.textarea;
                        button.on('click.editor.amazon-products', function() {
                            var input = prompt("ASIN/ISBN-10/Amazon Products URL");
                            asin = checkASIN(input);
                            if (asin) {
                                var text = '[amazon asin=' + asin + '][/amazon]';

                                //Add text to the editor
                                var pos = codemirror.getDoc().getCursor(true);
                                var posend = codemirror.getDoc().getCursor(false);

                                for (var i = pos.line; i < (posend.line + 1); i++) {
                                    codemirror.replaceRange(text + codemirror.getLine(i), { line: i, ch: 0 }, { line: i, ch: codemirror.getLine(i).length });
                                }

                                codemirror.setCursor({ line: posend.line, ch: codemirror.getLine(posend.line).length });
                                codemirror.focus();
                            }
                        });
                    }
                }
            });

            function checkASIN(input) {
                // Only ASIN/ISBN
                if (input.match(/^[a-zA-Z0-9]+$/)) {
                    return input.match(/^[a-zA-Z0-9]+$/);
                }

                // Extract ASIN by raw amazonURL
                if (input.match(/\/dp\/([a-zA-Z0-9]+)\//)) {
                    return input.match(/\/dp\/([a-zA-Z0-9]+)\//)[1];
                }
                if (input.match(/\/gp\/product\/([a-zA-Z0-9]+)\//)) {
                    return input.match(/\/gp\/product\/([a-zA-Z0-9]+)\//)[1];
                }

                return input;
            };
        });
    });
})(jQuery);