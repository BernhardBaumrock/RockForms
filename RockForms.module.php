<?php namespace ProcessWire;
/**
 * ProcessWire Frontend Forms using Nette Forms Package
 * 
 * // TODO: CSRF protection
 * // TODO: Honeypots
 * // TODO: Form upload
 *
 * @author Bernhard Baumrock, 30.04.2020
 * @license Licensed under MIT
 * @link https://www.baumrock.com
 */
// see https://github.com/nette/forms/issues/214
ini_set('session.use_strict_mode', 1);
class RockForms extends WireData implements Module, ConfigurableModule {
  
  /** @var WireArray */
  public $renderers = [];

  public static function getModuleInfo() {
    return [
      'title' => 'RockForms',
      'version' => '0.0.1',
      'summary' => 'ProcessWire Frontend Forms using Nette Forms Package',
      'autoload' => true,
      'singular' => true,
      'icon' => 'code',
      'requires' => ['RockNette'],
      'installs' => [],
    ];
  }

  public function init() {
    $this->loadAssets();
  }
  
  /**
   * Add hookable proxy methods for RockFormsRenderer
   */
  public function ___hookBefore(...$args) {}
  public function ___hookAfter(...$args) {
    return $args[2];
  }

  /**
   * Return a new Nette Form
   */
  public function form($options = []) {
    $this->modules->get('RockNette')->load();
    require_once("RockForm.php");
    require_once('RockFormsRenderer.php');

    // prepare options
    $opt = $this->wire(new WireData()); /** @var WireData $opt */
    $opt->setArray([
      'honeypots' => ['comment', 'message'],
    ]); // defaults
    $opt->setArray($options); // user options

    $form = new RockForm($this, $opt);
    return $form;
  }

  /**
   * Load renderers
   */
  public function loadAssets() {
    $files = $this->wire->files;
    $opt = ['extensions' => ['php']];
    $form = $this->form();
    
    foreach($this->getDirs() as $dir) {
      $dir = rtrim($dir, "/")."/";
      
      // renderers
      foreach($files->find($dir."renderers", $opt) as $file) {
        $this->renderers[$this->getFileName($file)] = $file;
      }

    }
  }

  /**
   * Add honeypots to form
   */
  public function ___addHoneypots($form) {
    $form->addHoneypots();
  }

  /**
   * Get file name of file
   * @return string
   */
  public function getFileName($file) {
    return pathinfo($file)['filename'];
  }

  /**
   * Get directories to scan for assets
   * Assets can be form renderers or form setups
   */
  public function ___getDirs() {
    return [
      $this->config->paths($this)."assets",
      $this->config->paths->assets."RockForms",
    ];
  }

  /**
  * Config inputfields
  * @param InputfieldWrapper $inputfields
  */
  public function getModuleConfigInputfields($inputfields) {
    return $inputfields;
  }

  public function __debugInfo() {
    return [
      'renderers' => $this->renderers,
    ];
  }
}