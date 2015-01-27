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
                'label' => 'Name',
            ),
        ));

        $this->add(array(
            'name' => 'separate_config',
            'type'  => 'radio',
            'attributes' => array(
                'id' => 'active',
                'value' => '0',
            ),
            'options' => array(
                'label' => 'Separate Configuration',
                'value_options' => array(
                    array('label' => 'Yes', 'value' => '1', 'label_attributes' => array('class' => 'radio-inline')),
                    array('label' => 'No', 'value' => '0', 'label_attributes' => array('class' => 'radio-inline')),
                ),
                'disable-twb' => true,
                'label_attributes' => array(
                    'class' => 'show'
                )
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