<?php

namespace Core\Router;

use Core\Entity\BaseEntity;


class UIdRoute extends \Zend_Controller_Router_Route
{
    
    /**
     * @var Doctrine\ORM\EntityManager
     * @Inject Doctrine\ORM\EntityManager
     */
    private $em;
    

    /**
     * Matches a user submitted path with parts defined by a map. Assigns and
     * returns an array of variables on a successful match.
     *
     * @param string $path Path used to match against this routing map
     * @return array|false An array of assigned values or a false on a mismatch
     */
    public function match($path, $partial = false)
    {
        if ($this->_isTranslated) {
            $translateMessages = $this->getTranslator()->getMessages();
        }

        $pathStaticCount = 0;
        $values          = array();
        $matchedPath     = '';

        if (!$partial) {
            $path = trim($path, $this->_urlDelimiter);
        }

        if ($path !== '') {
            $path = explode($this->_urlDelimiter, $path);

            foreach ($path as $pos => $pathPart) {
                // Path is longer than a route, it's not a match
                if (!array_key_exists($pos, $this->_parts)) {
                    if ($partial) {
                        break;
                    } else {
                        return false;
                    }
                }
                $part = $this->_parts[$pos];
                $matchedPath .= $pathPart . $this->_urlDelimiter;
                
                if(is_string($part))
                {
	
	                // If it's a wildcard, get the rest of URL as wildcard data and stop matching
	                if ($part == '*') {
	                    $count = count($path);
	                    for($i = $pos; $i < $count; $i+=2) {
	                        $var = urldecode($path[$i]);
	                        if (!isset($this->_wildcardData[$var]) && !isset($this->_defaults[$var]) && !isset($values[$var])) {
	                            $this->_wildcardData[$var] = (isset($path[$i+1])) ? urldecode($path[$i+1]) : null;
	                        }
	                    }
	
	                    $matchedPath = implode($this->_urlDelimiter, $path);
	                    break;
	                }
				}
	
                $name     = isset($this->_variables[$pos]) ? $this->_variables[$pos] : null;
                $pathPart = urldecode($pathPart);
                
	            if(is_string($part))
	            {
	
	                // Translate value if required
	                if ($this->_isTranslated && (substr($part, 0, 1) === '@' && substr($part, 1, 1) !== '@' && $name === null) || $name !== null && in_array($name, $this->_translatable)) {
	                    if (substr($part, 0, 1) === '@') {
	                        $part = substr($part, 1);
	                    }
	
	                    if (($originalPathPart = array_search($pathPart, $translateMessages)) !== false) {
	                        $pathPart = $originalPathPart;
	                    }
	                }
	
	                if (substr($part, 0, 2) === '@@') {
	                    $part = substr($part, 1);
	                }
                }

                // If it's a static part, match directly
                if ($name === null && $part != $pathPart) {
                    return false;
                }

                // If it's a variable with requirement, match a regex. If not - everything matches
                if ($part !== null){
                    if ($part instanceof UIdCheck){
                        // check uid
                        $uid = $this->em->getRepository('Entity\UId')->find($pathPart);
                        
                        if($uid == null){
                            return false;
                        }
                        if($part->getClass() != null && $part->getClass() != $uid->getClass()){
                            return false;
                        }
                        
                        $entity = $this->em->getRepository($uid->getClass())->find($pathPart);
                        if($entity != null){
                            $pathPart = $entity;
                        }
                    }
                    elseif(!preg_match($this->_regexDelimiter . '^' . $part . '$' . $this->_regexDelimiter . 'iu', $pathPart)) {
                        return false;
                    }
                }

                // If it's a variable store it's value for later
                if ($name !== null) {
                    $values[$name] = $pathPart;
                } else {
                    $pathStaticCount++;
                }
            }
        }

        // Check if all static mappings have been matched
        if ($this->_staticCount != $pathStaticCount) {
            return false;
        }

        $return = $values + $this->_wildcardData + $this->_defaults;

        // Check if all map variables have been initialized
        foreach ($this->_variables as $var) {
            if (!array_key_exists($var, $return)) {
                return false;
            } elseif ($return[$var] == '' || $return[$var] === null) {
                // Empty variable? Replace with the default value.
                $return[$var] = $this->_defaults[$var];
            }
        }

        $this->setMatchedPath(rtrim($matchedPath, $this->_urlDelimiter));

        $this->_values = $values;

        return $return;

    }

    /**
     * Assembles user submitted parameters forming a URL path defined by this route
     *
     * @param  array $data An array of variable and value pairs used as parameters
     * @param  boolean $reset Whether or not to set route defaults with those provided in $data
     * @return string Route path with user submitted parameters
     */
    public function assemble($data = array(), $reset = false, $encode = false, $partial = false)
    {
        if ($this->_isTranslated) {
            $translator = $this->getTranslator();

            if (isset($data['@locale'])) {
                $locale = $data['@locale'];
                unset($data['@locale']);
            } else {
                $locale = $this->getLocale();
            }
        }

        $url  = array();
        $flag = false;

        foreach ($this->_parts as $key => $part) {
            $name = isset($this->_variables[$key]) ? $this->_variables[$key] : null;

            $useDefault = false;
            if (isset($name) && array_key_exists($name, $data) && $data[$name] === null) {
                $useDefault = true;
            }

            if (isset($name)) {
                if (isset($data[$name]) && !$useDefault) {
                    $value = $data[$name];
                    unset($data[$name]);
                } elseif (!$reset && !$useDefault && isset($this->_values[$name])) {
                    $value = $this->_values[$name];
                } elseif (!$reset && !$useDefault && isset($this->_wildcardData[$name])) {
                    $value = $this->_wildcardData[$name];
                } elseif (array_key_exists($name, $this->_defaults)) {
                    $value = $this->_defaults[$name];
                } else {
                    require_once 'Zend/Controller/Router/Exception.php';
                    throw new Zend_Controller_Router_Exception($name . ' is not specified');
                }
                
                if($value instanceof BaseEntity){
                    $value = $value->getId();
                }

                if ($this->_isTranslated && in_array($name, $this->_translatable)) {
                    $url[$key] = $translator->translate($value, $locale);
                } else {
                    $url[$key] = $value;
                }
            } elseif ($part != '*') {
                if ($this->_isTranslated && substr($part, 0, 1) === '@') {
                    if (substr($part, 1, 1) !== '@') {
                        $url[$key] = $translator->translate(substr($part, 1), $locale);
                    } else {
                        $url[$key] = substr($part, 1);
                    }
                } else {
                    if (substr($part, 0, 2) === '@@') {
                        $part = substr($part, 1);
                    }

                    $url[$key] = $part;
                }
            } else {
                if (!$reset) $data += $this->_wildcardData;
                $defaults = $this->getDefaults();
                foreach ($data as $var => $value) {
                    if ($value !== null && (!isset($defaults[$var]) || $value != $defaults[$var])) {
                        $url[$key++] = $var;
                        $url[$key++] = $value;
                        $flag = true;
                    }
                }
            }
        }

        $return = '';

        foreach (array_reverse($url, true) as $key => $value) {
            $defaultValue = null;

            if (isset($this->_variables[$key])) {
                $defaultValue = $this->getDefault($this->_variables[$key]);

                if ($this->_isTranslated && $defaultValue !== null && isset($this->_translatable[$this->_variables[$key]])) {
                    $defaultValue = $translator->translate($defaultValue, $locale);
                }
            }

            if ($flag || $value !== $defaultValue || $partial) {
                if ($encode) $value = urlencode($value);
                $return = $this->_urlDelimiter . $value . $return;
                $flag = true;
            }
        }

        return trim($return, $this->_urlDelimiter);

    }
    
}       