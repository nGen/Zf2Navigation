<?php
namespace nGen\Zf2Navigation\NavigationManagement\Form;

//use Travel\Model\TripItineraryGraph;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

class NavigationPageAdvancedFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct()
    {
        // we want to ignore the name passed
        parent::__construct('navigation_page_advanced');
        $this->setAttribute('method', 'post');


        $this->add(array(
            'name' => 'route',
            'attributes' => array(
                'type'  => 'text',
                'id'    => 'route'
            ),
            'options' => array(
                'label' => 'Route Name',
            ),
        ));

        $this->add(array(
            'name' => 'module',
            'attributes' => array(
                'type'  => 'text',
                'id'    => 'module'
            ),
            'options' => array(
                'label' => 'Module',
            ),
        ));

        $this->add(array(
            'name' => 'controller',
            'attributes' => array(
                'type'  => 'text',
                'id'    => 'controller'
            ),
            'options' => array(
                'label' => 'Controller',
            ),
        ));

        $this->add(array(
            'name' => 'action',
            'attributes' => array(
                'type'  => 'text',
                'id'    => 'action'
            ),
            'options' => array(
                'label' => 'Action',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'params',
            'options' => array(
                'label' => 'Router Parameters:',
                'count' => 0,
                'allow_add' => true,
                'target_element' => array(
                    'type' => 'nGen\Zf2Navigation\NavigationManagement\Form\NavigationPageParamsFieldset'
                )
            )
        ));         

        $this->add(array(
            'name' => 'reset_params',
            'type'  => 'radio',
            'attributes' => array(
                'id' => 'reset_params',
                'value' => '0',
            ),
            'options' => array(
                'label' => 'Reset Params',
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
            'name' => 'active',
            'type'  => 'radio',
            'attributes' => array(
                'id' => 'active',
                'value' => '1',
            ),
            'options' => array(
                'label' => 'Active',
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
            'name' => 'visible',
            'type'  => 'radio',
            'attributes' => array(
                'id' => 'visible',
                'value' => '1',
            ),
            'options' => array(
                'label' => 'Visible',
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
            'type' => 'select',
            'name' => 'target',
            'attributes' => array(
                'id' => 'target',
                'value' => '_self',
            ),
            'options' => array(
                'label' => 'Target',
                'value_options' => array(
                    '_self' => 'Open in the Same window',
                    '_blank' => 'Open in new Window/Tab',
                    '_parent' => 'Open in parent frame',
                    '_top' => 'Open in the full body Window',
                ),
            )
        ));

        $this->add(array(
            'name' => 'rel',
            'attributes' => array(
                'type'  => 'text',
                'id'    => 'rel'
            ),
            'options' => array(
                'label' => 'Forward links',
            ),
        ));        

        $this->add(array(
            'name' => 'rev',
            'attributes' => array(
                'type'  => 'text',
                'id'    => 'rev'
            ),
            'options' => array(
                'label' => 'Reverse links',
            ),
        ));          

        $this->add(array(
            'name' => 'class',
            'attributes' => array(
                'type'  => 'text',
                'id'    => 'class'
            ),
            'options' => array(
                'label' => 'Class',
            ),
        ));         

        $this->add(array(
            'name' => 'position',
            'attributes' => array(
                'type'  => 'text',
                'id'    => 'position'
            ),
            'options' => array(
                'label' => 'Order',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'properties',
            'options' => array(
                'label' => 'Custom Properties:',
                'count' => 0,
                'allow_add' => true,
                'target_element' => array(
                    'type' => 'nGen\Zf2Navigation\NavigationManagement\Form\NavigationPageParamsFieldset'
                )
            )
        ));     

    }

    public function getInputFilterSpecification() {
        return array(
            "route" => array(
                'required' => false,
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
            "module" => array(
                'required' => false,
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
            "controller" => array(
                'required' => false,
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
            "action" => array(
                'required' => false,
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
            "reset_params" => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ),
            "active" => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ),
            "visible" => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ),
            "target" => array(
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
            "rel" => array(
                'required' => false,
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
            "rev" => array(
                'required' => false,
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
            "position" => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ),
        );
    }
}