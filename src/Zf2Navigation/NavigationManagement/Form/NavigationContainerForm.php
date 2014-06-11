<?php
namespace nGen\Zf2Navigation\NavigationManagement\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;

class NavigationContainerForm extends Form
{
    public function __construct($editMode = false)
    {
        // we want to ignore the name passed
        parent::__construct('navigation');

        $this->setAttribute('method', 'post');

        $this -> add (array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 600
                )
             )
        ));

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'id'    => 'name'
            ),
            'options' => array(
                'label' => 'name',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'class' => 'btn btn-primary',
                'id' => 'submitbutton',
            ),
        ));
		
    }
}