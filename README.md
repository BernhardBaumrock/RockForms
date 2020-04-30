# RockForms

ProcessWire Frontend Forms using Nette Forms Package

## Why?

Some may know that I've already built a module using the Nette Forms Package. Some may also know that there's a great PRO module for building forms: [FormBuilder](https://processwire.com/store/form-builder/). Some may also know that there's a [great thread about how to build forms using the PW forms API](https://processwire.com/talk/topic/2089-create-simple-forms-using-api/). And finally some may know the great [Nette Framework](https://doc.nette.org/) and its [Forms Package](https://doc.nette.org/en/3.0/forms) - see especially the docs about [standalone forms](https://doc.nette.org/en/3.0/forms#toc-standalone-forms).

This module tries to combine the best of both worlds: ProcessWire on the one hand (like translation tools, creating pages etc.) and NetteForms on the other (for its great client- and server-side validation).

## Features

* Have predefined renderers (eg. for Uikit)
* Render multiple forms on one page
* Easy AJAX forms
* Easy customization of the markup
* Easy integration of analytics
* Easy creation of PW pages from form submissions
* Provide shortcuts for render hooks
* Provide shortcuts for rendering custom HTML
* Live JS validation helper

## Installation

You need to install the module `RockNette` and load the nette forms package via composer (see the instructions on the RockNette module config screen).

## Setup a new form

```php
use \Nette\Forms\Form;
/** @var RockForm $form */
$form = $modules->get('RockForms')->form();

$form->addText('name', 'Name:');
$form->addPassword('password', 'Password:');
$form->addSubmit('send', 'Sign up');

echo $form; // renders the form
```

Using the typehint for your IDE can help working with the module:

![img](https://i.imgur.com/IpGXNQ2.png)

Adding the Nette Forms namespace helps getting the necessary constants to your fingertips:

![img](https://i.imgur.com/EokTi1c.png)

## Get submitted data

```php
if($form->isSuccess()) {
  bd($form->getValues());
}
```

![img](https://i.imgur.com/QS6nBUB.png)

## Set defaults and required state

```php
$form->addText('name', 'Name:')->setDefaultValue('Default Name');
$form->addPassword('password', 'Password:')->setRequired("You need to provide a password!");
```

![img](https://i.imgur.com/bIcd9U0.png)

## Adding validation rules

```php
$form->addPassword('password', 'Password:')
  ->addRule(Form::MIN_LENGTH, 'Password has to be at least %d characters long', 3)
  ->addRule(Form::PATTERN, 'Password must contain a number', '.*[0-9].*')
  ->setRequired("You need to provide a password!");
```

The great thing about NetteForms is that you only have to apply those rules **once** in your backend and you'll get the frontend (client side) validation automatically:

![img](https://i.imgur.com/Hliirgl.png)

![img](https://i.imgur.com/VUUU4Rr.png)

Note that some rules may only trigger after doing a `$form->isSuccess()`.

See https://doc.nette.org/en/3.0/form-validation and https://doc.nette.org/en/3.0/form-fields.

## Form fields

See https://doc.nette.org/en/3.0/form-fields for a list of all available fields.

## Form assets

Form assets are loaded from `/site/modules/RockForms/assets/` and `/site/assets/RockForms/` by default. You can add custom directories via hook:

```php
$wire->addHookAfter("RockForms::getDirs", function($event) {
  $event->return = array_merge($event->return, [
    $this->config->paths->templates."foo",
  ]);
});
```

### Renderers

Creating custom renderers is easy as cake. See the uikit renderer as an example. Applying this renderer to a form is also easy:

```php
$form->setRenderer('uikit');
```

See https://doc.nette.org/en/3.0/form-rendering for detailed instructions.

## Modifications before rendering the form

It is very likely that you want to customize your forms before rendering and we don't want to always create a renderer for this simple task. In ProcessWire we have hooks for that, but unfortunately we have no hooks in Nette Forms. That's why every `RockForm` comes with two magic properties called `beforeRender` and `afterRender`:

```php
$form->beforeRender = function($form) {
  $form->getControl('send')->addClass('uk-button-primary uk-button-small');
  $form->getLabel('password')->addClass('uk-text-danger');
};
$form->afterRender = function(&$html) {
  $html .= "<div>hooked</div>";
};
```

![img](https://i.imgur.com/0Oocp6N.png)

Note two things:
1) We are modifying `$html` by reference in the `afterRender` call
2) `getControl()` and `getLabel()` are two shortcut methods added via RockForms that do actually call the more verbose Nette API methods

```php
$form->getComponent('send')->getControlPrototype()->addClass('uk-button-primary uk-button-small');
$form->getComponent('password')->getLabelPrototype()->addClass('uk-text-danger');
```

See https://api.nette.org/3.0/Nette/Forms/Form.html and https://api.nette.org/3.0/Nette/Utils/Html.html.

