<?

/**
 * Very simple dependency injection implementation
 */
class VerySimpleDI {
    
    private $_objects = array();
    private $_singletons = array();
    
    /**
     * Get an instance of the class passed
     * @param string $type the class you want an instance of
     */
    public function getInstance($type){
        if(!array_key_exists($type, $this->_objects)){ // its not registered
            trigger_error("$type not registered, \"winging\" the instantiation", E_USER_NOTICE);
        } else if ($this->_objects[$type] == null){ // it has not yet been instantiated
            $this->_objects[$type] = $this->_instantiate($type);
        } else {
            if(!in_array($type, $this->_singletons)){ // if it is not a singleton - instantiate a new object
                $this->_objects[$type] = $this->_instantiate($type);
            } 
        }
        return $this->_objects[$type];
    }
    
    /**
     * Register a class in the class
     * @param string $type the name of the class to register
     * @param boolean $singleton should the class be treated as a singleton (only ever one object)
     */
    public function register($type, $singleton = false){
        
        if(isset($this->_objects[$type])){
            throw new Exception("cannot re-register a class");
        }
        $this->_objects[$type] = null;
        if($singleton){
            $this->_singletons[] = $type;
        }
    }
    
    /**
     * instantiates
     * @param string $type
     */
    private function _instantiate($type){
        
        $rClass = new \ReflectionClass($type);
        $ctor = $rClass->getConstructor();
        if($ctor == null){
            if($rClass->isInstantiable()){
                return new $type();
            } else {
                throw new Exception("$type is uninstantiable");
            }
        } else {
            $params = $ctor->getParameters();
            $args = array();
            foreach ($params as $param) {
                $class = $param->getClass();
                if($class == null){
                    throw new Exception("cannot instantiate $type because the type of the constructor parameter $param is not defined");
                }
                $args[] = $this->getInstance($param->getClass()->getName());
            }
            return $rClass->newInstanceArgs($args);
        }
    }
}
