<?php

$objH = & $this->newObject('htmlheading','htmlelements');
$objForm = & $this->newObject('form','htmlelements');
$inpButton =  $this->newObject('button','htmlelements');

//Button
$inpButton->cssClass = 'f-submit';
$inpButton->setValue('Course Admin');
$inpButton->setToSubmit();

echo "Debug Message = ".$uploadStatus."<br />";

if($uploadStatus == '0')
	$uploadStatus = "Successfully Loaded";
else if($uploadStatus == '1')
	$uploadStatus = "Un-Successfully Loaded";

echo "Upload Status = ".$uploadStatus;

//setup the form
$objForm->name = 'impfrm';
$objForm->action = $this->uri(array('action' => ''));

$objForm->addToForm($inpButton->show());

print $objForm->show().'<br/>';

?>