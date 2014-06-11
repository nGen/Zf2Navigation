<?php
namespace nGen\Zf2Navigation\NavigationManagement\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;

class NavigationPageForm extends Form
{
    public function __construct($editMode = false)
    {
        // we want to ignore the name passed
        parent::__construct('navigationPage');

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
            'name' => 'label',
            'attributes' => array(
                'type'  => 'text',
                'id'    => 'label'
            ),
            'options' => array(
                'label' => 'Label',
            ),
        ));

        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type'  => 'text',
                'id'    => 'title'
            ),
            'options' => array(
                'label' => 'Title',
            ),
        ));

        $this->add(array(
            'name' => 'uri',
            'attributes' => array(
                'type'  => 'text',
                'id'    => 'uri'
            ),
            'options' => array(
                'label' => 'URI (Link)',
            ),
        ));

        $this->add(array(
            'name' => 'menu',
            'type'  => 'radio',
            'attributes' => array(
                'id' => 'menu',
                'value' => '1',
            ),
            'options' => array(
                'label' => 'Menu',
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
            'name' => 'breadcrumbs',
            'type'  => 'radio',
            'attributes' => array(
                'id' => 'breadcrumbs',
                'value' => '1',
            ),
            'options' => array(
                'label' => 'Breadcrumbs',
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
            'name' => 'sitemap',
            'type'  => 'radio',
            'attributes' => array(
                'id' => 'sitemap',
                'value' => '1',
            ),
            'options' => array(
                'label' => 'Sitemap',
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
            'name' => 'advanced_settings',
            'type'  => 'radio',
            'attributes' => array(
                'id' => 'advanced_settings',
                'value' => '0',
            ),
            'options' => array(
                'label' => 'Advanced Settings',
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
            'name' => 'advanced',
            'type' => 'nGen\Zf2Navigation\NavigationManagement\Form\NavigationPageAdvancedFieldset',
            'attributes' => array(
                'id' => 'advanced',
                //'class' => 'hidden',
            ),
            'options' => array(
                'label' => 'Advanced Configuration:',
            )
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