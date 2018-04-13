(function ($) {


    $.fn.tinymcefield = function () {

        return this.each(function () {

            // Get field
            var field = $(this);

            // Avoid multiple initializations
            if (field.data('tinymcefield')) {
                return true;
            } else {
                field.data('tinymcefield', true);
            }

            // Get form field ID
            var fieldClasses = field.attr('class').split(" ");
            var editorId;

            $.each(fieldClasses, function (i, value) {
                if (value.indexOf('field-name') !== -1) {
                    editorId = fieldClasses[i].replace('field-name', 'form-field');
                }
            });

            // Remove any existing TinyMCE instances
            tinymce.EditorManager.execCommand('mceRemoveEditor', true, editorId);


            /**
             * Setup TinyMCE
             */

            // Options
            tinymce.init({
                selector: ".field-with-tinymce textarea",
                skin_url: "/panel/plugins/tinymce/css/skins/kirby",
                branding: false,
                menubar: field.data('menubar'),
                toolbar: field.data('toolbar'),
                plugins: field.data('plugins'),
                init_instance_callback: function (editor) {
                    var $editorContainer = $(".mce-tinymce");

                    editor.on("focus", function () {
                        $editorContainer.addClass("mce-tinymce--is-focused");
                    }).on("blur", function () {
                        $editorContainer.removeClass("mce-tinymce--is-focused");
                    }).on("change", function () {
                        editor.save();
                    });
                }
            });

            /**
             * Reset draggable items in sidebar to add compatibility with iframes
             */

            // Draggable
            app.content.root.find('.sidebar .draggable').draggable({
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
   
            // Droppable
            field.droppable({
                hoverClass: 'over',
                accept: app.content.root.find('.sidebar .draggable'),
                drop: function (e, ui) {
                     
                    // Trim Kirby Tag
                    var tag = ui.draggable.data('text');
                    tag = tag.replace(/^\s*\(/,"");
                    tag = tag.replace(/\)\s*$/,"");
                    // Extract attributes and create object from tag
                    var name = tag.substring(0, tag.indexOf(':'));
                    var attrs = tag.split(/(\w+:\s?)/);
                    // Removes empty first key
                    attrs.shift(); 
                    attrs = attrs.map( function( value ) {
                        return value.replace(/:\s*$/,"").trim();
                    });
                    // Create object from array
                    var attrObj = {};
                    for( var i = 0; i < attrs.length; i += 2 ) {
                        attrObj[ attrs[i] ] = attrs[i + 1]; 
                    }

                    // Check what type of Kirby tag to replace
                    if ( name == 'link' ) {

                        tinymce.activeEditor.execCommand('mceInsertLink', false, window.location.origin + '/' + attrObj.link );
                        tinymce.activeEditor.insertContent( attrObj.text );

                    } else if ( name == 'image' ) {

                        tinymce.activeEditor.insertContent( '<img src="' + window.location.origin + '/' + attrObj.image + '">' );

                    } else if ( name == 'file' ) {

                        tinymce.activeEditor.execCommand('mceInsertLink', false, attrObj.file );
                        tinymce.activeEditor.insertContent( attrObj.file );

                    }

                }
            });

        });

    };

})(jQuery);