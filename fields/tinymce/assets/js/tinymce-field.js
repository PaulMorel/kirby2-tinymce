(function($) {

    $.fn.tinymcefield = function() {

        return this.each(function() {

            var field = $(this);
             // avoid multiple inits
            if(field.data('tinymcefield')) {
                return true;
            } else {
                field.data('tinymcefield', true);
            }

            // Get form field ID
            var fieldClasses = field.attr('class').split(" ");
            var editorId;

            $.each(fieldClasses, function(i, value){
                if ( value.indexOf('field-name') !== -1 ) {
                    editorId = fieldClasses[i].replace('field-name', 'form-field');
                }
            });

            // Remove an existing TinyMCE instances
            tinymce.EditorManager.execCommand('mceRemoveEditor', true, editorId);


            // Options
            var options = {
                selector: ".field-with-tinymce textarea",
                skin_url: "/panel/plugins/tinymce/css/skins/kirby",
                branding: false,
                menubar: field.data('menubar'),
                init_instance_callback: function (editor) {
                    var editorContainer = document.querySelector(".mce-tinymce");

                    editor.on("focus", function () {
                        editorContainer.classList.add("mce-tinymce--is-focused");
                    }).on("blur", function () {
                        editorContainer.classList.remove("mce-tinymce--is-focused");
                    }).on("change", function () {
                        editor.save();
                    });
                }
            };

            // Initialize TinyMCE
           tinymce.init(options);

        });

    };

})(jQuery);