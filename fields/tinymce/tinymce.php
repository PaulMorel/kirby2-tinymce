<?php

use Markdownify\Converter;
use Markdownify\ConverterExtra;

class TinyMCEField extends BaseField
{

    public static $assets = [
        'js' => [
            'tinymce-field.js',
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
     * @return Brick
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

        return $input;
    }

    public function element() {
        $element = parent::element();
        $element->data('field', 'tinymcefield');

        return $element;
    }
    /**
     * Field value
     * @return string
     */
    public function value() {
        $convertMarkdowntoHTML = c::get('markdown.extra') ? new ParsedownExtra : new Parsedown;

        return $convertMarkdowntoHTML->text(parent::value());
    }

    /**
     * Field on save
     * @return string
     */
    public function result() {
        $convertHTMLtoMarkdown = c::get('markdown.extra') ? new ConverterExtra : new Converter;

        return $convertHTMLtoMarkdown->parseString(parent::result());
    }
}