<?php

use Markdownify\Converter;
use Markdownify\ConverterExtra;

/**
 * Class TinyMCEField
 */
class TinyMCEField extends BaseField
{
    /**
     * Field assets
     *
     * @since 0.1.0
     * @var array
     */
    public static $assets = [
        'js' => [
            'tinymce-field.js',
            'tinymce.min.js'
        ],
    ];

    /**
     * TinyMCE plugin options
     *
     * @since 0.1.0
     * @var array|null
     */
    public $plugins;

    /**
     * TinyMCE toolbar options
     *
     * @since 0.1.0
     * @var string|null
     */
    public $toolbar;

    /**
     * TinyMCE menubar options
     *
     * @since 0.1.0
     * @var string|null
     */
    public $menubar;

    /**
     * Field option defaults
     *
     * @since 0.1.0
     * @var array
     */
    protected $defaults = [
        'plugins'   =>  [],
        'toolbar'   =>  '',
        'menubar'   =>  'edit insert view format table tools help'
    ];

    /**
     * TinyMCEField constructor
     */
    public function __construct() {
        $this->type = 'tinymce';

        // Set default options if unset

        if ( ! isset( $this->menubar ) ) {
            $this->menubar = c::get('plugin.tinymce.menubar', $this->defaults['menubar'] );
        }
        if ( ! isset( $this->plugins ) ) {
            $this->plugins = c::get('plugin.tinymce.plugins', $this->defaults['plugins'] );
        }
        if ( ! isset( $this->toolbar ) ) {
            $this->toolbar = c::get('plugin.tinymce.toolbar', $this->defaults['toolbar'] );
        }
    }

    /**
     * Create input element
     *
     * @since 0.1.0
     * @return Brick
     */
    public function input() {

        $input = new Brick('textarea');
        $input->addClass('input');
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

    /**
     * Create input wrapper element
     *
     * @since 0.1.0
     * @return Brick
     */
    public function element() {
        $element = parent::element();
        $element->addClass('field-with-tinymce');
        $element->data('field', 'tinymcefield');
        $element->data('menubar', $this->menubar);
        $element->data('plugins', $this->plugins);
        $element->data('toolbar', $this->toolbar);
        return $element;
    }

    /**
     * Fetch field value from content
     *
     * @since 0.1.0
     * @return string
     */
    public function value() {
        $convertMarkdowntoHTML = c::get('markdown.extra') ? new ParsedownExtra : new Parsedown;

        return $convertMarkdowntoHTML->text(parent::value());
    }

    /**
     * Save field value to content
     *
     * @since 0.1.0
     * @return string
     */
    public function result() {
        $convertHTMLtoMarkdown = c::get('markdown.extra') ? new ConverterExtra : new Converter;

        return $convertHTMLtoMarkdown->parseString(parent::result());
    }
}