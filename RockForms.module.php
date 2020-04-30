<?php namespace ProcessWire;
/**
 * ProcessWire Frontend Forms using Nette Forms Package
 *
 * @author Bernhard Baumrock, 30.04.2020
 * @license Licensed under MIT
 * @link https://www.baumrock.com
 */
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
   * Return a new Nette Form
   */
  public function form() {
    $this->modules->get('RockNette')->load();
    require_once("RockForm.php");
    $form = new RockForm($this);
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