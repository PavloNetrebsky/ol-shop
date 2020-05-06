<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalParameter class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-09
 */
class iCalParameter {
  /**
   * @var string
   * @access private
   */
  private $paramKey;
  /**
   * @var string
   * @access private
   */
  private $paramValue;
/**
 * parameter construct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-19
 * @param string $paramKey   parameter key
 * @param string $paramValue parameter value
 * @return object instance
 */
  public function __construct( $paramKey, $paramValue ) {
    $this->paramKey   = $paramKey;
    $this->paramValue = trim( $paramValue, ' "' );
    if( FALSE !== strpos( $this->paramValue, '"' ))
      $this->paramValue = str_replace('"', "'", $this->paramValue );
  }
/**
 * parameter destruct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-09
 * @return void
 */
  public function __destruct() {
    unset( $this->paramKey, $this->paramValue );
  }
/**
 * creates formatted output for calendar component property parameter
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-11
 * @return string
 */
  public function create() {
    $paramValue2 = (( FALSE !== strpos( $this->paramValue, ' ' )) ||
                    ( FALSE !== strpos( $this->paramValue, ':' )) ||
                    ( FALSE !== strpos( $this->paramValue, ';' )) ||
                    ( FALSE !== strpos( $this->paramValue, "'" )) ||
                    ( FALSE !== strpos( $this->paramValue, ',' ))) ? '"'.$this->paramValue.'"' : $this->paramValue;
    switch( $this->paramKey ) {
      case 'ALTREP' :
      case 'CN' :
      case 'DIR' :
      case 'SENT-BY' :
        $delim       = ( FALSE !== strpos( $paramValue2, '"' )) ? '' : '"';
        return ";{$this->paramKey}=$delim{$paramValue2}$delim";
        break;
      case 'DELEGATED-FROM' :
      case 'DELEGATED-TO' :
      case 'MEMBER' :
        $parts       = explode( ',', $this->paramValue );
        foreach( $parts as $i => $v ) {
          $delim     = ( FALSE !== strpos( $v, '"' )) ? '' : '"';
          $parts[$i] = "$delim$v$delim";
        }
        return ";{$this->paramKey}=".implode( ',', $parts );
        break;
      default :
        return ";{$this->paramKey}=$paramValue2";
        break;
    }
    return '';
  }
/**
 * check if this parameter key equals argument
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-09
 * @param string $paramKey parameter key
 * @return bool
 */
  public function exists( $paramKey ) {
    return ( $this->paramKey == $paramKey );
  }
/**
 * return parameter Value
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-12
 * @param string $paramKey parameter key
 * @return mixed
 */
  public function get( $paramKey=FALSE ) {
    if( $paramKey )
      return ( $this->exists( $paramKey )) ? $this->paramValue : FALSE;
    else
      return array( $this->paramKey => $this->paramValue );
  }
/**
 * set parameter value
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-18
 * @param string $paramValue parameter value
 * @return bool
 */
  public function set( $paramValue ) {
    if( ! empty( $paramValue ))
      $this->paramValue = $paramValue;
    return TRUE;
  }
}
