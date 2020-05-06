<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalParameterFactory class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1.1 - 2013-09-17
 */
class iCalParameterFactory {
/**
 * property parameters, all and default
 *
 * @var array
 * @static
 */
  public static $propParams = array(
    'ACTION'           => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'ATTACH'           => array( 'allP'      => array( 'ENCODING'       => array( 'BASE64', '8BIT' )
                                                     , 'FMTTYPE'        => '*'
                                                     , 'VALUE'          => 'BINARY'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array( 'ENCODING'       => '8BIT' ))
  , 'ATTENDEE'         => array( 'allP'      => array( 'CUTYPE'         => '*'
                                                     , 'MEMBER'         => 'CAL-ADDRESS'
                                                     , 'ROLE'           => '*'
                                                     , 'PARTSTAT'       => '*'
                                                     , 'RSVP'           => 'bool'
                                                     , 'DELEGATED-TO'   => 'CAL-ADDRESS'
                                                     , 'DELEGATED-FROM' => 'CAL-ADDRESS'
                                                     , 'SENT-BY'        => 'CAL-ADDRESS'
                                                     , 'CN'             => '*'
                                                     , 'DIR'            => 'URI'
                                                     , 'LANGUAGE'       => '*'
                                                     , 'X-'  => '*' )
                               , 'defaultsP' => array( 'CUTYPE'         => 'INDIVIDUAL'
                                                     , 'PARTSTAT'       => 'NEEDS-ACTION'
                                                     , 'ROLE'           => 'REQ-PARTICIPANT'
                                                     , 'RSVP'           => 'FALSE' ))
  , 'CALSCALE'         => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'CATEGORIES'       => array( 'allP'      => array( 'LANGUAGE'       => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'CLASS'            => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'COMMENT'          => array( 'allP'      => array( 'ALTREP' => 'URI'
                                                     , 'LANGUAGE'       => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'COMPLETED'        => array( 'allP'      => array( 'X-'  => '*' )
                               , 'defaultsP' => array())
  , 'CONTACT'          => array( 'allP'      => array( 'ALTREP'         => 'URI'
                                                     , 'LANGUAGE'       => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'CREATED'          => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'DESCRIPTION'      => array( 'allP'      => array( 'ALTREP'         => 'URI'
                                                     , 'LANGUAGE'       => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'DTEND'            => array( 'allP'      => array( 'VALUE'          => array( 'DATE', 'DATE-TIME' )
                                                     , 'TZID'           => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array( 'VALUE'          => 'DATE-TIME' ))
  , 'DTSTAMP'          => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'DTSTART'          => array( 'allP'      => array( 'VALUE'          => array( 'DATE', 'DATE-TIME' )
                                                     , 'TZID'           => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array( 'VALUE'          => 'DATE-TIME' ))
  , 'DUE'              => array( 'allP'      => array( 'VALUE'          => array( 'DATE', 'DATE-TIME' )
                                                     , 'TZID'           => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array( 'VALUE'          => 'DATE-TIME' ))
  , 'DURATION'         => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'EXDATE'           => array( 'allP'      => array( 'VALUE'          => array( 'DATE', 'DATE-TIME' )
                                                     , 'TZID'           => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array( 'VALUE'          => 'DATE-TIME' ))
  , 'EXRULE'           => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'FREEBUSY'         => array( 'allP'      => array( 'FBTYPE'         => array( 'FREE', 'BUSY', 'BUSY-UNAVAILABLE', 'BUSY-TENTATIVE', 'X-' )
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array()) // 'FBTYPE'         => 'BUSY' ))
  , 'GEO'              => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'LAST-MODIFIED'    => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'LOCATION'         => array( 'allP'      => array( 'ALTREP'         => 'URI'
                                                     , 'LANGUAGE'       => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'METHOD'           => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'ORGANIZER'        => array( 'allP'      => array( 'CN'             => '*'
                                                     , 'DIR'            => 'URI'
                                                     , 'SENT-BY'        => 'CAL-ADDRESS'
                                                     , 'LANGUAGE'       => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'PRIORITY'         => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'PRODID'           => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'PERCENT-COMPLETE' => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'RDATE'            => array( 'allP'      => array( 'VALUE'          => array( 'DATE', 'DATE-TIME', 'PERIOD' )
                                                     , 'TZID'           => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array( 'VALUE'          => 'DATE-TIME' ))
  , 'RECURRENCE-ID'    => array( 'allP'      => array( 'VALUE'          => array( 'DATE', 'DATE-TIME' )
                                                     , 'TZID'           => '*'
                                                     , 'RANGE'          => 'THISANDFUTURE'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array( 'VALUE'          => 'DATE-TIME' ))
  , 'RELATED-TO'       => array( 'allP'      => array( 'RELTYPE'        => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array( 'RELTYPE'        => 'PARENT' ))
  , 'REPEAT'           => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'RESOURCES'        => array( 'allP'      => array( 'ALTREP'         => 'URI'
                                                     , 'LANGUAGE'       => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'RRULE'            => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'REQUEST-STATUS'   => array( 'allP'      => array( 'LANGUAGE'       => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'SEQUENCE'         => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'STATUS'           => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'SUMMARY'          => array( 'allP'      => array( 'ALTREP'         => 'URI'
                                                     , 'LANGUAGE'       => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'TRANSP'           => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'TRIGGER'          => array( 'allP'      => array( 'VALUE'          => array( 'DURATION', 'DATE-TIME' )
                                                     , 'RELATED'        => array( 'START', 'END' )
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array( 'RELATED'        =>  'START'
                                                     , 'VALUE'          => 'DURATION'))
  , 'TZID'             => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'TZNAME'           => array( 'allP'      => array( 'LANGUAGE'       => '*'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'TZOFFSETFROM'     => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'TZOFFSETTO'       => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'TZURL'            => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'UID'              => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'URL'              => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'VERSION'          => array( 'allP'      => array( 'X-'             => '*' )
                               , 'defaultsP' => array())
  , 'X-'               => array( 'allP'      => array( 'LANGUAGE'       => '*'
                                                     , 'VALUE'          => 'TEXT'
                                                     , 'X-'             => '*' )
                               , 'defaultsP' => array( 'VALUE'          => 'TEXT' )));
/**
 * configuration used in property parameters
 *
 * @var array
 * @static
 */
  public static $parameterCfgs = array( 'language', 'tzid' );
/**
 * property factory function, returns property instance or FALSE
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-18
 * @param string $propName   property name
 * @param string $paramKey   parameter key
 * @param string $paramValue parameter value
 * @uses iCalParameterFactory::$propParams
 * @uses iCalParameter
 * @static
 * @return mixed
 */
  public static function factory( $propName, $paramKey, $paramValue ) {
    $propName  = strtoupper( $propName );
    $propName2 = ( 'X-' == substr( $propName, 0, 2 )) ? 'X-' : $propName;
    if( !isset( self::$propParams[$propName2] ))
      return FALSE;
    $paramKey  = strtoupper( $paramKey );
    $paramKey2 = ( 'X-' == substr( $paramKey, 0, 2 )) ? 'X-' : $paramKey;
    if( 'X-' == $propName2 )
      return new iCalParameter( $paramKey, $paramValue );
    if( ! isset( self::$propParams[$propName2]['allP'][$paramKey2] ))
      return FALSE;
    if( is_array( self::$propParams[$propName2]['allP'][$paramKey2] )) {
      $paramValue = strtoupper( $paramValue );
      if( !in_array( $paramValue, self::$propParams[$propName2]['allP'][$paramKey2] ))
        return FALSE;
    }
    elseif( 'CAL-ADDRESS' == self::$propParams[$propName2]['allP'][$paramKey2] ) {
      if(( '"MAILTO:' != strtoupper( substr( $paramValue, 0, 8 ))) && ( 'MAILTO:' != strtoupper( substr( $paramValue, 0, 7 )))) {
        if( '"' == substr( $paramValue, 0, 1 ))
          $paramValue = '"MAILTO:'.substr( $paramValue, 1 );
        else
          $paramValue = 'MAILTO:'.$paramValue;
      }
    }
    return new iCalParameter( $paramKey, $paramValue );
  }
/**
 * add default property parameters
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-17
 * @param string $propName   property name
 * @param array  $parameters parameters
 * @uses iCalParameterFactory::$propParams
 * @static
 * @return array
 */
  public static function addDefaults( $propName, $parameters ) {
    $propName  = strtoupper( $propName );
    $propName2 = ( 'X-' == substr( $propName, 0, 2 )) ? 'X-' : $propName;
    foreach( self::$propParams[$propName2]['defaultsP'] as $defParamKey => $defParamValue ) {
      if( ! isset( $parameters[$defParamKey] ))
        $parameters[$defParamKey] = self::$propParams[$propName2]['defaultsP'][$defParamKey];
    }
    return $parameters;
  }
/**
 * check if parameter is valid for property
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-08
 * @param string $propName property name
 * @param string $paramKey parameter key
 * @uses iCalParameterFactory::$propParams
 * @static
 * @return bool
 */
  public static function isAllowed( $propName, $paramKey ) {
    $propName   = strtoupper( $propName );
    if( 'X-'   == substr( $propName, 0, 2 ))
      $propName = 'X-';
    $paramKey   = strtoupper( $paramKey );
    if( 'X-'   == substr( $paramKey, 0, 2 ))
      $paramKey = 'X-';
    return ( isset( self::$propParams[$propName]['allP'][$paramKey] ));
  }
/**
 * convert string parameters to array parameters
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.1 - 2013-10-11
 * @param mixed $parameters
 * @static
 * @return array
 */
  public static function params2arr( $parameters ) {
    if( empty( $parameters ))
      return array();
    if( is_array( $parameters ))
      return $parameters;
    if( ! is_string( $parameters ))
      $parameters = (string) $parameters;
    $temp             = explode( ';', $parameters );
    $parameters       = array();
    foreach( $temp as $param ) {
      if( empty( $param ))
        continue;
      if( FALSE === strpos( $param, '=' ))
        continue;
      list( $k, $v )  = explode( '=', $param );
      $parameters[$k] = $v;
    }
    return $parameters;
  }
/**
 * remove default property parameters
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1.1 - 2013-09-21
 * @param string $propName   property name
 * @param array  $parameters parameters
 * @uses iCalParameterFactory::$propParams
 * @uses iCalParameter::exists()
 * @uses iCalParameter::get()
 * @static
 * @return array
 */
  public static function removeDefaults( $propName, $parameters ) {
    if( empty( $parameters ))
      return array();
    $propName  = strtoupper( $propName );
    $propName2 = ( 'X-' == substr( $propName, 0, 2 )) ? 'X-' : $propName;
    foreach( $parameters as $pix => $parameter ) {
      if( empty( $parameter ))
        continue;
      foreach( self::$propParams[$propName2]['defaultsP'] as $defParamKey => $defParamValue ) {
        if( $parameter->exists( $defParamKey ) && ( $parameter->get( $defParamKey ) == $defParamValue )) {
          unset( $parameters[$pix] );
          break;
        }
      }
    }
    return ( empty( $parameters )) ? array() : $parameters;
  }
}
