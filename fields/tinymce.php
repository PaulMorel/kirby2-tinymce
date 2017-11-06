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
//        $input->addClass('input');
//        $input->attr([
//            'type'         => $this->type(),
//            'value'        => '',
//            'required'     => $this->required(),
//            'name'         => $this->name(),
//            'autocomplete' => $this->autocomplete() === false ? 'off' : 'on',
//            'autofocus'    => $this->autofocus(),
//            'placeholder'  => $this->i18n($this->placeholder()),
//            'readonly'     => $this->readonly(),
//            'disabled'     => $this->disabled(),
//            'id'           => $this->id()
//        ]);
//        if ( ! is_array($this->value()) ) {
//            $input->val($this->value());
//        }
//        if ( $this->readonly() ) {
//            $input->attr('tabindex', '-1');
//            $input->addClass('input-is-readonly');
//        }

        $wrapper = new Brick('div', false);
        $wrapper->append($input);
        $wrapper->append('<script>tinymce.init({ selector: "#mytextarea" });</script>');
        return $wrapper;
    }

}