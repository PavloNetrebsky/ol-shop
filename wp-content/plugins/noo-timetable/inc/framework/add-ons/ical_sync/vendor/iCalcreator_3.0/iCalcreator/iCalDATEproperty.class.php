<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalDATEproperty class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-12
 */
class iCalDATEproperty extends iCalBASEproperty {
/**
 * iCalDATEproperty construct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-18
 * @param string $propName   property name
 * @param string $content    property content
 * @param array  $parameters property parameters
 * @param array  $config     calendar configuration
 * @uses iCalPropertyFactory::manageTheDATE()
 * @uses iCalPropertyFactory::isVALUEset()
 * @uses iCalParameterFactory::addDefaults()
 * @uses iCalParameterFactory::isAllowed()
 * @uses iCalParameterFactory::factory()
 * @return object instance
 */
  public function __construct( $propName, $content, $parameters, $config ) {
    $this->propName   = strtoupper( $propName );
            /* manage content */
    if( isset( $config['localdatetime'] ))
      $parameters['VALUE']  = 'DATE-TIME';
    if( 'TRIGGER' == $this->propName )
      $parameters['VALUE']  = 'DATE-TIME';
    list( $this->content, $tzid ) = iCalPropertyFactory::manageTheDATE( $content, $parameters, $config );
            /* fix parameters */
    if( isset( $config['localdatetime'] )) {
      if( 'Z' == substr( $this->content, -1 ))
        $this->content = substr( $this->content, 0, ( strlen( $this->content ) - 1 ));
      unset( $parameters['TZID'], $config['localdatetime'] );
    }
    elseif( iCalPropertyFactory::isVALUEset( $parameters, 'DATE' ) || ( 'Z' == substr( $this->content, -1 )))
      unset( $parameters['TZID'], $tzid );
    elseif( ! empty( $tzid )) {
      if( in_array( $tzid, array( 'GMT', 'UTC', 'Z' )) && ( 'Z' != substr( $this->content, -1 ))) {
        $this->content     .= 'Z';
        unset( $parameters['TZID'], $tzid );
      }
      else
        $parameters['TZID'] = $tzid;
    }
    else
      unset(  $parameters['TZID'] );
    $parameters             = iCalParameterFactory::addDefaults( $this->propName, $parameters );
    if( 'TRIGGER' == $this->propName )
      unset( $parameters['RELATED'] );
    $this->parameters       = array();
    foreach( $parameters as $k => $v )
      if( ! empty( $v ))
        $this->setParameter( $k, $v );
            /* fix config */
    $this->config           = array();
    foreach( $config as $k => $v )
      if( is_bool( $v ) || ! empty( $v ))
        $this->config[$k] = $v;
  }
/**
 * return property value (str/arr depending on config), opt. with parameters
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-05
 * @param bool $full return with parameters or not
 * @uses iCalPropertyFactory::strdate2arr()
 * @uses iCalParameterFactory::params2arr()
 * @return mixed
 */
  public function get( $full=FALSE ) {
    $res = parent::get( $full );
    if( ! isset( $this->config['strdate2arr'] ) || ! $this->config['strdate2arr'] )
      return $res;
    if( $full )
      return array( 'value' => iCalPropertyFactory::strdate2arr( $res['value'] ), 'params' => iCalParameterFactory::params2arr( $res['params'] ));
    else
      return iCalPropertyFactory::strdate2arr( $res );
  }
}
