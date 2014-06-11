<?php
namespace nGen\Zf2Navigation\NavigationManagement\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class NavigationPageParamsFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct()
    {
        // we want to ignore the name passed
        parent::__construct('navigation_page_params');

        $this->add(array(
            'name' => 'param_name',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Name',
                'id'    => 'param_name'
            ),
            'options' => array(
                'label' => 'Name',
            ),
        ));

        $this->add(array(
            'name' => 'param_value',
            'attributes' => array(
                'type'  => 'text',
                'placeholder' => 'Value',
                'id'    => 'param_value'
            ),
            'options' => array(
                'label' => 'Value',
            ),
        ));
    }

    public function getInputFilterSpecification() {
        return array(
            "param_name" => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'inclusive' => true,
                            'min' => 1,
                            'max' => 100,
                        ),
                    ),
                ),
            ),
            "param_value" => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'inclusive' => true,
                            'min' => 1,
                            'max' => 100,
                        ),
                    ),
                ),
            ),
        );
    }
}