<?php

namespace WebDev\Loader;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Loader
 *
 * @package WebDev\Loader
 * @author Andrei Kulbeda
 */
abstract class Loader implements LoaderContract
{

    /**
     * @var $name
     */
    protected $name;

    /**
     * @var null|Model $callModel
     */
    protected $callModel;

    /**
     * @var null|array $previous
     */
    protected $previous;

    /**
     * Loader constructor.
     *
     * @param null|Model $model
     * @param null|array $previous
     */
    public function __construct($model = null, $previous = null)
    {
        $this->callModel = $model;
        $this->previous = $previous;
    }

    /**
     * Get call model
     *
     * @return Model|null
     */
    public function getModel()
    {
        return $this->callModel;
    }

    /**
     * Previous object
     *
     * @return array|null
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    abstract public function init();
}
