<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalBINARYproperty class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-21
 */
class iCalBINARYproperty extends iCalBASEproperty {
/**
 * iCalDURATIONproperty construct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-13
 * @param string $propName   property name
 * @param string $content    property content
 * @param array  $parameters property parameters
 * @param array  $config     calendar configuration
 * @uses parent::__construct()
 * @return object instance
 */
  public function __construct( $propName, $content, $parameters, $config ) {
    $config['fixed'] = array( 'VALUE' => 'BINARY', 'ENCODING' => 'BASE64' ); // required!!
    parent::__construct( $propName, $content, $parameters, $config );
  }
}
