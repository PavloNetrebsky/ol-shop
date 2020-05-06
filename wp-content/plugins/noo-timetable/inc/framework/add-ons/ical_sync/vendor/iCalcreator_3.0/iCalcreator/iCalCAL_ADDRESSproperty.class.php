<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalCAL_ADDRESSproperty class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-13
 */
class iCalCAL_ADDRESSproperty extends iCalBASEproperty {
/**
 * iCalCAL_ADDRESSproperty construct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1.3 - 2013-09-22
 * @param string $propName   property name
 * @param string $content    property content
 * @param array  $parameters property parameters
 * @param array  $config     calendar configuration
 * @uses iCalParameterFactory::addDefaults()
 * @uses iCalParameterFactory::$propParams
 * @uses iCalCAL_ADDRESSproperty::mailtoCheck()
 * @uses iCalParameterFactory::factory()
 * @uses iCalParameterFactory::isAllowed()
 * @return object instance
 */
  public function __construct( $propName, $content, $parameters, $config ) {
    $this->propName       = strtoupper( $propName );
    $this->content        = (( FALSE !== strpos( $content, '@' )) && ( 'mailto:' != substr( $content, 0, 7 )) && ( 'MAILTO:' != substr( $content, 0, 7 ))) ? 'MAILTO:'.$content : $content;
            /* fix parameters */
    $parameters           = array_change_key_case( $parameters, CASE_UPPER );
    foreach( $parameters as $k => $v ) {
      if( is_array( $v )) {
        foreach( $v as $i => $v2 )
          $parameters[$k][$i] = str_replace( '"', "'", trim( $v2, '"\'' ));
      }
      else
        $parameters[$k]   = str_replace( '"', "'", trim( $v, '"\'' ));
    }
    if( isset( $parameters['CN'] ) && ! isset( $parameters['LANGUAGE'] ) && isset( $config['language'] ) && ! empty( $config['language'] ))
      $parameters['LANGUAGE'] = $config['language'];
    $parameters           = iCalParameterFactory::addDefaults( $this->propName, $parameters );
    $this->parameters     = array();
    foreach( iCalParameterFactory::$propParams[$this->propName]['allP'] as $k => $v ) { // set parameters in rfc2445 order
      if( ! isset( $parameters[$k] ))
        continue;
      if( ! isset( $config['x-params'] )) { // no in freebusy/alarm components
        if( 'CAL-ADDRESS' == $v )
          $parameters[$k]   = self::mailtoCheck( $parameters[$k] );
        $this->parameters[] = iCalParameterFactory::factory( $this->propName, $k, $parameters[$k] );
      }
      unset( $parameters[$k] );
    }
    $xparams = array();
    foreach( $parameters as $k => $v ) {
      if( ! isset( iCalParameterFactory::$propParams[$this->propName]['allP'][$k] ))
        $xparams[$k] = $v;
    }
    ksort( $xparams, SORT_STRING );
    foreach( $xparams as $k => $v ) {
      if( ! empty( $parameters[$k] ))
        $this->parameters[] = iCalParameterFactory::factory( $this->propName, $k, $v );
    }
            /* fix config */
    unset( $config['x-params'] );
    $this->config     = array();
    foreach( $config as $k => $v )
      if( is_bool( $v ) || ! empty( $v ))
        $this->config[$k] = $v;
  }
/**
 * check if 'mailto' is prefixed
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-20
 * @param string $value
 * @access private
 * @static
 * @return string
 */
  private static function mailtoCheck( $value ) {
    if( ! is_array( $value ))
      $value = explode( ',', $value );
    foreach( $value as & $v ) {
      $v     = trim( $v, '"\'' );
      if( FALSE === strpos( $v, '@' ))
        continue;
      if( 'mailto:' != strtolower( substr( $v, 0, 7 )))
        $v   = 'MAILTO:'.$v;
      else
        $v   = 'MAILTO:'.substr( $v, 7 );
    }
    return implode( ',', $value );
  }
}
