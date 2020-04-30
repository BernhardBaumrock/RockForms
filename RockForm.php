<?php namespace ProcessWire;
use \Nette\Forms\Form;
class RockForm extends Form {
  public $wire;
  public $forms;
  public $options;
  public $rendererName;

  public function __construct($forms, $options) {
    $this->forms = $forms;
    $this->wire = $forms->wire;
    $this->getElementPrototype()->addClass('RockForm');
    $this->options = $options;

    // set the RockForms renderer
    $renderer = new \ProcessWire\RockFormsRenderer($this);
    $this->setRenderer($renderer);

    // security and spam protection
    $this->addProtection('Security token has expired, please submit the form again');
    $this->addHoneypots();
  }

  /**
   * Apply given renderer to form
   */
  public function _setRenderer(string $name) {
    $this->rendererName = $name;
  }

  /**
   * Add hook api to this form
   */
  public function addHookBefore($method, $fu) {
    $this->addHook($method, $fu, "Before");
  }
  public function addHookAfter($method, $fu) {
    $this->addHook($method, $fu, "After");
  }
  public function addHook($method, $fu, $when) {
    $form = $this;
    $hook = "RockForms::hook$when";
    $this->wire->addHook($hook, function($event) use($form, $method, $fu) {
      if($event->arguments(0) !== $form) return;
      if($event->arguments(1) !== $method) return;
      $event->object = $form;
      $event->method = $method;
      $args = $event->arguments();
      unset($args[0]);
      unset($args[1]);
      $event->arguments = array_values($args);
      $fu->__invoke($event);
    });
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
   * Add a single honeypot field to the form
   */
  public function addHoney($name) {
    $this->addText($name)->addRule(Form::BLANK)->setOption('type', 'hidden');
    $this->getControl($name)->addClass('uk-hidden');
  }

  /**
   * Add honeypots to the form
   */
  public function addHoneypots() {
    $honeypots = $this->options->honeypots ?: [];
    foreach($honeypots as $pot) $this->addHoney($pot);
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
    return parent::__toString();
  }
}
