<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalComponentFactory class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.12 - 2013-12-12
 */
class iCalComponentFactory {
/**
 * components and sub-components
 *
 * @access private
 * @var array
 * @static
 */
  private static $compNames              = array( 'VCALENDAR' => array( 'VTIMEZONE', 'VEVENT', 'VTODO', 'VJOURNAL', 'VFREEBUSY' )
                                                , 'VEVENT'    => array( 'VALARM' )
                                                , 'VTODO'     => array( 'VALARM' )
                                                , 'VJOURNAL'  => array()
                                                , 'VFREEBUSY' => array()
                                                , 'VTIMEZONE' => array( 'STANDARD', 'DAYLIGHT' ));
/**
 * valid components in method self::selectComponents
 *
 * @access private
 * @var array
 * @static
 */
  private static $selectComponents2Comps = array( 'VEVENT', 'VTODO', 'VJOURNAL', 'VFREEBUSY' );
/**
 * basic configuration keys and default values for components
 *
 * @var array
 * @access protected
 */
  public static $baseCfgs                = array( 'allowempty'     => TRUE
                                                , 'dosplit'        => TRUE
                                                , 'language'       => ''
                                                , 'nl'             => "\r\n"
                                                , 'removedefaults' => TRUE
                                                , 'strdate2arr'    => FALSE
                                                , 'tzid'           => ''
                                                , 'unique_id'      => null );
/**
 * configuration key used in vcalendar for PRODID property
 *
 * @access private
 * @var array
 * @static
 */
  public static $prodidCfgs              = array( 'unique_id', 'language'  );
/**
 * configuration keys and default values used in vcalendar only
 *
 * @access private
 * @var array
 * @static
 */
  public static $calendarCfgs            = array( 'delimiter'      => DIRECTORY_SEPARATOR
                                                , 'directory'      => '.'
                                                , 'filename'       => null
                                                , 'url'            => null );

/**
 * initiate static some vars defaults
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-18
 * uses iCalComponentFactory::$baseCfgs
 * uses iCalComponentFactory::$calendarCfgs
 * @static
 * @return void
 */
  public static function init() {
    self::$baseCfgs['unique_id']    = ( isset( $_SERVER['SERVER_NAME'] )) ? gethostbyname( $_SERVER['SERVER_NAME'] ) : 'localhost';
    self::$calendarCfgs['filename'] = date( 'YmdHis' ).'.ics';
  }
/**
 * component factory method
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-08
 * @param string  $compName component name
 * @param array   $config   calendar configuration
 * @uses iCalVEVENT
 * @uses iCalVTODO
 * @uses iCalVJOURNAL
 * @uses iCalVFREEBUSY
 * @uses iCalVALARM
 * @uses iCalVTIMEZONE
 * @uses iCalSTANDARD
 * @uses iCalDAYLIGHT
 * @uses iCalComponentFactory::isAllowed()
 * @uses iCalBASEcomponent::setProperty()
 * @uses iCalComponentFactory::makeUid()
 * @uses iCalBASEcomponent::getConfig()
 * @static
 * @return mixed
 */
  public static function factory( $compName, $config=array() ) {
    $compName = strtoupper( $compName );
    switch( $compName ) {
      case 'EVENT':
      case 'VEVENT':
        $comp = new iCalVEVENT(    $config );
        break;
      case 'TODO':
      case 'VTODO':
        $comp = new iCalVTODO(     $config );
        break;
      case 'JOURNAL':
      case 'VJOURNAL':
        $comp = new iCalVJOURNAL(  $config );
        break;
      case 'FREEBUSY':
      case 'VFREEBUSY':
        $comp = new iCalVFREEBUSY( $config );
        break;
      case 'ALARM':
      case 'VALARM':
        return new iCalVALARM(     $config );
        break;
      case 'VTIMEZONE':
        return new iCalVTIMEZONE(  $config );
        break;
      case 'STANDARD':
        return new iCalSTANDARD(   $config );
        break;
      case 'DAYLIGHT':
        return new iCalDAYLIGHT(   $config );
        break;
      default:
        return FALSE;
    }
    if( iCalPropertyFactory::isAllowed( $comp->compName, 'UID' ))
      $comp->setProperty( 'UID', self::makeUid( $comp->getConfig( 'unique_id' )));
    if( iCalPropertyFactory::isAllowed( $comp->compName, 'DTSTAMP' ))
      $comp->setProperty( 'DTSTAMP' );
    return $comp;
  }
/**
 * create a calendar timezone and standard/daylight components
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.13 - 2013-12-22
 * Generates components for all transitions in a date range, based on contribution by Yitzchok Lavi <icalcreator@onebigsystem.com>
 * Additional changes jpirkey
 * @param object $calendar, reference to an iCalcreator calendar instance
 * @param string $timezone, a PHP5 (DateTimeZone) valid timezone
 * @param array  $xProp,    *[x-propName => x-propValue], optional
 * @param int    $from      a unix timestamp
 * @param int    $to        a unix timestamp
 * @uses iCalVCALENDAR::getProperty()
 * @uses iCalVCALENDAR::newComponent()
 * @uses iCalBASEcomponent::setproperty()
 * @uses iCalUTC_OFFSETproperty::offsetSec2His()
 * @static
 * @return bool
 */
  public static function createTimezone( $calendar, $timezone, $xProp=array(), $from=null, $to=null ) {
    if( empty( $timezone ))
      return FALSE;
    if( !empty( $from ) && !is_int( $from ))
      return FALSE;
    if( !empty( $to )   && !is_int( $to ))
      return FALSE;
    try {
      $dtz               = new DateTimeZone( $timezone );
      $transitions       = $dtz->getTransitions();
      $utcTz             = new DateTimeZone( 'UTC' );
    }
    catch( Exception $e ) { return FALSE; }
    if( empty( $to )) {
      $dates             = array_keys( $calendar->getProperty( 'dtstart' ));
      sort( $dates );
      if( empty( $dates ))
        $dates           = array( date( 'Ymd' ));
    }
    if( !empty( $from ))
      $dateFrom          = new DateTime( "@$from" );             // set lowest date (UTC)
    else {
      $from              = reset( $dates );                      // set lowest date to the lowest dtstart date
      $dateFrom          = new DateTime( $from.'T000000', $dtz );
      $dateFrom->modify( '-7 month' );                           // set $dateFrom to seven month before the lowest date
      $dateFrom->setTimezone( $utcTz );                          // convert local date to UTC
    }
    $dateFromYmd         = $dateFrom->format('Y-m-d' );
    if( !empty( $to ))
      $dateTo            = new DateTime( "@$to" );               // set end date (UTC)
    else {
      $to                = end( $dates );                        // set highest date to the highest dtstart date
      $dateTo            = new DateTime( $to.'T235959', $dtz );
      $dateTo->modify( '+7 month' );                             // set $dateTo to seven month after the highest date
      $dateTo->setTimezone( $utcTz );                            // convert local date to UTC
    }
    $dateToYmd           = $dateTo->format('Y-m-d' );
    unset( $dtz );
    $transTemp           = array();
    $prevOffsetfrom      = 0;
    $stdIx  = $dlghtIx   = null;
    $prevTrans           = FALSE;
    foreach( $transitions as $tix => $trans ) {                  // all transitions in date-time order!!
      $date              = new DateTime( "@{$trans['ts']}" );    // set transition date (UTC)
      $transDateYmd      = $date->format('Y-m-d' );
      if ( $transDateYmd < $dateFromYmd ) {
        $prevOffsetfrom  = $trans['offset'];                     // previous trans offset will be 'next' trans offsetFrom
        $prevTrans       = $trans;                               // save it in case we don't find any that match
        $prevTrans['offsetfrom'] = ( 0 < $tix ) ? $transitions[$tix-1]['offset'] : 0;
        continue;
      }
      if( $transDateYmd > $dateToYmd )
        break;                                                   // loop always (?) breaks here
      if( !empty( $prevOffsetfrom ) || ( 0 == $prevOffsetfrom )) {
        $trans['offsetfrom'] = $prevOffsetfrom;                  // i.e. set previous offsetto as offsetFrom
        $date->modify( $trans['offsetfrom'].'seconds' );         // convert utc date to local date
        $trans['time'] = $date->format( 'Ymd\THis' );            // set date to array to ease up dtstart and (opt) rdate setting
      }
      $prevOffsetfrom    = $trans['offset'];
      if( TRUE !== $trans['isdst'] ) {                           // standard timezone
        if( !empty( $stdIx ) && isset( $transTemp[$stdIx]['offsetfrom'] )  && // check for any repeating rdate's (in order)
           ( $transTemp[$stdIx]['abbr']       ==   $trans['abbr'] )        &&
           ( $transTemp[$stdIx]['offsetfrom'] ==   $trans['offsetfrom'] )  &&
           ( $transTemp[$stdIx]['offset']     ==   $trans['offset'] )) {
          $transTemp[$stdIx]['rdate'][]        =   $trans['time'];
          continue;
        }
        $stdIx           = $tix;
      } // end standard timezone
      else {                                                     // daylight timezone
        if( !empty( $dlghtIx ) && isset( $transTemp[$dlghtIx]['offsetfrom'] ) && // check for any repeating rdate's (in order)
           ( $transTemp[$dlghtIx]['abbr']       ==   $trans['abbr'] )         &&
           ( $transTemp[$dlghtIx]['offsetfrom'] ==   $trans['offsetfrom'] )   &&
           ( $transTemp[$dlghtIx]['offset']     ==   $trans['offset'] )) {
          $transTemp[$dlghtIx]['rdate'][]        =   $trans['time'];
          continue;
        }
        $dlghtIx         = $tix;
      } // end daylight timezone
      $transTemp[$tix]   = $trans;
    } // end foreach( $transitions as $tix => $trans )
    $tz  = $calendar->newComponent( 'VTIMEZONE' );
    $tz->setProperty( 'tzid', $timezone );
    if( !empty( $xProp )) {
      foreach( $xProp as $xPropName => $xPropValue )
        if( 'x-' == strtolower( substr( $xPropName, 0, 2 )))
          $tz->setProperty( $xPropName, $xPropValue );
    }
    if( empty( $transTemp )) {      // if no match found
      if( $prevTrans ) {            // then we use the last transition (before startdate) for the tz info
        $date = new DateTime( "@{$prevTrans['ts']}" );           // set transition date (UTC)
        $date->modify( $prevTrans['offsetfrom'].'seconds' );     // convert utc date to local date
        $prevTrans['time'] = $date->format( 'Ymd\THis' );        // set date to array to ease up dtstart setting
        $transTemp[0] = $prevTrans;
      }
      else {                        // or we use the timezone identifier to BUILD the standard tz info (?)
        $date = new DateTime( 'now', new DateTimeZone( $timezone ));
        $transTemp[0] = array( 'time'       => $date->format( 'Ymd\THis O' )
                             , 'offset'     => $date->format( 'Z' )
                             , 'offsetfrom' => $date->format( 'Z' )
                             , 'isdst'      => FALSE );
      }
    }
    unset( $transitions, $date, $prevTrans );
    foreach( $transTemp as $tix => $trans ) {
      $type  = ( TRUE !== $trans['isdst'] ) ? 'STANDARD' : 'DAYLIGHT';
      $scomp = $tz->newComponent( $type );
      $scomp->setProperty( 'dtstart',         $trans['time'] );
//      $scomp->setProperty( 'x-utc-timestamp', $tix.' : '.$trans['ts'] );   // test ###
      if( !empty( $trans['abbr'] ))
        $scomp->setProperty( 'tzname',        $trans['abbr'] );
      if( isset( $trans['offsetfrom'] ))
        $scomp->setProperty( 'tzoffsetfrom',  iCalUTC_OFFSETproperty::offsetSec2His( $trans['offsetfrom'] ));
      $scomp->setProperty( 'tzoffsetto',      iCalUTC_OFFSETproperty::offsetSec2His( $trans['offset'] ));
      if( isset( $trans['rdate'] ))
        $scomp->setProperty( 'RDATE',         $trans['rdate'] );
    }
    unset( $transTemp );
    return TRUE;
  }
/**
 * check if sub-component is allowed
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-09
 * @param string  $compName component name
 * @param array   $config   calendar configuration
 * @uses iCalComponentFactory::$compNames
 * @static
 * @return mixed
 */
  public static function isAllowed( $baseName, $compName ) {
    $baseName = strtoupper( $baseName );
    $compName = strtoupper( $compName );
    if( !isset( self::$compNames[$baseName] ))
      return FALSE;
    if( in_array( $compName, self::$compNames[$baseName] ))
      return TRUE;
    return (( 'V' != substr( $compName, 0, 1 )) && ( in_array( 'V'.$compName, self::$compNames[$baseName] )));
  }
/**
 * check if component is a 'base' component
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-07
 * @param string $compName component name
 * @uses iCalComponentFactory::$selectComponents2Comps
 * @static
 * @return bool
 */
  public static function isbaseComponent( $compName ) {
    return ( in_array( strtoupper( $compName ), self::$selectComponents2Comps ));
  }
/**
 * check if component is a calendar component
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-07
 * @param string $compName component name
 * @uses iCalComponentFactory::$compNames
 * @static
 * @return bool
 */
  public static function iscalendarComponent( $compName ) {
    return ( in_array( strtoupper( $compName ), self::$compNames['VCALENDAR'] ));
  }
/**
 * create an unique id for a calendar component object instance
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-24
 * @param string $unique_id a 'site' unique id
 * @static
 * @return string
 */
  public static function makeUid( $unique_id ) {
    $date      = date('Ymd\THisT');
    $unique    = substr(microtime(), 2, 4);
    $base      = 'aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPrRsStTuUvVxXuUvVwWzZ1234567890';
    $end       = strlen( $base ) - 1;
    $length    = 6;
    for( $p    = 0; $p < $length; $p++ )
      $unique .= $base[mt_rand( 0, $end )]; //$unique .= $base{mt_rand( 0, $end )};
    return $date.'-'.$unique.'@'.$unique_id;
  }
/**
 * helper function for selectComponents, set property X-CURRENT-DTEND/DUE
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.2 - 2013-10-15
 * @param object $comp       component to update
 * @param array  $dateFormat
 * @param int    $timestamp1
 * @param string $cmpYMD1    date('Ymd') related to $timestamp1
 * @param int    $timestamp2
 * @param string $cmpYMD2    date('Ymd') related to $timestamp2
 * @param array  $tz         date array opt. with key for 'tz'
 * @param array  $SCbools    (end) date booleans
 * @access private
 * @static
 * @return void
 */
  private static function SCsetXCurrentEnd( $comp, $dateFormat, $timestamp1, $cmpYMD1, $timestamp2, $cmpYMD2, $tz, $SCbools ) {
    if( ! $SCbools[ 'dtendExist'] && ! $SCbools[ 'dueExist'] && ! $SCbools[ 'durationExist'] )
      return;
    $H = ( $cmpYMD1 < $cmpYMD2 ) ? 23 : date( 'H', $timestamp2 );
    $i = ( $cmpYMD1 < $cmpYMD2 ) ? 59 : date( 'i', $timestamp2 );
    $s = ( $cmpYMD1 < $cmpYMD2 ) ? 59 : date( 's', $timestamp2 );
    $tend = mktime( $H, $i, $s, date( 'm', $timestamp1 ), date( 'd', $timestamp1 ), date( 'Y', $timestamp1 ) ); // on a day-basis !!!
    if( $SCbools[ 'endAllDayEvent'] && $SCbools[ 'dtendExist'] )
      $tend += ( 24 * 3600 ); // alldaysevents has an end date 'day after' meaning this day
    $datestring = date( $dateFormat['end'], $tend );
    if( isset( $tz['tz'] ))
      $datestring .= ' '.$tz['tz'];
    $propName = ( ! $SCbools[ 'dueExist'] ) ? 'X-CURRENT-DTEND' : 'X-CURRENT-DUE';
    $comp->setProperty( $propName, $datestring );
  }
/**
 * helper function for selectComponents, set property X-CURRENT-DTSTART
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.1 - 2013-10-15
 * @param object $comp       component to update
 * @param array  $dateFormat
 * @param int    $timestamp1
 * @param string $cpmYMD1    date('Ymd') related to $timestamp1
 * @param int    $timestamp2
 * @param string $cpmYMD2    date('Ymd') related to $timestamp2
 * @param array  $tz         date array opt. with key for 'tz'
 * @access private
 * @static
 * @return void
 */
  private static function SCsetXCurrentStart( $comp, $dateFormat, $timestamp1, $cpmYMD1=FALSE, $timestamp2=FALSE, $cpmYMD2=FALSE, $tz=FALSE ) {
    if( $cpmYMD2 && ( $cpmYMD1 <= $cpmYMD2 )) // check date after dtstart
      $timestamp1  = $timestamp2;
    $datestring    = date( $dateFormat['start'], $timestamp1 );
    if( isset( $tz['tz'] ))
      $datestring .= ' '.$tz['tz'];
    $comp->setProperty( 'X-CURRENT-DTSTART', $datestring );
  }
/**
 * select components from calendar on date basis
 *
 * Ensure DTSTART is set for every component.
 * No date controls occurs.
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.5 - 2013-11-07
 * @param array $components
 * @param mixed $startY optional, start Year,  default current Year ALT. array selecOptions ( *[ <propName> => <uniqueValue> ] )
 * @param int   $startM optional, start Month, default current Month
 * @param int   $startD optional, start Day,   default current Day
 * @param int   $endY   optional, end   Year,  default $startY
 * @param int   $endY   optional, end   Month, default $startM
 * @param int   $endY   optional, end   Day,   default $startD
 * @param mixed $cType  optional, calendar component type(-s), default FALSE=all else string/array type(-s)
 * @param bool  $flat   optional, FALSE (default) => output : array[Year][Month][Day][]
 *                                TRUE            => output : array[] (ignores split)
 * @param bool  $any    optional, TRUE (default) - select component(-s) that occurs within period
 *                                FALSE          - only component(-s) that starts within period
 * @param bool  $split  optional, TRUE (default) - one component copy every DAY it occurs during the
 *                                                 period (implies flat=FALSE)
 *                                FALSE          - one occurance of component only in output array
 * @uses iCalComponentFactory::$selectComponents2Comps
 * @uses iCalBASEcomponent::getProperty()
 * @uses iCalPropertyFactory::recur2date()
 * @uses iCalBASEcomponent::setProperty()
 * @uses iCalDURATIONproperty::duration2date()
 * @uses iCalComponentFactory::SCsetXCurrentEnd()
 * @uses iCalComponentFactory::SCsetXCurrentStart();
 * @static
 * @return array or FALSE
 */
  static public function selectComponents( & $components, $startY=FALSE, $startM=FALSE, $startD=FALSE, $endY=FALSE, $endM=FALSE, $endD=FALSE, $cType=FALSE, $flat=FALSE, $any=TRUE, $split=TRUE ) {
            /* check  if empty calendar */
    if( 0 >= count( $components )) return FALSE;
            /* check default dates */
    if( !$startY ) $startY = date( 'Y' );
    if( !$startM ) $startM = date( 'm' );
    if( !$startD ) $startD = date( 'd' );
    $startDate = mktime( 0, 0, 0, $startM, $startD, $startY );
    if( !$endY )   $endY   = $startY;
    if( !$endM )   $endM   = $startM;
    if( !$endD )   $endD   = $startD;
    $endDate   = mktime( 23, 59, 59, $endM, $endD, $endY );
            /* check component types */
    if( empty( $cType ))
      $cType = self::$selectComponents2Comps;
    else {
      if( ! is_array( $cType ))
        $cType = array( $cType );
      foreach( $cType as & $theType ) {
        $theType = strtoupper( $theType );
        if( ! in_array( $theType, self::$selectComponents2Comps ))
          $theType = 'VEVENT';
      }
      $cType = array_unique( $cType );
      if( 0 >= count( $cType ))
        $cType = self::$selectComponents2Comps;
    }
    if(( FALSE === $flat ) && ( FALSE === $any )) // invalid combination
      $split = FALSE;
    if(( TRUE === $flat ) && ( TRUE === $split )) // invalid combination
      $split = FALSE;
            /* iterate components */
    $result       = array();
    $compUIDcmp   = null;
    $recurridList = array();
    foreach ( $components as $cix => $component ) {
      if( empty( $component ))
        continue;
            /* deselect unvalid type components */
      if( !in_array( $component->compName, $cType ))
        continue;
      $strdate2arr = $component->getConfig( 'strdate2arr' ); // safe cfg strdate2arr
      $component->setConfig( 'strdate2arr', FALSE );         // make sure strdate2arr is OFF
      $start = $component->getProperty( 'dtstart' );
            /* select due when dtstart is missing */
      if( empty( $start ) && ( $component->compName == 'VTODO' ) && ( FALSE === ( $start = $component->getProperty( 'due' )))) {
        $component->setConfig( 'strdate2arr', $strdate2arr ); // restore cfg
        continue;
      }
      if( empty( $start )) {
        $component->setConfig( 'strdate2arr', $strdate2arr ); // restore cfg
        continue;
      }
      $compUID      = $component->getProperty( 'UID' );
      if( $compUIDcmp != $compUID ) {
        $compUIDcmp = $compUID;
        unset( $exdatelist, $recurridList );
      }
      $SCbools      = array( 'dtendExist' => FALSE,  'dueExist' => FALSE,  'durationExist' => FALSE, 'endAllDayEvent' => FALSE );
      $recurrid     = FALSE;
      $dateFormat   = array();
      unset( $end, $startWdate, $endWdate, $rdurWsecs, $rdur, $workstart, $workend ); // clean up
      $startWdate   = strtotime( $start );
      $dateFormat['start'] = ( 8 < strlen( $start )) ? 'Ymd\THis' : 'Ymd';
            /* get end date from dtend/due/duration properties */
      $end = $component->getProperty( 'dtend' );
      if( !empty( $end )) {
        $SCbools['dtendExist'] = TRUE;
        $dateFormat['end'] = ( 8 < strlen( $end )) ? 'Ymd\THis' : 'Ymd';
      }
      if( empty( $end ) && ( $component->compName == 'VTODO' )) {
        $end = $component->getProperty( 'due' );
        if( !empty( $end )) {
          $SCbools['dueExist'] = TRUE;
          $dateFormat['end'] = ( 8 < strlen( $end )) ? 'Ymd\THis' : 'Ymd';
        }
      }
      if( !empty( $end ) && ( 9 > strlen( $end ))) {
            /* a DTEND without time part regards an event that ends the day before,
             for an all-day event DTSTART=20071201 DTEND=20071202 (taking place 20071201!!! */
        $SCbools['endAllDayEvent'] = TRUE;
        $end .= 'T235959';
      }
      if( empty( $end )) {
        $end = $component->getProperty( 'duration', FALSE, FALSE, TRUE );// in dtend format
        if( !empty( $end ))
          $SCbools['durationExist'] = TRUE;
          $dateFormat['end'] = ( 8 < strlen( $start )) ? 'Ymd\THis' : 'Ymd';
      }
      if( empty( $end )) // assume one day duration if missing end date
        $end = substr( $start, 0, 8 ).'T235959';
      $endWdate = strtotime( $end );
      if( $endWdate < $startWdate ) { // MUST be after start date!!
        $end = substr( $start, 0, 8 ).'T235959';
        $endWdate = strtotime( $end );
      }
      $rdurWsecs  = $endWdate - $startWdate; // compute event (component) duration in seconds
            /* make a list of optional exclude dates for component occurence from exrule and exdate */
      $exdatelist = array();
      $workstart  = date( 'Ymd\THis', ( $startDate - $rdurWsecs ));
      $workend    = date( 'Ymd\THis', ( $endDate - $rdurWsecs ));
      while( FALSE !== ( $exrule = $component->getProperty( 'exrule' )))    // check exrule
        iCalPropertyFactory::recur2date( $exdatelist, $exrule, $start, $workstart, $workend );
      while( FALSE !== ( $exdate = $component->getProperty( 'exdate' ))) {  // check exdate
        $exdate = explode( ',', $exdate );
        foreach( $exdate as $theExdate ) {
          $exWdate = strtotime( substr( $theExdate, 0, 8 ).'T000000' );     // on a day-basis !!!
          if((( $startDate - $rdurWsecs ) <= $exWdate ) && ( $endDate >= $exWdate ))
            $exdatelist[$exWdate] = TRUE;
        } // end - foreach( $exdate as $theExdate )
      }  // end - check exdate
            /* check recurrence-id (note, a missing sequence is the same as sequence=0 so don't test for sequence), remove hit with reccurr-id date */
      if( FALSE !== ( $t = $recurrid = $component->getProperty( 'recurrence-id' ))) {
        $recurrid = strtotime( substr( $recurrid, 0, 8 ).'T000000' ); // on a day-basis !!!
        $recurridList[$recurrid] = TRUE;                                             // no recurring to start this day
      } // end recurrence-id/sequence test
            /* select only components with.. . */
      if(( !$any && ( $startWdate >= $startDate ) && ( $startWdate <= $endDate )) || // (dt)start within the period
         (  $any && ( $startWdate < $endDate ) && ( $endWdate >= $startDate ))) {    // occurs within the period
            /* add the selected component (WITHIN valid dates) to output array */
        if( $flat ) { // any=true/false, ignores split
          if( !$recurrid )
            $result[$compUID] = $component; // to output (but no one with recurrence-id)
        }
        elseif( $split ) { // split the original component
          if( $endWdate > $endDate )
            $endWdate = $endDate;     // use period end date
          $rstart   = ( $startWdate < $startDate ) ? $startDate : $startWdate; // use period start date
          $startYMD = $rstartYMD = date( 'Ymd', $rstart );
          $endYMD   = date( 'Ymd', $endWdate );
          $checkDate = mktime( 0, 0, 0, date( 'm', $rstart ), date( 'd', $rstart ), date( 'Y', $rstart ) ); // on a day-basis !!!
          if( !isset( $exdatelist[$checkDate] )) { // exclude any recurrence START date, found in exdatelist
            while( $rstartYMD <= $endYMD ) { // iterate
              if( isset( $exdatelist[$checkDate] ) ||                   // exclude any recurrence date, found in the exdatelist
                ( isset( $recurridList[$checkDate] ) && !$recurrid )) { // or in the recurridList, but not itself
                $rstart   += ( 24 * 3600 ); // step one day
                $rstartYMD = date( 'Ymd', $rstart );
                continue;
              }
              self::SCsetXCurrentStart( $component, $dateFormat, $checkDate, $rstartYMD, $rstart, $startYMD, $start );
              self::SCsetXCurrentEnd( $component, $dateFormat, $rstart, $rstartYMD, $endWdate, $endYMD, $end, $SCbools );
              $wd        = getdate( $rstart );
              $result[$wd['year']][$wd['mon']][$wd['mday']][$compUID] = clone $component; // to output
              $rstart   += ( 24 * 3600 ); // step one day
              $rstartYMD = date( 'Ymd', $rstart );
              $checkDate = mktime( 0, 0, 0, date( 'm', $rstart ), date( 'd', $rstart ), date( 'Y', $rstart ) ); // on a day-basis !!!
            } // end while( $rstart <= $endWdate )
          } // end if( !isset( $exdatelist[$checkDate] ))
        } // end elseif( $split )   -  else use component date
        elseif( $recurrid && !$flat && !$any && !$split )
          $continue = TRUE;
        else { // !$flat && !$split, i.e. no flat array and DTSTART within period
          $checkDate = mktime( 0, 0, 0, date( 'm', $startWdate ), date( 'd', $startWdate ), date( 'Y', $startWdate ) ); // on a day-basis !!!
          if(( ! $any || ! isset( $exdatelist[$checkDate] )) &&   // exclude any recurrence date, found in exdatelist
              ( ! isset( $recurridList[$checkDate] ) || $recurrid )) { // or in the recurridList, but not itself
            $wd = getdate( $startWdate );
            $result[$wd['year']][$wd['mon']][$wd['mday']][$compUID] = $component; // to output
          }
        }
      } // end if(( $startWdate >= $startDate ) && ( $startWdate <= $endDate ))
            /* if 'any' components, check components with reccurrence rules, removing all excluding dates */
      if( TRUE === $any ) {
            /* make a list of optional repeating dates for component occurence, rrule, rdate */
        $recurlist = array();
        while( FALSE !== ( $rrule = $component->getProperty( 'rrule' )))    // check rrule
          iCalPropertyFactory::recur2date( $recurlist, $rrule, $start, $workstart, $workend );
        foreach( $recurlist as $recurkey => $recurvalue )                   // key=match date as timestamp
          $recurlist[$recurkey] = $rdurWsecs;                               // add duration in seconds
        while( FALSE !== ( $rdate = $component->getProperty( 'rdate' ))) {  // check rdate
          $rdate = explode( ',', $rdate );
          foreach( $rdate as $theRdate ) {
            if( FALSE !== ( $pos = strpos( '/', $theRdate ))) {             // all days within PERIOD
              $theRdate  = explode( '/', $theRdate, 2 );
              $rstart    = strtotime( $theRdate[0] );
              if(( $rstart < ( $startDate - $rdurWsecs )) || ( $rstart > $endDate ))
                continue;
              if( ctype_digit( substr( $theRdate[1], 0, 8 )))               // date-date period
                $rend    = strtotime( $theRdate[1] );
              else                                                          // date-duration period
                $rend    = strtotime( iCalDURATIONproperty::duration2date( $theRdate[0], $theRdate[1] ));
              while( $rstart < $rend ) {
                $recurlist[$rstart] = $rdurWsecs; // set start date for recurrence instance + rdate duration in seconds
                $rstart += ( 24 * 3600 ); // step one day
              }
            } // PERIOD end
            else { // single date
              $theRdate = strtotime( $theRdate );
              if((( $startDate - $rdurWsecs ) <= $theRdate ) && ( $endDate >= $theRdate ))
                $recurlist[$theRdate] = $rdurWsecs; // set start date for recurrence instance + event duration in seconds
            }
          }
        }  // end - check rdate
        foreach( $recurlist as $recurkey => $durvalue ) { // remove all recurrence START dates found in the exdatelist
          $checkDate = mktime( 0, 0, 0, date( 'm', $recurkey ), date( 'd', $recurkey ), date( 'Y', $recurkey ) ); // on a day-basis !!!
          if( isset( $exdatelist[$checkDate] )) // no recurring to start this day
            unset( $recurlist[$recurkey] );
        }
        if( 0 < count( $recurlist )) {
          ksort( $recurlist );
          $xRecurrence = 1;
          $component2  = clone $component;
          $compUID     = $component2->getProperty( 'UID' );
          foreach( $recurlist as $recurkey => $durvalue ) {
            if((( $startDate - $rdurWsecs ) > $recurkey ) || ( $endDate < $recurkey )) // not within period
              continue;
            $checkDate = mktime( 0, 0, 0, date( 'm', $recurkey ), date( 'd', $recurkey ), date( 'Y', $recurkey ) ); // on a day-basis !!!
            if( isset( $recurridList[$checkDate] )) // no recurring to start this day
              continue;
            if( isset( $exdatelist[$checkDate] ))   // check excluded dates
              continue;
            if( $startWdate >= $recurkey )          // exclude component start date
              continue;
            $rstart = $recurkey;
            $rend   = $recurkey + $durvalue;
           /* add repeating components within valid dates to output array, only start date set */
            if( $flat ) {
              if( !isset( $result[$compUID] )) // only one comp
                $result[$compUID] = clone $component2; // to output
            }
           /* add repeating components within valid dates to output array, one each day */
            elseif( $split ) {
              $xRecurrence += 1;
              if( $rend > $endDate )
                $rend = $endDate;
              $startYMD = $rstartYMD = date( 'Ymd', $rstart );
              $endYMD   = date( 'Ymd', $rend );
              while( $rstart <= $rend ) { // iterate.. .
                $checkDate      = mktime( 0, 0, 0, date( 'm', $rstart ), date( 'd', $rstart ), date( 'Y', $rstart ) ); // on a day-basis !!!
                if( isset( $recurridList[$checkDate] )) // no ocurrence to start this day
                  break;
                if( isset( $exdatelist[$checkDate] ))   // exclude any recurrence START date, found in exdatelist
                  break;
                if( $rstart >= $startDate ) {           // date after dtstart
                  self::SCsetXCurrentStart( $component2, $dateFormat, $checkDate, $rstartYMD, $rstart, $startYMD, $start );
                  self::SCsetXCurrentEnd( $component2, $dateFormat, $rstart, $rstartYMD, $endWdate, $endYMD, $end, $SCbools );
                  $component2->setProperty( 'X-RECURRENCE', $xRecurrence );
                  $wd = getdate( $rstart );
                  $result[$wd['year']][$wd['mon']][$wd['mday']][$compUID] = clone $component2; // copy to output
                } // end if( $checkDate > $startYMD ) { // date after dtstart
                $rstart        += ( 24 * 3600 ); // step one day
                $rstartYMD      = date( 'Ymd', $rstart );
              } // end while( $rstart <= $rend )
            } // end elseif( $split )
            elseif( $rstart >= $startDate ) {           // date within period   //* flat=FALSE && split=FALSE => one comp every recur startdate *//
              $xRecurrence += 1;
              $checkDate = mktime( 0, 0, 0, date( 'm', $rstart ), date( 'd', $rstart ), date( 'Y', $rstart ) ); // on a day-basis !!!
              if( !isset( $exdatelist[$checkDate] )) {  // exclude any recurrence START date, found in exdatelist
                self::SCsetXCurrentStart( $component2, $dateFormat, $rstart, FALSE, FALSE, FALSE, $start );
                $tend = $rstart + $rdurWsecs;
                self::SCsetXCurrentEnd( $component2, $dateFormat, $tend, date( 'Ymd', $tend ), $endWdate, date( 'Ymd', $endWdate ), $end, $SCbools );
                $component2->setProperty( 'X-RECURRENCE', $xRecurrence );
                $wd = getdate( $rstart );
                $result[$wd['year']][$wd['mon']][$wd['mday']][$compUID] = clone $component2; // copy to output
              } // end if( !isset( $exdatelist[$checkDate] ))
            } // end elseif( $rstart >= $startDate )
          } // end foreach( $recurlist as $recurkey => $durvalue )
          unset( $component2 );
        } // end if( 0 < count( $recurlist ))
            /* deselect components with startdate/enddate not within period */
        if(( $endWdate < $startDate ) || ( $startWdate > $endDate ))
          continue;
      } // end if( TRUE === $any )
      $component->setConfig( 'strdate2arr', $strdate2arr ); // restore cfg
    } // end foreach ( $this->components as $cix => $component )
    unset( $SCbools, $recurrid, $recurridList,
           $end, $startWdate, $endWdate, $rdurWsecs, $rdur, $exdatelist, $recurlist, $workstart, $workend, $dateFormat ); // clean up
    if( 0 >= count( $result )) return FALSE;
    elseif( !$flat ) {
      foreach( $result as $y => $yeararr ) {
        foreach( $yeararr as $m => $montharr ) {
          foreach( $montharr as $d => $dayarr ) {
            if( empty( $result[$y][$m][$d] ))
                unset( $result[$y][$m][$d] );
            else {
              $result[$y][$m][$d] = array_values( $dayarr ); // skip tricky UID-index
              if( 1 < count( $result[$y][$m][$d] )) {        // sort also on Hms
                iCalComponentFactory::sortDate( $result[$y][$m][$d] );
                usort( $result[$y][$m][$d], array( 'iCalComponentFactory', 'cmpfcn' ));
              }
            }
          }
          if( empty( $result[$y][$m] ))
              unset( $result[$y][$m] );
          else
            ksort( $result[$y][$m] );
        }
        if( empty( $result[$y] ))
            unset( $result[$y] );
        else
          ksort( $result[$y] );
      }
      if( empty( $result ))
          unset( $result );
      else
        ksort( $result );
    } // end elseif( !$flat )
    if( 0 >= count( $result ))
      return FALSE;
    return $result;
  }
/**
 * select components from calendar on based on specific property value(-s)
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-08
 * @param array $components calendar components
 * @param array $selectOptions, (string) key => (mixed) value, (key=propertyName)
 * @uses iCalComponentFactory::$selectComponents2Comps
 * @uses iCalPropertyFactory::isget2otherProp()
 * @uses iCalBASEcomponent::getProperty()
 * @uses iCalPropertyFactory::isMultiple()
 * @static
 * @return array
 */
  public static function selectComponents2( & $components, $selectOptions ) {
    $output = array();
    foreach( $components as $cix => $component ) {
      if( ! in_array( $component->compName, self::$selectComponents2Comps ))
        continue;
      $uid = $component->getProperty( 'UID' );
      foreach( $selectOptions as $propName => $pvalue ) {
        $propName = strtoupper( $propName );
        if( ! iCalPropertyFactory::isget2otherProp( $propName ))
          continue;
        if( !is_array( $pvalue ))
          $pvalue = array( $pvalue );
        if(( 'UID' == $propName ) && in_array( $uid, $pvalue )) {
          $output[$uid][] = clone $component;
          continue;
        }
        elseif( iCalPropertyFactory::isMultiple( $component->compName, $propName ) ) {
          $propValues = array();
          while( FALSE !== ( $d = $component->getProperty( $propName ))) {
            $d = explode( ',', $d );
            foreach( $d as $d2 )
              $propValues[] = $d2;
          }
          foreach( $pvalue as $theValue ) {
            if( in_array( $theValue, $propValues )) { //  && !isset( $output[$uid] )) {
              $output[$uid][] = clone $component;
              break;
            }
          }
          continue;
        } // end   elseif( // multiple occurrence?
        elseif( FALSE === ( $d = $component->getProperty( $propName ))) // single occurrence
          continue;
        if( 'SUMMARY' == $propName ) {
          foreach( $pvalue as $theValue ) {
            if( FALSE !== stripos( $d, $theValue ) && !isset( $output[$uid] )) {
              $output[$uid][] = $component;
              break;
            }
          }
        }
        elseif( FALSE !== ( $pos = strpos( $d, ',' ))) {
          $d = explode( ',', $d );
          foreach( $d as $d2 ) {
            if( in_array( $d2, $pvalue ) && !isset( $output[$uid] ))
              $output[$uid][] = clone $component;
          }
        }
        elseif( in_array( $d, $pvalue ) && !isset( $output[$uid] ))
          $output[$uid][] = $component;
      } // end foreach( $selectOptions as $propName => $pvalue ) {
    } // end foreach( $this->components as $cix => $component ) {
    if( !empty( $output )) {
      ksort( $output ); // uid order
      $output2 = array();
      foreach( $output as $uid => $components ) {
        foreach( $components as $component )
          $output2[] = clone $component;
      }
      $output = $output2;
      unset( $output2 );
    }
    return $output;
  }
/**
 * fetch specific (argument) property value to be used as sort key
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-08
 * @param array  $components calendar components
 * @param string $sortArg    sort argument
 * @uses iCalPropertyFactory::issortArg()
 * @uses iCalPropertyFactory::isMultiple()
 * @uses iCalBASEcomponent::getProperty()
 * @static
 * @return void
 *
 */
  public static function sortArg( & $components, $sortArg ) {
    $sortArg = strtoupper( $sortArg );
    if( ! iCalPropertyFactory::issortArg( $sortArg ))
      return;
    foreach( $components as $cix => & $c ) {
      $c->srtk = array( '0', '0', '0', '0' );
      if( iCalPropertyFactory::isMultiple( $c->compName, $sortArg )) { // multiple ocurrence
        $propValues = array();
        while( FALSE !== ( $d = $c->getProperty( $sortArg )))
          $propValues[] = $d;
        if( !empty( $propValues )) {
          sort( $propValues, SORT_STRING );
          $c->srtk[0] = $propValues[0];
          if( 'RELATED-TO'  == $sortArg )
            $c->srtk[0] .= $c->getProperty( 'uid' );
        }
        elseif( 'RELATED-TO'  == $sortArg )
          $c->srtk[0] = $c->getProperty( 'uid' );
      }
      elseif( FALSE !== ( $d = $c->getProperty( $sortArg ))) { // single ocurrence
        $c->srtk[0] = $d;
        if( 'UID' == $sortArg ) {
          if( FALSE !== ( $d = $c->getProperty( 'recurrence-id' ))) {
            $c->srtk[1] = $d;
            if( FALSE === ( $c->srtk[2] = $c->getProperty( 'sequence' )))
              $c->srtk[2] = PHP_INT_MAX;
          }
          else
            $c->srtk[1] = $c->srtk[2] = PHP_INT_MAX;
        }
      }
    }
  }
/**
 * fetch properties (if exist) x-current-dtstart, dtstart, x-current-dtend, dtend
 * x-current-due, due, duration, created, dtstamp and uid before sort
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-29
 * @param array  $components calendar components
 * @uses iCalBASEcomponent::getProperty()
 * @static
 * @return void
 *
 */
  public static function sortDate( & $components) {
    foreach( $components as $cix => & $c ) {
      $c->srtk = array( '0', '0', '0', '0' );
      if(       FALSE !== ( $d          = $c->getProperty( 'X-CURRENT-DTSTART' )))
        $c->srtk[0]     =   $d;
      elseif(   FALSE === ( $c->srtk[0] = $c->getProperty( 'DTSTART' )))
        $c->srtk[0]     = 0;             // sortkey 0 : dtstart
      if(       FALSE !== ( $d          = $c->getProperty( 'X-CURRENT-DTEND' )))
        $c->srtk[1]     =   $d;
      elseif(   FALSE === ( $c->srtk[1] = $c->getProperty( 'DTEND' ))) {
        if(     FALSE !== ( $d          = $c->getProperty( 'X-CURRENT-DUE' )))
          $c->srtk[1]   =   $d;
        elseif( FALSE === ( $c->srtk[1] = $c->getProperty( 'DUE' ))) {
          if(   FALSE === ( $c->srtk[1] = $c->getProperty( 'DURATION', FALSE, FALSE, TRUE )))
            $c->srtk[1] =   0;           // sortkey 1 : dtend/due(/dtstart+duration)
        }
      }
      if(       FALSE === ( $c->srtk[2] = $c->getProperty( 'CREATED' )))
        if(     FALSE === ( $c->srtk[2] = $c->getProperty( 'DTSTAMP' )))
          $c->srtk[2]   =   0;            // sortkey 2 : created/dtstamp
      if(       FALSE === ( $c->srtk[3] = $c->getProperty( 'UID' )))
        $c->srtk[3]     =   0;            // sortkey 3 : uid
    }
  }
/**
 * vcalendar sort callback function
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-24
 * @param array $a calendar component
 * @param array $b calendar component
 * @static
 * @return int
 */
  public static function cmpfcn( $a, $b ) {
    if(        empty( $a ))                       return -1;
    if(        empty( $b ))                       return  1;
    if( 'VTIMEZONE'     == $a->compName ) {
      if( 'VTIMEZONE'   != $b->compName )         return -1;
      elseif( $a->srtk[0] <= $b->srtk[0] )        return -1;
      else                                        return  1;
    }
    elseif( 'VTIMEZONE' == $b->compName )         return  1;
    for( $k = 0; $k < 4 ; $k++ ) {
      if(        empty( $a->srtk[$k] ))           return -1;
      elseif(    empty( $b->srtk[$k] ))           return  1;
      elseif( $a->srtk[$k] < $b->srtk[$k] )       return -1;
      elseif( $a->srtk[$k] > $b->srtk[$k] )       return  1;
    }
    return 0;
  }
}
