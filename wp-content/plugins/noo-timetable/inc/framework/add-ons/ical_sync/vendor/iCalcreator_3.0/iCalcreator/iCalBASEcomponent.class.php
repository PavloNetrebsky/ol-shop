<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalBASEcomponent class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.9 - 2013-12-12
 */
abstract class iCalBASEcomponent {
/**
 * @var string
 */
  public    $compName;
/**
 * @var array
 * @access protected
 */
  protected $properties;
/**
 * @var array
 * @access protected
 */
  protected $components;
/**
 * @var int
 * @access protected
 */
  protected $compix;
/**
 * @var array
 * @access protected
 */
  protected $config;
/**
 * @var string
 * @static
 */
  public static $version        = 'iCalcreator 3.0';
/**
 * iCalBASEcomponent construct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-18
 * @param  array  $config configuration array
 * @uses iCalComponentFactory::$baseCfgs
 * @uses iCalBASEcomponent::setConfig()
 * @return object instance
 */
  public function __construct( $config ) {
    $this->properties = array();
    $this->components = array();
    $this->compix     = 0;
    $this->config     = iCalComponentFactory::$baseCfgs; // set defaults
    $this->setConfig( $config );
  }
/**
 * iCalBASEcomponent destruct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-09
 * @return void
 */
  public function __destruct() {
    unset( $this->compName, $this->properties, $this->components, $this->compix, $this->config );
  }
/**
 * return component in iCal format
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-13
 * @uses iCalBASEcomponent::setProperty()
 * @uses iCalPropertyFactory::isAllowed()
 * @uses iCalComponentFactory::makeUid()
 * @uses iCalBASEcomponent::getConfig()
 * @uses iCalBASEproperty::create()
 * @uses iCalPropertyFactory::issplittableProp(
 * @uses iCalBASEcomponent::create()
 * @return string
 */
  public function create() {
    $output        = 'BEGIN:'.$this->compName.$this->config['nl'];
    if( iCalPropertyFactory::isAllowed( $this->compName, 'UID' )     && ! isset( $this->properties['UID'] ))
      $this->setProperty( 'UID', iCalComponentFactory::makeUid( $this->getConfig( 'unique_id' )));
    if( iCalPropertyFactory::isAllowed( $this->compName, 'DTSTAMP' ) && ! isset( $this->properties['DTSTAMP'] ))
      $this->setProperty( 'DTSTAMP' );
    foreach( $this->properties as $propName => $property ) {
      if( is_array( $property )) {
        if( iCalPropertyFactory::issplittableProp( $propName ) && ( 1 < count( $property )))
          usort( $property, array( 'iCalPropertyFactory', 'cmpfcn' ));
        foreach( $property as $p2 )
          $output .= $p2->create();
      }
      else
        $output   .= $property->create();
    }
    foreach( $this->components as $component )
      $output     .= $component->create();
    return $output.'END:'.$this->compName.$this->config['nl'];
  }
            /** config methods */
/**
 * general component config get
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-20
 * @param mixed $key, calendar configuration key(-s)
 * @uses iCalBASEcomponent::getProperty()
 * @uses iCalComponentFactory::$baseCfgs
 * @uses iCalBASEcomponent::getConfig()
 * @uses iCalPropertyFactory::isMultiple()
 * @return mixed
 */
  public function getConfig( $key=FALSE ) {
    if( empty( $key ))
      return $this->config;
    elseif( is_array( $key )) {
      $output = array();
      foreach( $key as $k )
        $output[$k] = $this->getConfig( $k );
      return $output;
    }
    $key   = strtolower( $key );
    if( 'newlinechar' == $key )
      $key = 'nl';
    if( array_key_exists( $key, iCalComponentFactory::$baseCfgs )) // allowempty,dosplit,language,nl,removedefaults,strdate2arr,tzid,unique_id
      return $this->config[$key];
    switch( $key ) {
      case 'compsinfo':
        $output = array();
        foreach( $this->components as $cix => $component ) {
          if( empty( $component )) continue;
          $output[$cix]['ordno'] = $cix + 1;
          $output[$cix]['type']  = $component->compName;
          $output[$cix]['uid']   = $component->getProperty( 'uid' );
          $output[$cix]['props'] = $component->getConfig( 'propinfo' );
          $output[$cix]['sub']   = $component->getConfig( 'compsinfo' );
        }
        return $output;
        break;
      case 'propinfo':
        $output = array();
        foreach( $this->properties as $propName => $pValue )
          $output[$propName] = ( iCalPropertyFactory::isMultiple( $this->compName, $propName )) ? count( $pValue ): 1;
        return $output;
        break;
      case 'setpropertynames':
        return array_keys( $this->properties );
        break;
      default:
        return ( array_key_exists( $key, $this->config )) ? $this->config[$key] : FALSE;
        break;
    }
    return FALSE;
  }
/**
 * general component config set
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.4 - 2013-10-19
 * @param mixed  $config configuration array/key
 * @param string $value
 * @uses iCalBASEcomponent::setConfig()
 * @uses iCalComponentFactory::$baseCfgs
 * @uses iCalParameterFactory::$parameterCfgs
 * @uses iCalBASEcomponent::setParameter()
 * @uses iCalBASEcomponent::getConfig(
 * @uses iCalBASEproperty::setConfig()
 * @return mixed
 */
  public function setConfig( $config, $value = FALSE) {
    if( is_array( $config )) {
      foreach( $config as $key => $value )
        $this->setConfig( $key, $value );
      return $this;
    } // end if( is_array( $config ))
    $key       = strtolower( $config );
    if( 'newlinechar' == $key )
      $key     = 'nl';
    $value     = (( 'nl' == $key ) || is_bool( $value )) ? $value : trim( $value );
    if( in_array( $key, iCalParameterFactory::$parameterCfgs )) { // language,tzid
      if( empty( $value ))
        $value = '';
      $this->config[$key] = $value;
      if( ! empty( $value ))
        $this->setParameter( $key, $value, FALSE ); // soft insert, only if configValue not set and value not empty
      foreach( $this->components as & $c )
        $c->setConfig( $key, $value );
    }
    elseif( array_key_exists( $key, iCalComponentFactory::$baseCfgs )) { // allowempty,dosplit,language,nl,removedefaults,strdate2arr,tzid,unique_id
      if( ! is_bool( $value ) && empty( $value ))
        $value = iCalComponentFactory::$baseCfgs[$key]; // restore value
      $this->config[$key] = $value;
      foreach( $this->properties as $propName => & $property ) {
        if( is_array( $property )) {
          foreach( $property as & $p2 )
            $p2->setConfig( $key, $value );
        }
        else
          $property->setConfig( $key, $value );
      }
      foreach( $this->components as & $c )
        $c->setConfig( $key, $value );
    }
    return $this;
  }
/**
 * delete component property
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.11 - 2013-12-12
 * @param string $propName property name
 * @param int    $propix   property index
 * @uses iCalPropertyFactory::isAllowed()
 * @uses iCalPropertyFactory::isMultiple()
 * @return bool
 */
  public function deleteProperty( $propName, $propix=null ) {
    $propName   = strtoupper( $propName );
    if( ! iCalPropertyFactory::isAllowed( $this->compName, $propName ))
      return FALSE;
    if( ! isset( $this->properties[$propName] ))
      return FALSE;
    if( iCalPropertyFactory::isMultiple( $this->compName, $propName )) {
      if( 'X-' == substr( $propName, 0, 2 ))
        unset( $this->properties[$propName] );
      else {
        if( empty( $propix ))
          $propix = ( isset( $this->propdelix ) && isset( $this->propdelix[$propName] )) ? $this->propdelix[$propName] + 2 : 1;
        $this->propdelix[$propName] = --$propix;
        $ak = array_keys( $this->properties[$propName] );
        while( ! isset($this->properties[$propName][$propix] ) && ( 0 < count( $this->properties[$propName] )) && ( $propix < end( $ak )))
          $propix++;
        $this->propdelix[$propName] = $propix;
        if( isset( $this->properties[$propName][$propix] ))
          unset( $this->properties[$propName][$propix] );
        if( empty( $this->properties[$propName] ))
          unset( $this->properties[$propName], $this->propdelix[$propName] );
      }
    }
    else
      unset( $this->properties[$propName] );
    return TRUE;
  }
/**
 * return a component property value
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.11 - 2013-12-12
 * @param string $propName optional, property name
 * @param int    $propix optional, if specific property is wanted in case of multiply occurences
 * @param bool   $inclParam optional, if to include property parameters
 * @param bool   $specform option, (DURATION only) if to convert duration value to date(-time)
 * @uses iCalBASEcomponent::getProperty()
 * @uses iCalPropertyFactory::isAllowed()
 * @uses iCalPropertyFactory::isMultiple()
 * @uses iCalBASEproperty::get()
 * @uses iCalDURATIONproperty::duration2date()
 * @uses iCalBASEcomponent::getConfig()
 * @uses iCalPropertyFactory::strdate2arr()
 * @uses iCalParameterFactory::params2arr()
 * @return mixed
 */
  public function getProperty( $propName=FALSE, $propix=FALSE, $inclParam=FALSE, $specform=FALSE ) {
    $propName   = strtoupper( $propName );
    if( 'GEOLOCATION' == $propName ) {
      $content = ( FALSE === ( $loc = $this->getProperty( 'LOCATION' ))) ? '' : $loc.' ';
      if( FALSE === ( $geo = $this->getProperty( 'GEO' )))
        return FALSE;
      list( $latitude, $longitude ) = explode( ';', $geo );
      return $content.$latitude.$longitude.'/';
    }
    if( empty( $propName ))
      $propName = 'X-';
    if( ! iCalPropertyFactory::isAllowed( $this->compName, $propName, __METHOD__ ))
      return FALSE;
    if(( 'X-' != substr( $propName, 0, 2 )) && ! isset( $this->properties[$propName] ))
      return FALSE;
    $vaae = (( 'VALARM' == $this->compName ) && // special condition for attach in email action valarm, also in setProperty
             ( 'ATTACH' == $propName ) &&
             ( FALSE   !== ( $action = $this->getProperty( 'ACTION' ))) &&
             ( 'EMAIL'  != $action )) ? FALSE : TRUE;
    if( $vaae && iCalPropertyFactory::isMultiple( $this->compName, $propName )) {
      if(( 'X-' == substr( $propName, 0, 2 )) && ( 'X-' != $propName )) {
        if( isset( $this->properties[$propName] ))
          return $this->properties[$propName]->get( $inclParam );
        return FALSE;
      }
      if( empty( $propix ))
        $propix = ( isset( $this->propgetix ) &&  isset( $this->propgetix[$propName] )) ? $this->propgetix[$propName] + 2 : 1;
      $this->propgetix[$propName] = --$propix;
      if( 'X-' == substr( $propName, 0, 2 )) {
        $pix = 0;
        foreach( $this->properties as $pName => $pValue ) {
          if( 'X-' != substr( $pName, 0, 2 ))
            continue;
          if( $pix == $propix )
            return $pValue->get( $inclParam );
          $pix += 1;
        }
        unset( $this->propgetix[$propName] );
        return FALSE;
      } // end if( 'X-' == substr( $propName, 0, 2 ))
      $ak = array_keys( $this->properties[$propName] );
      while( ! isset($this->properties[$propName][$propix] ) && ( 0 < count( $this->properties[$propName] )) && ( $propix < end( $ak )))
        $propix++;
      $this->propgetix[$propName] = $propix;
      if( isset( $this->properties[$propName][$propix] ))
        return $this->properties[$propName][$propix]->get( $inclParam );
      unset( $this->propgetix[$propName] );
      if( empty( $this->propgetix ))
        unset( $this->propgetix );
      return FALSE;
    } // end if( iCalPropertyFactory::isMultiple
    elseif(( 'DURATION' == $propName ) && $specform && isset( $this->properties['DTSTART'] ) && isset( $this->properties['DURATION'] )) {
      $dval = $this->properties['DURATION']->get( TRUE );
      $pval = iCalDURATIONproperty::duration2date( $this->properties['DTSTART']->get(), $dval['value'] );
      if( $this->getConfig( 'strdate2arr' )) {
        $pval = iCalPropertyFactory::strdate2arr( $pval );
        if( $inclParam )
          $dval['params'] = iCalParameterFactory::params2arr( $dval['params'] );
      }
      return ( $inclParam ) ? array( 'value' => $pval, 'params' => $dval['params'] ) : $pval;
    }
    elseif( isset( $this->properties[$propName] ))
      return $this->properties[$propName]->get( $inclParam );
    return FALSE;
  }
/**
 * set component property, returns FALSE on error, otherwise 'this'
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.9 - 2013-12-12
 * @param mixed $args variable number of function arguments,
 *                    first argument is ALWAYS component name,
 *                    second ALWAYS property value (start arg.)!
 * @uses iCalPropertyFactory::isAllowed()
 * @uses iCalPropertyFactory::parse()
 * @uses iCalBASEproperty::get()
 * @uses iCalComponentFactory::$baseCfgs
 * @uses iCalPropertyFactory::isMultiple()
 * @uses iCalBASEcomponent::getConfig()
 * @uses iCalPropertyFactory::factory()
 * @uses iCalPropertyFactory::issplittableProp()
 * @uses iCalBASEcomponent::getProperty()
 * @uses iCalBASEcomponent::setConfig()
 * @uses iCalParameterFactory::params2arr()
 * @return mixed
 */
  public function setProperty() {
    $numargs    = func_num_args();
    if( 1 > $numargs )
      return FALSE;
    $arglist    = func_get_args();
    $propName   = trim( strtoupper( $arglist[0] ));
    if( ! iCalPropertyFactory::isAllowed( $this->compName, $propName, __METHOD__ ))
      return FALSE;
    $arglist[1] = ( isset( $arglist[1] )) ? $arglist[1] : null;
    if( is_string( $arglist[1] ) && ( ! isset( $arglist[2] ) || empty( $arglist[2] ))) // split property content into value and parameters
      list( $arglist[1], $arglist[2] ) = iCalPropertyFactory::parse( $arglist[1] );
    $content    = '';
    $index      = null;
    $parameters = array();
    $propName2  = ( 'X-' == substr( $propName, 0, 2 )) ? 'X-' : $propName;
    $dt         = 'DateTime';
    switch( $propName2 ) {
      case 'COMPLETED':
      case 'CREATED':
      case 'DTSTAMP':
      case 'LAST-MODIFIED':
        if( !isset( $arglist[1] ))                                                    // set current UTC date, no 'break' here
          $arglist[1]    = date('Ymd\THis\Z', mktime( date( 'H' ), date( 'i' ), date( 's' ) - date( 'Z'), date( 'm' ), date( 'd' ), date( 'Y' )));
      case 'DTEND':
      case 'DTSTART':
      case 'DUE':
      case 'RECURRENCE-ID':
        if( !isset( $arglist[1] ))
          break;
        elseif(( $arglist[1] instanceof $dt ) ||                                      // datetime as an datetime object instance
                 is_array( $arglist[1] ) ||                                           // array date, datetime or timestamp UTC
               ( is_string( $arglist[1] ) && ( 3 <= strlen( trim( $arglist[1] ))))) { // string date
          $content       = $arglist[1];
          $parameters    = ( isset( $arglist[2] ) && is_array( $arglist[2] )) ? $arglist[2] : array();
          break;
        }
        elseif( ! isset( $arglist[2] ) || ! isset( $arglist[3] )) {                   // value in all arguments?
          $content       = null;
          $parameters    = array();
          break;
        }
        $content         = array( 'year' => $arglist[1], 'month' => $arglist[2], 'day' => $arglist[3] );
        if( ! isset( $arglist[4] ) || is_array( $arglist[4] )) {                      // 3-4 args
          $parameters    = ( ! isset( $arglist[4] )) ? array() : $arglist[4];
          break;
        }
        if( ! isset( $arglist[5] ) || ! isset( $arglist[6] )) {
          $parameters    = array();
          break;
        }
        $content         = array_merge( $content, array( 'hour' => $arglist[4], 'min' => $arglist[5], 'sec' => $arglist[6] ));
        if( ! isset( $arglist[7] ) || is_array( $arglist[7] )) {                      // 6-7 args
          $parameters    = ( ! isset( $arglist[7] )) ? array() : $arglist[7];
          break;
        }
        $content['tz']   = $arglist[7];
        $parameters      = ( ! isset( $arglist[8] )) ? array() : $arglist[8];         // 7-8 args
        break;
      case 'DURATION':
        if(( is_array( $arglist[1] ) ||                                               // duration array
           ( is_string( $arglist[1] )) && ( 3 <= strlen( trim( $arglist[1] ))))) {    // duration string
          $content       = $arglist[1];
          $parameters    = ( isset( $arglist[2] ) && is_array( $arglist[2] )) ? $arglist[2] : array();
          break;
        }
        elseif( empty( $arglist[1] ) && isset( $arglist[2] ) && is_array( $arglist[2] ) && empty( $arglist[2] )) { // empty content
          $content       = array();
          $parameters    = array();
          break;
        }
        $content  = array();                                                          // value in all arguments
        $content['week'] = ( isset( $arglist[1] ) &&  ! empty( $arglist[1]  )) ? $arglist[1] : 0;
        $content['day']  = ( isset( $arglist[2] ) &&  ! empty( $arglist[2]  )) ? $arglist[2] : 0;
        $content['hour'] = ( isset( $arglist[3] ) &&  ! empty( $arglist[3]  )) ? $arglist[3] : 0;
        $content['min']  = ( isset( $arglist[4] ) &&  ! empty( $arglist[4]  )) ? $arglist[4] : 0;
        $content['sec']  = ( isset( $arglist[5] ) &&  ! empty( $arglist[5]  )) ? $arglist[5] : 0;
        $parameters      = ( isset( $arglist[6] ) && is_array( $arglist[6] ))  ? $arglist[6] : array();
        break;
      case 'FREEBUSY':
        if( ctype_alnum( $arglist[1] ) && ! ctype_digit( $arglist[1] ) && isset( $arglist[2] ) && ( ! isset( $arglist[3] ) || is_array( $arglist[3] ))) {
          $content       = ( isset( $arglist[2] ) &&  ! empty( $arglist[2] )) ? $arglist[2] : null;
          $parameters    = ( isset( $arglist[3] ) && is_array( $arglist[3] )) ? $arglist[3] : array();
          $parameters['FBTYPE'] = $arglist[1];
          $index         = ( isset( $arglist[4] ) && is_int( $arglist[3] )) ? ((int) $arglist[3] ) : null;
        }
        else { // from parse
          $content       = $arglist[1];
          $parameters    = ( isset( $arglist[2] ) && is_array( $arglist[2] )) ? $arglist[2] : array();
          $index         = null;
        }
        break;
      case 'GEO':
        if( is_string( $arglist[1] ) && ( FALSE !== strpos( $arglist[1], ';' ))) {
          $content       = $arglist[1];
          $parameters    = ( isset( $arglist[2] ) && is_array( $arglist[2] )) ? $arglist[2] : array();
        }
        elseif( isset( $arglist[1] ) &&  isset( $arglist[2] )) {
          $content       = ( is_string( $arglist[1] )) ? $arglist[1] : number_format( (float) $arglist[1], 10, '.', '' );
          $content      .= ';';
          $content      .= ( is_string( $arglist[2] )) ? $arglist[2] : number_format( (float) $arglist[2], 10, '.', '' );
          $parameters    = ( isset( $arglist[3] ) && is_array( $arglist[3] )) ? $arglist[3] : array();
        }
        break;
      case 'REQUEST-STATUS':
        if( isset( $arglist[1] ) && ! empty( $arglist[1] ) &&
          ( ! isset( $arglist[2] ) || is_array( $arglist[2] )) &&
          ( ! isset( $arglist[3] ) || ctype_digit((string) $arglist[3] ))) {
          $content       = $arglist[1];
          $parameters    = ( isset( $arglist[2] ) && is_array( $arglist[2] )) ? $arglist[2] : array();
          $index         = ( isset( $arglist[3] ) && is_int( $arglist[3] )) ? ((int) $arglist[3] ) : null;
        }
        else {
          $content       = ( isset( $arglist[1] ) &&  isset( $arglist[2] )) ? array( $arglist[1], $arglist[2] ) : null;
          if( isset( $arglist[3] ) && ! empty( $arglist[3] ))
            $content[]   = $arglist[3];
          $parameters    = ( isset( $arglist[4] ) && is_array( $arglist[4] )) ? $arglist[4] : array();
          $index         = ( isset( $arglist[5] ) && is_int( $arglist[5] )) ? ((int) $arglist[5] ) : null;
        }
        break;
      case 'PERCENT-COMPLETE':             // 4 value type INTEGER
      case 'PRIORITY':
        if( ! isset( $arglist[1] ) || ( '0' > $arglist[1] ))
          $arglist[1]    = '0';
      case 'REPEAT':
        if( ! isset( $arglist[1] ) || ( '0' > $arglist[1] ))
          $arglist[1]    = '1';
      case 'SEQUENCE':
        if( ! isset( $arglist[1] ) || ( '0' > $arglist[1] ))
          $content       = ( isset( $this->properties[$propName] )) ? ( $this->properties[$propName]->get() + 1 ): '1';
        else
          $content       = $arglist[1];
        $parameters      = ( isset( $arglist[2] ) && is_array( $arglist[2] )) ? $arglist[2] : array();
        $index           = null;
        break;
      case 'TRIGGER':
        if( is_array( $arglist[1] ) ||         // timestamp UTC, datetime eller duration array
           ( $arglist[1] instanceof $dt )) {   // datetime as an datetime object instance
          $content       = $arglist[1];
          $parameters    = ( isset( $arglist[2] ) && is_array( $arglist[2] )) ? $arglist[2] : array();
        }
        elseif( is_string( $arglist[1] ) && ( !isset( $arglist[2] ) || is_array( $arglist[2] ))) {  // duration or date in a string
          $content       = $arglist[1];   // duration or date in a string
          $parameters    = ( isset( $arglist[2] ) && is_array( $arglist[2] )) ? $arglist[2] : array();
        }
        else {                         // value in all arguments, first check for datetime
          if( isset( $arglist[1] ) && ! empty( $arglist[1] ) && isset( $arglist[2] ) && ! empty( $arglist[2] ) && isset( $arglist[3] ) && ! empty( $arglist[3] )) { // datetime
            $content     = array( 'year'  => $arglist[1], 'month' => $arglist[2], 'day'   => $arglist[3], 'tz' => 'Z' );
            $content['hour'] = ( isset( $arglist[5] )  &&  ! empty( $arglist[5] ))  ? $arglist[5] : 0;
            $content['min']  = ( isset( $arglist[6] )  &&  ! empty( $arglist[6] ))  ? $arglist[6] : 0;
            $content['sec']  = ( isset( $arglist[7] )  &&  ! empty( $arglist[7] ))  ? $arglist[7] : 0;
            $parameters      = ( isset( $arglist[10] ) && is_array( $arglist[10] )) ? $arglist[10] : array();
            $parameters['VALUE'] = 'DATE-TIME';
          }
          else {                       // or duration
            $content     = array();
            $content['day']  = ( isset( $arglist[3] ) &&  ! empty( $arglist[3]  )) ? $arglist[3] : 0;
            $content['week'] = ( isset( $arglist[4] ) &&  ! empty( $arglist[4]  )) ? $arglist[4] : 0;
            $content['hour'] = ( isset( $arglist[5] ) &&  ! empty( $arglist[5]  )) ? $arglist[5] : 0;
            $content['min']  = ( isset( $arglist[6] ) &&  ! empty( $arglist[6]  )) ? $arglist[6] : 0;
            $content['sec']  = ( isset( $arglist[7] ) &&  ! empty( $arglist[7]  )) ? $arglist[7] : 0;
            $parameters      = ( isset( $arglist[10]) && is_array( $arglist[10] )) ? $arglist[10] : array();
            if( isset( $arglist[8] )) // relatedStart
              $parameters['RELATED'] = ( $arglist[8] ) ? 'START' : 'END';
            if( isset( $arglist[9] )) // before
              $content['before'] = $arglist[9];
            else
              $content['before'] = TRUE;
            $parameters['VALUE'] = 'DURATION';
          }
        } // end TRIGGER, value in all arguments
        break;
      case 'COMMENT':
      case 'DESCRIPTION':
      case 'SUMMARY':
      case 'X-':
        $arglist[1]      = ( isset( $arglist[1] )) ? str_replace( array( "\r\n", "\n\r", "\n", "\r" ), '\n', $arglist[1] ) : null; // no 'break' here!!
     default;
        $content         = ( isset( $arglist[1] ) && ( ! empty( $arglist[1] ) || ( '0' == $arglist[1] ))) ? $arglist[1] : null;
        $parameters      = ( isset( $arglist[2] ) && is_array( $arglist[2] )) ? $arglist[2] : array();
        $index           = ( isset( $arglist[3] ) && is_int( $arglist[3] )) ? ((int) $arglist[3] ) : null;
        break;
    } // end  switch( $propName2 )
    if( empty( $content ) &&
      (( 'UID' == $propName ) || (( is_null( $content ) || ( '' == $content )) && ( TRUE !== $this->getConfig( 'allowempty' )))))
      return $this;
    $cfg  = $this->getConfig( array_keys( iCalComponentFactory::$baseCfgs ));
    if(( 'ATTENDEE' == $propName ) && (( 'VFREEBUSY' == $this->compName ) || ( 'VALARM' == $this->compName )))
      $cfg['x-params'] = TRUE;                  // only x-params allowed
    $vaae = (( 'VALARM' == $this->compName ) && // special condition for attach in email action valarms, also in getProperty
             ( 'ATTACH' == $propName ) &&
             ( FALSE   !== ( $action = $this->getProperty( 'ACTION' ))) &&
             ( 'EMAIL'  != $action )) ? FALSE : TRUE;
    if( $vaae && iCalPropertyFactory::isMultiple( $this->compName, $propName2 )) {
      if( 'X-' == $propName2 )
        $this->properties[$propName] = iCalPropertyFactory::factory( $propName, $content, $parameters, $cfg );
      else {
        if( is_null( $index )) {
          if( ! isset( $this->properties[$propName] ))
            $index = 0;
          else {
            $ak    = array_keys( $this->properties[$propName] );
            $index =  end( $ak ) + 1;
          }
        }
        else
          $index--;
        $property       = iCalPropertyFactory::factory( $propName, $content, $parameters, $cfg );
        if( iCalPropertyFactory::issplittableProp( $propName ) && ( FALSE !== $this->getConfig( 'dosplit' ))) {
          $propVal      = $property->get( TRUE );
          if( is_array( $propVal['value'] ) && ( 1 == count( $propVal['value'] )) || ( FALSE === strpos( $propVal['value'], ',' )))
            $this->properties[$propName][$index] = $property;
          else {
            $this->setConfig( 'dosplit', FALSE );
            $parameters = iCalParameterFactory::params2arr( $propVal['params'] );
            if( ! is_array( $propVal['value'] ))
              $propVal['value'] = explode( ',', $propVal['value'] );
            foreach( $propVal['value'] as $singleValue ) {
              $index++;
              if( 'FREEBUSY' == $propName ) {
                $fbtype = ( isset( $parameters['FBTYPE'] )) ? $parameters['FBTYPE'] : 'BUSY';
                $this->setProperty( $propName, $fbtype, $singleValue, $parameters, $index );
              }
              else
                $this->setProperty( $propName, trim( $singleValue ), $parameters, $index );
            }
            $this->setConfig( 'dosplit', TRUE );
          }
        } // end 'dosplit'
        else
          $this->properties[$propName][$index] = $property;
        if( 1 < count( $this->properties[$propName] ))
          ksort( $this->properties[$propName] );
      }
    } // end isMultiple
    elseif(( 'DTSTART' == $propName ) && in_array( $this->compName, array( 'STANDARD', 'DAYLIGHT' ))) //    isAllowed( 'VTIMEZONE', $this->compName ) ??
      $this->properties[$propName]   = iCalPropertyFactory::factory( $propName, $content, $parameters, ( $cfg + array( 'localdatetime' => TRUE )));
    else
      $this->properties[$propName]   = iCalPropertyFactory::factory( $propName, $content, $parameters, $cfg );
    return $this;
 }
/**
 * set property parameter
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-04
 * @param string $paramKey   property parameter key
 * @param string $paramValue property parameter value
 * @param bool   $replace
 * @uses iCalPropertyFactory::isAllowed()
 * @uses iCalBASEproperty::setParameter()
 * @uses iCalBASEcomponent::setParameter()
 * @access private
 * @return bool
 */
  private function setParameter( $paramKey, $paramValue, $replace=TRUE ) {
    $paramKey  = strtoupper( $paramKey );
    foreach( $this->properties as $propName => & $property ) {
      if( iCalParameterFactory::isAllowed( $propName, $paramKey )) {
        if( is_array( $property )) {
          foreach( $property as & $p2 )
            $p2->setParameter( $paramKey, $paramValue, $replace );
        }
        else
          $property->setParameter( $paramKey, $paramValue, $replace );
      }
    }
    return TRUE;
  }
/**
 * delete calendar component from container
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-05
 * @param mixed $arg1 ordno / component type / component uid
 * @param mixed $arg2 optional, ordno if arg1 = component type
 * @uses iCalBASEcomponent::getProperty()
 * @return bool
 */
  public function deleteComponent( $arg1, $arg2=FALSE  ) {
    $argType = $index = null;
    if ( ctype_digit( (string) $arg1 )) {
      $argType = 'INDEX';
      $index   = (int) $arg1 - 1;
    }
    elseif(( strlen( $arg1 ) <= 9 ) && ( FALSE === strpos( $arg1, '@' ))) {
      $argType = strtoupper( $arg1 );
      $index   = ( !empty( $arg2 ) && ctype_digit( (string) $arg2 )) ? (( int ) $arg2 - 1 ) : 0;
    }
    $cixType = 0;
    foreach ( $this->components as $cix => $component) {
      if( empty( $component )) continue;
      if(( 'INDEX' == $argType ) && ( $index == $cix )) {
        unset( $this->components[$cix] );
        return TRUE;
      }
      elseif( $argType == $component->compName ) {
        if( $index == $cixType ) {
          unset( $this->components[$cix] );
          return TRUE;
        }
        $cixType++;
      }
      elseif( empty( $argType ) && ( $arg1 == $component->getProperty( 'uid' ))) {
        unset( $this->components[$cix] );
        return TRUE;
      }
    }
    return FALSE;
  }
/**
 * get calendar component from container
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-27
 * @param mixed $arg1 optional, ordno/component type/ component uid
 * @param mixed $arg2 optional, ordno if arg1 = component type
 * @uses iCalBASEcomponent::getComponent2()
 * @uses iCalBASEcomponent::getProperty()
 * @return object
 */
  public function getComponent( $arg1=FALSE, $arg2=FALSE ) {
    $index = $argType = null;
    if ( FALSE === $arg1 ) { // first or next in component chain
      $argType = 'INDEX';
      if( isset( $this->compix[$argType] ))
        $this->compix[$argType] += 1;
      else
        $this->compix = array( $argType => 1 );
      $index = $this->compix[$argType];
    }
    elseif( is_array( $arg1 ))
      return self::getComponent2( $arg1 );
    elseif ( ctype_digit( (string) $arg1 )) { // specific component in chain
      $argType = 'INDEX';
      $index   = (int) $arg1;
      unset( $this->compix );
    }
    elseif(( strlen( $arg1 ) <= 9 ) && ( FALSE === strpos( $arg1, '@' ))) { // object class name
      unset( $this->compix['INDEX'] );
      $argType = strtoupper( $arg1 );
      if( FALSE === $arg2 ) {
        if( isset( $this->compix[$argType] ))
          $this->compix[$argType] += 1;
        else
          $this->compix = array( $argType => 1 );
        $index = $this->compix[$argType];
      }
      elseif( isset( $arg2 ) && ctype_digit( (string) $arg2 ))
        $index = (int) $arg2;
    }
    elseif(( strlen( $arg1 ) > 9 ) && ( FALSE !== strpos( $arg1, '@' ))) { // UID as 1st argument
      if( !$arg2 ) {
        $argType = $arg1;
        if( isset( $this->compix[$argType] ))
          $this->compix[$argType] += 1;
        else
          $this->compix = array( $argType => 1 );
        $index = $this->compix[$argType];
      }
      elseif( isset( $arg2 ) && ctype_digit( (string) $arg2 ))
        $index = (int) $arg2;
    }
    if( isset( $index ))
      $index  -= 1;
    $ckeys = array_keys( $this->components );
    if( ! empty( $index) && ( $index > end(  $ckeys )))
      return FALSE;
    $cix1gC = 0;
    foreach ( $this->components as $cix => $component) {
      if( empty( $component )) continue;
      if( 'INDEX' == $argType ) {
        if( $index == $cix1gC )
          return clone $component;
        $cix1gC++;
      }
      elseif( $argType == $component->compName ) {
        if( $index == $cix1gC )
          return clone $component;
        $cix1gC++;
      }
      elseif( empty( $argType ) && ( $arg1 == $component->getProperty( 'uid' ))) { // UID
        if( $index == $cix1gC )
          return clone $component;
        $cix1gC++;
      }
    } // end foreach ( $this->components.. .
            /* not found.. . */
    if( isset( $argType ))
      unset( $this->compix[$argType] );
    return FALSE;
  }
/**
 * get calendar component from container,  search on array( *[propertyName => propertyValue] )
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-04
 * @param array $arg1 search key/value pairs
 * @uses iCalPropertyFactory::isAllowed()
 * @uses iCalPropertyFactory::isget2dateProp()
 * @uses iCalPropertyFactory::isget2otherProp()
 * @uses iCalPropertyFactory::isMultiple()
 * @uses iCalBASEcomponent::getProperty()
 * @access private
 * @return mixed
 */
  private function getComponent2( $arg1 ) {
    $arg1   = array_change_key_case( $arg1, CASE_UPPER );
    $arg2   = implode( '-', array_keys( $arg1 ));
    if( isset( $this->compix[$arg2] ))
      $this->compix[$arg2] += 1;
    else
      $this->compix = array( $arg2 => 1 );
    $index = $this->compix[$arg2];
    $index -= 1;
    $ckeys  = array_keys( $this->components );
    if( !empty( $index) && ( $index > end(  $ckeys )))
      return FALSE;
    $cix1gC = 0;
    foreach ( $this->components as $cix => $component) {
      $hit  = array();
      foreach( $arg1 as $pName => $pValue ) {
        if(( ! iCalPropertyFactory::isAllowed( $component->compName, $pName )) ||
           ( ! iCalPropertyFactory::isget2dateProp( $pName ) && ! iCalPropertyFactory::isget2otherProp( $pName )))
          continue;
        if( iCalPropertyFactory::isMultiple( $component->compName, $pName )) {
          $propValues = array();
          while( FALSE !== ( $value = $component->getProperty( $pName ))) {
            $value = ( FALSE !== strpos( $value, ',' )) ? explode( ',', $value ) : array( $value );
            foreach( $value as $vpart )
              $propValues[] = $vpart;
          }
          $hit[] = ( in_array( $pValue, $propValues )) ? TRUE : FALSE;
          unset( $propValues );
          continue;
        } // end   if(.. .// multiple occurrence
        elseif( FALSE === ( $value = $component->getProperty( $pName ))) { // single occurrence
          $hit[] = FALSE; // missing property
          continue;
        }
        if( 'SUMMARY' == $pName ) { // exists within (any case)
          $hit[] = ( FALSE !== stripos( $value, $pValue )) ? TRUE : FALSE;
          continue;
        }
        if( iCalPropertyFactory::isget2dateProp( $pName )) {
          if( substr( $pValue, 0, 8 ) != substr( $value, 0, 8 )) {
            $hit[] = FALSE;
            continue;
          }
          if(( 8 < strlen( $pValue )) && ( 8 < strlen( $value )))
            $hit[] = ( $pValue == $value ) ? TRUE : FALSE;
          else
            $hit[] = TRUE;
          continue;
        }
        elseif( !is_array( $value ))
          $value = array( $value );
        foreach( $value as $part ) {
          $part = ( FALSE !== strpos( $part, ',' )) ? explode( ',', $part ) : array( $part );
          foreach( $part as $subPart ) {
            if( $pValue == $subPart ) {
              $hit[] = TRUE;
              continue 3;
            }
          }
        } // end foreach( $value as $part )
        $hit[] = FALSE; // no hit in property
      } // end  foreach( $arg1 as $pName => $pValue )
      if( in_array( TRUE, $hit )) {
        if( $index == $cix1gC )
          return clone $component;
        $cix1gC++;
      }
    } // end foreach ( $this->components as $cix => $component) {
            /* not found.. . */
    unset( $this->compix );
    return FALSE;
  }
/**
 * create new calendar component, already included within calendar
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-05
 * @param string $compType component type
 * @uses iCalPropertyFactory::isAllowed()
 * @uses iCalComponentFactory::factory()
 * @uses iCalComponentFactory::$baseCfgs
 * @return mixed object instance (or FALSE if not accepted in calendar/component)
 */
  public function newComponent( $compType ) {
    $keys   = array_keys( $this->components );
    $ix     = end( $keys) + 1;
    if( ! iCalComponentFactory::isAllowed( $this->compName, $compType ))
      return FALSE;
    switch( strtoupper( $compType )) {
      case 'TIMEZONE':
        $compType = 'VTIMEZONE';
      case 'STANDARD':
      case 'VTIMEZONE':
        array_unshift( $this->components, iCalComponentFactory::factory( $compType, $this->getConfig( array_keys( iCalComponentFactory::$baseCfgs ))));
        $ix = 0;
        break;
      case 'DAYLIGHT':
      default:
        $this->components[$ix] = iCalComponentFactory::factory( $compType, $this->getConfig( array_keys( iCalComponentFactory::$baseCfgs )));
        break;
    }
    return $this->components[$ix];
  }
/**
 * parse component content (string/array) into properties and subcomponents
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc9 - 2013-09-05
 * @param mixed $content to parse
 * @uses iCalBASEcomponent::getConfig()
 * @uses iCalComponentFactory::isAllowed()
 * @uses iCalBASEcomponent::newComponent()
 * @uses iCalBASEcomponent::parse()
 * @uses iCalBASEcomponent::setProperty()
 * @return this
 */
  public function parse( $content=FALSE) {
    if( ! is_array( $content ))
      $content = preg_split( "/[\r\n|\n\r|\n|\r]/", $content );
    $subCompName  = '';
    $subCompProps = array();
    foreach( $content as $line ) {
      if( 'BEGIN:'.$this->compName == substr( $line, 0, ( 6 + strlen( $this->compName ))))     // this component starts
        continue;
      if( 'END:'.$this->compName   == substr( $line, 0, ( 4 + strlen( $this->compName ))))     // this component ends
        continue;
      if(( 'BEGIN:' == substr( $line, 0, 6 )) && empty( $subCompName )) {                      // a subcomponent starts
        $subCompName = strtoupper( substr( $line, 6 ));
        $subCompProps = array();
        continue;
      }
      if( 'END:'.$subCompName      == substr( $line, 0, ( 4 + strlen( $subCompName )))) {      // a subcomponent ends
        if( ! empty( $subCompName ) && iCalComponentFactory::isAllowed( $this->compName, $subCompName ))
          $this->newComponent( $subCompName )->parse( $subCompProps );
        $subCompName  = '';
        $subCompProps = array();
        continue;
      }
      if( ! empty( $subCompName )) {
        $subCompProps[] = $line;
        continue;
      }
            /* get property name */
      $propName   = '';
      $cix        = 0;
      while( FALSE !== ( $char = substr( $line, $cix, 1 ))) {
        if( in_array( $char, array( ':', ';' )))
          break;
        $propName .= strtoupper( $char );
        $cix++;
      }
            /* only valid properties starts every line!! */
      if( ! iCalPropertyFactory::isAllowed( $this->compName, $propName ))
        continue;
      $line = ( strlen( $propName ) < strlen( $line )) ? substr( $line, $cix) : ':';
      if(( ':' == $line ) && ( TRUE !== $this->getConfig( 'allowempty' )))
        continue;
      $this->setProperty( $propName, $line );
    } // end foreach( $content as $line )
    if( ! empty( $subCompName ) &&  ! empty( $subCompProps ))
      $this->newComponent( $subCompName )->parse( $subCompProps );
    return $this;
  }
/**
 * add calendar component to container
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-10
 * @param object $component calendar component
 * @param mixed $arg1 optional, ordno/component type/ component uid
 * @param mixed $arg2 optional, ordno if arg1 = component type
 * @uses iCalBASEcomponent::setConfig()
 * @uses iCalBASEcomponent::getConfig()
 * @uses iCalComponentFactory::$baseCfgs
 * @uses iCalComponentFactory::isbaseComponent()
 * @uses iCalComponentFactory::iscalendarComponent()
 * @uses iCalBASEcomponent::getProperty()
 * @return this
 */
  public function setComponent( $component, $arg1=FALSE, $arg2=FALSE  ) {
    $component->setConfig( $this->getConfig( array_keys( iCalComponentFactory::$baseCfgs )));
    if( iCalComponentFactory::isbaseComponent( $component->compName )) {
            /* make sure dtstamp and uid is set */
      $dummy1 = $component->getProperty( 'dtstamp' );
      $dummy2 = $component->getProperty( 'uid' );
    }
    if( empty( $arg1 )) { // plain insert, last in chain
      $this->components[] = $component;
      return $this;
    }
    $argType = $index = null;
    if( ctype_digit( (string) $arg1 )) { // index insert/replace
      $argType = 'INDEX';
      $index   = (int) $arg1 - 1;
    }
    elseif( iCalComponentFactory::iscalendarComponent( strtoupper( $arg1 ))) {
      $argType = strtoupper( $arg1 );
      $index = ( ctype_digit( (string) $arg2 )) ? ((int) $arg2) - 1 : 0;
    }
    // else if arg1 is set, arg1 must be an UID
    $cix1sC = 0;
    foreach ( $this->components as $cix => $component2) {
      if( empty( $component2 )) continue;
      if(( 'INDEX' == $argType ) && ( $index == $cix )) { // index insert/replace
        $this->components[$cix] = $component;
        return $this;
      }
      elseif( $argType == $component2->compName ) { // component Type index insert/replace
        if( $index == $cix1sC ) {
          $this->components[$cix] = $component;
          return $this;
        }
        $cix1sC++;
      }
      elseif( empty( $argType ) && ( $arg1 == $component2->getProperty( 'uid' ))) { // UID insert/replace
        $this->components[$cix] = $component;
        return $this;
      }
    }
            /* arg1=index and not found.. . insert at index .. .*/
    if( 'INDEX' == $argType ) {
      $this->components[$index] = $component;
      ksort( $this->components, SORT_NUMERIC );
    }
    else    /* not found.. . insert last in chain anyway .. .*/
      $this->components[] = $component;
    return $this;
  }
}
