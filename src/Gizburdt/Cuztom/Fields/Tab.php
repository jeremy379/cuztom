<?php

namespace Gizburdt\Cuztom\Fields;

use Gizburdt\Cuztom\Cuztom;
use Gizburdt\Cuztom\Support\Guard;

Guard::directAccess();

class Tab extends Field
{
    /**
     * Tabs type.
     * @var string
     */
    protected $_tabs_type;

    /**
     * Title.
     * @var string
     */
    public $title;

    /**
     * Fields.
     * @var array
     */
    public $fields = array();

    /**
     * Construct.
     *
     * @param array $args
     * @since 3.0
     */
    public function __construct($args)
    {
        parent::__construct($args);

        if (! $this->id) {
            $this->id = Cuztom::uglify($this->title);
        }
    }

    /**
     * Output.
     *
     * @param  array  $args
     * @return string
     * @since  3.0
     */
    public function output($value = null)
    {
        Cuztom::view('fields/tab', array(
            'tab'   => $this,
            'args'  => $args,
            'type'  => $this->_tabs_type
        ));
    }

    /**
     * Save.
     *
     * @param  int          $object
     * @param  string|array $values
     * @return string
     * @since  3.0
     */
    public function save($object, $values)
    {
        foreach ($this->fields as $id => $field) {
            $field->save($object, $values);
        }
    }

    /**
     * Set the tabs type.
     * @param string $type
     */
    public function set_tabs_type($type)
    {
        $this->_tabs_type = $type;
    }

    /**
     * Build.
     *
     * @param  array        $data
     * @param  string|array $value
     * @return void
     * @since  3.0
     */
    public function build($data, $value)
    {
        foreach ($data as $type => $field) {
            if (is_string($type) && $type == 'bundle') {
                // $tab->fields = $this->build( $fields );
            } else {
                $args  = array_merge($field, array(
                    '_meta_type' => $this->_meta_type,
                    '_object'    => $this->_object,
                    '_value'     => @$value[$field['id']][0]
                ));

                $field = Field::create($args);

                $this->fields[$field->id] = $field;
            }
        }
    }
}
