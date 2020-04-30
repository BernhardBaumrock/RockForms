<?php namespace ProcessWire;
class RockFormsRenderer extends \Nette\Forms\Rendering\DefaultFormRenderer {
  private $rockform;
  private $rockforms;
  public function __construct($rockform) {
    $this->rockform = $rockform;
    $this->rockforms = $rockform->forms;
  }
  public function render(\Nette\Forms\Form $form, string $mode = null): string {
    $this->rockforms->hookBefore($form, 'render', $mode);
    $return = parent::render($form, $mode);
    $return = $this->rockforms->hookAfter($form, 'render', $return);
    return $return;
  }
  public function renderBegin(): string {
    $this->rockforms->hookBefore($this->rockform, 'renderBegin');
    $return = parent::renderBegin();
    $return = $this->rockforms->hookAfter($this->rockform, 'renderBegin', $return);
    return $return;
  }
  public function renderEnd(): string {
    $this->rockforms->hookBefore($this->rockform, 'renderEnd');
    $return = parent::renderEnd();
    $return = $this->rockforms->hookAfter($this->rockform, 'renderEnd', $return);
    return $return;
  }
  public function renderErrors(\Nette\Forms\IControl $control = null, bool $own = true): string {
    $this->rockforms->hookBefore($this->rockform, 'renderErrors', $control, $own);
    $return = parent::renderErrors($control, $own);
    $return = $this->rockforms->hookAfter($this->rockform, 'renderErrors', $return);
    return $return;
  }
  public function renderBody(): string {
    $this->rockforms->hookBefore($this->rockform, 'renderBody');
    $return = parent::renderBody();
    $return = $this->rockforms->hookAfter($this->rockform, 'renderBody', $return);
    return $return;
  }
  public function renderControls($parent): string {
    $this->rockforms->hookBefore($this->rockform, 'renderControls', $parent);
    $return = parent::renderControls($parent);
    $return = $this->rockforms->hookAfter($this->rockform, 'renderControls', $return);
    return $return;
  }
  public function renderPair(\Nette\Forms\IControl $control): string {
    $this->rockforms->hookBefore($this->rockform, 'renderPair', $control);
    $return = parent::renderPair($control);
    $return = $this->rockforms->hookAfter($this->rockform, 'renderPair', $return);
    return $return;
  }
  public function renderPairMulti(array $controls): string {
    $this->rockforms->hookBefore($this->rockform, 'renderPairMulti', $controls);
    $return = parent::renderPairMulti($controls);
    $return = $this->rockforms->hookAfter($this->rockform, 'renderPairMulti', $return);
    return $return;
  }
  public function renderLabel(\Nette\Forms\IControl $control): \Nette\Utils\Html {
    $this->rockforms->hookBefore($this->rockform, 'renderLabel', $control);
    $return = parent::renderLabel($control);
    $return = $this->rockforms->hookAfter($this->rockform, 'renderLabel', $return);
    return $return;
  }
  public function renderControl(\Nette\Forms\IControl $control): \Nette\Utils\Html {
    $this->rockforms->hookBefore($this->rockform, 'renderControl', $control);
    $return = parent::renderControl($control);
    $return = $this->rockforms->hookAfter($this->rockform, 'renderControl', $return);
    return $return;
  }

  
  public function getWrapper(string $name): \Nette\Utils\Html {
    $this->rockforms->hookBefore($this->rockform, 'getWrapper', $name);
    $return = parent::getWrapper($name);
    $return = $this->rockforms->hookAfter($this->rockform, 'getWrapper', $return);
    return $return;
  }
}
