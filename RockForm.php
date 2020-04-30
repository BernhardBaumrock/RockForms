<?php namespace ProcessWire;
class RockForm extends \Nette\Forms\Form {
  public $wire;
  public $forms;
  public $rendererName;

  public $beforeRender;
  public $afterRender;

  public function __construct($forms) {
    $this->forms = $forms;
    $this->wire = $forms->wire;
    $this->getElementPrototype()->addClass('RockForm');
  }

  /**
   * Apply given renderer to form
   */
  public function _setRenderer(string $name) {
    $this->rendererName = $name;
  }

  /**
   * Shortcut to get the control property by name
   */
  public function getControl($name) {
    return $this->getComponent($name)->getControlPrototype();
  }
  
  /**
   * Shortcut to get the label property by name
   */
  public function getLabel($name) {
    return $this->getComponent($name)->getLabelPrototype();
  }

  /**
   * Render this form when requested as string (eg echo)
   */
  public function __toString(): string {
    // apply custom renderer if one was set
    $renderers = $this->forms->renderers;
    if($name = $this->rendererName AND array_key_exists($name, $renderers)) {
      $form = $this;
      $renderer = $this->getRenderer();
      include($renderers[$name]);
    }
    if(is_callable($this->beforeRender)) $this->beforeRender->__invoke($this);
    $out = parent::__toString();
    if(is_callable($this->afterRender)) $this->afterRender->__invoke($out);
    return $out;
  }
}
