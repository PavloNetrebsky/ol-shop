<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalDURATIONproperty class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.4 - 2013-10-19
 */
class iCalDURATIONproperty extends iCalBASEproperty {
/**
 * iCalDURATIONproperty construct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-28
 * @param string $propName   property name
 * @param string $content    property content
 * @param array  $parameters property parameters
 * @param array  $config     calendar configuration
 * @uses iCalDURATIONproperty::durationStr2arr()
 * @uses iCalDURATIONproperty::duration2str()
 * @uses iCalDURATIONproperty::durationCorrect()
 * @uses parent::__construct()
 * @return object instance
 */
  public function __construct( $propName, $content, $parameters, $config ) {
    if( ! is_array( $content ))
      $content = self::durationStr2arr( $content );
    elseif( 'TRIGGER' == strtoupper( $propName )) {
      if( isset( $content['relatedStart'] )) {
        $parameters['RELATED'] = ( $content['relatedStart'] ) ? 'START' : 'END';
        unset( $content['relatedStart'] );
      }
      if( ! isset( $content['before'] ))
        $content['before'] = TRUE;
    }
    $content = self::duration2str( self::durationCorrect( $content ));
    if( 'TRIGGER' == strtoupper( $propName )) {
      if( isset( $parameters['RELATED'] ))
        $parameters['RELATED'] = strtoupper( $parameters['RELATED'] );
    }
    parent::__construct( $propName, $content, $parameters, $config );
  }
/**
 * convert startdate+duration to a "Ymd\THis" format datetime
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.4 - 2013-10-19
 * @param string  $startdate start datetime
 * @param string  $duration  duration to add
 * @uses iCalDURATIONproperty::durationStr2arr()
 * @static
 * @return string
 */
  public static function duration2date( $startdate, $duration ) {
    if( is_array( $startdate )) {
      $temp   = sprintf( '%04d%02d%02d', $startdate['year'], $startdate['month'], $startdate['day'] );
      if( ! isset( $startdate['hour'] )) $startdate['hour']  = 0;
      if( ! isset( $startdate['min']  )) $startdate['min']   = 0;
      if( ! isset( $startdate['sec']  )) $startdate['sec']   = 0;
      $temp  .= sprintf( 'T%02d%02d%02d', $startdate['hour'], $startdate['min'], $startdate['sec'] );
      $startdate = $temp;
    }
    $duration = self::durationStr2arr( $duration );
    $dtend    = 0;
    if( isset( $duration['week'] )) $dtend += ( $duration['week'] * 7 * 24 * 60 * 60 );
    if( isset( $duration['day'] ))  $dtend += ( $duration['day']      * 24 * 60 * 60 );
    if( isset( $duration['hour'] )) $dtend += ( $duration['hour']          * 60 * 60 );
    if( isset( $duration['min'] ))  $dtend += ( $duration['min']                * 60 );
    if( isset( $duration['sec'] ))  $dtend +=   $duration['sec'];
    if( isset( $duration['sign'] ) && ( '-' ==  $duration['sign'] ))
      $dtend *= -1;
    return date( 'Ymd\THis', strtotime( "$startdate + $dtend seconds" ));
  }
/**
 * creates formatted output for calendar component property data value type duration
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-25
 * @param array $duration array( sign, week, day, hour, min, sec )
 * @static
 * @return string
 */
  public static function duration2str( $duration ) {
    $output    = ( isset( $duration['sign'] ) && ( '-' == $duration['sign'] )) ? '-' : '';
    if( isset( $duration['week'] ) && ( 0 < $duration['week'] ))
      return $output.'P'.$duration['week'].'W';
    $output   .= 'P';
    if( isset($duration['day'] ) && ( 0 < $duration['day'] ))
      $output .= $duration['day'].'D';
    if(( isset( $duration['hour']) && ( 0 < $duration['hour'] )) ||
       ( isset( $duration['min'])  && ( 0 < $duration['min'] ))  ||
       ( isset( $duration['sec'])  && ( 0 < $duration['sec'] ))) {
      $output .= 'T';
      $output .= ( isset( $duration['hour']) && ( 0 < $duration['hour'] )) ? $duration['hour'].'H' : '0H';
      $output .= ( isset( $duration['min'])  && ( 0 < $duration['min'] ))  ? $duration['min']. 'M' : '0M';
      $output .= ( isset( $duration['sec'])  && ( 0 < $duration['sec'] ))  ? $duration['sec']. 'S' : '0S';
    }
    if( '-P' == $output )
      $output  = 'P';
    if( 'P' == $output )
      $output .= 'T0H0M0S';
    return $output;
  }
/**
 * corrects duration parts
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-28
 * @param array $duration array( sign/before, week, day, hour, min, sec ) or numeric keyed array
 * @static
 * @return array
 */
  public static function durationCorrect( $duration ) {
    if( empty( $duration ))
      return array();
    $output = array();
    if(     isset( $duration['sign'] )   && ( '-'  == $duration['sign'] ))
      $output['sign'] = '-';
    elseif( isset( $duration['before'] ) && ( TRUE == $duration['before'] ))
      $output['sign'] = '-';
    if( isset( $duration[0] )) {
      foreach( $duration as $k => $v ) {
        switch( $k ) {
          case 0: $duration['week'] = $v; unset( $duration[0] ); break;
          case 1: $duration['day']  = $v; unset( $duration[1] ); break;
          case 2: $duration['hour'] = $v; unset( $duration[2] ); break;
          case 3: $duration['min']  = $v; unset( $duration[3] ); break;
          case 4: $duration['sec']  = $v; unset( $duration[4] ); break;
        }
      }
    }
    $part           = 0;
    if( isset( $duration['sec'] )  && ( 0 < $duration['sec'] ))
      $part         =   $duration['sec'];
    if( isset( $duration['min'] )  && ( 0 < $duration['min'] ))
      $part        += ( $duration['min'] * 60 );
    if( isset( $duration['hour'] ) && ( 0 < $duration['hour'] ))
      $part        += ( $duration['hour'] * 60 * 60 );
    if( isset( $duration['day'] )  && ( 0 < $duration['day'] ))
      $part        += ( $duration['day'] * 60 * 60 * 24 );
    if( isset( $duration['week'] ) && ( 0 < $duration['week'] ))
      $part        += ( $duration['week'] * 60 * 60 * 24 * 7 );
    $output['week'] = (int) floor( $part / ( 60 * 60 * 24 * 7 ));
    $part           =            ( $part % ( 60 * 60 * 24 * 7 ));
    $output['day']  = (int) floor( $part / ( 60 * 60 * 24 ));
    $part           =            ( $part % ( 60 * 60 * 24 ));
    $output['hour'] = (int) floor( $part / ( 60 * 60 ));
    $part           =            ( $part % ( 60 * 60 ));
    $output['min']  = (int) floor( $part /   60 );
    $output['sec']  =            ( $part %   60 );
    if( !empty( $output['week'] ))
      unset( $output['day'], $output['hour'], $output['min'], $output['sec'] );
    else {
      unset( $output['week'] );
      if( empty( $output['day'] ))
        unset( $output['day'] );
      if(( 0 == $output['hour'] ) && ( 0 == $output['min'] ) && ( 0 == $output['sec'] ))
        unset( $output['hour'], $output['min'], $output['sec'] );
    }
    return $output;
  }
/**
 * convert duration string to array
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-20
 * @param string $duration string formatted duration
 * @static
 * @return array
 */
  public static function durationStr2arr( $duration ) {
    $duration = (string) trim( $duration );
    $output   = array();
    $output['sign'] = ( '-' == substr( $duration, 0, 1 )) ? '-' : '+';
    while( in_array( $duration[0], array( '-', '+', 'P' ))) //$duration{0}
      $duration = substr( $duration, 1 ); // skip -/+/P
    $duration = str_replace ( 'T', '', $duration );
    $val      = '';
    for( $ix=0; $ix < strlen( $duration ); $ix++ ) {
      switch( strtoupper( substr( $duration, $ix, 1 ))) {
       case 'W':
         $output['week'] = $val;
         $val            = '';
         break;
       case 'D':
         $output['day']  = $val;
         $val            = '';
         break;
       case 'H':
         $output['hour'] = $val;
         $val            = '';
         break;
       case 'M':
         $output['min']  = $val;
         $val            = '';
         break;
       case 'S':
         $output['sec']  = $val;
         $val            = '';
         break;
       default:
         $val           .= substr( $duration, $ix, 1 );
      }
    }
    return $output;
  }
}
