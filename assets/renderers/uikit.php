<?php namespace ProcessWire;
/** @var \Nette\Forms\Rendering\DefaultFormRenderer $renderer */
foreach($form->getComponents() as $component) {
  switch(true) {
    case $component instanceof \Nette\Forms\Controls\RadioList:
      $component->getControlPrototype()->addClass('uk-radio');
      $component->getSeparatorPrototype()->setName('span class="uk-margin-small-left"');
      $component->getContainerPrototype()->addClass('uk-alert-danger');
    break;
    case $component instanceof \Nette\Forms\Controls\TextInput:
      $component->getControlPrototype()
        ->addClass('uk-input')
        ->addPlaceholder($component->label->getText())
        ;
    break;
    case $component instanceof \Nette\Forms\Controls\TextArea:
      $component->getControlPrototype()
        ->addClass('uk-textarea')
        ->addPlaceholder($component->label->getText())
        ;
    break;
    case $component instanceof \Nette\Forms\Controls\SubmitButton:
      $component->getControlPrototype()->addClass('uk-button');
    break;
  }
}
