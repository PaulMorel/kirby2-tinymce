<?php

class TinyMCEField extends BaseField
{


    public static $assets = [
        'js'  => [
            'tinymce.min.js'
        ],
    ];


    /**
     * TinyMCEField constructor.
     */
    public function __construct() {
        $this->type = 'tinymce';
    }

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

        $wrapper = new Brick('div', false);
        $wrapper->append($input);
        $wrapper->append('<script>tinymce.init({ selector: "textarea.field-tinymce", skin_url: "/panel/plugins/tinymce/assets/css/skins/lightgray"  });</script>');
        return $wrapper;
    }

}