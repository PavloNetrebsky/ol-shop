<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalBASEproperty class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.4 - 2013-10-19
 */
abstract class iCalBASEproperty {
  /**
   * @var string
   */
  public    $propName;
  /**
   * @var array
   * @access protected
   */
  protected $parameters;
  /**
   * @var string
   * @access protected
   */
  protected $content;
  /**
   * @var array
   * @access protected
   */
  protected $config;
/**
 * iCalBASEproperty construct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-18
 * @param string $propName   property name
 * @param string $content    property content
 * @param array  $parameters property parameters
 * @param array  $config     calendar configuration
 * @uses iCalParameterFactory::addDefaults()
 * @uses iCalParameterFactory::factory()
 * @return object instance
 */
  public function __construct( $propName, $content, $parameters, $config ) {
    $this->propName   = strtoupper( $propName );
    $this->content    = $content;
    if( isset( $config['fixed'] )) {
      foreach( $config['fixed'] as $k => $v )
        $parameters[$k] = $v;
      unset( $config['fixed'] );
    }
    $parameters       = iCalParameterFactory::addDefaults( $this->propName, $parameters );
    $this->parameters = array();
    foreach( $parameters as $paramKey => $paramValue )
      $this->setParameter( $paramKey, $paramValue );
    $this->config     = array();
    foreach( $config as $k => $v )
      if( is_bool( $v ) || ! empty( $v ))
        $this->config[$k] = $v;
  }
/**
 * iCalBASEproperty destruct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-09
 * @return void
 */
  public function __destruct() {
    unset( $this->propName, $this->parameters, $this->content, $this->config );
  }
/**
 * return property in iCal format
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1.1 - 2013-09-21
 * @uses iCalParameterFactory::removeDefaults()
 * @uses iCalParameter::create()
 * @uses iCalPropertyFactory::size75()
 * @return string
 */
  public function create() {
    $output       = $this->propName;
    if( isset( $this->config['removedefaults'] ) && ( FALSE !== $this->config['removedefaults'] ))
      $parameters = iCalParameterFactory::removeDefaults( $this->propName, $this->parameters );
    else
      $parameters = & $this->parameters;
    foreach( $parameters as $parameter ) {
      if( empty( $parameter ))
        continue;
      $output    .= $parameter->create();
    }
    return iCalPropertyFactory::size75( $output.':'.$this->content, $this->config['nl'] );
  }
/**
 * return property value, opt. with parameters
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1.1 - 2013-09-21
 * @param bool $full return with parameters or not
 * @uses iCalParameter::create()
 * @return mixed
 */
  public function get( $full=FALSE ) {
    if( ! $full )
      return $this->content;
    $params = '';
    foreach( $this->parameters as $parameter ) {
      if( empty( $parameter ))
        continue;
      $params .= $parameter->create();
    }
    return array( 'name' => $this->propName, 'value' => $this->content, 'params' => $params );
  }
/**
 * property config set
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.4 - 2013-10-19
 * @param string $key
 * @param string $value
 * @uses iCalPropertyFactory::isPropertyCfg()
 * @return void
 */
  public function setConfig( $key, $value ) {
    if( iCalPropertyFactory::isPropertyCfg( $key ))
      $this->config[$key] = $value;
  }
/**
 * set property parameter
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1.1 - 2013-09-21
 * @param string $paramKey
 * @param string $paramValue
 * @param bool   $replace
 * @uses iCalParameterFactory::isAllowed()
 * @uses iCalParameter::exists()
 * @uses iCalParameterFactory::factory()
 * @return bool
 */
  public function setParameter( $paramKey, $paramValue, $replace=TRUE ) {
    if( iCalParameterFactory::isAllowed( $this->propName, $paramKey )) {
      foreach( $this->parameters as & $p ) {
        if( empty( $p ))
          continue;
        if( $p->exists( $paramKey )) {
          if( $replace )
            $p->set( $paramValue );
          return TRUE;
          break;
        }
      }
      $this->parameters[] = iCalParameterFactory::factory( $this->propName, $paramKey, $paramValue );
      return TRUE;
    }
    return FALSE;
  }
}
