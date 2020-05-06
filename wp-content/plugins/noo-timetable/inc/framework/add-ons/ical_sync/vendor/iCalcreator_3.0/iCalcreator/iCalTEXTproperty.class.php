<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalTEXTproperty class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-09
 */
class iCalTEXTproperty extends iCalBASEproperty {
/**
 * iCalTEXTproperty construct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-08
 * @param string $propName   property name
 * @param string $content    property content
 * @param array  $parameters property parameters
 * @param array  $config     calendar configuration
 * @uses iCalParameterFactory::isAllowed()
 * @uses parent::__construct()
 * @return object instance
 */
  public function __construct( $propName, $content, $parameters, $config ) {
    $propName   = strtoupper( $propName );
    if( is_array( $content )) {
      switch( $propName ) {
        case 'REQUEST-STATUS':
          $temp = '';
          foreach( $content as $k => $v ) {
            switch( $k ) {
              case 0;  $temp  = number_format( (float) $v, 2, '.', ''); break;
              case 1;  $temp .= ";$v";                                  break;
              case 2;  $temp .= ( ! empty( $v )) ? ";$v" : '';          break;
            }
          }
          $content = $temp;
          break;
        case 'CATEGORIES':
        case 'RESOURCES':
          sort( $content );
          $content = implode( ',', $content );
        default:
          break;
      }
    }
    if( isset( $config['language'] ) &&  ! empty( $config['language'] ) &&
      ! isset( $parameters['LANGUAGE'] ) || empty( $parameters['LANGUAGE'] ) &&
        iCalParameterFactory::isAllowed( $this->propName, 'LANGUAGE' ))
      $parameters['LANGUAGE'] = $config['language'];
    $content    = str_replace( "\\\\", '\\', $content);
    $content    = str_replace( '\,',  ',',   $content );
    $content    = str_replace( '\;',  ';',   $content );
    if( in_array( $propName, array( 'ACTION', 'STATUS', 'TRANSP' )))
      $content  = strtoupper( $content );
    parent::__construct( $propName, $content, $parameters, $config );
  }
/**
 * return TEXT property in iCal format
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-25
 * @uses iCalParameterFactory::removeDefaults()
 * @uses iCalParameter::create()
 * @uses iCalPropertyFactory::strrep()
 * @uses iCalPropertyFactory::size75()
 * @return string
 */
  public function create() {
    $output       = $this->propName;
    if( empty( $this->content ))
      $text       = '';
    else {
      if( isset( $this->config['removedefaults'] ) && ( FALSE !==  $this->config['removedefaults'] ))
        $parameters = iCalParameterFactory::removeDefaults( $this->propName, $this->parameters );
      foreach( $parameters as $parameter )
        $output    .= $parameter->create();
      $text         = $this->content;
      switch ( $this->propName ) {
        case 'REQUEST-STATUS':
          $parts    = explode( ';', $text );
          foreach( $parts as $k => & $part )
            if( 0 < $k )
              $part = iCalPropertyFactory::strrep( $part );
          $text     = implode( ';', $parts );
          break;
        case 'CATEGORIES':
        case 'RESOURCES':
          break;
        default:
          $parts    = explode( '\n', $text );
          foreach( $parts as  & $part )
            $part   = iCalPropertyFactory::strrep( $part );
          $text     = implode( '\n', $parts );
          break;
      } // end switch ( $this->propName )
    } // end if( ! empty( $this->content ))
    return iCalPropertyFactory::size75( $output.':'.$text, $this->config['nl'] );
  }
/**
 * return property value, opt. with parameters
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1.8 - 2013-09-28
 * @param bool $full return with parameters or not
 * @uses iCalBASEproperty::get()
 * @return mixed
 */
  public function get( $full=FALSE ) {
    if(( 'X-' == substr( $this->propName, 0, 2 )) && ! $full )
      return array( $this->propName, $this->content );
    return parent::get( $full );
  }
}
