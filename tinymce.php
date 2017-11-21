<?php
/**
 * Kirby TinyMCE
 *
 * Adds the familiar rich-text editor TinyMCE to the Kirby 2 CMS administration
 * panel. Requires Kirby 2.5.3 or later.
 *
 * @version 0.1.0
 * @author Paul Morel <paul.morel@gmail.com>
 * @copyright  Paul Morel <paul.morel@gmail.com>
 * @link https://github.com/PaulMorel/kirby-tinymce
 * @license   GNU LGPL-2.1 <https://opensource.org/licenses/LGPL-2.1>
 */

if ( ! function_exists('panel') ) return;

/**
 * Require Composer packages
 */
require_once __DIR__ . DS . 'vendor' . DS . 'autoload.php';

/**
 * Registers the TinyMCE field
 */
$kirby->set('field', 'tinymce', __DIR__ . DS . 'fields' . DS . 'tinymce');