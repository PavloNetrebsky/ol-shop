<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalREXDATEproperty class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-11
 */
class iCalREXDATEproperty extends iCalBASEproperty {
/**
 * iCalREXDATEproperty construct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-18
 * @param string $propName   property name
 * @param string $content    property content
 * @param array  $parameters property parameters
 * @param array  $config     calendar configuration
 * @uses iCalPropertyFactory::manageTheDATE()
 * @uses iCalParameterFactory::addDefaults()
 * @uses iCalParameterFactory::isAllowed()
 * @uses iCalParameterFactory::factory()
 * @return object instance
 */
  public function __construct( $propName, $content, $parameters, $config ) {
    $this->propName = strtoupper( $propName );
    if( empty( $content ))
      $content      = array();
    elseif( is_string( $content ))
      $content      = explode( ',', $content );
            /* manage content */
    $tzid           = FALSE;
    foreach( $content as $rix => & $date ) {
      list( $date, $tzid ) = iCalPropertyFactory::manageTheDATE( $date, $parameters, $config );
      if( ! isset( $parameters['TZID'] ) && ! empty( $tzid ))
        $parameters['TZID'] = $tzid;
    }
    sort( $content );
    $fstPerZ = ( isset( $parameters['TZID'] ) && in_array( $parameters['TZID'], array( 'GMT', 'UTC', 'Z' ))) ? TRUE : FALSE;
    foreach( $content as $rix => & $date ) {
      if((   0 == $rix ) && ( 'Z' == substr( $date, -1 ))) // check only 1st period for 'Z'
        $fstPerZ    = TRUE;
      elseif(   $fstPerZ && ( 'Z' != substr( $date, -1 )))
        $date      .= 'Z';
      elseif( ! $fstPerZ && ( 'Z' == substr( $date, -1 )))
        $date       = substr( $date, 0, ( strlen( $date ) -1 ));
    }
    if( $fstPerZ )
      unset( $parameters['TZID'] );
    sort( $content );
    $this->content  = implode( ',', $content );
            /* fix paramemers */
    $parameters     = iCalParameterFactory::addDefaults( $this->propName, $parameters );
    $this->parameters = array();
    foreach( $parameters as $k => $v )
      $this->setParameter( $k, $v );
            /* fix config */
    unset( $config['format'] );
    $this->config   = array();
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
 * @uses iCalREXDATEproperty::str2arr()
 * @uses iCalParameterFactory::params2arr()
 * @return mixed
 */
  public function get( $full=FALSE ) {
    $res = parent::get( $full );
    if( ! isset( $this->config['strdate2arr'] ) || ! $this->config['strdate2arr'] )
      return $res;
    if( $full )
      return array( 'value' => self::str2arr( $res['value'] ), 'params' => iCalParameterFactory::params2arr( $res['params'] ));
    else
      return self::str2arr( $res );
  }
/**
 * convert property value from string to arr
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-05
 * @param string $value string formatted dates
 * @access private
 * @uses iCalPropertyFactory::strdate2arr()
 * @static
 * @return array
 */
  private static function str2arr( $value ) {
    $temp    = explode( ',', $value );
    $res     = array();
    foreach( $temp as $date )
      $res[] = iCalPropertyFactory::strdate2arr( $date );
    return $res;
  }
}
