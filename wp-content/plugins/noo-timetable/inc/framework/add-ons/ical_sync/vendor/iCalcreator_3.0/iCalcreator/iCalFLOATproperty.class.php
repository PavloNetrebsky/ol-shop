<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalFLOATproperty class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-02
 */
class iCalFLOATproperty extends iCalBASEproperty {
  /**
   * @access private
   * @var string
   * @static
   */
  private static $geoLatFmt  = '%09.6f';
  /**
   * @access private
   * @var string
   * @static
   */
  private static $geoLongFmt = '%8.6f';
/**
 * iCalFLOATproperty construct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-02
 * @param string $propName   property name
 * @param string $content    property content
 * @param array  $parameters property parameters
 * @param array  $config     calendar configuration
 * @uses parent::__construct()
 * @return object instance
 */
  public function __construct( $propName, $content, $parameters, $config ) {
    if( ! empty( $content )) {
      list( $latitude, $longitude) = explode( ";", $content, 2 );
      $content = self::geo2str2( $latitude, self::$geoLatFmt ).';'.self::geo2str2( $longitude, self::$geoLongFmt );
    }
    parent::__construct( $propName, $content, $parameters, $config );
  }
/**
 * mgnt geo part output
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-03
 * @param string $ll
 * @return string
 */
  private static function geo2str2( $ll, $format ) {
    $ll     = floatval( $ll );
    if( 0.0 < $ll )
      $sign = '+';
    else
      $sign = ( 0.0 > $ll ) ? '-' : '';
    return rtrim( rtrim( $sign.sprintf( $format, abs( $ll )), '0' ), '.' );
  }
}
