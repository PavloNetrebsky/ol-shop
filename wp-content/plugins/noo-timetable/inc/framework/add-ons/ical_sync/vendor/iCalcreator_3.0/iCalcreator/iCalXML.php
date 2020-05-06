<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalcreator XML (rfc6321) helper functions
 * @package iCalcreator
 * @subpackage iCalXML
 */
/**
 * format iCal XML output, rfc6321, using PHP SimpleXMLElement
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-08-18
 * @param object $calendar, iCalcreator vcalendar instance reference
 * @uses iCalBASEcomponent::$version
 * @uses iCalPropertyFactory::$compProps
 * @uses _addXMLchild()
 * @uses iCalVCALENDAR::getComponent()
 * @uses iCalBASEcomponent::getConfig()
 * @uses iCalBASEcomponent::getProperty()
 * @uses iCalPropertyFactory::isMultiple()
 * @return string
 */
function iCal2XML( & $calendar ) {
            /** fix an SimpleXMLElement instance and create root element */
  $xmlstr     = '<?xml version="1.0" encoding="utf-8"?><icalendar xmlns="urn:ietf:params:xml:ns:icalendar-2.0">';
  $xmlstr      .= '<!-- created '.date( 'Ymd\THis\Z', mktime( date('H'), date('i'), (date('s') - date( 'Z' )), date('m'), date('d'), date('Y')));
  $xmlstr      .= ' using kigkonsult.se '.iCalBASEcomponent::$version.' iCal2XML (rfc6321) -->';
  $xmlstr    .= '</icalendar>';
  $xml        = new SimpleXMLElement( $xmlstr );
  $vcalendar  = $xml->addChild( 'vcalendar' );
            /** fix calendar properties */
  $properties = $vcalendar->addChild( 'properties' );
  foreach( iCalPropertyFactory::$compProps[$calendar->compName] as $propName => $d ) {
    if( 'X-' == substr( $propName, 0, 2 )) {
      while( FALSE !== ( $content = $calendar->getProperty( FALSE, FALSE, TRUE )))
        _addXMLchild( $properties, $content['name'], 'unknown', $content['value'], $content['params'] );
    }
    elseif( FALSE !== ( $content = $calendar->getProperty( $propName )))
      _addXMLchild( $properties, $propName, 'text', $content );
  }
            /** prepare to fix components with properties */
  $components    = $vcalendar->addChild( 'components' );
  while( FALSE !== ( $component = $calendar->getComponent())) {
    $compName   = $component->compName;
    $child      = $components->addChild( strtolower( $compName ));
    $properties = $child->addChild( 'properties' );
    $props      = $component->getConfig( 'setPropertyNames' );
            /** fix component properties */
    foreach( $props as $propName ) {
      switch( $propName ) {
        case 'ATTACH':          // may occur multiple times
          while( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE ))) {
            $type = ( FALSE !== stripos( $content['params'], "VALUE=BINARY" )) ? 'binary' : 'uri';
            _addXMLchild( $properties, $propName, $type, $content['value'], $content['params'] );
          }
          break;
        case 'ATTENDEE':
          while( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE )))
            _addXMLchild( $properties, $propName, 'cal-address', $content['value'], $content['params'] );
          break;
        case 'ORGANIZER':
          if( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE )))
            _addXMLchild( $properties, $propName, 'cal-address', $content['value'], $content['params'] );
          break;
        case 'EXDATE':
          while( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE ))) {
            $type = (( FALSE !== stripos( $content['params'], "VALUE=DATE" )) && ( FALSE === stripos( $content['params'], "VALUE=DATE-TIME" ))) ? 'date' : 'date-time';
            _addXMLchild( $properties, $propName, $type, $content['value'], $content['params'] );
          }
          break;
        case 'FREEBUSY':
          while( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE ))) {
            if( is_array( $content ) && isset( $content['value']['fbtype'] )) {
              $content['params']['FBTYPE'] = $content['value']['fbtype'];
              unset( $content['value']['fbtype'] );
            }
            _addXMLchild( $properties, $propName, 'period', $content['value'], $content['params'] );
          }
          break;
        case 'REQUEST-STATUS':
          while( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE )))
            _addXMLchild( $properties, $propName, 'rstatus', $content['value'], $content['params'] );
          break;
        case 'RDATE':
          while( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE ))) {
            $type = 'date-time';
            if( FALSE !== stripos( $content['params'], "VALUE=" )) {
              if(( FALSE !== stripos( $content['params'], "VALUE=DATE" )) && ( FALSE === stripos( $content['params'], "VALUE=DATE-TIME" )))
                $type = 'date';
              elseif( FALSE !== stripos( $content['params'], "VALUE=PERIOD" ))
                $type = 'period';
            }
            _addXMLchild( $properties, $propName, $type, $content['value'], $content['params'] );
          }
          break;
        case 'CATEGORIES':
        case 'CLASS':
        case 'COMMENT':
        case 'CONTACT':
        case 'DESCRIPTION':
        case 'LOCATION':
        case 'RELATED-TO':
        case 'RESOURCES':
        case 'STATUS':
        case 'SUMMARY':
        case 'TRANSP':
        case 'TZID':
        case 'UID':
          if( iCalPropertyFactory::isMultiple( $component->compName, $propName )) {
            while( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE )))
              _addXMLchild( $properties, $propName, 'text', $content['value'], $content['params'] );
          }
          elseif( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE )))
            _addXMLchild( $properties, $propName, 'text', $content['value'], $content['params'] );
          break;
        case 'CREATED':         // single occurence
        case 'COMPLETED':
        case 'DTSTAMP':
        case 'LAST-MODIFIED':
        case 'DTSTART':
        case 'DTEND':
        case 'DUE':
        case 'RECURRENCE-ID':
          if( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE ))) {
            $type = (( FALSE !== stripos( $content['params'], "VALUE=DATE" )) && ( FALSE === stripos( $content['params'], "VALUE=DATE-TIME" ))) ? 'date' : 'date-time';
            _addXMLchild( $properties, $propName, $type, $content['value'], $content['params'] );
          }
          break;
        case 'DURATION':
          if( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE )))
            _addXMLchild( $properties, $propName, 'duration', $content['value'], $content['params'] );
          break;
        case 'EXRULE':
        case 'RRULE':
          while( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE )))
            _addXMLchild( $properties, $propName, 'recur', $content['value'], $content['params'] );
          break;
        case 'GEO':
          if( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE )))
            _addXMLchild( $properties, $propName, 'geo', $content['value'], $content['params'] );
          break;
        case 'PERCENT-COMPLETE':
        case 'PRIORITY':
        case 'SEQUENCE':
          if( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE )))
            _addXMLchild( $properties, $propName, 'integer', $content['value'], $content['params'] );
          break;
        case 'TZURL':
        case 'URL':
          if( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE )))
            _addXMLchild( $properties, $propName, 'uri', $content['value'], $content['params'] );
          break;
        default:
          if(( 'X-' == substr( $propName, 0, 2 )) && ( FALSE !== ( $content = $component->getProperty( $propName, FALSE, TRUE ))))
            _addXMLchild( $properties, $propName, 'unknown', $content['value'], $content['params'] );
          break;
      } // end switch( $propName )
    } // end foreach( $props as $prop )
          /** fix subComponent properties, if any */
    while( FALSE !== ( $subcomp = $component->getComponent())) {
      $subCompName  = $subcomp->compName;
      $child2       = $child->addChild( strtolower( $subCompName ));
      $properties   = $child2->addChild( 'properties' );
      $subCompProps = $subcomp->getConfig( 'setPropertyNames' );
      $action = FALSE;
      foreach( $subCompProps as $propName ) {
        switch( $propName ) {
          case 'ACTION':      // single occurence below, if set
          case 'SUMMARY':
          case 'DESCRIPTION':
            if( FALSE !== ( $content = $subcomp->getProperty( $propName, FALSE, TRUE ))) {
              _addXMLchild( $properties, $propName, 'text', $content['value'], $content['params'] );
              if( 'ACTION' == $propName )
                 $action = strtoupper( $content['value'] );
            }
            break;
          case 'COMMENT':
          case 'TZNAME':
            while( FALSE !== ( $content = $subcomp->getProperty( $propName, FALSE, TRUE )))
              _addXMLchild( $properties, $propName, 'text', $content['value'], $content['params'] );
            break;
          case 'ATTACH':          // may occur multiple times in emailprop, only once in audio-/procedureprop
            while( FALSE !== ( $content = $subcomp->getProperty( $propName, FALSE, TRUE ))) {
              $type = ( FALSE !== stripos( $content['params'], "VALUE=BINARY" )) ? 'binary' : 'uri';
              _addXMLchild( $properties, $propName, $type, $content['value'], $content['params'] );
              if( 'EMAIL' != $action )
                break;
            }
            break;
          case 'ATTENDEE':
            while( FALSE !== ( $content = $subcomp->getProperty( $propName, FALSE, TRUE )))
              _addXMLchild( $properties, $propName, 'cal-address', $content['value'], $content['params'] );
            break;
          case 'RDATE':
            while( FALSE !== ( $content = $subcomp->getProperty( $propName, FALSE, TRUE ))) {
              $type = 'date-time';
              if( FALSE !== stripos( $content['params'], "VALUE=" )) {
                if(( FALSE !== stripos( $content['params'], "VALUE=DATE" )) && ( FALSE === stripos( $content['params'], "VALUE=DATE-TIME" )))
                  $type = 'date';
                elseif( FALSE !== stripos( $content['params'], "VALUE=PERIOD" ))
                  $type = 'period';
              }
              _addXMLchild( $properties, $propName, $type, $content['value'], $content['params'] );
            }
            break;
          case 'DTSTART':
            if( FALSE !== ( $content = $subcomp->getProperty( $propName, FALSE, TRUE )))
              _addXMLchild( $properties, $propName, 'date-time', $content['value'], $content['params'] );
            break;
          case 'DURATION':
            if( FALSE !== ( $content = $subcomp->getProperty( $propName, FALSE, TRUE )))
              _addXMLchild( $properties, $propName, 'duration', $content['value'], $content['params'] );
            break;
          case 'REPEAT':
            if( FALSE !== ( $content = $subcomp->getProperty( $propName, FALSE, TRUE )))
              _addXMLchild( $properties, $propName, 'integer', $content['value'], $content['params'] );
            break;
          case 'TRIGGER':
            if( FALSE !== ( $content = $subcomp->getProperty( $propName, FALSE, TRUE ))) {
              $type = ( FALSE !== stripos( $content['params'], "VALUE=DATE-TIME" )) ? 'date-time' : 'duration';
              _addXMLchild( $properties, $propName, $type, $content['value'], $content['params'] );
            }
            break;
          case 'TZOFFSETTO':
          case 'TZOFFSETFROM':
            if( FALSE !== ( $content = $subcomp->getProperty( $propName, FALSE, TRUE )))
              _addXMLchild( $properties, $propName, 'utc-offset', $content['value'], $content['params'] );
            break;
          case 'RRULE':
            while( FALSE !== ( $content = $subcomp->getProperty( $propName, FALSE, TRUE )))
              _addXMLchild( $properties, $propName, 'recur', $content['value'], $content['params'] );
            break;
          default:
            if(( 'X-' == substr( $propName, 0, 2 )) && ( FALSE !== ( $content = $subcomp->getProperty( $propName, FALSE, TRUE ))))
              _addXMLchild( $properties, $propName, 'unknown', $content['value'], $content['params'] );
            break;
        } // switch( $propName ) {
      } // end foreach( $subCompProps as $propName )
    } // end while( FALSE !== ( $subcomp = $component->getComponent()))
  } // end while( FALSE !== ( $component = $calendar->getComponent()))
  return $xml->asXML();
}
/**
 * Add children to a SimpleXMLelement
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.2 - 2013-10-15
 * @param object $parent,  reference to a SimpleXMLelement node
 * @param string $name,    new element node name
 * @param string $type,    content type, subelement(-s) name
 * @param string $content, new subelement content
 * @param array  $params,  new element 'attributes'
 * @return void
 */
function _addXMLchild( $parent, $name, $type, $content, $params=array()) {
            /** create new child node */
  $name         = strtolower( $name );
  $child        = $parent->addChild( $name );
  if( !empty( $params )) {
    $params1    = explode( ';', $params );
    $params     = array();
    foreach( $params1 as $param ) {
      if( empty( $param ))
        continue;
      list( $k, $v ) = explode( '=', $param, 2 );
      if( 'VALUE' != $k )
        $params[$k] = $v;
    }
    unset( $params1 );
    if( ! empty( $params )) {
      $parameters = $child->addChild( 'parameters' );
      foreach( $params as $param => $parVal ) {
        $p1 = $parameters->addChild( strtolower( $param ));
        if( 'X-' == substr( $param, 0, 2  ))
          $p2 = $p1->addChild( 'unknown', htmlentities( $parVal ));
        else {
          switch( $param ) {
            case 'ALTREP':
            case 'DIR':            $pType = 'uri';         break;
            case 'DELEGATED-FROM':
            case 'DELEGATED-TO':
            case 'MEMBER':
            case 'SENT-BY':        $pType = 'cal-address'; break;
            case 'RSVP':           $pType = 'boolean';     break ;
            default:               $pType = 'text';        break;
          } // end switch
          if( 'cal-address' == $pType )
            $parVal = explode( ',', $parVal );
          else
            $parVal = array( $parVal );
          foreach( $parVal as $part ) {
            if( FALSE !== strpos( $part, '"' ))
              $part = str_replace( '"', '', $part );
            $p2 = $p1->addChild( $pType, htmlentities( $part ));
          }
        } // end else
      } // end foreach( $params as $param => $parVal )
    } // end if( ! empty( $params ))
  } // end if( !empty( $params ))
  if(( empty( $content ) && ( '0' != (string) $content )) || ( !is_array( $content) && ( '-' != substr( $content, 0, 1 ) && ( 0 > $content ))))
    return;
            /** store content */
  switch( $type ) {
    case 'binary':
      $v = $child->addChild( $type, $content );
      break;
    case 'boolean':
      break;
    case 'cal-address':
      $v = $child->addChild( $type, $content );
      break;
    case 'date':      //   <date>2011-05-17</date>
      $content = explode( ',', $content );
      foreach( $content as $c )
        $v = $child->addChild( $type, substr( $c, 0, 4 ).'-'.substr( $c, 4, 2 ).'-'.substr( $c, 6, 2 ));
      break;
    case 'date-time': //   <date-time>2011-05-17T12:00:00</date-time>
      $content = explode( ',', $content );
      foreach( $content as $c )
        $v = $child->addChild( $type, substr( $c, 0, 4 ).'-'.substr( $c, 4, 2 ).'-'.substr( $c, 6, 2 ).'T'.substr( $c, 9, 2 ).':'.substr( $c, 11, 2 ).':'.substr( $c, 13 ));
      break;
    case 'duration':
      $v = $child->addChild( $type, $content );
      break;
    case 'geo':
      list( $latitude, $longitude ) = explode( ';', $content );
      $v1 = $child->addChild( 'latitude',  $latitude );
      $v1 = $child->addChild( 'longitude', $longitude );
      break;
    case 'integer':
      $v = $child->addChild( $type, $content );
      break;
    case 'period': //      <start>2011-05-17T12:00:00</start> dito end
      $content = explode( ',', $content );
      foreach( $content as $period ) {
        $period = explode( '/', $period, 2 );
        $v1 = $child->addChild( $type );
        $v2 = $v1->addChild( 'start', substr( $period[0], 0, 4 ).'-'.substr( $period[0], 4, 2 ).'-'.substr( $period[0], 6, 2 ).'T'.substr( $period[0], 9, 2 ).':'.substr( $period[0], 11, 2 ).':'.substr( $period[0], 13 ));
        if( in_array( $period[1][0], array( '+', '-', 'P' ))) //$period[1]{0}
          $v2 = $v1->addChild( 'duration', $period[1] );
        else
          $v2 = $v1->addChild( 'end', substr( $period[1], 0, 4 ).'-'.substr( $period[1], 4, 2 ).'-'.substr( $period[1], 6, 2 ).'T'.substr( $period[1], 9, 2 ).':'.substr( $period[1], 11, 2 ).'.'.substr( $period[1], 13 ));
      }
      break;
    case 'recur':
      $content = explode( ';', $content );
      foreach( $content as $theRule ) {
        list( $rulelabel, $rulevalue ) = explode( '=', $theRule, 2 );
        $rulelabel = strtolower( $rulelabel );
        switch( $rulelabel ) {
          case 'bysecond':
          case 'bymonute':
          case 'byhour':
          case 'bymonthday':
          case 'byyearday':
          case 'byweekday':
          case 'bymonth':
          case 'bysetpos':
            $rulevalue = explode( ',', $rulevalue );
            foreach( $rulevalue as $vix => $valuePart )
              $v = $child->addChild( $rulelabel, $valuePart );
            break;
          case 'byday':
            $rulevalue = explode( ',', $rulevalue );
            foreach( $rulevalue as $valuePart )
              $p    = $child->addChild( $rulelabel, $valuePart );
            break;
          case 'until':
            $t = substr( $rulevalue, 0, 4 ).'-'.substr( $rulevalue, 4, 2 ).'-'.substr( $rulevalue, 6, 2 );
            if( 8 < strlen( $rulevalue ))
              $t .= substr( $rulevalue, 8, 3 ).':'.substr( $rulevalue, 11, 2 ).':'.substr( $rulevalue, 13 );
            $rulevalue = $t;
          case 'freq':
          case 'count':
          case 'interval':
          case 'wkst':
          default:
            $p = $child->addChild( $rulelabel, $rulevalue );
            break;
        } // end switch( $rulelabel )
      } // end foreach( $content as $rulelabel => $rulevalue )
      break;
    case 'rstatus':
      if( ! empty( $content )) {
        $content = explode( ';', $content, 3 );
        $v = $child->addChild( 'code', $content[0] );
        $v = $child->addChild( 'description', htmlentities( $content[1] ));
        if( isset( $content[2] ))
          $v = $child->addChild( 'data', htmlentities( $content[2] ));
      }
      break;
    case 'text':
      $v = $child->addChild( $type, htmlentities( $content ));
      break;
    case 'time':
      break;
    case 'uri':
      $v = $child->addChild( $type, $content );
      break;
    case 'utc-offset':
      switch( strlen( $content )) {
        case 4:
          $content = substr( $content, 0, 2 ).':'.substr( $content, -2 );
          break;
        case 5:
          $content = substr( $content, 0, 3 ).':'.substr( $content, -2 );
          break;
        case 6:
          $content = substr( $content, 0, 2 ).':'.substr( $content, 2, 2 ).':'.substr( $content, -2 );
          break;
        case 7:
          $content = substr( $content, 0, 3 ).':'.substr( $content, 3, 2 ).':'.substr( $content, -2 );
          break;
      }
      $v = $child->addChild( $type, $content );
      break;
    case 'unknown':
    default:
      $v = $child->addChild( 'unknown', htmlentities( $content ));
      break;
  }
}
/**
 * parse xml file into iCalcreator instance
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-09
 * @param  string $xmlfile xml file
 * @param  array  $iCalcfg iCalcreator config array (opt)
 * @uses XML2iCal()
 * @return mixediCalcreator instance or FALSE on error
 */
function XMLfile2iCal( $xmlfile, $iCalcfg=array()) {
  if( FALSE === ( $xml = file_get_contents( $xmlfile )))
    return FALSE;
  return XML2iCal( $xml, $iCalcfg );
}
/**
 * parse XML string into iCalcreator instance, alias of XML2iCal
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-19
 * @param  string $xml xml string
 * @param  array  $iCalcfg iCalcreator config array (opt)
 * @uses XML2iCal()
 * @return mixed  iCalcreator instance or FALSE on error
 */
function XMLstr2iCal( $xml, $iCalcfg=array()) {
  return XML2iCal( $xml, $iCalcfg);
}
/**
 * parse XML string into iCalcreator instance
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-19
 * @param  string $xml xml string
 * @param  array  $iCalcfg iCalcreator config array (opt)
 * @uses XMLgetTagContent1()
 * @uses iCalVCALENDAR::__construct()
 * @uses XMLgetComps()
 * @return mixed  iCalcreator instance or FALSE on error
 */
function XML2iCal( $xml, $iCalcfg=array()) {
  $xml  = str_replace( array( "\r\n", "\n\r", "\n", "\r" ), '', $xml );
  $xml  = XMLgetTagContent1( $xml, 'vcalendar', $endIx );
  $iCal = new vcalendar( $iCalcfg );
  XMLgetComps( $iCal, $xml );
  unset( $xml );
  return $iCal;
}
/**
 * parse XML string into iCalcreator components
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-20
 * @param  object $iCal, iCalcreator vcalendar or component object instance
 * @param  string $xml xml string
 * @uses XMLgetTagContent1()
 * @uses XMLgetTagContent2()
 * @uses iCalComponentFactory::isAllowed()
 * @uses iCalBASEcomponent::newComponent()
 * @return bool
 */
function XMLgetComps( $iCal, $xml ) {
  $sx      = 0;
  while(( FALSE !== substr( $xml, $sx, 1 )) && ( '<properties>' != substr( $xml, $sx, 12 )) && ( '<components>' != substr( $xml, $sx, 12 )))
    $sx   += 1;
  if( FALSE === substr( $xml, $sx, 1 ))
    return FALSE;
  if( '<properties>' == substr( $xml, $sx, 12 )) {
    $xml2  = XMLgetTagContent1( $xml, 'properties', $endIx );
    XMLgetProps3( $iCal, $xml2 );
    $xml   = substr( $xml, $endIx );
  }
  if( '<components>' == substr( $xml, 0, 12 )) {
    $xml     = XMLgetTagContent1( $xml, 'components', $endIx );
  }
  while( ! empty( $xml )) {
    $xml2  = XMLgetTagContent2( $xml, $tagName, $endIx );
    if( iCalComponentFactory::isAllowed( $iCal->compName, $tagName ))
      XMLgetComps( $iCal->newComponent( $tagName ), $xml2 );
    $xml   = substr( $xml, $endIx);
  }
  unset( $xml );
  return $iCal;
}
/**
 * parse XML into iCalcreator properties
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-21
 * @param  array  $iCal iCalcreator calendar/component instance
 * @param  string $xml xml string
 * @uses XMLgetTagContent2()
 * @uses iCalBASEcomponent::setProperty()
 * @uses XMLgetTagContent1()
 * @return void
 */
function XMLgetProps3( $iCal, $xml) {
  while( ! empty( $xml )) {
    $xml2           = XMLgetTagContent2( $xml, $propName, $endIx );
    $propName       = strtoupper( $propName );
    if( empty( $xml2 )) {
      $iCal->setProperty( $propName );
      $xml          = substr( $xml, $endIx);
      continue;
    }
    $params         = array();
    if( '<parameters/>' == substr( $xml2, 0, 13 ))
      $xml2         = substr( $xml2, 13 );
    elseif( '<parameters>' == substr( $xml2, 0, 12 )) {
      $xml3         = XMLgetTagContent1( $xml2, 'parameters', $endIx2 );
      while( ! empty( $xml3 )) {
        $xml4       = XMLgetTagContent2( $xml3, $paramKey, $endIx3 );
        $paramKey   = strtoupper( $paramKey );
        if( in_array( $paramKey, array( 'DELEGATED-FROM', 'DELEGATED-TO', 'MEMBER' ))) {
          while( ! empty( $xml4 )) {
            if( ! isset( $params[$paramKey] ))
              $params[$paramKey]  = XMLgetTagContent1( $xml4, 'cal-address', $endIx4 );
            else
              $params[$paramKey] .= ','.XMLgetTagContent1( $xml4, 'cal-address', $endIx4 );
            $xml4   = substr( $xml4, $endIx4 );
          }
        }
        else {
          $t        = html_entity_decode( XMLgetTagContent2( $xml4, $pType, $endIx4 ));
          if( 'boolean' == $pType )
            $t      = (( 'FALSE' == $t ) || ( TRUE !== (bool) $t )) ? 'FALSE' : 'TRUE';
          if( ! isset( $params[$paramKey] ))
            $params[$paramKey]  = $t;
          else
            $params[$paramKey] .= ",$t";
        }
        $xml3       = substr( $xml3, $endIx3 );
      }
      $xml2         = substr( $xml2, $endIx2 );
    } // end if( '<parameters>' == substr( $xml2, 0, 12 ))
    $value          = XMLgetTagContent2( $xml2, $valueType, $endIx3 ); // the first one of opt. many valueParts
    switch( $propName ) {
      case 'CATEGORIES':
      case 'RESOURCES':
        $tValue     = array();
        while( ! empty( $xml2 )) {
          $tValue[] = html_entity_decode( XMLgetTagContent1( $xml2, 'text', $endIx4 ));
          $xml2     = substr( $xml2, $endIx4 );
        }
        $value      = implode( ',', $tValue );
        break;
      case 'EXDATE':   // multiple single-date(-times) may exist
      case 'RDATE':
        if( 'period' != $valueType ) {
          if( 'date' == $valueType )
            $params['VALUE'] = 'DATE';
          $tValue   = array();
          while( ! empty( $xml2 ) && ( '<date' == substr( $xml2, 0, 5 ))) {
            $tValue[] = XMLgetTagContent2( $xml2, $pType, $endIx4 );
            $xml2   = substr( $xml2, $endIx4 );
          }
          $value    = implode( ',', $tValue );
          break;
        }
      case 'FREEBUSY':
        if( 'RDATE' == $propName )
          $params['VALUE'] = 'PERIOD';
        $tValue     = array();
        while( ! empty( $xml2 ) && ( '<period>' == substr( $xml2, 0, 8 ))) {
          $xml3     = XMLgetTagContent2( $xml2, $pType, $endIx4 ); // period
          $t        = array();
          while( ! empty( $xml3 )) {
            $t[]    = XMLgetTagContent2( $xml3, $pType, $endIx5 ); // start - end/duration
            $xml3   = substr( $xml3, $endIx5 );
          }
          $tValue[] = implode( '/', $t );
          $xml2     = substr( $xml2, $endIx4 );
        }
        $value      = implode( ',', $tValue );
        break;
      case 'TZOFFSETTO':
      case 'TZOFFSETFROM':
        $value      = str_replace( ':', '', $value );
        break;
      case 'GEO':
        $tValue     = array( 'latitude' => $value );
        $tValue['longitude'] = XMLgetTagContent1( substr( $xml2, $endIx3 ), 'longitude', $endIx3 );
        $value      = $tValue;
        break;
      case 'EXRULE':
      case 'RRULE':
        $tValue     = array( $valueType => $value );
        $xml2       = substr( $xml2, $endIx3 );
        while( ! empty( $xml2 )) {
          $t        = XMLgetTagContent2( $xml2, $valueType, $endIx4 );
          switch( $valueType ) {
            case 'freq':
            case 'count':
            case 'until':
            case 'interval':
            case 'wkst':
              $tValue[$valueType] = $t;
              break;
            case 'byday':
              if( 2 == strlen( $t ))
                $tValue[$valueType][] = array( 'DAY' => $t );
              else {
                $day = substr( $t, -2 );
                $key = substr( $t, 0, ( strlen( $t ) - 2 ));
                $tValue[$valueType][] = array( $key, 'DAY' => $day );
              }
              break;
            default:
              $tValue[$valueType][] = $t;
          }
          $xml2     = substr( $xml2, $endIx4 );
        }
        $value      = $tValue;
        break;
      case 'REQUEST-STATUS':
        $tValue     = array();
        while( ! empty( $xml2 )) {
          $t        = html_entity_decode( XMLgetTagContent2( $xml2, $valueType, $endIx4 ));
          $tValue[$valueType] = $t;
          $xml2     = substr( $xml2, $endIx4 );
        }
        $value      = ( ! empty( $tValue )) ? $tValue : array( 'code' => null, 'description' => null );
        if( !isset( $value['data'] ))
          $value['data'] = FALSE;
        break;
      default:
        switch( $valueType ) {
          case 'binary':    $params['VALUE'] = 'BINARY';           break;
          case 'date':      $params['VALUE'] = 'DATE';             break;
          case 'date-time': $params['VALUE'] = 'DATE-TIME';        break;
          case 'text':
          case 'unknown':   $value = html_entity_decode( $value ); break;
        }
        break;
    } // end switch( $propName )
    if( empty( $value ) && ( is_array( $value ) || ( '0' > $value )))
      $value = '';
    if( 'FREEBUSY' == $propName ) {
      $fbtype = $params['FBTYPE'];
      unset( $params['FBTYPE'] );
      $iCal->setProperty( $propName, $fbtype, $value, $params );
    }
    elseif(  'GEO' == $propName )
      $iCal->setProperty( $propName, $value['latitude'], $value['longitude'], $params );
    elseif( 'REQUEST-STATUS' == $propName )
      $iCal->setProperty( $propName, $value['code'], $value['description'], $value['data'], $params );
    else
      $iCal->setProperty( $propName, $value, $params );
    $xml            = substr( $xml, $endIx);
  } // end while( ! empty( $xml ))
}
/**
 * fetch a specific XML tag content
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc2.8 - 2013-12-12
 * @param  string $xml xml string
 * @param  string $tagName xml tag name
 * @param  int $endIx last pos ix for tag
 * @return string
 */
function XMLgetTagContent1( $xml, $tagName, & $endIx=0 ) {
  $strlen    = strlen( $tagName );
  $sx1       = 0;
  while( FALSE !== substr( $xml, $sx1, 1 )) {
    if(( FALSE !== substr( $xml, ( $sx1 + $strlen + 1 ), 1 )) &&
       ( strtolower( "<$tagName>" )   == strtolower( substr( $xml, $sx1, ( $strlen + 2 )))))
      break;
    if(( FALSE !== substr( $xml, ( $sx1 + $strlen + 3 ), 1 )) &&
       ( strtolower( "<$tagName />" ) == strtolower( substr( $xml, $sx1, ( $strlen + 4 ))))) { // empty tag
      $endIx = $strlen + 5;
      return '';
    }
    if(( FALSE !== substr( $xml, ( $sx1 + $strlen + 2 ), 1 )) &&
       ( strtolower( "<$tagName/>" )  == strtolower( substr( $xml, $sx1, ( $strlen + 3 ))))) { // empty tag
      $endIx = $strlen + 4;
      return '';
    }
    $sx1    += 1;
  }
  if( FALSE === substr( $xml, $sx1, 1 )) {
//    $endIx   = ( empty( $sx )) ? 0 : $sx - 1;  org
    $endIx   = ( empty( $sx1 )) ? 0 : $sx1 - 1;
    return '';
  }
  if( FALSE === ( $pos = stripos( $xml, "</$tagName>" ))) { // missing end tag??
    $endIx   = strlen( $xml ) + 1;
    return '';
  }
  $endIx     = $pos + $strlen + 3;
  return substr( $xml, ( $sx1 + $strlen + 2 ), ( $pos - $sx1 - 2 - $strlen ));
}
/**
 * fetch next (unknown) XML tagname AND content
 *
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0rc1 - 2013-06-23
 * @param  string $xml xml string
 * @param  string $tagName xml tag name
 * @param  int $endIx last pos ix for tag
 * @return string
 */
function XMLgetTagContent2( $xml, & $tagName, & $endIx ) {
  $endIx       = strlen( $xml ) + 1; // just in case.. .
  $sx1         = 0;
  while( FALSE !== substr( $xml, $sx1, 1 )) {
    if( '<' == substr( $xml, $sx1, 1 )) {
      if(( FALSE !== substr( $xml, ( $sx1 + 3 ), 1 )) && ( '<!--' == substr( $xml, $sx1, 4 ))) // skip comment
        $sx1  += 1;
      else
        break;
    }
    else
      $sx1    += 1;
  }
  $sx2         = $sx1;
  while( FALSE !== substr( $xml, $sx2, 1 )) {
    if(( FALSE !== substr( $xml, ( $sx2 + 1 ), 1 )) && ( '/>' == substr( $xml, $sx2, 2 ))) { // empty tag
      $tagName = trim( substr( $xml, ( $sx1 + 1 ), ( $sx2 - $sx1 - 1 )));
      $endIx   = $sx2 + 2;
      return '';
    }
    if( '>' == substr( $xml, $sx2, 1 ))
      break;
    $sx2      += 1;
  }
  $tagName     = substr( $xml, ( $sx1 + 1 ), ( $sx2 - $sx1 - 1 ));
  $endIx       = $sx2 + 1;
  if( FALSE === substr( $xml, $sx2, 1 ))
    return '';
  $strlen      = strlen( $tagName );
  if(( 'duration' == $tagName ) &&
     ( FALSE !== ( $pos1 = stripos( $xml, "<duration>",  $sx1+1  ))) &&
     ( FALSE !== ( $pos2 = stripos( $xml, "</duration>", $pos1+1 ))) &&
     ( FALSE !== ( $pos3 = stripos( $xml, "</duration>", $pos2+1 ))) &&
     ( $pos1 < $pos2 ) && ( $pos2 < $pos3 ))
    $pos = $pos3;
  elseif( FALSE === ( $pos = stripos( $xml, "</$tagName>", $sx2 )))
    return '';
  $endIx       = $pos + $strlen + 3;
  return substr( $xml, ( $sx1 + $strlen + 2 ), ( $pos - $strlen - 2 ));
}
