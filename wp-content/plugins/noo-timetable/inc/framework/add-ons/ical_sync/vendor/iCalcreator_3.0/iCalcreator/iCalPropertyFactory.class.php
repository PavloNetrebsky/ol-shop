<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalPropertyFactory class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.4 - 2013-10-19
 */
class iCalPropertyFactory {
/**
 * calendar/component properties and occurrence
 *
 * @var array
 * @static
 */
  public static $compProps = array(
    'VCALENDAR' => array( 'VERSION'          => 'x'              // must exist
                        , 'PRODID'           => null
                        , 'CALSCALE'         => 1                // zero or one ocurrence
                        , 'METHOD'           => 1
                        , 'X-'               => 'm' )            // zero, one or many ocurrencies
   ,'VEVENT'    => array( 'ATTACH'           => 'm'
                        , 'ATTENDEE'         => 'm'
                        , 'CATEGORIES'       => 'm'
                        , 'CLASS'            => 1
                        , 'COMMENT'          => 'm'
                        , 'CONTACT'          => 'm'
                        , 'CREATED'          => 1
                        , 'DESCRIPTION'      => 1
                        , 'DTEND'            => 'DURATION'       // if DTEND is set, DURATION must not
                        , 'DTSTAMP'          => 1
                        , 'DTSTART'          => 1
                        , 'DURATION'         => 'DTEND'          // if DURATION is set, DTEND must not
                        , 'EXDATE'           => 'm'
                        , 'EXRULE'           => 'm'
                        , 'GEO'              => 1
                        , 'LAST-MODIFIED'    => 1
                        , 'LOCATION'         => 1
                        , 'ORGANIZER'        => 1
                        , 'PRIORITY'         => 1
                        , 'RDATE'            => 'm'
                        , 'RECURRENCE-ID'    => 1
                        , 'RELATED-TO'       => 'm'
                        , 'REQUEST-STATUS'   => 'm'
                        , 'RESOURCES'        => 'm'
                        , 'RRULE'            => 'm'
                        , 'SEQUENCE'         => 1
                        , 'STATUS'           => 1
                        , 'SUMMARY'          => 1
                        , 'TRANSP'           => 1
                        , 'UID'              => 1
                        , 'URL'              => 1
                        , 'X-'               => 'm' )
   ,'VTODO'     => array( 'ATTACH'           => 'm'
                        , 'ATTENDEE'         => 'm'
                        , 'CATEGORIES'       => 'm'
                        , 'CLASS'            => 1
                        , 'COMMENT'          => 'm'
                        , 'COMPLETED'        => 1
                        , 'CONTACT'          => 'm'
                        , 'CREATED'          => 1
                        , 'DESCRIPTION'      => 1
                        , 'DTSTAMP'          => 1
                        , 'DTSTART'          => 1
                        , 'DUE'              => 'DURATION'       // if DURATION is set, DUE must not
                        , 'DURATION'         => 'DUE'            // if DUE is set, DURATION must not
                        , 'EXDATE'           => 'm'
                        , 'EXRULE'           => 'm'
                        , 'GEO'              => 1
                        , 'LAST-MODIFIED'    => 1
                        , 'LOCATION'         => 1
                        , 'ORGANIZER'        => 1
                        , 'PERCENT-COMPLETE' => 1
                        , 'PRIORITY'         => 1
                        , 'RDATE'            => 'm'
                        , 'RECURRENCE-ID'    => 1
                        , 'RELATED-TO'       => 'm'
                        , 'REQUEST-STATUS'   => 'm'
                        , 'RESOURCES'        => 'm'
                        , 'RRULE'            => 'm'
                        , 'SEQUENCE'         => 1
                        , 'STATUS'           => 1
                        , 'SUMMARY'          => 1
                        , 'UID'              => 1
                        , 'URL'              => 1
                        , 'X-'               => 'm' )
   ,'VJOURNAL'  => array( 'ATTACH'           => 'm'
                        , 'ATTENDEE'         => 'm'
                        , 'CATEGORIES'       => 'm'
                        , 'CLASS'            => 1
                        , 'COMMENT'          => 'm'
                        , 'CONTACT'          => 'm'
                        , 'CREATED'          => 1
                        , 'DESCRIPTION'      => 'm'
                        , 'DTSTAMP'          => 1
                        , 'DTSTART'          => 1
                        , 'EXDATE'           => 'm'
                        , 'EXRULE'           => 'm'
                        , 'LAST-MODIFIED'    => 1
                        , 'ORGANIZER'        => 1
                        , 'RDATE'            => 'm'
                        , 'RECURRENCE-ID'    => 1
                        , 'RELATED-TO'       => 'm'
                        , 'REQUEST-STATUS'   => 'm'
                        , 'RRULE'            => 'm'
                        , 'SEQUENCE'         => 1
                        , 'STATUS'           => 1
                        , 'SUMMARY'          => 1
                        , 'UID'              => 1
                        , 'URL'              => 1
                        , 'X-'               => 'm' )
   ,'VFREEBUSY' => array( 'ATTENDEE'         => 'm'
                        , 'COMMENT'          => 'm'
                        , 'CONTACT'          => '1'
                        , 'DTEND'            => '1'
                        , 'DTSTAMP'          => 1
                        , 'DTSTART'          => 1
                        , 'DURATION'         => 1
                        , 'FREEBUSY'         => 'm'
                        , 'ORGANIZER'        => 1
                        , 'REQUEST-STATUS'   => 'm'
                        , 'UID'              => 1
                        , 'URL'              => 1
                        , 'X-'               => 'm' )
   ,'VALARM'    => array( 'ACTION'           => 'x'
                        , 'ATTACH'           => 'm'              // 1 in action audio, 'm' in action email, 1 in action procedure, fixed in iCalBASEcomponent
                        , 'ATTENDEE'         => 'M'              // one to many in action email only
                        , 'DESCRIPTION'      => 'x'              // must exists in action display/email, may exist (1) in action procedure
                        , 'DURATION'         => 1                // (DURATION and REPEAT) or none, ToDo!!!
                        , 'REPEAT'           => 1                // (DURATION and REPEAT) or none, ToDo!!!
                        , 'SUMMARY'          => 'x'              // required in action email
                        , 'TRIGGER'          => 'x'
                        , 'X-'               => 'm' )
   ,'VTIMEZONE' => array( 'LAST-MODIFIED'    => 1
                        , 'TZID'             => 'x'
                        , 'TZURL'            => 1
                        , 'X-'               => 'm' )
   ,'STANDARD'  => array( 'COMMENT'
                        , 'DTSTART'          => 'x'
                        , 'RDATE'            => 'm'
                        , 'RRULE'            => 'm'
                        , 'TZNAME'           => 'm'
                        , 'TZOFFSETFROM'     => 'x'
                        , 'TZOFFSETTO'       => 'x'
                        , 'X-'               => 'm' )
   ,'DAYLIGHT'  => array( 'COMMENT'          => 'm'
                        , 'DTSTART'          => 'x'
                        , 'RDATE'            => 'm'
                        , 'RRULE'            => 'm'
                        , 'TZNAME'           => 'm'
                        , 'TZOFFSETFROM'     => 'x'
                        , 'TZOFFSETTO'       => 'x'
                        , 'X-'               => 'm' ));
/**
 * parse property parameters parts
 *
 * @access private
 * @var array
 * @static
 */
  private static $parValPrefix    = array( 'MStz'   => array( 'utc-', 'utc+', 'gmt-', 'gmt+' )
                                         , 'Proto3' => array( 'fax:', 'cid:', 'sms:', 'tel:', 'urn:' )
                                         , 'Proto4' => array( 'crid:', 'news:', 'pres:' )
                                         , 'Proto6' => array( 'mailto:' ));
/**
 * parse property parameters date parts
 *
 * @access private
 * @var array
 * @static
 */
  private static $monthList       = array( 'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'sept', 'oct', 'nov', 'dec'
                                         , 'i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 'ix', 'x', 'xi', 'xii' );
/**
 * properties allowing a comma-separated list and splittable
 *
 * @access private
 * @var array
 * @static
 */
  private static $splittableProps = array( 'CATEGORIES', 'EXDATE', 'FREEBUSY', 'RDATE', 'RESOURCES' );
/**
 * date properties used in component::getComponent2
 *
 * @access private
 * @var array
 * @static
 */
  private static $get2dateProps   = array( 'DTSTART', 'DTEND', 'DUE', 'CREATED', 'COMPLETED', 'DTSTAMP', 'LAST-MODIFIED', 'RECURRENCE-ID' );
/**
 * other properties used in component::getComponent2
 *
 * @access private
 * @var array
 * @static
 */
  private static $get2otherProps  = array( 'ATTENDEE', 'CATEGORIES', 'CONTACT', 'LOCATION', 'ORGANIZER', 'PRIORITY'
                                         , 'RELATED-TO', 'RESOURCES', 'STATUS', 'SUMMARY', 'UID', 'URL' );
/**
 * valid properties used in component::getProperty
 *
 * @access private
 * @var array
 * @static
 */
  private static $validGetProps   = array( 'ATTENDEE', 'CATEGORIES', 'CONTACT', 'DTSTART', 'GEOLOCATION', 'LOCATION', 'ORGANIZER', 'PRIORITY'
                                         , 'RESOURCES', 'STATUS', 'SUMMARY', 'RECURRENCE-ID-UID', 'RELATED-TO', 'R-UID', 'UID', 'URL' );
/**
 * valid properties used in sort
 *
 * @access private
 * @var array
 * @static
 */
  private static $sortArgs        = array( 'ATTENDEE', 'CATEGORIES', 'CONTACT', 'DTSTAMP', 'LOCATION', 'ORGANIZER'
                                         , 'PRIORITY', 'RELATED-TO', 'RESOURCES', 'STATUS', 'SUMMARY', 'UID', 'URL' );
/**
 * configuration used in properties
 *
 * @access private
 * @var array
 * @static
 */
  private static $propertyCfgs = array( 'removedefaults', 'strdate2arr' );
/**
 * property factory function, returns property instance or FALSE
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-18
 * @param string $propName   property name
 * @param string $content    property content
 * @param array $parameters  property parameters
 * @param array $config      calendar configuration
 * @uses iCalPropertyFactory::isVALUEset()
 * @uses iCalPropertyFactory::inString()
 * @uses iCalTEXTproperty
 * @uses iCalDATEproperty
 * @uses iCalREXDATEproperty
 * @uses iCalCAL_ADDRESSproperty
 * @uses iCalDURATIONproperty
 * @uses iCalBINARYproperty
 * @uses iCalURIproperty
 * @uses iCalINTEGERproperty
 * @uses iCalUTC_OFFSETproperty
 * @uses iCalFLOATproperty
 * @uses iCalPERIODproperty
 * @uses iCalREXRULEproperty
 * @static
 * @return iCalBASEproperty child object instance
 */
  public static function factory( $propName, $content='', $parameters=array(), $config=array() ) {
    $propName     = strtoupper( $propName );
    $parameters   = array_change_key_case( $parameters, CASE_UPPER );
    if( isset( $parameters['VALUE'] ))
      $parameters['VALUE'] = strtoupper( $parameters['VALUE'] );
    $propName2    = ( 'X-' == substr( $propName, 0, 2 )) ? 'X-' : $propName;
    if(( 'RDATE' == $propName ) &&        // check for RDATE with PERIOD format
        ( self::isVALUEset( $parameters, 'PERIOD' )   || self::inString( $content, 'VALUE=PERIOD' )))
      $propName2 .= '-PERIOD';
    elseif(( 'TRIGGER' == $propName ) &&  // check TRIGGER with DATE-TIME format
       ( self::isVALUEset( $parameters, 'DATE-TIME' ) || self::inString( $content, 'VALUE=DATE-TIME' )))
      $propName2 .= '-DATE-TIME';
    elseif(( 'ATTACH' == $propName ) &&   // check ATTACH in BINARY format
       ( self::isVALUEset( $parameters, 'BINARY' )    || self::inString( $content, 'VALUE=BINARY' )))
      $propName2 .= '-BINARY';
    switch( $propName2 ) {
      case 'ACTION':                      // value type TEXT
      case 'CALSCALE':
      case 'CATEGORIES':
      case 'CLASS':
      case 'COMMENT':
      case 'CONTACT':
      case 'DESCRIPTION':
      case 'LOCATION':
      case 'METHOD':
      case 'RELATED-TO':
      case 'REQUEST-STATUS':
      case 'RESOURCES':
      case 'STATUS':
      case 'SUMMARY':
      case 'TRANSP':
      case 'TZID':
      case 'TZNAME':
      case 'UID':
      case 'X-':
        return new iCalTEXTproperty(        $propName, $content, $parameters, $config );
        break;
      case 'COMPLETED':                    // value type UTC DATE-TIME, no 'break' here
      case 'CREATED':
      case 'DTSTAMP':
      case 'LAST-MODIFIED':
      case 'TRIGGER-DATE-TIME':
        unset( $parameters['VALUE'] );
        $config['UTC'] = TRUE;
      case 'DTSTART':                      // value type DATE/DATE-TIME
      case 'DTEND':
      case 'DUE':
      case 'RECURRENCE-ID':
        return new iCalDATEproperty(        $propName, $content, $parameters, $config );
        break;
      case 'EXDATE':                       // value type (multiple) DATE/DATE-TIME
      case 'RDATE':
        return new iCalREXDATEproperty(     $propName, $content, $parameters, $config );
        break;
      case 'ATTENDEE':                     // value type cal_adress
      case 'ORGANIZER':
        return new iCalCAL_ADDRESSproperty( $propName, $content, $parameters, $config );
        break;
      case 'DURATION':                     // value type duration
      case 'TRIGGER':
        return new iCalDURATIONproperty(    $propName, $content, $parameters, $config );
        break;
      case 'ATTACH-BINARY':                // value type BINARY
        return new iCalBINARYproperty(      $propName, $content, $parameters, $config );
        break;
      case 'ATTACH':                       // value type URI
      case 'TZURL':
      case 'URL':
        return new iCalURIproperty(         $propName, $content, $parameters, $config );
        break;
      case 'PERCENT-COMPLETE':             // value type INTEGER
      case 'PRIORITY':
      case 'REPEAT':
      case 'SEQUENCE':
        return new iCalINTEGERproperty(     $propName, $content, $parameters, $config );
        break;
      case 'TZOFFSETFROM':                 // value type UTC-OFFSET
      case 'TZOFFSETTO':
        return new iCalUTC_OFFSETproperty(  $propName, $content, $parameters, $config );
        break;
      case 'GEO':                          // value type FLOAT ';' FLOAT
        return new iCalFLOATproperty(       $propName, $content, $parameters, $config );
        break;
      case 'RDATE-PERIOD':                 // value type PERIOD in UTC time format!
      case 'FREEBUSY':
        return new iCalPERIODproperty(      $propName, $content, $parameters, $config );
        break;
      case 'EXRULE':                       // value type RECUR
      case 'RRULE':
        return new iCalREXRULEproperty(     $propName, $content, $parameters, $config );
        break;
      default: // should never be used.. .  ;)
        if( is_array( $content ))
          $content = @implode( ', ', $content );
        elseif( is_object( $content ))
          $content = serialize( $content );
        else
          $content = (string) $content;
// error_log( __METHOD__." VARNING unknown $propName, content=".var_export( $content, TRUE ));
        return new iCalTEXTproperty(        $propName, $content, $parameters, $config );
        break;
    }
    return FALSE;
  }
/**
 * property sort callback function
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-08
 * @param array $a property object instance
 * @param array $b property object instance
 * @static
 * @return int
 */
  public static function cmpfcn( $a, $b ) {
    return strcasecmp( $a->get(), $b->get());
  }
/**
 * check if input string contains VALUE-part
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-25
 * @param string $content   property content
 * @param string $valuePart seach string
 * @static
 * @return bool
 */
  private static function inString( $content, $valuePart ) {
    return ( is_string( $content ) && ! empty( $content ) && (( FALSE !== stripos( $content, ";$valuePart;" )) || ( FALSE !== stripos( $content, ";$valuePart:" ))));
  }
/**
 * check if property is valid in component
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-07
 * @param string $compName component name
 * @param string $propName property name
 * @param string $method   called from
 * @uses iCalPropertyFactory::$compProps
 * @static
 * @return bool
 */
  public static function isAllowed( $compName, $propName, $method=null ) {
    $compName = strtoupper( $compName );
    $propName = strtoupper( $propName );
    if(( 'VCALENDAR' == $compName ) && ( 'PRODID' == $propName ))
      return ( 'iCalBASEcomponent::getProperty' == $method );
    if( 'X-' == substr( $propName, 0, 2 )) // allways allowed, multiple ocurrence
      return TRUE;
    return ( isset( self::$compProps[$compName][$propName] ));
  }
/**
 * check if property ia a (getComponent2) date property
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-07
 * @param string $propName property name
 * @uses iCalPropertyFactory::$get2dateProps
 * @static
 * @return bool
 */
  public static function isget2dateProp( $propName ) {
    return ( in_array( strtoupper( $propName ), self::$get2dateProps ));
  }
/**
 * check if property ia a (getComponent2) other property
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-07
 * @param string $propName property name
 * @uses iCalPropertyFactory::$get2otherProps
 * @static
 * @return bool
 */
  public static function isget2otherProp( $propName ) {
    return ( in_array( strtoupper( $propName ), self::$get2otherProps ));
  }
/**
 * check multiple ocurrence of property
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-07
 * @param string $compName component name
 * @param string $propName property name
 * @uses iCalPropertyFactory::$compProps
 * @static
 * @return bool
 */
  public static function isMultiple( $compName, $propName ) {
    $propName = strtoupper( $propName );
    if( 'X-' == substr( $propName, 0, 2 )) // allways allowed, multiple ocurrence
      return TRUE;
    $compName = strtoupper( $compName );
    return ( isset( self::$compProps[$compName][$propName] ) && ( in_array( self::$compProps[$compName][$propName], array( 'm', 'M' ))));
  }
/**
 * check if string contains an UTC/iCal offset
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1.2 - 2013-09-21
 * @param string $input offset string
 * @static
 * @return bool
 */
  public static function isOffset( $input ) {
    $input = (string) trim( $input );
    return ( ! empty( $input ) && (( 0 < preg_match(        "/\d{4}/", $input )) ||
                                   ( 0 < preg_match( "/[|+|\-]\d{4}/", $input )) ||
                                   ( 0 < preg_match(        "/\d{6}/", $input )) ||
                                   ( 0 < preg_match( "/[|+|\-]\d{6}/", $input ))));
  }
/**
 * check if key is a property config key
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.4 - 2013-10-19
 * @param string $key
 * @static
 * @return bool
 */
  public static function isPropertyCfg( $key ) {
    return ( in_array( strtolower( $key ), self::$propertyCfgs ));
  }
/**
 * check if property is a splittable property
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-07
 * @param string $propName property name
 * @uses iCalPropertyFactory::$splittableProps
 * @static
 * @return bool
 */
  public static function issplittableProp( $propName ) {
    return ( in_array( strtoupper( $propName ), self::$splittableProps ));
  }
/**
 * check if property name is a sort argument
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-08
 * @param string $propName property name
 * @uses iCalPropertyFactory::$sortArgs
 * @static
 * @return bool
 */
  public static function issortArg( $propName ) {
    return ( in_array( strtoupper( $propName ), self::$sortArgs ));
  }
/**
 * check if property is valid when using iCalVCALENDAR::getProperties()
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-07
 * @param string $propName property name
 * @uses iCalPropertyFactory::$validGetProps
 * @static
 * @return bool
 */
  public static function isvalidGetProp( $propName ) {
    return ( in_array( strtoupper( $propName ), self::$validGetProps ));
  }
/**
 * check if parameter contains specific VALUE
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-31
 * @param array $param      parameter array
 * @param string $valuePart search value
 * @static
 * @return bool
 */
  public static function isVALUEset( $parameters, $valuePart ) {
    return ( ! empty( $parameters ) && isset( $parameters['VALUE'] ) && ( $valuePart == strtoupper( $parameters['VALUE'] )));
  }
/**
 * manages DATE/DATE-TIME (UTC?) contents
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc9 - 2013-05-29
 * @param mixed $content    date property content
 * @param array $parameters date property parameters
 * @param array $config     calendar configuration
 * @uses iCalPropertyFactory::manageTheDATE_obj()
 * @uses iCalPropertyFactory::manageTheDATE_ts()
 * @uses iCalPropertyFactory::manageTheDATE_arr()
 * @uses iCalPropertyFactory::manageTheDATE_str()
 * @static
 * @return string
 */
  public static function manageTheDATE( $content, $parameters, $config ) {
    if( empty( $content ))
      return array( '', '' );
            /* content as an datetime object instance */
    $dt = 'DateTime';
    if( $content instanceof $dt )
      return self::manageTheDATE_obj(   $content, $parameters, $config );
            /* content in array format and timestamp */
    if( is_array( $content ) && isset(  $content['timestamp'] ))
      return self::manageTheDATE_ts(    $content, $parameters, $config );
            /* content in all other array formats */
    if( is_array( $content ))
      return self::manageTheDATE_arr(   $content, $parameters, $config );
            /* content in string format */
    return self::manageTheDATE_str(     $content, $parameters, $config );
  }
/**
 * manages DATE/DATE-TIME (UTC?) contents, input as an array
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-17
 * @access private
 * @param mixed $content    date property content
 * @param array $parameters date property parameters
 * @param array $config     calendar configuration
 * @uses iCalPropertyFactory::isVALUEset()
 * @uses iCalPropertyFactory::selectTZID()
 * @static
 * @return array
 */
  private static function manageTheDATE_arr( $content, $parameters, $config ) {
    if( isset( $content[3] ) && !isset( $content[4] )) { // Y-m-d with tz
      $temp       = $content[3];
      $content[3] = $content[4] = $content[5] = 0;
      $content[6] = $temp;
    }
    $temp     = array();
    $tzid     = '';
    foreach( $content as $k => $v ) {
      switch ( $k ) {
        case '0': case 'year':    $temp['year']  = (int) $v; break;
        case '1': case 'month':   $temp['month'] = (int) $v; break;
        case '2': case 'day':     $temp['day']   = (int) $v; break;
        case '3': case 'hour':    $temp['hour']  = (int) $v; break;
        case '4': case 'min' :    $temp['min']   = (int) $v; break;
        case '5': case 'sec' :    $temp['sec']   = (int) $v; break;
        case '6': case 'tz'  :    $temp['tz']    =       $v; break;
      }
    }
    $output = sprintf( '%04d%02d%02d', $temp['year'], $temp['month'], $temp['day'] );
    if( self::isVALUEset( $parameters, 'DATE' ))                         //   output VALUE=DATE
      return array( substr( $output, 0, 8 ), '' );
    if( ! isset( $temp['hour'] )) $temp['hour']  = 0;
    if( ! isset( $temp['min']  )) $temp['min']   = 0;
    if( ! isset( $temp['sec']  )) $temp['sec']   = 0;
    $output  .= sprintf( 'T%02d%02d%02d', $temp['hour'], $temp['min'], $temp['sec'] );
    if(     isset( $temp['tz'] ))
      $tzid   =    $temp['tz'];
    elseif( isset( $parameters['TZID'] ))
      $tzid   =    $parameters['TZID'];
    elseif( isset( $config['tzid'] ))
      $tzid   =    $config['tzid'];
    else
      $tzid   = ''; // date_default_timezone_get();
    if( in_array( $tzid, array( '+0000', '-0000', '+000000', '-000000', 'GMT', 'UTC', 'Z' )))
      return array( $output.'Z', '' );
    if( self::isOffset( $tzid )) {
      $d = date( 'Ymd\THis\Z', mktime( $temp['hour'], $temp['min'], ( $temp['sec'] - self::tz2offset( $tzid )), $temp['month'], $temp['day'], $temp['year'] ));
      return array( $d, self::selectTZID( $temp, $parameters, $config ));
    }
    if( isset( $config['UTC'] )) {                                       //   or UTC DATE-TIME
      try {
        if( empty( $tzid ))
          $date = new DateTime( $output );
        else
          $date = new DateTime( $output, new DateTimeZone( $tzid ));
        $date->setTimezone( new DateTimeZone( 'UTC' ));
        return array( $date->format( 'Ymd\THis\Z' ), '' );
      }
      catch( Exception $e ) {
        return array( $output.'Z', '' );
      }
    }
    if( empty( $tzid )) // ??
      $tzid = self::selectTZID( $temp, $parameters, $config );
    return array( $output, $tzid );                                      //   or DATE-TIME
  }
/**
 * manages DATE/DATE-TIME (UTC?) contents, input as an datetime object instance
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-17
 * @access private
 * @param mixed $content    date property content
 * @param array $parameters date property parameters
 * @param array $config     calendar configuration
 * @uses iCalPropertyFactory::isVALUEset()
 * @static
 * @return array
 */
  private static function manageTheDATE_obj( $content, $parameters, $config ) {
    if( self::isVALUEset( $parameters, 'DATE' ))                           //   output VALUE=DATE
      return array( $content->format( 'Ymd' ), '' );
    if( isset( $config['UTC'] )) {                                         //   or UTC DATE-TIME
      $content->setTimezone( new DateTimeZone( 'UTC' ));
      return array( $content->format( 'Ymd\THis\Z' ), '' );
    }                                                                      //   or DATE-TIME
    return array( $content->format( 'Ymd\THis' ), $content->getTimezone()->getName());
  }
/**
 * manages DATE/DATE-TIME (UTC?) contents, input as a string
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-28
 * @access private
 * @param mixed $content    date property content
 * @param array $parameters date property parameters
 * @param array $config     calendar configuration
 * @uses iCalPropertyFactory::$monthList
 * @uses iCalPropertyFactory::isVALUEset()
 * @uses iCalPropertyFactory::isOffset()
 * @uses iCalPropertyFactory::tz2offset()
 * @static
 * @return array
 */
  private static function manageTheDATE_str( $content, $parameters, $config ) {
    $content      = trim( $content );
    $tzid         = '';
    if( 'Z'      == substr( $content, -1 )) {
      $tzid       = 'UTC';
      $content    = trim( substr( $content, 0, ( strlen( $content ) - 1 )));
    }
    else {                                                              // check/split trailing offset or timezone
      if(       0 < preg_match( "/.*\040[+|\-|]\d{4}\z/", $content )) { // check for trailing four pos offset
        $tzid     = trim( substr( $content, -5 ));
        $content  = trim( substr( $content, 0, ( strlen( $content ) - 5 )));
      }
      elseif(   0 < preg_match( "/.*\040[+|\-|]\d{6}\z/", $content )) { // check for trailing six pos offset
        $tzid     = trim( substr( $content, -7 ));
        $content  = trim( substr( $content, 0, ( strlen( $content ) - 7 )));
      }
      elseif(   0 < preg_match( "/.*\040\D*\/\D*\z/", $content )) {     // check for trailing PHP timezone
        $pos      = strrpos( $content, ' ' );
        $tzid     = trim( substr( $content, $pos ));
        $content  = trim( substr( $content, 0, $pos ));
      }
      elseif(   0 < preg_match( "/.*\040\D*\z/", $content )) {          // check for trailing other timezone?
        $pos      = strrpos( $content, ' ' );
        $temp     = strtolower( trim( substr( $content, $pos )));
        if( ! in_array( $temp, self::$monthList )) {
          $tzid   = $temp;
          $content = trim( substr( $content, 0, $pos ));
        }
      }
      if( empty( $tzid )) {
        if( isset( $parameters['TZID'] ))
          $tzid   = $parameters['TZID'];
        elseif( isset( $config['tzid'] ))
          $tzid   = $config['tzid'];
        else
          $tzid   = ''; // date_default_timezone_get();
      }
    } // end  check/split trailing offset or timezone
    if( self::isVALUEset( $parameters, 'DATE' )) {                      // output VALUE=DATE
      if(       0 < preg_match( "/^\d{8}.*/", $content ))               // YYYYmmdd
        return array( substr( $content, 0, 8 ), '' );
      if(       0 < preg_match( "/^\d{4}[-|\/]\d{2}[-|\/]\d{2}.*/", $content )) // YYYY-mm-dd or YYYY/mm/dd
        return array( substr( $content, 0, 4 ).substr( $content, 5, 2 ).substr( $content, 8, 2 ), '' );
      return array( date( 'Ymd', strtotime( $content )), '' );
    }
    if((        0 < preg_match( "/\d{8}T\d{6}Z/", $content )) && isset( $config['UTC'] )) // always return UTC 'as is'
      return array( $content, '' );
    if( empty( $tzid )) {
      if( isset( $config['UTC'] ))
        return array( date( 'Ymd\THis\Z', strtotime( $content )), '' );
      return array( date( 'Ymd\THis', strtotime( $content )), '' );
    }
    elseif( self::isOffset( $tzid ))
      return array( date( 'Ymd\THis\Z', ( strtotime( $content ) - self::tz2offset( $tzid ))), '' );
    else { // tzid = timezone
      if( ! isset( $config['UTC'] ) && ( 'UTC' != $tzid ))
        return array( date( 'Ymd\THis', strtotime( $content )), $tzid );
      try {
        $date     = new DateTime( $content, new DateTimeZone( $tzid ));
        $date->setTimezone( new DateTimeZone( 'UTC' ));
        return array( $date->format( 'Ymd\THis\Z' ), '' );
      }
      catch( Exception $e ) {
        return array( date( 'Ymd\THis\Z', strtotime( $content.' UTC' )), '' );
      }
    }
  }
/**
 * manages DATE/DATE-TIME (UTC?) contents, input as a timestamp
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-17
 * @access private
 * @param mixed $content    date property content
 * @param array $parameters date property parameters
 * @param array $config     calendar configuration
 * @uses iCalPropertyFactory::isVALUEset()
 * @uses iCalPropertyFactory::isOffset()
 * @uses iCalPropertyFactory::tz2offset()
 * @static
 * @return array
 */
  private static function manageTheDATE_ts( $content, $parameters, $config ) {
    if( self::isVALUEset( $parameters, 'DATE' ))                         //   output VALUE=DATE
      return array( date( 'Ymd', $content['timestamp'] ), '' );
    if( isset( $config['UTC'] )) {                                       //   or UTC DATE-TIME
      try {
        $content = new DateTime( "@{$content['timestamp']}" );
        return array( $content->format( 'Ymd\THis\Z' ), '' );
      }
      catch( Exception $e ) {
        return array( date( 'Ymd\THis\Z', $content['timestamp'] ), '' ); // ??
      }
    }
    if(     isset( $content['tz'] ))                                     //   or DATE-TIME
      $tzid      = $content['tz'];
    elseif( isset( $parameters['TZID'] ))
      $tzid      = $parameters['TZID'];
    elseif( isset( $config['tzid'] ))
      $tzid      = $config['tzid'];
    else
      $tzid      = '';
    try {
      $date      = new DateTime( "@{$content['timestamp']}" );
    }
    catch( Exception $e ) {
      return array( date( 'Ymd\THis', $content['timestamp'] ), $tzid );
    }
    try {
      if( empty( $tzid ))
        return array( $date->format( 'Ymd\THis\Z' ), '' );
      elseif( self::isOffset( $tzid )) {
        $date->modify( self::tz2offset( $tzid ).' seconds' );
        $tzid    = self::selectTZID( $content, $parameters, $config );
      }
      else
        $date->setTimezone( new DateTimeZone( $tzid ));
      return array( $date->format( 'Ymd\THis' ), $tzid );
    }
    catch( Exception $e ) {
      return array( date( 'Ymd\THis', $content['timestamp'] ), $tzid );  // ??
    }
  }
/**
 * parse (split) property (string) content into property value and parameters array
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-30
 * @param mixed $content property content
 * @uses iCalPropertyFactory::$parValPrefix
 * @static
 * @return array
 */
  public static function parse( $content ) {
    if( ! is_string( $content ) || empty( $content ))
      return array( $content, array());
    if( ':' == substr( $content, 0, 1 )) {    // no parse due to no parameters exist
      $content    = ( 1 < strlen( $content )) ? substr( $content, 1 ) : null;
      return array( $content, array());
    }
    elseif( ';'  != substr( $content, 0, 1 )) // no parse due to no parameters exist
      return array( $content, array());
            /* separate attributes from value in content */
    $params       = array();
    $paramix      = -1;
    $clen         = strlen( $content );
    $WithinQuotes = FALSE;
    $cix          = 0;
    while( FALSE !== substr( $content, $cix, 1 )) {
      if(  ! $WithinQuotes  &&   (  ':' == $content[$cix] )                   &&
                                 ( substr( $content,$cix,     3 )  != '://' ) &&
         ( ! in_array( strtolower( substr( $content,$cix - 6, 4 )), self::$parValPrefix['MStz'] ))   &&
         ( ! in_array( strtolower( substr( $content,$cix - 3, 4 )), self::$parValPrefix['Proto3'] )) &&
         ( ! in_array( strtolower( substr( $content,$cix - 4, 5 )), self::$parValPrefix['Proto4'] )) &&
         ( ! in_array( strtolower( substr( $content,$cix - 6, 7 )), self::$parValPrefix['Proto6'] ))) {
        $paramEnd = TRUE;
        if(( $cix < ( $clen - 4 )) &&
             ctype_digit( substr( $content, $cix+1, 4 ))) { // an URI with a (4pos) portnr??
          for( $c2ix = $cix; 3 < $c2ix; $c2ix-- ) {
            if( '://' == substr( $content, $c2ix - 2, 3 )) {
              $paramEnd = FALSE;
              break; // an URI with a portnr!!
            }
          }
        }
        if( $paramEnd) {
          $content = substr( $content, ( $cix + 1 ));
          break;
        }
        $cix++;
      } // end  if(  ! $WithinQuotes  &&   (  ':' == $content[$cix] )
      if( '"' == $content[$cix] )
        $WithinQuotes = ! $WithinQuotes;
      if( ';' == $content[$cix] ) //$content{$cix}
        $params[++$paramix] = '';
      else
        $params[$paramix] .= $content[$cix]; //$content[$cix]
      $cix++;
    } // end while( FALSE !== substr( $line, $cix, 1 ))
            /* make parameters in array format */
    $parameters = array();
    foreach( $params as $param ) {
      list( $k, $v ) = explode( '=', $param, 2 );
      $parameters[strtoupper( $k )] = $v;
    }
    if( isset( $parameters['VALUE'] ))
      $parameters['VALUE'] = strtoupper( $parameters['VALUE'] );
    return array( $content, $parameters );
  }
/**
 * converts a string (iCal) recur pattern to array
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-30
 * @param string $recur recur property content
 * @static
 * @return array
 */
  public static function recur2arr( $recur ) {
    $values = explode( ';', $recur );
    $recur = array();
    foreach( $values as $value2 ) {
      if( empty( $value2 ))
        continue; // ;-char in start/end pos?
      $value3 = explode( '=', $value2, 2 );
      $rulelabel = strtoupper( $value3[0] );
      switch( $rulelabel ) {
        case 'BYDAY': {
          $value4 = explode( ',', $value3[1] );
          if( 1 < count( $value4 )) {
            foreach( $value4 as $v5ix => $value5 ) {
              $value6 = array();
              $dayno = $dayname = null;
              $value5 = trim( (string) $value5 );
              if(( ctype_alpha( substr( $value5, -1 ))) &&
                 ( ctype_alpha( substr( $value5, -2, 1 )))) {
                $dayname = substr( $value5, -2, 2 );
                if( 2 < strlen( $value5 ))
                  $dayno = substr( $value5, 0, ( strlen( $value5 ) - 2 ));
              }
              if( $dayno )
                $value6[] = $dayno;
              if( $dayname )
                $value6['DAY'] = strtoupper( $dayname );
              $value4[$v5ix] = $value6;
            }
          }
          else {
            $value4 = array();
            $dayno  = $dayname = null;
            $value5 = trim( (string) $value3[1] );
            if(( ctype_alpha( substr( $value5, -1 ))) &&
               ( ctype_alpha( substr( $value5, -2, 1 )))) {
                $dayname = substr( $value5, -2, 2 );
              if( 2 < strlen( $value5 ))
                $dayno = substr( $value5, 0, ( strlen( $value5 ) - 2 ));
            }
            if( $dayno )
              $value4[] = $dayno;
            if( $dayname )
              $value4['DAY'] = strtoupper( $dayname );
          }
          $recur[$rulelabel] = $value4;
          break;
        }
        default: {
          $value4 = explode( ',', $value3[1] );
          if( 1 < count( $value4 ))
            $value3[1] = $value4;
          $recur[$rulelabel] = $value3[1];
          break;
        }
      } // end - switch $rulelabel
    } // end - foreach( $values.. .
    return $recur;
  }
/**
 * updates an array with timestamps keys based on a recur pattern
 *
 * if missing, UNTIL is set 1 year from startdate (emergency break)
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.3 - 2013-10-15
 * @param array $result    array to update, array([timestamp] => TRUE)
 * @param array $recurIn   pattern for recurrency (only value part, params ignored)
 * @param array $wdate     component start date
 * @param array $startdate start date
 * @param array $enddate   optional
 * @uses iCalPropertyFactory::recurIntervalIx()
 * @uses iCalPropertyFactory::stepdate()
 * @uses iCalPropertyFactory::recurBYcntcheck()
 * @static
 * @return void
 * @todo BYHOUR, BYMINUTE, BYSECOND, WEEKLY at year end/start
 */
  public static function recur2date( & $result, $recurIn, $wdate, $startdate, $enddate=FALSE ) {
    $wdateStart    = $wdate;
    $wdatets       = strtotime( $wdate );
    $startdatets   = strtotime( $startdate );
    if( ! $enddate )
      $enddate     = date( 'Ymd\THis', strtotime( "$startdate + 1 year" ));
// echo "recur __in_ comp start ".$wdate." period start "$startdate." period end ".$enddate."<br>\n";print_r($recur);echo "<br>\n";//test###
    $endDatets     = strtotime( $enddate );       // fix break
    $recur         = self::recur2arr( $recurIn ); // recur pattern from string to array format
    if( ! isset( $recur['COUNT'] ) && ! isset( $recur['UNTIL'] ))
      $recur['UNTIL'] = $enddate; // create break
    if( isset( $recur['UNTIL'] )) {
      $tdatets     = strtotime( $recur['UNTIL'] );
      if( $endDatets > $tdatets ) {
        $endDatets = $tdatets; // emergency break
        $enddate   = date( 'Ymd\THis', $endDatets );
      }
      else
        $recur['UNTIL'] = date( 'Ymd\THis', $endDatets );
    }
    if( $wdatets > $endDatets ) {
// echo "recur out of date ".date('Y-m-d H:i:s',$wdatets)."<br>\n";//test
      return array(); // nothing to do.. .
    }
    if( ! isset( $recur['FREQ'] )) // "MUST be specified.. ."
      $recur['FREQ'] = 'DAILY'; // ??
    $wkst = ( isset( $recur['WKST'] ) && ( 'SU' == $recur['WKST'] )) ? 24*60*60 : 0; // ??
    $weekStart = (int) date( 'W', ( $wdatets + $wkst ));
    if( !isset( $recur['INTERVAL'] ))
      $recur['INTERVAL'] = 1;
    $countcnt = ( ! isset( $recur['BYSETPOS'] )) ? 1 : 0; // DTSTART counts as the first occurrence
            /* find out how to step up dates and set index for interval count */
    $step = array();
    if( 'YEARLY' == $recur['FREQ'] )
      $step['year']  = 1;
    elseif( 'MONTHLY' == $recur['FREQ'] )
      $step['month'] = 1;
    elseif( 'WEEKLY' == $recur['FREQ'] )
      $step['day']   = 7;
    else
      $step['day']   = 1;
    if( isset( $step['year'] ) && isset( $recur['BYMONTH'] ))
      $step = array( 'month' => 1 );
    if( empty( $step ) && isset( $recur['BYWEEKNO'] )) // ??
      $step = array( 'day' => 7 );
    if( isset( $recur['BYYEARDAY'] ) || isset( $recur['BYMONTHDAY'] ) || isset( $recur['BYDAY'] ))
      $step = array( 'day' => 1 );
    $intervalarr = array();
    if( 1 < $recur['INTERVAL'] ) {
      $intervalix = self::recurIntervalIx( $recur['FREQ'], $wdate, $wkst );
      $intervalarr = array( $intervalix => 0 );
    }
    if( isset( $recur['BYSETPOS'] )) { // save start date + weekno
      $bysetposymd1 = $bysetposymd2 = $bysetposw1 = $bysetposw2 = array();
// echo "bysetposXold_start=$bysetposYold $bysetposMold $bysetposDold<br>\n"; // test ###
      $recur['BYSETPOS'] = explode( ',', $recur['BYSETPOS'] );
      if( 'YEARLY' == $recur['FREQ'] ) {
        $wdate          = substr( $wdate, 0, 4 ).'0101'; // start from beginning of year
        $wdatets        = strtotime( $wdate );
        self::stepdate( $enddate, $endDatets, array( 'year' => 1 )); // make sure to count whole last year
      }
      elseif( 'MONTHLY' == $recur['FREQ'] ) {
        $wdate          = substr( $wdate, 0, 6 ).'01';// start from beginning of month
        $wdatets        = strtotime( $wdate );
        self::stepdate( $enddate, $endDatets, array( 'month' => 1 )); // make sure to count whole last month
      }
      else
        self::stepdate( $enddate, $endDatets, $step); // make sure to count whole last period
// echo "BYSETPOS endDat++ =$enddate step=".var_export($step,TRUE)."<br>\n";//test###
      $bysetposWold = (int) date( 'W', ( $wdatets + $wkst ));
      $bysetposYold = substr( $wdate, 0, 4 );
      $bysetposMold = substr( $wdate, 4, 2 );
      $bysetposDold = substr( $wdate, 6, 2 );
    } // end if( isset( $recur['BYSETPOS'] )) {
    else
      self::stepdate( $wdate, $wdatets, $step);
    $year_old     = null;
    $daynames     = array( 'SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA' );
             /* MAIN LOOP */
// echo "recur start=$wdate end=$enddate<br>\n";//test
    while( TRUE ) {
      if( isset( $endDatets ) && ( $wdatets > $endDatets ))
        break;
      if( isset( $recur['COUNT'] ) && ( $countcnt >= $recur['COUNT'] ))
        break;
      if( $year_old != substr( $wdate, 0, 4 )) {
        $year_old   = (int) substr( $wdate, 0, 4 );
        $daycnts    = array();
        $yeardays   = $weekno = 0;
        $yeardaycnt = array();
        foreach( $daynames as $dn )
          $yeardaycnt[$dn] = 0;
        for( $m = 1; $m <= 12; $m++ ) { // count up and update up-counters
          $daycnts[$m] = array();
          $weekdaycnt = array();
          foreach( $daynames as $dn )
            $weekdaycnt[$dn] = 0;
          $mcnt     = date( 't', mktime( 0, 0, 0, $m, 1, $year_old ));
          for( $d   = 1; $d <= $mcnt; $d++ ) {
            $daycnts[$m][$d] = array();
            if( isset( $recur['BYYEARDAY'] )) {
              $yeardays++;
              $daycnts[$m][$d]['yearcnt_up'] = $yeardays;
            }
            if( isset( $recur['BYDAY'] )) {
              $day    = date( 'w', mktime( 0, 0, 0, $m, $d, $year_old ));
              $day    = $daynames[$day];
              $daycnts[$m][$d]['DAY'] = $day;
              $weekdaycnt[$day]++;
              $daycnts[$m][$d]['monthdayno_up'] = $weekdaycnt[$day];
              $yeardaycnt[$day]++;
              $daycnts[$m][$d]['yeardayno_up'] = $yeardaycnt[$day];
            }
            if(  isset( $recur['BYWEEKNO'] ) || ( $recur['FREQ'] == 'WEEKLY' ))
              $daycnts[$m][$d]['weekno_up'] = (int) date('W',mktime(0, 0, $wkst, $m, $d, $year_old ));
          }
        }
        $daycnt = 0;
        $yeardaycnt = array();
        if(  isset( $recur['BYWEEKNO'] ) || ( $recur['FREQ'] == 'WEEKLY' )) {
          $weekno = null;
          for( $d=31; $d > 25; $d-- ) { // get last weekno for year
            if( !$weekno )
              $weekno = $daycnts[12][$d]['weekno_up'];
            elseif( $weekno < $daycnts[12][$d]['weekno_up'] ) {
              $weekno = $daycnts[12][$d]['weekno_up'];
              break;
            }
          }
        }
        for( $m = 12; $m > 0; $m-- ) { // count down and update down-counters
          $weekdaycnt = array();
          foreach( $daynames as $dn )
            $yeardaycnt[$dn] = $weekdaycnt[$dn] = 0;
          $monthcnt = 0;
          $mcnt     = date( 't', mktime( 0, 0, 0, $m, 1, $year_old ));
          for( $d   = $mcnt; $d > 0; $d-- ) {
            if( isset( $recur['BYYEARDAY'] )) {
              $daycnt -= 1;
              $daycnts[$m][$d]['yearcnt_down'] = $daycnt;
            }
            if( isset( $recur['BYMONTHDAY'] )) {
              $monthcnt -= 1;
              $daycnts[$m][$d]['monthcnt_down'] = $monthcnt;
            }
            if( isset( $recur['BYDAY'] )) {
              $day  = $daycnts[$m][$d]['DAY'];
              $weekdaycnt[$day] -= 1;
              $daycnts[$m][$d]['monthdayno_down'] = $weekdaycnt[$day];
              $yeardaycnt[$day] -= 1;
              $daycnts[$m][$d]['yeardayno_down'] = $yeardaycnt[$day];
            }
            if(  isset( $recur['BYWEEKNO'] ) || ( $recur['FREQ'] == 'WEEKLY' ))
              $daycnts[$m][$d]['weekno_down'] = ($daycnts[$m][$d]['weekno_up'] - $weekno - 1);
          }
        }
      }
            /* check interval */
      if( 1 < $recur['INTERVAL'] ) {
            /* create interval index */
        $intervalix = self::recurIntervalIx( $recur['FREQ'], $wdate, $wkst );
            /* check interval */
        $currentKey = array_keys( $intervalarr );
        $currentKey = end( $currentKey ); // get last index
        if( $currentKey != $intervalix )
          $intervalarr = array( $intervalix => ( $intervalarr[$currentKey] + 1 ));
        if(( $recur['INTERVAL'] != $intervalarr[$intervalix] ) &&
           ( 0 != $intervalarr[$intervalix] )) {
            /* step up date */
// echo "skip: ".implode('-',$wdate)." ix=$intervalix old=$currentKey interval=".$intervalarr[$intervalix]."<br>\n";//test
          self::stepdate( $wdate, $wdatets, $step);
          continue;
        }
        else // continue within the selected interval
          $intervalarr[$intervalix] = 0;
// echo "cont: $wdate ix=$intervalix old=$currentKey interval=".$intervalarr[$intervalix]."<br>\n";//test
      } // end if( 1 < $recur['INTERVAL'] )
      $updateOK = TRUE;
      $m = (int) substr($wdate, 4, 2 );
      $d = (int) substr($wdate, 6, 2 );
      if( $updateOK && isset( $recur['BYMONTH'] ))
        $updateOK = self::recurBYcntcheck( $recur['BYMONTH']
                                         , $m
                                         ,($m - 13));
      if( $updateOK && isset( $recur['BYWEEKNO'] ))
        $updateOK = self::recurBYcntcheck( $recur['BYWEEKNO']
                                         , $daycnts[$m][$d]['weekno_up']
                                         , $daycnts[$m][$d]['weekno_down'] );
      if( $updateOK && isset( $recur['BYYEARDAY'] ))
        $updateOK = self::recurBYcntcheck( $recur['BYYEARDAY']
                                         , $daycnts[$m][$d]['yearcnt_up']
                                         , $daycnts[$m][$d]['yearcnt_down'] );
      if( $updateOK && isset( $recur['BYMONTHDAY'] ))
        $updateOK = self::recurBYcntcheck( $recur['BYMONTHDAY']
                                         , substr( $wdate, 6, 2 )
                                         , $daycnts[$m][$d]['monthcnt_down'] );
// echo "efter BYMONTHDAY: $wdate status: "; echo ($updateOK) ? 'TRUE' : 'FALSE'; echo "<br>\n";//test###
      if( $updateOK && isset( $recur['BYDAY'] )) {
        $updateOK = FALSE;
        if( isset( $recur['BYDAY']['DAY'] )) { // single day, opt with year/month day order no
          $daynoexists = $daynosw = $daynamesw =  FALSE;
          if( $recur['BYDAY']['DAY'] == $daycnts[$m][$d]['DAY'] )
            $daynamesw = TRUE;
          if( isset( $recur['BYDAY'][0] )) {
            $daynoexists = TRUE;
            if(( isset( $recur['FREQ'] ) && ( $recur['FREQ'] == 'MONTHLY' )) || isset( $recur['BYMONTH'] ))
              $daynosw = self::recurBYcntcheck( $recur['BYDAY'][0]
                                              , $daycnts[$m][$d]['monthdayno_up']
                                              , $daycnts[$m][$d]['monthdayno_down'] );
            elseif( isset( $recur['FREQ'] ) && ( $recur['FREQ'] == 'YEARLY' ))
              $daynosw = self::recurBYcntcheck( $recur['BYDAY'][0]
                                              , $daycnts[$m][$d]['yeardayno_up']
                                              , $daycnts[$m][$d]['yeardayno_down'] );
          }
          if((  $daynoexists &&  $daynosw && $daynamesw ) ||
             ( !$daynoexists && !$daynosw && $daynamesw )) {
            $updateOK = TRUE;
// echo "m=$m d=$d day=".$daycnts[$m][$d]['DAY']." yeardayno_up=".$daycnts[$m][$d]['yeardayno_up']." daynoexists:$daynoexists daynosw:$daynosw daynamesw:$daynamesw updateOK:$updateOK<br>\n"; // test ###
          }
// echo "m=$m d=$d day=".$daycnts[$m][$d]['DAY']." yeardayno_up=".$daycnts[$m][$d]['yeardayno_up']." daynoexists:$daynoexists daynosw:$daynosw daynamesw:$daynamesw updateOK:$updateOK<br>\n"; // test ###
        } // end single day
        else { // multiple days
          foreach( $recur['BYDAY'] as $bydayvalue ) {
            $daynoexists = $daynosw = $daynamesw = FALSE;
            if( isset( $bydayvalue['DAY'] ) && ( $bydayvalue['DAY'] == $daycnts[$m][$d]['DAY'] ))
              $daynamesw = TRUE;
            if( isset( $bydayvalue[0] )) {
              $daynoexists = TRUE;
              if(( isset( $recur['FREQ'] ) && ( $recur['FREQ'] == 'MONTHLY' )) ||
                   isset( $recur['BYMONTH'] ))
                $daynosw = self::recurBYcntcheck( $bydayvalue['0']
                                                , $daycnts[$m][$d]['monthdayno_up']
                                                , $daycnts[$m][$d]['monthdayno_down'] );
              elseif( isset( $recur['FREQ'] ) && ( $recur['FREQ'] == 'YEARLY' ))
                $daynosw = self::recurBYcntcheck( $bydayvalue['0']
                                                , $daycnts[$m][$d]['yeardayno_up']
                                                , $daycnts[$m][$d]['yeardayno_down'] );
            }
// echo "daynoexists:$daynoexists daynosw:$daynosw daynamesw:$daynamesw<br>\n"; // test ###
            if((  $daynoexists &&  $daynosw && $daynamesw ) ||
               ( !$daynoexists && !$daynosw && $daynamesw )) {
              $updateOK = TRUE;
              break;
            }
          }
        } // end multiple days
      }
// echo "efter BYDAY: $wdate status: "; echo ($updateOK) ? 'TRUE' : 'FALSE'; echo "<br>\n"; // test ###
            /* check BYSETPOS */
      if( $updateOK ) {
        if( isset( $recur['BYSETPOS'] ) &&
          ( in_array( $recur['FREQ'], array( 'YEARLY', 'MONTHLY', 'WEEKLY', 'DAILY' )))) {
          if( isset( $recur['WEEKLY'] )) {
            if( $bysetposWold == $daycnts[substr($wdate, 4, 2 )][substr($wdate, 6, 2 )]['weekno_up'] )
              $bysetposw1[] = $wdatets;
            else
              $bysetposw2[] = $wdatets;
          }
          else {
            if(( isset( $recur['FREQ'] ) && ( 'YEARLY'      == $recur['FREQ'] )  &&
                                            ( $bysetposYold == substr($wdate, 0, 4 )))   ||
               ( isset( $recur['FREQ'] ) && ( 'MONTHLY'     == $recur['FREQ'] )  &&
                                           (( $bysetposYold == substr($wdate, 0, 4 ))  &&
                                            ( $bysetposMold == substr($wdate, 4, 2 )))) ||
               ( isset( $recur['FREQ'] ) && ( 'DAILY'       == $recur['FREQ'] )  &&
                                           (( $bysetposYold == substr($wdate, 0, 4 ))  &&
                                            ( $bysetposMold == substr($wdate, 4, 2 ))  &&
                                            ( $bysetposDold == substr($wdate, 6, 2 ))))) {
// echo "bysetposymd1[]=".date('Y-m-d H:i:s',$wdatets)."<br>\n";//test
              $bysetposymd1[] = $wdatets;
            }
            else {
// echo "bysetposymd2[]=".date('Y-m-d H:i:s',$wdatets)."<br>\n";//test
              $bysetposymd2[] = $wdatets;
            }
          }
        }
        else {
            /* update result array if BYSETPOS is set */
          $countcnt++;
          if( $startdatets <= $wdatets ) { // only output within period
            $result[$wdatets] = TRUE;
// echo "recur ".date('Y-m-d H:i:s',$wdatets)."<br>\n";//test
          }
// echo "recur undate ".date('Y-m-d H:i:s',$wdatets)." okdatstart ".date('Y-m-d H:i:s',$startdatets)."<br>\n";//test
          $updateOK = FALSE;
        }
      } // end if( $updateOK )
            /* step up date */
      self::stepdate( $wdate, $wdatets, $step);
            /* check if BYSETPOS is set for updating result array */
      if( $updateOK && isset( $recur['BYSETPOS'] )) {
        $bysetpos       = FALSE;
        if( isset( $recur['FREQ'] ) && ( 'YEARLY'  == $recur['FREQ'] ) &&
          ( $bysetposYold != substr($wdate, 0, 4 ))) {
          $bysetpos     = TRUE;
          $bysetposYold = substr($wdate, 0, 4 );
        }
        elseif( isset( $recur['FREQ'] ) && ( 'MONTHLY' == $recur['FREQ'] &&
         (( $bysetposYold != substr($wdate, 0, 4 ) ) || ( $bysetposMold != substr($wdate, 4, 2 ))))) {
          $bysetpos     = TRUE;
          $bysetposYold = substr($wdate, 0, 4 );
          $bysetposMold = substr($wdate, 4, 2 );
        }
        elseif( isset( $recur['FREQ'] ) && ( 'WEEKLY'  == $recur['FREQ'] )) {
          $weekno = (int) date( 'W', mktime( 0, 0, $wkst, (int) substr($wdate, 4, 2 ), (int) substr($wdate, 6, 2 ), (int) substr($wdate, 0, 4 )));
          if( $bysetposWold != $weekno ) {
            $bysetposWold = $weekno;
            $bysetpos     = TRUE;
          }
        }
        elseif( isset( $recur['FREQ'] ) && ( 'DAILY'   == $recur['FREQ'] ) &&
         (( $bysetposYold != substr($wdate, 0, 4 ) )  ||
          ( $bysetposMold != substr($wdate, 4, 2 )) ||
          ( $bysetposDold != substr($wdate, 6, 2 )))) {
          $bysetpos     = TRUE;
          $bysetposYold = substr($wdate, 0, 4 );
          $bysetposMold = substr($wdate, 4, 2 );
          $bysetposDold = substr($wdate, 6, 2 );
        }
        if( $bysetpos ) {
          if( isset( $recur['BYWEEKNO'] )) {
            $bysetposarr1 = & $bysetposw1;
            $bysetposarr2 = & $bysetposw2;
          }
          else {
            $bysetposarr1 = & $bysetposymd1;
            $bysetposarr2 = & $bysetposymd2;
          }
// echo 'test före out startYMD (weekno)='.$wdateStart." ($weekStart) "; // test ###
          foreach( $recur['BYSETPOS'] as $ix ) {
            if( 0 > $ix ) // both positive and negative BYSETPOS allowed
              $ix = ( count( $bysetposarr1 ) + $ix + 1);
            $ix--;
            if( isset( $bysetposarr1[$ix] )) {
              if( $startdatets <= $bysetposarr1[$ix] ) { // only output within period
//                $testdate   = date( 'Ymd\THis', $bysetposarr1[$ix] );                // test ###
//                $testweekno = (int) date( 'W', mktime( 0, 0, $wkst, (int)substr($testdate, 4, 2 ), (int)substr($testdate, 6, 2 ), (int)substr($testdate, 0, 4 ) )); // test ###
// echo " testYMD (weekno)=".$testdate." ($testweekno)";   // test ###
                $result[$bysetposarr1[$ix]] = TRUE;
// echo " recur ".date('Y-m-d H:i:s',$bysetposarr1[$ix]); // test ###
              }
              $countcnt++;
            }
            if( isset( $recur['COUNT'] ) && ( $countcnt >= $recur['COUNT'] ))
              break;
          }
// echo "<br>\n"; // test ###
          $bysetposarr1 = $bysetposarr2;
          $bysetposarr2 = array();
        }
      }
    }
    unset( $recur, $daycnts, $yeardaycnt, $weekdaycnt, $intervalix, $bysetposw1, $bysetposw2, $bysetposymd1, $bysetposymd2, $bysetposarr1, $bysetposarr2 );
  }
/**
 * check recur arrays
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-24
 * @access private
 * @param array $BYvalue   recur By-value
 * @param array $upValue   upper value
 * @param array $downValue lower value
 * @static
 * @return bool
 */
  private static function recurBYcntcheck( $BYvalue, $upValue, $downValue ) {
    if( is_array( $BYvalue ) &&
      ( in_array( $upValue, $BYvalue ) || in_array( $downValue, $BYvalue )))
      return TRUE;
    return (( $BYvalue == $upValue ) || ( $BYvalue == $downValue )) ? TRUE : FALSE;
  }
/**
 * update/create interval index
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-24
 * @access private
 * @param string $freq recur FREQuence
 * @param string $date date
 * @param int    $wkst week start
 * @static
 * @return int
 */
  private static function recurIntervalIx( $freq, $date, $wkst ) {
    switch( $freq ) {
      case 'YEARLY':
        $intervalix = substr( $date, 0, 4 );
        break;
      case 'MONTHLY':
        $intervalix = substr( $date, 0, 6 );
        break;
      case 'WEEKLY':
        $wdatets    = strtotime( $date );
        $intervalix = (int) date( 'W', ( $wdatets + $wkst ));
       break;
      case 'DAILY':
      default:
        $intervalix = substr( $date, 0, 8 );
        break;
    }
    return $intervalix;
  }
/**
 * step date, return updated date, array and timestamp
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-04
 * @access private
 * @param array $date      date to step
 * @param int   $timestamp date timestamp
 * @param array $step      default array( 'day' => 1 )
 * @static
 * @return void
 */
  private static function stepdate( & $date, & $timestamp, $step=array( 'day' => 1 )) {
    foreach( $step as $stepix => $stepvalue ) // only one key!
      $timestamp = strtotime( "$date + $stepvalue $stepix" );
    $date        = date( 'Ymd\THis', $timestamp );
  }
/**
 * select TZID
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-24
 * @access private
 * @param array $temp       arg 1 to check
 * @param array $parameters arg 2 to check
 * @param array $config     arg 3 to check
 * @uses iCalPropertyFactory::isOffset()
 * @static
 * @static
 * @return string
 */
  private static function selectTZID( $temp, $parameters, $config ) {
    if( isset( $temp['tz'] )             && ! empty( $temp['tz'] )         && ! self::isOffset( $temp['tz'] ))
      return $temp['tz'];
    elseif( isset( $parameters['TZID'] ) && ! empty( $parameters['TZID'] ) && ! self::isOffset( $parameters['TZID'] ))
      return $parameters['TZID'];
    elseif( isset( $config['tzid'] )     && ! empty( $config['tzid'] )     && ! self::isOffset( $config['tzid'] ))
      return $config['tzid'];
    else
      return '';
  }
/**
 * break lines at pos 75
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-11
 * @param string $value string to break lines
 * @param string $nl    new line character(-s)
 * @link http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
 * @static
 * @return string
 */
  public static function size75( $string, $nl ) {
    $tmp             = $string;
    $string          = '';
    $cCnt = $x       = 0;
    while( TRUE ) {
      if( !isset( $tmp[$x] )) {
        $string     .= $nl;                           // loop breakes here
        break;
      }
      elseif(( 74   <= $cCnt ) && ( '\\'  == $tmp[$x] ) && ( 'n' == $tmp[$x+1] )) {
        $string     .= $nl.' \n';                     // don't break lines inside '\n'
        $x          += 2;
        if( !isset( $tmp[$x] )) {
          $string   .= $nl;
          break;
        }
        $cCnt        = 3;
      }
      elseif( 75    <= $cCnt ) {
        $string     .= $nl.' ';
        $cCnt        = 1;
      }
      $byte          = ord( $tmp[$x] );
      $string       .= $tmp[$x];
      switch( TRUE ) {
        case(( $byte >= 0x20 ) && ( $byte <= 0x7F )): // characters U-00000000 - U-0000007F (same as ASCII)
          $cCnt     += 1;
          break;                                      // add a one byte character
        case(( $byte & 0xE0) == 0xC0 ):               // characters U-00000080 - U-000007FF, mask 110XXXXX
          if( isset( $tmp[$x+1] )) {
            $cCnt   += 1;
            $string  .= $tmp[$x+1];
            $x       += 1;                            // add a two bytes character
          }
          break;
        case(( $byte & 0xF0 ) == 0xE0 ):              // characters U-00000800 - U-0000FFFF, mask 1110XXXX
          if( isset( $tmp[$x+2] )) {
            $cCnt   += 1;
            $string .= $tmp[$x+1].$tmp[$x+2];
            $x      += 2;                             // add a three bytes character
          }
          break;
        case(( $byte & 0xF8 ) == 0xF0 ):              // characters U-00010000 - U-001FFFFF, mask 11110XXX
          if( isset( $tmp[$x+3] )) {
            $cCnt   += 1;
            $string .= $tmp[$x+1].$tmp[$x+2].$tmp[$x+3];
            $x      += 3;                             // add a four bytes character
          }
          break;
        case(( $byte & 0xFC ) == 0xF8 ):              // characters U-00200000 - U-03FFFFFF, mask 111110XX
          if( isset( $tmp[$x+4] )) {
            $cCnt   += 1;
            $string .= $tmp[$x+1].$tmp[$x+2].$tmp[$x+3].$tmp[$x+4];
            $x      += 4;                             // add a five bytes character
          }
          break;
        case(( $byte & 0xFE ) == 0xFC ):              // characters U-04000000 - U-7FFFFFFF, mask 1111110X
          if( isset( $tmp[$x+5] )) {
            $cCnt   += 1;
            $string .= $tmp[$x+1].$tmp[$x+2].$tmp[$x+3].$tmp[$x+4].$tmp[$x+5];
            $x      += 5;                             // add a six bytes character
          }
        default:                                      // add any other byte without counting up $cCnt
          break;
      } // end switch( TRUE )
      $x            += 1;                             // next 'byte' to test
    } // end while( TRUE ) {
    return $string;
  }
/**
 * convert string date to (iCalcreator 2.x) array date
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-06
 * @param string $date date to convert
 * @static
 * @return array
 */
  public static function strdate2arr( $date ) {
    $output = array( 'year'  => substr( $date, 0, 4 )
                   , 'month' => substr( $date, 4, 2 )
                   , 'day'   => substr( $date, 6, 2 ));
    if( 8 < strlen( $date )) {
      $output['hour'] = substr( $date,  9, 2 );
      $output['min']  = substr( $date, 11, 2 );
      $output['sec']  = substr( $date, 13, 2 );
      if( 'Z' == substr( $date, -1 ))
        $output['tz'] = 'Z';
    }
    return $output;
  }
/**
 * add a backslash for special characters
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-12
 * @param string $text to check for characters
 * @static
 * @return string
 */
  public static function strrep( $text ) {
    if( FALSE !== strpos( $text, '"' ))
      $text = str_replace('"',   "'",     $text );
    if( FALSE !== strpos( $text, '\\' ))
      $text = str_replace('\\',   '\\\\', $text );
    if( FALSE !== strpos( $text, ',' ))
      $text = str_replace(',',   '\,',    $text );
    if( FALSE !== strpos( $text, ';' ))
      $text = str_replace(';',   '\;',    $text );
    return $text;
  }
/**
 * convert offset, [+/-]HHmm[ss], to seconds, used when correcting UTC to localtime or v.v.
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-10
 * @param string $tz offset
 * @static
 * @return int
 */
  public static function tz2offset( $tz ) {
    $tz           = trim( (string)  $tz );
    if( ! in_array( $tz[0], array( '+', '-' ))) // $tz{0}
      $tx         = '+'.$tz;
    $offset       = ((int) substr(  $tz, 1, 2 ) * 3600 ) + ((int) substr( $tz, 3, 2 ) * 60 );
    $offset      += ( 7  == strlen( $tz )) ? (int) substr( $tz, -2 ) : 0;
    $offset      *= ('-' == substr( $tz, 0, 1 )) ? -1 : 1;
    return $offset;
  }
}
