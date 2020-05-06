<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalINTEGERproperty class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-10
 */
class iCalINTEGERproperty extends iCalBASEproperty {
/**
 * iCalINTEGERproperty construct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-10
 * @param string $propName   property name
 * @param string $content    property content
 * @param array  $parameters property parameters
 * @param array  $config     calendar configuration
 * @uses parent::__construct()
 * @return object instance
 */
  public function __construct( $propName, $content, $parameters, $config ) {
    if( empty( $content ))
      $content = '0';  // default
    parent::__construct( $propName, $content, $parameters, $config );
  }
}
