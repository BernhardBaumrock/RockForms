<?php namespace ProcessWire;
/**
 * ProcessWire Frontend Forms using Nette Forms Package
 *
 * @author Bernhard Baumrock, 30.04.2020
 * @license Licensed under MIT
 * @link https://www.baumrock.com
 */
class RockForms extends WireData implements Module, ConfigurableModule {

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
  * Config inputfields
  * @param InputfieldWrapper $inputfields
  */
  public function getModuleConfigInputfields($inputfields) {
    return $inputfields;
  }
}