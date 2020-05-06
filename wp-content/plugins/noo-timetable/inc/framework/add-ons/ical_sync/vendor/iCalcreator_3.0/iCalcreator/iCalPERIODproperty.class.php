<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalPERIODproperty class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-21
 */
class iCalPERIODproperty extends iCalBASEproperty {

/**
 * iCalPERIODproperty construct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-18
 * @param string $propName   property name
 * @param string $content    property content
 * @param array  $parameters property parameters
 * @param array  $config     calendar configuration
 * @uses iCalPropertyFactory::manageTheDATE()
 * @uses iCalDURATIONproperty::duration2str()
 * @uses iCalDURATIONproperty::durationCorrect()
 * @uses iCalDURATIONproperty::durationStr2arr()
 * @uses iCalParameterFactory::$propParams
 * @uses iCalParameterFactory::addDefaults()
 * @uses iCalParameterFactory::factory()
 * @return object instance
 */
  public function __construct( $propName, $content, $parameters, $config ) {
    $this->propName = strtoupper( $propName );
    if( 'FREEBUSY' == $this->propName )
      $config['UTC']   = TRUE;
            /* manage content */
    $temp           = array();
    if( is_string( $content ))
      $content    = explode( ',', $content );
    elseif( empty( $content ))
      $content = array();
    foreach( $content as $p1 => $period ) {       // i.e. periods => period
      if( empty( $period ))
        continue;
      $inPeriod     = array();
      if( is_string( $period ) && ( FALSE !== strpos( $period, '/' )))
        $period = explode( '/', $period, 2 );
      foreach( $period as $p2 => $part ) {        // pairs => periodpart I and II
        $pairMember = array();
        if( is_array( $part )) {
          if(( 0 == $p2 ) ||                      // allways a date-time as first value
             (( isset( $part['year'] ) || isset( $part['timestamp'] ))) ||
             (( 3 <= count( $part )) && isset( $part[0] ) && isset( $part[1] ) && isset( $part[2] ) && checkdate((int) $part[1], (int) $part[2], (int) $part[0] ))) {
            list( $pairMember, $tzid ) = iCalPropertyFactory::manageTheDATE( $part, $parameters, $config );
          }
          else                                    // array format duration, second value
            $pairMember = iCalDURATIONproperty::duration2str( iCalDURATIONproperty::durationCorrect( $part ));
        }
        elseif(( 3 <= strlen( trim( $part ))) &&  // string format duration, second value
               ( in_array( $part[0], array( 'P', '+', '-' )))) //$part{0}
          $pairMember = iCalDURATIONproperty::duration2str( iCalDURATIONproperty::durationCorrect( iCalDURATIONproperty::durationStr2arr( $part )));
        elseif( 8 <= strlen( trim( $part )))      // text date ex. 2006-08-03 10:12:18, 1st or 2nd value
          list( $pairMember, $tzid ) = iCalPropertyFactory::manageTheDATE( $part, $parameters, $config );
        $inPeriod[] = $pairMember;
      } // end foreach( $period as $p2 => $part )
      $temp[]       = implode( '/', $inPeriod );
    } // end foreach( $content as $p1 => $period )
    sort( $temp );
    $fstPerZ = ( isset( $parameters['TZID'] ) && in_array( $parameters['TZID'], array( 'GMT', 'UTC', 'Z' ))) ? TRUE : FALSE;
    foreach( $temp as $p1 => & $period ) { // i.e. periods => period
      $part         = explode( '/', $period );
      if(( 0 == $p1 ) && ( 'Z' == substr( $part[0], -1 ))) // check only 1st period for 'Z'
        $fstPerZ    = TRUE;
      elseif( $fstPerZ && ( 'Z' != substr( $part[0], -1 )))
        $part[0]   .= 'Z';
      elseif( ! $fstPerZ && ( 'Z' == substr( $part[0], -1 )))
        $part[0]    = substr( $part[0], 0, ( strlen( $part[0] ) -1 ));
      if( ! in_array( substr( $part[1], 0, 1 ), array( 'P', '+', '-' ))) {
        if( $fstPerZ && ( 'Z' != substr( $part[1], -1 )))
          $part[1] .= 'Z';
        elseif( ! $fstPerZ && ( 'Z' == substr( $part[1], -1 )))
          $part[1]  = substr( $part[1], 0, ( strlen( $part[1] ) -1 ));
      }
      $period       = implode( '/', $part );
    }
    if( $fstPerZ )
      unset( $parameters['TZID'] );
    $this->content  = implode( ',', $temp );
            /* fix paramemers */
    if( 'RDATE' == $this-> propName )
      $parameters['VALUE'] = 'PERIOD'; // required!!
    elseif( 'FREEBUSY' == $this-> propName ) {
      if( ! isset( $parameters['FBTYPE'] ))
        $parameters['FBTYPE'] = 'BUSY';
      else {
        $parameters['FBTYPE'] = strtoupper( $parameters['FBTYPE'] );
        if(( ! in_array( $parameters['FBTYPE'], iCalParameterFactory::$propParams['FREEBUSY']['allP']['FBTYPE'] )) && ( 'X-' != substr( $parameters['FBTYPE'], 0, 2 )))
          $parameters['FBTYPE'] = 'BUSY';
      }
    }
    if( 1 < count( $parameters ))
      ksort( $parameters );
    $parameters     = iCalParameterFactory::addDefaults( $this->propName, $parameters );
    $this->parameters = array();
    foreach( $parameters as $k => $v )
      $this->setParameter( $k, $v );
            /* fix config */
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
 * @uses iCalPERIODproperty::str2arr()
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
 * convert property value from string to array
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-05
 * @param string $periods string formatted periods
 * @access private
 * @uses iCalPropertyFactory::strdate2arr()
 * @uses iCalDURATIONproperty::durationStr2arr()
 * @static
 * @return array
 */
  private static function str2arr( $periods ) {
    $periods        = explode( ',', $periods );
    $res            = array();
    foreach( $periods as $pix => $period ) {
      $parts        = explode( '/', $period );
      $res[$pix][0] = iCalPropertyFactory::strdate2arr( $parts[0] );
      $res[$pix][1] = ( 'P' != $parts[1] ) ? iCalPropertyFactory::strdate2arr( $parts[1] ) : iCalDURATIONproperty::durationStr2arr( $parts[1] );
    }
    return $res;
  }
}
