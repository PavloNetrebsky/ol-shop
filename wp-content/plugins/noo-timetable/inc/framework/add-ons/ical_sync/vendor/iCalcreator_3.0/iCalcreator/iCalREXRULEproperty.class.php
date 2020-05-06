<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalREXRULEproperty class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.2 - 2013-05-24
 */
class iCalREXRULEproperty extends iCalBASEproperty {
/**
 * rexrule days
 *
 * @var array
 * @access private
 * @static
 */
  private static $days = array( 'SU' => 0, 'MO' => 1, 'TU' => 2, 'WE' => 3, 'TH' => 4, 'FR' => 5, 'SA' => 6 );
/**
 * iCalREXRULEproperty construct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-18
 * @param string $propName   property name
 * @param string $content    property content
 * @param array  $parameters property parameters
 * @param array  $config     calendar configuration
 * @uses iCalPropertyFactory::recur2arr()
 * @uses iCalPropertyFactory::manageTheDATE()
 * @uses iCalREXRULEproperty::rfcn()
 * @uses iCalREXRULEproperty::bydaySort()
 * @uses iCalParameterFactory::addDefaults()
 * @uses iCalParameterFactory::isAllowed()
 * @uses iCalParameterFactory::factory()
 * @return object instance
 */
  public function __construct( $propName, $content, $parameters, $config ) {
    $this->propName   = strtoupper( $propName );
            /* manage content */
    if( is_array( $content )) {
      $rexrules       = array();
      foreach( $content as $k => $v )
        $rexrules[strtoupper( $k )] = $v;
    }
    else
      $rexrules       = iCalPropertyFactory::recur2arr( $content );
            /* set recurrence rules in rfc2445 specification order */
    $content          = '';
    if( isset( $rexrules['FREQ'] ))
      $content       .= 'FREQ='.$rexrules['FREQ'];
    if( isset( $rexrules['UNTIL'] )) {
      if((( is_array( $rexrules['UNTIL'] ) &&
          ! isset( $rexrules['UNTIL']['hour'] ) && ! isset( $rexrules['UNTIL'][4] ) && ! isset( $rexrules['UNTIL']['timestamp'] ))) ||
         ( is_string( $rexrules['UNTIL'] ) && ( 11 > strlen( $rexrules['UNTIL'] )))) {
        list( $untilDate, $tzid ) = iCalPropertyFactory::manageTheDATE( $rexrules['UNTIL'], array( 'VALUE' => 'DATE' ), $config );
        $content     .= ';UNTIL='.$untilDate;
      }
      else {
        list( $untilDate, $tzid ) = iCalPropertyFactory::manageTheDATE( $rexrules['UNTIL'], array(), array( 'UTC' => TRUE ));
        $content     .= ';UNTIL='.$untilDate;
        if( 'Z' != substr( $content, -1 ))
          $content       .= 'Z';
      }
    }
    elseif( isset( $rexrules['COUNT'] ))
      $content       .= ';COUNT='.$rexrules['COUNT'];
    if( isset( $rexrules['INTERVAL'] ))
      $content       .= ';INTERVAL='.$rexrules['INTERVAL'];
    if( isset( $rexrules['BYSECOND'] ))
      $content       .= ';BYSECOND='.self::rfcn( $rexrules['BYSECOND'] );
    if( isset( $rexrules['BYMINUTE'] ))
      $content       .= ';BYMINUTE='.self::rfcn( $rexrules['BYMINUTE'] );
    if( isset( $rexrules['BYHOUR'] ))
      $content       .= ';BYHOUR='.self::rfcn( $rexrules['BYHOUR'] );
    if( isset( $rexrules['BYDAY'] )) {
      $byday          = array( '' );
      $bx             = 0;
      foreach( $rexrules['BYDAY'] as $bydayPart ) {
        if( ! empty( $byday[$bx] ) && ! ctype_digit( substr( $byday[$bx], -1 ))) // new day
          $byday[++$bx] = '';
        if( ! is_array( $bydayPart ))   // day without order number
          $byday[$bx] .= (string) $bydayPart;
        else {                          // day with order number
          foreach( $bydayPart as $bydayPart2 )
            $byday[$bx] .= ( ctype_alpha( $bydayPart2 )) ? strtoupper( $bydayPart2 ) : (string) $bydayPart2;
        }
      } // end foreach( $rexrules['BYDAY']
      if( 1 < count( $byday ))
        usort( $byday, array( 'self', 'bydaySort' ));
      $content       .= ';BYDAY='.implode( ',', $byday );
    } // end if( isset( $rexrules['BYDAY'] ))
    if( isset( $rexrules['BYMONTHDAY'] ))
      $content       .= ';BYMONTHDAY='.self::rfcn( $rexrules['BYMONTHDAY'] );
    if( isset( $rexrules['BYYEARDAY'] ))
      $content       .= ';BYYEARDAY='.self::rfcn( $rexrules['BYYEARDAY'] );
    if( isset( $rexrules['BYWEEKNO'] ))
      $content       .= ';BYWEEKNO='.self::rfcn( $rexrules['BYWEEKNO'] );
    if( isset( $rexrules['BYMONTH'] ))
      $content       .= ';BYMONTH='.self::rfcn( $rexrules['BYMONTH'] );
    if( isset( $rexrules['BYSETPOS'] ))
      $content       .= ';BYSETPOS='.self::rfcn( $rexrules['BYSETPOS'] );
    if( isset( $rexrules['WKST'] ))
      $content       .= ';WKST='.$rexrules['WKST'];
    $this->content    = $content;
            /* fix paramemers */
    $parameters       = iCalParameterFactory::addDefaults( $this->propName, $parameters );
    $this->parameters = array();
    foreach( $parameters as $k => $v )
      $this->setParameter( $k, $v );
            /* fix config */
    $this->config     = array();
    foreach( $config as $k => $v )
      if( is_bool( $v ) || ! empty( $v ))
        $this->config[$k] = $v;
  }
/**
 * sort by weekday
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.2 - 2013-10-15
 * @access private
 * @param string $bydaya;
 * @param string $bydayb
 * @uses iCalREXRULEproperty::$days
 * @static
 * @return object instance
 */
  private static function bydaySort( $bydaya, $bydayb ) {
    return ( self::$days[substr( $bydaya, -2 )] < self::$days[substr( $bydayb, -2 )] ) ? -1 : 1;
  }
/**
 * sort by content
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-30
 * @access private
 * @param mixed $ruleValue;
 * @static
 * @return mixed
 */
  private static function rfcn ( $ruleValue ) {
    if( ! is_array( $ruleValue ))
      $ruleValue = explode( ',', $ruleValue );
    sort( $ruleValue );
    return implode( ',', $ruleValue );
  }
}
