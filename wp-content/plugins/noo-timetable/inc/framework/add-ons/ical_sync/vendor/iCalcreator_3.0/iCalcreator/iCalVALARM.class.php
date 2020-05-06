<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalVALARM class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-11
 */
class iCalVALARM extends iCalBASEcomponent {
/**
 * iCalVALARM construct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-11
 * @param array $config calendar configuration
 * @uses parent::__construct()
 * @return object instance
 */
  public function __construct( $config ) {
    $this->compName   = 'VALARM';
    parent::__construct( $config );
  }
}
