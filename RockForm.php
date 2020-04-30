<?php namespace ProcessWire;
class RockForm extends \Nette\Forms\Form {
  
  public $wire;
  public $forms;

  public function __construct($forms) {
    $this->forms = $forms;
    $this->wire = $forms->wire;

    $this->setHtmlAttribute('class', 'RockForm');
  }
}
