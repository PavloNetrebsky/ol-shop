<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalVCALENDAR class
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.6 - 2013-11-09
 */
class iCalVCALENDAR extends iCalBASEcomponent {
/**
 * iCalVCALENDAR construct
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-18
 * @param array $config calendar configuration
 * @uses iCalBASEcomponent::__construct()
 * @uses iCalComponentFactory::$calendarCfgs
 * @uses iCalVCALENDAR::setConfig()
 * @uses iCalPropertyFactory::factory()
 * @uses iCalVCALENDAR::getConfig()
 * @uses iCalComponentFactory::$baseCfgs
 * @uses iCalVCALENDAR::setProdid()
 * @return object instance
 */
  public function __construct( $config=array()) {
    $this->compName   = 'VCALENDAR';
    parent::__construct( array());
    $this->setConfig( iCalComponentFactory::$calendarCfgs );
    $this->setConfig( $config );
    $this->properties = array( 'VERSION' => iCalPropertyFactory::factory( 'VERSION', '2.0', array(), $this->getConfig( array_keys( iCalComponentFactory::$baseCfgs ))));
    $this->setProdid();
  }
/**
 * iCalVCALENDAR factory
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-09
 * @param array $config calendar configuration
 * @uses iCalVCALENDAR
 * @static
 * @return object instance
 */
  public static function factory( $config ) {
    return new iCalVCALENDAR( $config );
  }
/**
 * return iCalcreator version
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-28
 * @uses iCalBASEcomponent::$version
 * @return string
 */
  public static function iCalcreatorVersion() {
    return trim( substr( parent::$version, strpos( parent::$version, ' ' )));
  }
/**
 * return calendar
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-02
 * @uses parent::create()
 * @return string
 */
  public function createCalendar() {
    return parent::create();
  }
/**
 * vcalendar config get
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-20
 * @param mixed $key, calendar configuration key(-s)
 * @uses iCalVCALENDAR::getConfig()
 * @uses iCalBASEcomponent::getConfig()
 * @return mixed
 */
  public function getConfig( $key=FALSE ) {
    if( empty( $key ))
      return $this->config;
    elseif( is_array( $key )) {
      $output = array();
      foreach( $key as $k )
        $output[$k] = $this->getConfig( $k );
      return $output;
    }
    $key   = strtolower( $key );
    switch( $key ) {
      case 'delimiter':
      case 'directory':
      case 'filename':
        return $this->config[$key];
        break;
      case 'dirfile':
        return $this->config['directory'].$this->config['delimiter'].$this->config['filename'];
        break;
      case 'fileinfo':
        return array( $this->config['directory'], $this->config['filename'], $this->getConfig( 'filesize' ));
        break;
      case 'filesize':
        $size    = 0;
        if( empty( $this->config['url'] )) {
          $dirfile = $this->getConfig( 'dirfile' );
          if( ! is_file( $dirfile ) || ( FALSE === ( $size = filesize( $dirfile ))))
            $size = 0;
          clearstatcache();
        }
        return $size;
        break;
      case 'url':
        return ( array_key_exists( $key, $this->config )) ? $this->config[$key] : FALSE;
        break;
      default:
        return parent::getConfig( $key );
        break;
    }
    return FALSE;
  }
/**
 * vcalendar config set
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-20
 * @param mixed  $config configuration array/key
 * @param string $value  configuration value
 * @uses iCalComponentFactory::$calendarCfgs
 * @uses iCalVCALENDAR::setConfig()
 * @uses iCalComponentFactory::$prodidCfgs
 * @uses iCalComponentFactory::$baseCfgs
 * @uses parent::setConfig()
 * @uses iCalVCALENDAR::setProdid();
 * @return mixed
 */
  public function setConfig( $config, $value = FALSE) {
    if( is_array( $config )) {
      $config  = array_change_key_case( $config );
      foreach( iCalComponentFactory::$calendarCfgs as $key => $default ) { // delimiter, directory, filename, url
        if( array_key_exists( $key, $config )) {
          $this->setConfig( $key, $config[$key] );
          unset( $config[$key] );
        }
      }
      foreach( iCalComponentFactory::$prodidCfgs as $key ) { // unique_id,language
        if( array_key_exists( $key, $config )) {
          $this->setConfig( $key, $config[$key] );
          unset( $config[$key] );
        }
      }
      foreach( $config as $key => $value ) // ??
        if( isset( $config[$key] ))
          $this->setConfig( $key, $value );
      return $this;
    } // end if( is_array( $config ))
    $key = strtolower( $config );
    if( 'newlinechar' == $key )
      $key   = 'nl';
    if( ! is_bool( $value ) && empty( $value )) {
      if(     array_key_exists( $key, iCalComponentFactory::$baseCfgs ))
        $this->config[$key] = iCalComponentFactory::$baseCfgs[$key];
      elseif( array_key_exists( $key, iCalComponentFactory::$calendarCfgs ))
        $this->config[$key] = iCalComponentFactory::$calendarCfgs[$key];
    }
    if( in_array( $key, iCalComponentFactory::$prodidCfgs )) {
      parent::setConfig( $key, $value );
      $this->setProdid();
      return $this;
    }
    switch( $key ) {
      case 'delimiter':
        $this->config[$key] = trim( $value );
        break;
      case 'directory':
        if( FALSE !== ( $value = realpath( rtrim( trim( $value ), $this->config['delimiter'] )))) {
            /* local directory */
          $this->config[$key] = $value;
          unset( $this->config['url'] );
        }
        else {
          $this->config[$key] = iCalComponentFactory::$calendarCfgs[$key];
          return FALSE;
        }
        clearstatcache();
        break;
      case 'filename':
        $value   = trim( $value );
        if( ! empty( $this->url )) {
          $this->config[$key] = $value;
          break;
        }
        $dirfile = $this->getConfig( 'directory' ).$this->getConfig( 'delimiter' ).$value;
        if( file_exists( $dirfile )) {
            /* local file exists */
          if( is_readable( $dirfile ) || is_writable( $dirfile ))
            $this->config[$key] = $value;
          else {
            $this->config[$key] = iCalComponentFactory::$calendarCfgs[$key];
            clearstatcache();
            return FALSE;
          }
        }
        elseif( is_readable($this->getConfig( 'directory' ) ) || is_writable( $this->getConfig( 'directory' )))
            /* read- or writable directory */
          $this->config[$key] = $value;
        else {
          $this->config[$key] = iCalComponentFactory::$calendarCfgs[$key];
          clearstatcache();
          return FALSE;
        }
        clearstatcache();
        break;
      case 'url':
            /* remote file - URL */
        $value   = str_replace( array( 'HTTP://', 'WEBCAL://', 'webcal://' ), 'http://', trim( $value ));
        $value   = str_replace( 'HTTPS://', 'https://', trim( $value ));
        if(( 'http://' != substr( $value, 0, 7 )) && ( 'https://' != substr( $value, 0, 8 )))
          return FALSE;
        if( '.ics' != strtolower( substr( $value, -4 ))) {
          $this->config[$key] = $value;
          $this->config['filename'] = iCalComponentFactory::$calendarCfgs['filename'];
        }
        $s1      = ( isset( $this->config[$key] )) ? $this->config[$key] : null;
        $this->config[$key] = $value;
        $s2      = $this->config['directory'];
        $this->config['directory'] = iCalComponentFactory::$calendarCfgs['directory'];
        $parts   = pathinfo( $value );
        if( FALSE === $this->setConfig( 'filename',  $parts['basename'] )) {
          $this->config['url']       = $s1;
          $this->config['directory'] = $s2;
          return FALSE;
        }
        break;
      default:
        return parent::setConfig( $key, $value );
        break;
    } // end switch( $key )
    return $this;
  }
/**
 * create a calendar timezone and standard/daylight components
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1.5 - 2013-09-24
 * @param string $timezone, a PHP5 (DateTimeZone) valid timezone, if missing using X-WR-TIMEZONE
 * @param array  $xProp,    *[x-propName => x-propValue], optional timezone X-properties
 * @param int    $from      a unix timestamp, if missing, the earliest DTSTART is used
 * @param int    $to        a unix timestamp, if missing, the latest DTSTART is used
 * @uses iCalComponentFactory::factoryTimezone()
 * @return bool
 */
  public function createTimezone( $timezone=null, $xProp=array(), $from=null, $to=null ) {
    if( empty( $timezone ) && ( FALSE === ( $timezone = $this->getProperty( 'X-WR-TIMEZONE' ))))
      return FALSE;
    return iCalComponentFactory::createTimezone( $this, $timezone, $xProp, $from, $to );
  }
/**
 * a HTTP redirect header is sent with created, updated and/or parsed calendar.
 * Method will NOT terminate execution.
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-03
 * @param bool $utf8Encode if to UTF8encode output
 * @param bool $gzip       if to gzip output
 * @uses iCalVCALENDAR::createCalendar()
 * @uses iCalVCALENDAR::getConfig()
 * @return redirect
 */
  public function returnCalendar( $utf8Encode=FALSE, $gzip=FALSE ) {
    $output   = $this->createCalendar();
    if( $utf8Encode )
      $output = utf8_encode( $output );
    if( $gzip ) {
      $output = gzencode( $output, 9 );
      header( 'Content-Encoding: gzip' );
      header( 'Vary: *' );
      header( 'Content-Length: '.strlen( $output ));
    }
    header( 'Content-Type: text/calendar; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename="'.$this->getConfig( 'filename' ).'"' );
    header( 'Cache-Control: max-age=10' );
    echo( $output );
  }
/**
 * save content in a file
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-16
 * @uses iCalVCALENDAR::getConfig()
 * @uses iCalVCALENDAR::createCalendar()
 * @return bool
 */
  public function saveCalendar() {
    if( FALSE === ( $dirfile = $this->getConfig( 'url' )))
      $dirfile = $this->getConfig( 'dirfile' );
    if( FALSE === ( $iCalFile = @fopen( $dirfile, 'w' )))
      return FALSE;
    if( FALSE === fwrite( $iCalFile, $this->createCalendar() ))
      return FALSE;
    fclose( $iCalFile );
    return TRUE;
  }
/**
 * if recent version of calendar file exists (default one hour), an HTTP redirect header is sent
 * else FALSE is returned. Method will NOT terminate execution.
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-03
 * @param int $timeout optional, default 3600 sec
 * @uses iCalVCALENDAR::getConfig()
 * @return bool
 */
  public function useCachedCalendar( $timeout=3600) {
    $filesize    = $this->getConfig( 'filesize' );
    if( 0 >= $filesize )
      return FALSE;
    $dirfile     = $this->getConfig( 'dirfile' );
    if( time() - filemtime( $dirfile ) < $timeout) {
      clearstatcache();
      $dirfile   = $this->getConfig( 'dirfile' );
      $filename  = $this->getConfig( 'filename' );
      header( 'Content-Type: text/calendar; charset=utf-8' );
      header( 'Content-Length: '.$filesize );
      header( 'Content-Disposition: attachment; filename="'.$filename.'"' );
      header( 'Cache-Control: max-age=10' );
      $fp = @fopen( $dirfile, 'r' );
      if( $fp ) {
        fpassthru( $fp );
        fclose( $fp );
        return TRUE;
      }
    }
    return FALSE;
  }
/**
 * create an unique id for this calendar object instance
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-05
 * @uses parent::getConfig()
 * @uses parent::$version
 * @uses iCalPropertyFactory::factory()
 * @access private
 * @return void
 */
  private function setProdid() {
    $language = (($c = parent::getConfig( 'language' )) && empty( $c )) ? '' : strtoupper( $c );
    $content  = '-//'.$this->config['unique_id'].'//NONSGML kigkonsult.se '.iCalBASEcomponent::$version.'//'.$language;
    $this->properties['PRODID'] = iCalPropertyFactory::factory( 'PRODID', $content, array(), $this->getConfig());
  }
/**
 * parse calendar content (array) into components etc
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.6 - 2013-11-09
 * @param mixed $content calendar string/array to parse
 * @uses iCalVCALENDAR::getConfig()
 * @uses iCalBASEcomponent::parse()
 * @return this
 */
  public function parse( $content=FALSE ) {
    if( empty( $content )) {
            /* directory+filename is set previously via setConfig url or directory+filename  */
      if( FALSE === ( $filename = $this->getConfig( 'url' ))) {
        if( FALSE === ( $filename = $this->getConfig( 'dirfile' )))
          return FALSE;                 /* err 1 */
        if( ! is_file( $filename ))
          return FALSE;                 /* err 2 */
        if( ! is_readable( $filename ))
          return FALSE;                 /* err 3 */
      }
            /* READ FILE */
      if( FALSE === ( $content = @file_get_contents( $filename )))
        return FALSE;                 /* err 5 */
    }
    elseif( is_array( $content ))
      $content = implode( $this->config['nl'], $content );
            /* fix dummy line separator */
    $sep       = substr( microtime(), 2, 4 );
    $base      = 'aAbB!cCdD"eEfF#gGhH¤iIjJ%kKlL&mMnN/oOpP(rRsS)tTuU=vVxX?uUvV*wWzZ-1234_5678|90';
    $len       = strlen( $base ) - 1;
    for( $p = 0; $p < 6; $p++ )
      $sep    .= $base[mt_rand( 0, $len )];// $sep    .= $base{mt_rand( 0, $len )};
            /* fix empty lines */
    $content = str_replace( array( "\r\n", "\n\r", "\n", "\r" ), $sep, $content );
    $content = str_replace( $sep.$sep, $sep.str_pad( '', 75 ).$sep, $content );
    $content = str_replace( $sep, $this->config['nl'], $content );
            /* fix line folding */
    $content = str_replace( array( $this->config['nl'].' ', $this->config['nl']."\t" ), '', $content );
            /* split in component/property lines */
    $content = explode( $this->config['nl'], $content );
            /* skip leading (empty/invalid) lines */
    foreach( $content as $lix => $line ) {
      if( FALSE !== stripos( $line, 'BEGIN:VCALENDAR' ))
        break;
      unset( $content[$lix] );
    }
    $rcnt = count( $content );
    if( 3 > $rcnt )                  /* err 10 */
      return FALSE;
            /* skip trailing empty lines and ensure an end row */
    $lix  = array_keys( $content );
    $lix  = end( $lix );
    while( 3 < $lix ) {
      $tst = trim( $content[$lix] );
      if(( '\n' == $tst ) || empty( $tst )) {
        unset( $content[$lix] );
        $lix--;
        continue;
      }
      if( FALSE === stripos( $content[$lix], 'END:VCALENDAR' ))
        $content[] = 'END:VCALENDAR';
      break;
    }
    return parent::parse( $content );
  }
/**
 * return all calendar values and counts for specific property
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-09-03
 * @param string $propName, property name
 * @uses iCalBASEcomponent::getProperty()
 * @uses iCalComponentFactory::isbaseComponent()
 * @uses iCalPropertyFactory::isMultiple()
 * @access private
 * @return array
 */
  private function getProperties( $propName ) {
    $propName   = strtoupper( $propName );
    $output     = array();
    foreach ( $this->components as $cix => $component) {
      if( ! iCalComponentFactory::isbaseComponent( $component->compName ))
        continue;
      if(( 'GEOLOCATION' == $propName ) && ( FALSE !== ( $gl = $component->getProperty( $propName )))) {
        $output[$gl] = ( !isset( $output[$gl] )) ? 1 : ( $output[$gl] + 1 );
        continue;
      }
      if( iCalPropertyFactory::isMultiple( $component->compName, $propName )) {
        while( FALSE !== ( $content = $component->getProperty( $propName ))) {
          if( empty( $content ))
            continue;
          if( FALSE !== strpos( $content, ',' )) {
            $content = explode( ',', $content );
            foreach( $content as $partVal )
              $output[$partVal] = ( !isset( $output[$partVal] )) ? 1 : ( $output[$partVal] + 1 );
          } // end elseif( FALSE !== strpos( $content, ',' ))
          else
              $output[$content] = ( !isset( $output[$content] )) ? 1 : ( $output[$content] + 1 );
        }
        continue;
      }
      elseif(( 3 < strlen( $propName )) && ( 'UID' == substr( $propName, -3 ))) {
        if( FALSE !== ( $content = $component->getProperty( 'RECURRENCE-ID' )))
          $content = $component->getProperty( 'UID' );
      }
      elseif( FALSE === ( $content = $component->getProperty( $propName )))
        continue;
      if(( FALSE === $content ) || empty( $content ))
        continue;
      elseif( 'DTSTART' == $propName ) {
        $key  = substr( $content, 0, 8 );
        $output[$key] = ( !isset( $output[$key] )) ? 1 : $output[$key] + 1;
      }
      elseif(( 'SUMMARY' != $propName ) && ( FALSE !== strpos( $content, ',' ))) {
        $content = explode( ',', $content );
        foreach( $content as $partVal )
          $output[$partVal] = ( !isset( $output[$partVal] )) ? 1 : ( $output[$partVal] + 1 );
      } // end elseif( is_array( $content ))
      else
        $output[$content] = ( !isset( $output[$content] )) ? 1 : ( $output[$content] + 1 );
    } // end foreach ( $this->components as $cix => $component)
    if( !empty( $output ))
      ksort( $output );
    return $output;
  }
/**
 * return a calendar (component) property value
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-10
 * @param string $propName optional, property name
 * @param int    $propix optional, if specific property is wanted in case of multiply occurences
 * @param bool   $inclParam optional, if to include property parameters
 * @param bool   $specform option, (DURATION only) if to convert duration value to date(-time)
 * @uses iCalPropertyFactory::isvalidGetProp()
 * @uses iCalVCALENDAR::getProperties()
 * @uses parent::getProperty()
 * @return mixed
 */
  public function getProperty( $propName=FALSE, $propix=FALSE, $inclParam=FALSE, $specform=FALSE ) {
    $propName   = strtoupper( $propName );
    if( iCalPropertyFactory::isvalidGetProp( $propName ))
      return $this->getProperties( $propName );
    return parent::getProperty( $propName, $propix, $inclParam, $specform );
  }
/**
 * replace component in vcalendar
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1.7 - 2013-09-25
 * @param object $component calendar component
 * @uses iCalComponentFactory::isbaseComponent()
 * @uses iCalVCALENDAR::setComponent()
 * @uses iCalBASEcomponent::getProperty()
 * @return bool
 */
  function replaceComponent( $component  ) {
    if( iCalComponentFactory::isbaseComponent( $component->compName ))
      return $this->setComponent( $component, $component->getProperty( 'UID' ));
    if(( 'vtimezone' != $component->objName ) || ( FALSE === ( $tzid = $component->getProperty( 'TZID' ))))
      return FALSE;
    foreach( $this->components as $cix => $comp ) {
      if( 'VTIMEZONE' != $comp->objName )
        continue;
      if( $tzid == $comp->getComponent( 'TZID' )) {
        $this->components[$cix] = $component;
        return TRUE;
      }
    }
    $this->setComponent( $component );
    $this->sort();
    return TRUE;
  }
/**
 * select components from calendar on date or selectOption basis
 *
 * Ensure DTSTART is set for every component.
 * No date controls occurs.
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-02
 * @param mixed $startY optional, start Year,  default current Year ALT. array selecOptions ( *[ <propName> => <uniqueValue> ] )
 * @param int   $startM optional, start Month, default current Month
 * @param int   $startD optional, start Day,   default current Day
 * @param int   $endY   optional, end   Year,  default $startY
 * @param int   $endY   optional, end   Month, default $startM
 * @param int   $endY   optional, end   Day,   default $startD
 * @param mixed $cType  optional, calendar component type(-s), default FALSE=all else string/array type(-s)
 * @param bool  $flat   optional, FALSE (default) => output : array[Year][Month][Day][]
 *                                TRUE            => output : array[] (ignores split)
 * @param bool  $any    optional, TRUE (default) - select component(-s) that occurs within period
 *                                FALSE          - only component(-s) that starts within period
 * @param bool  $split  optional, TRUE (default) - one component copy every DAY it occurs during the
 *                                                 period (implies flat=FALSE)
 *                                FALSE          - one occurance of component only in output array
 * @uses iCalComponentFactory::selectComponents2()
 * @uses iCalComponentFactory::selectComponents()
 * @return mixed
 */
  public function selectComponents( $startY=FALSE, $startM=FALSE, $startD=FALSE, $endY=FALSE, $endM=FALSE, $endD=FALSE, $cType=FALSE, $flat=FALSE, $any=TRUE, $split=TRUE ) {
            /* check  if empty calendar */
    if( 0 >= count( $this->components )) return FALSE;
    if( is_array( $startY ))
      return iCalComponentFactory::selectComponents2( $this->components, $startY );
    $this->sort( 'UID' );
    return iCalComponentFactory::selectComponents( $this->components, $startY, $startM, $startD, $endY, $endM, $endD, $cType, $flat, $any, $split );
  }
/**
 * sort iCal components
 *
 * ascending sort on properties (if exist) x-current-dtstart, dtstart,
 * x-current-dtend, dtend, x-current-due, due, duration, created, dtstamp, uid if called without arguments,
 * otherwise sorting on specific (argument) property values
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-05-23
 * @param string $sortArg optional, sort argument
 * @uses iCalComponentFactory::sortArg()
 * @uses iCalComponentFactory::sortDate()
 * @uses iCalComponentFactory::cmpfcn()
 * @return this
 *
 */
  public function sort( $sortArg=FALSE ) {
    if( 2 > count( $this->components ))
      return $this;
    if( $sortArg )
      iCalComponentFactory::sortArg( $this->components, $sortArg );
    else
      iCalComponentFactory::sortDate( $this->components );
    usort( $this->components, array( 'iCalComponentFactory', 'cmpfcn' ));
    foreach( $this->components as $cix => & $c )
      unset( $c->srtk );
    return $this;
  }
}
