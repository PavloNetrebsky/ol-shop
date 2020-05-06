<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalUTC_OFFSETproperty class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-10
 */
class iCalUTC_OFFSETproperty extends iCalBASEproperty {
/**
 * transforms offset in seconds to [-/+]hhmm[ss]
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-05
 * @param string $seconds to transform
 * @static
 * @return string
 */
  public static function offsetSec2His( $seconds ) {
    if( '-' == substr( $seconds, 0, 1 )) {
      $output  = '-';
      $seconds = substr( $seconds, 1 );
    }
    elseif( '+' == substr( $seconds, 0, 1 )) {
      $output  = '+';
      $seconds = substr( $seconds, 1 );
    }
    else
      $output  = '+';
    $hour      = (int) floor( $seconds / 3600 );
    $seconds   = $seconds % 3600;
    $min       = (int) floor( $seconds / 60 );
    $output   .= sprintf( '%02d%02d', $hour, $min );
    $seconds   = $seconds % 60;
    return ( 0 < $seconds) ? $output.sprintf( '%02d', $seconds ) : $output;
  }
}
