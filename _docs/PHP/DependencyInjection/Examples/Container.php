<?php

/*
 * Class Container
 */

 class Container
 {
     /**
      * @var array
      */
     protected $instances = [];

     /**
      * @param $abstract
      * @param null $concrete
      */
     public function set($abstract, $concrete = null)
     {
         if ($concrete === null) {
             $concrete = $abstract;
         }

         $this->instances[$abstract] = $concrete;
     }

     /**
      * @param $abstract
      *
      * @return array $parameters
      * @return mixed|object|null
      *
      * @throws Exception
      */
     public function get($abstract, $parameters = [])
     {
         if (!isset($this->instances[$abstract])) {
             $this->set($abstract);
         }

         return $this->resolve($this->instances[$abstract], $parameters);
     }

     /**
      * resolve single.
      *
      * @param $concrete
      * @param $parameters
      *
      * @return mixed|object
      *
      * @throws Exception
      */
     public function resolve($concrete, $parameters)
     {
         if ($concrete instanceof Closure) {
             return $concrete($this, $parameters);
         }

         $reflector = new ReflectionClass($concrete);
         if (!$reflector->isInstantiable()) {
             throw new Exception('Class '.$concrete.'is not instantiable');
         }

         $constructor = $reflector->getConstructor();
         if (is_null($constructor)) {
             return $reflector->newInstance();
         }

         $parameters = $constructor->getParameters();
         $dependencies = $this->getDependencies($parameters);

         return $reflector->newInstanceArgs($dependencies);
     }

     /**
      * get all dependencies resolved.
      *
      * @param $parameters
      *
      * @return array
      *
      * @throws Exception
      */
     public function getDependencies($parameters)
     {
         $dependencies = [];

         foreach ($parameters as $parameter) {
             $dependency = ($parameter->getType() && !$parameter->getType()->isBuiltin()) ?
                            new ReflectionClass($parameter->getType()->getName()) :
                            null;

             if ($dependency === null) {
                 if ($parameter->isDefaultValueAvailable()) {
                     $dependencies[] = $parameter->getDefaultValue();
                 } else {
                     throw new Exception('Parameter '.$parameter->getName().' has no default value');
                 }
             } else {
                 $dependencies[] = $this->get($dependency->name);
             }
         }

         return $dependencies;
     }
 }
