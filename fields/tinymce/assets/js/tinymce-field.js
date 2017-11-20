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

            // your field plugin code
            tinymce.init({
                selector: "textarea.field-tinymce",
                skin_url: "/panel/plugins/tinymce/css/skins/kirby",
                branding: false,
                menubar: "edit insert view format table tools help",
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
            });

            console.log('Plugin Loaded');

        });

    };

})(jQuery);