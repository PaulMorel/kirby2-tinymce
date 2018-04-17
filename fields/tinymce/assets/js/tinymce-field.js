(function ($) {


    /**
     * Field Setup
     */
    $.fn.tinymcefield = function () {

        return this.each(function () {

            /* Get field object and field ID */
            var $field = $(this);
            var fieldID = $field.find('textarea').attr('id');
            var pageURL = $field.data('page-url');
            var siteURL = $field.data('site-url');

            /* Avoid multiple initializations */
            if ( $field.data('tinymcefield') ) {
                return true;
            } else {
                $field.data('tinymcefield', true);
            }

            /*
             * Destroy TinyMCE instance. Makes the field work after the panel 
             * reloads on save, etc. Forgot why this is needed.
             */
            if ( fieldID in tinymce.editors ) {
                tinymce.EditorManager.execCommand('mceRemoveEditor', true, fieldID);
            }


            /* Reset draggable items in sidebar to add compatibility with iframes */
            var $sidebar = $('.sidebar .draggable');

            $sidebar.draggable({
                iframeFix: true,
                iframeScroll: true,
                helper: function (e, ui) {
                    return $('<div class="draggable-helper"></div>');
                },
                start: function (e, ui) {

                    var url = $(this).data('url');

                    if (url) {
                        ui.helper.html('<img src="' + url + '">');
                        ui.helper.addClass('draggable-helper-with-image');
                    } else {
                        ui.helper.text($(this).data('helper'));
                    }

                }
            });

            /* Initialize TinyMCE */
            tinymce.init({
                selector: '#' + fieldID,
                skin_url: "/panel/plugins/tinymce/css/skins/kirby",
                branding: false,
                relative_urls : false,
                remove_script_host : false,
                menubar: $field.data('menubar'),
                toolbar: $field.data('toolbar'),
                plugins: $field.data('plugins'),
                init_instance_callback: function (editor) {

                    /* Setup focus behavior */
                    editor.on("focus", function () {
                        editor.dom.addClass(editor.editorContainer, "focus");
                    }).on("blur", function () {
                        editor.dom.removeClass(editor.editorContainer, "focus");
                    }).on("change", function () {
                        editor.save();
                    });


                    /* Setup drop zone */
                    $(editor.editorContainer).droppable({
                        hoverClass: 'over',
                        accept: $sidebar,
                        drop: function (e, ui) {

                            // Trim Kirby Tag
                            var tag = ui.draggable.data('text');
                            tag = tag.replace(/^\s*\(/, "");
                            tag = tag.replace(/\)\s*$/, "");

                            // Extract attributes and create object from tag
                            var tagName = tag.substring(0, tag.indexOf(':'));
                            var tagAttrs = tag.split(/(\w+:\s?)/);

                            // Removes empty first key
                            tagAttrs.shift();
                            tagAttrs = tagAttrs.map(function (value) {
                                return value.replace(/:\s*$/, "").trim();
                            });

                            // Create object from array
                            var tagAttrObj = {};
                            for (var i = 0; i < tagAttrs.length; i += 2) {
                                tagAttrObj[tagAttrs[i]] = tagAttrs[i + 1];
                            }

                            // Check what type of Kirby tag to replace
                            if ( tagName == 'link' ) {

                                editor.execCommand('mceInsertLink', false, siteURL + '/' + tagAttrObj.link);
                                editor.insertContent(tagAttrObj.text);

                            } else if ( tagName == 'image' ) {

                                editor.insertContent('<img src="' + pageURL + '/' + tagAttrObj.image + '">');

                            } else if ( tagName == 'file' ) {

                                editor.execCommand('mceInsertLink', false, pageURL + '/' + tagAttrObj.file);
                                editor.insertContent(tagAttrObj.file);

                            }

                        }
                    });
                }
            });





        });

    };


})(jQuery);