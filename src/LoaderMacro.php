<?php

namespace WebDev\Loader;

/**
 * Class LoaderMacro
 *
 * @package WebDev\Loader
 * @author Andrei Kulbeda
 */
trait LoaderMacro
{

    /**
     * @var array $result
     */
    protected $result = [];

    /**
     * Loader
     *
     * @param array ...$args
     * @return array|bool
     */
    public function loader(...$args) : array
    {
        if (!empty($args[0])) {
            foreach ($args as $instance) {
                switch (true) {
                    case $instance instanceof LoaderContract:
                        $this->eachLoader($instance->getName(), $instance);
                        break;
                    case is_string($instance):
                        $this->result[$instance] = parent::load($instance)->{$instance};
                        break;
                }
            }
        }

        return $this->result;
    }

    /**
     * Each
     *
     * @param string $name
     * @param object $instance
     * @param null $previous
     */
    protected function eachLoader(string $name, $instance, $previous = null)
    {
        $static = (new $instance($this, $previous));
        array_set($this->result, $name, $static->init());

        if (!is_null($instance->getModel()) && $instance->getModel() instanceof LoaderContract) {
            $getIsArray = array_get($this->result, $name);

            if (count($getIsArray, COUNT_RECURSIVE) - count($getIsArray)) {
                foreach ($getIsArray as $key => $insNew) {
                    $path = "{$name}.{$key}.{$instance->getModel()->getName()}";

                    $this->eachLoader($path, $instance->getModel(), $insNew);
                }
            } else {
                $this->eachLoader("{$name}.{$instance->getModel()->getName()}", $instance->getModel(), $static->init());
            }
        }
    }
}