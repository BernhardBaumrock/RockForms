# RockForms

ProcessWire Frontend Forms using Nette Forms Package

## Why?

Some may know that I've already built a module using the Nette Forms Package. Some may also know that there's a great PRO module for building forms: [FormBuilder](https://processwire.com/store/form-builder/). Some may also know that there's a [great thread about how to build forms using the PW forms API](https://processwire.com/talk/topic/2089-create-simple-forms-using-api/). And finally some may know the great [Nette Framework](https://doc.nette.org/) and its [Forms Package](https://doc.nette.org/en/3.0/forms) - see especially the docs about [standalone forms](https://doc.nette.org/en/3.0/forms#toc-standalone-forms).

This module tries to combine the best of both worlds: ProcessWire on the one hand (like translation tools, creating pages etc.) and NetteForms on the other (for its great client- and server-side validation). As great as Nette Forms are, as much am I missing the power of ProcessWire hooks there. That's why RockForms comes with a custom form renderer that triggers a hookable method call on the `RockForms` object.

## Features

* CSRF protection
* Field value restore
* Have predefined renderers (eg. for Uikit)
* Render multiple forms on one page
* Easy AJAX forms
* Full control over markup
* Easy integration of analytics
* Easy creation of PW pages from form submissions
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

As mentioned above `RockForms` comes with a custom renderer that adds hook magic to nette forms. This renderer replaces the `DefaultFormRenderer` of Nette with `RockFormsRenderer`. You can also modify this renderer easily and add custom renderers for any CSS framework you like. See the `uikit` renderer as an example!

Setting the renderer modifications:

```php
$form->_setRenderer('uikit');
```

You can even use variables to make your customizer customizable:

```php
$form->_setRenderer('uikit', [
  'foo' => 'bar',
]);
```

See https://doc.nette.org/en/3.0/form-rendering for detailed instructions.

### Hooking a RockForm

A RockForm extends a Nette Form object and is therefore by default **not** hookable, but thanks to the custom Renderer and the hookable proxy method we get hooking powers for all render methods of the form. Important note: The structure of the hook arguments is a little different, because the hookable method actually lives in the RockForms module. That means that the $event->object would always be the RockForms module and not the RockForm instance. When using the `$form->addHookBefore()` and `$form->addHookAfter()` methods (directly on the RockForm object), then the HookEvent will automatically be modified so that the syntax is the same as a in a regular hook. This means that the hook is only fired for this single instance of a RockForm!

If you wanted to hook all render calls of any RockForm, you'd need to hook the RockForms module:

```php
$wire->addHook("RockForms::hookAfter", function($event) {
  $rockforms = $event->object;
  $form = $event->arguments(0);
  $method = $event->arguments(1);
  if($method !== 'renderBegin') return;
  bd($event, "renderBegin");
});
```

![img](https://i.imgur.com/gl4yZ74.png)

When you hook the `RockForm` object you get a regular HookEvent structure, though some features may not work (eg $event->replace):

```php
$form->addHookBefore("renderPair", function($event) {
  bd($event->arguments(), 'render Pair');
});
```
![img](https://i.imgur.com/sJHEx77.png)

Modifying form controls:

```php
$form->addHookBefore("render", function($event) {
  $form = $event->object;
  $form->getLabel('mail')->addClass('uk-margin-right');
  $form->getControl('submit')->addClass('uk-button-primary uk-button-small uk-margin-small-top');
});
```

Note that the `getLabel()` and `getControl()` methods are added via RockForms and not part of Nette! The more verbose Nette way of doing this would be:

```php
$form->getComponent('mail')->getLabelPrototype()->addClass('uk-margin-right');
$form->getComponent('submit')->getControlPrototype()->addClass('uk-button-primary uk-button-small uk-margin-small-top');
```

See https://api.nette.org/3.0/Nette/Forms/Form.html and https://api.nette.org/3.0/Nette/Utils/Html.html.

You can also easily modify the markup of the whole rendered form:

```php
$form->addHookAfter('render', function($event) {
  $form = $event->return;
  $event->return = "<h1>My Form</h1>$form<div>end of form</div>";
});
```

Until now everything was quite easy, but sometimes we want to modify the whole wrapper of a form control. We can set the global wrapper settings via `$renderer->wrappers['foo']['bar']` but then all wrappers would change. Using hooks it is a little verbose but possible:

```php
$wrappers = $form->getRenderer()->wrappers;
$form->addHookBefore("renderPair", function($event) use($wrappers) {
  $component = $event->arguments(0);
  $renderer = $event->object->getRenderer();
  $renderer->wrappers = $wrappers; // reset wrapper on every component
  if($component->name !== 'foo') return;
  $renderer->wrappers['pair']['container'] = "tr style='outline: 1px solid red;'";
});
```
![img](https://i.imgur.com/4fDOFTn.png)

### Manual form rendering

See https://github.com/nette/forms/tree/master/examples.

## Honeypot

// TODO
