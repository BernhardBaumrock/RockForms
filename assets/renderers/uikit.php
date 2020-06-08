<?php namespace ProcessWire;
/** @var \Nette\Forms\Rendering\DefaultFormRenderer $renderer */

// helpful for debugging
// bd($renderer);
// bd($form);
// bd($vars);

// setup customization variables
$tableClass = $vars->tableClass ?? 'uk-table uk-table-small uk-table-responsive';
$tdClass = $vars->tdClass ?? '';

// set wrappers
$renderer->wrappers['controls']['container'] = "table class='$tableClass'";
$renderer->wrappers['label']['container'] = "td class='$tdClass'";

// modify controls
foreach($form->getComponents() as $component) {
  if($component instanceof \Nette\Forms\Controls\RadioList) {
    $component->getControlPrototype()->addClass('uk-radio');
    $component->getSeparatorPrototype()->setName('span class="uk-margin-small-left"');
    $component->getContainerPrototype()->addClass('uk-alert-danger');
  }
  elseif($component instanceof \Nette\Forms\Controls\TextInput) {
    $component->getControlPrototype()->addClass('uk-input');
  }
  elseif($component instanceof \Nette\Forms\Controls\TextArea) {
    $component->getControlPrototype()->addClass('uk-textarea');
  }
  elseif($component instanceof \Nette\Forms\Controls\SubmitButton) {
    $component->getControlPrototype()->addClass('uk-button');
  }
}
