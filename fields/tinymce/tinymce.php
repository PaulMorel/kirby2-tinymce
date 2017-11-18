<?php

/**
 * Class TinyMCEField
 */
class TinyMCEField extends BaseField
{

    public static $assets = [
        'js' => [
            'tinymce.min.js'
        ],
    ];

    /**
     * TinyMCEField constructor.
     */
    public function __construct() {
        $this->type = 'tinymce';
    }

    /**
     * @return string
     */
    public function input() {
        $input = new Brick('textarea', false);
        $input->addClass('field-tinymce');
        $input->attr([
            'required'     => $this->required(),
            'name'         => $this->name(),
            'autocomplete' => $this->autocomplete() === false ? 'off' : 'on',
            'autofocus'    => $this->autofocus(),
            'placeholder'  => $this->i18n($this->placeholder()),
            'readonly'     => $this->readonly(),
            'disabled'     => $this->disabled(),
            'id'           => $this->id()
        ]);

        $input->html($this->value() ? htmlentities($this->value(), ENT_NOQUOTES, 'UTF-8') : false);

        if ( $this->readonly() ) {
            $input->attr('tabindex', '-1');
            $input->addClass('input-is-readonly');
        }

        $init_script = '<script>
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
        </script>';

        return $input . $init_script;
    }

    /**
     * Field value
     * @return string
     */
    public function value() {
        return parent::value();
    }

    /**
     * Field on save
     * @return string
     */
    public function result() {
        return parent::result();
    }
}