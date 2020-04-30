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

## Custom Inputfield markup
