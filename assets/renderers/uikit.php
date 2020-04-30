<?php namespace ProcessWire;
/** @var \Nette\Forms\Rendering\DefaultFormRenderer $renderer */
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
