<?php

$objH = & $this->newObject('htmlheading','htmlelements');
$objForm = & $this->newObject('form','htmlelements');
$inpButton =  $this->newObject('button','htmlelements');

//Button
$inpButton->cssClass = 'f-submit';
$inpButton->setValue('Pass Course');
$inpButton->setToSubmit();

//setup the form
$objForm->name = 'impfrm';
$objForm->action = $this->uri(array('action' => 'createzip'));

$objForm->addToForm($inpButton->show());

print $objForm->show().'<br/>';

?>