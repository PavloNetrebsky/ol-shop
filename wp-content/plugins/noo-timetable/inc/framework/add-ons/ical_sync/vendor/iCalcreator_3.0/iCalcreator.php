<?php
/**
 * @copyright copyright (c) 2013-2014 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      http://kigkonsult.se/iCalcreator/index.php
 * @license   iC3license1.txt
 * @package   iCalcreator
 * @version   3.0
 */
/**
 * iCalcreator class requirements file
 *
 * @package iCalcreator
 * @author Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @since 3.0 - 2013-09-19
 */

if( class_exists('iCalComponentFactory.class.php') ) return;

/**
 * iCalcreator class includes init
 */
$classLib = dirname( __FILE__ ).DIRECTORY_SEPARATOR.'iCalcreator'.DIRECTORY_SEPARATOR;
/**
 * iCalcreator internal component classes
 */
require_once $classLib.'iCalComponentFactory.class.php';
iCalComponentFactory::init();
require_once $classLib.'iCalBASEcomponent.class.php';
require_once $classLib.'iCalVCALENDAR.class.php';
require_once $classLib.'iCalVEVENT.class.php';
require_once $classLib.'iCalVTODO.class.php';
require_once $classLib.'iCalVJOURNAL.class.php';
require_once $classLib.'iCalVFREEBUSY.class.php';
require_once $classLib.'iCalVALARM.class.php';
require_once $classLib.'iCalVTIMEZONE.class.php';
require_once $classLib.'iCalSTANDARD.class.php';
require_once $classLib.'iCalDAYLIGHT.class.php';
/**
 * iCalcreator internal property classes
 */
require_once $classLib.'iCalPropertyFactory.class.php';
require_once $classLib.'iCalBASEproperty.class.php';
require_once $classLib.'iCalBINARYproperty.class.php';
require_once $classLib.'iCalCAL_ADDRESSproperty.class.php';
require_once $classLib.'iCalDATEproperty.class.php';
require_once $classLib.'iCalDURATIONproperty.class.php';
require_once $classLib.'iCalFLOATproperty.class.php';
require_once $classLib.'iCalINTEGERproperty.class.php';
require_once $classLib.'iCalPERIODproperty.class.php';
require_once $classLib.'iCalREXDATEproperty.class.php';
require_once $classLib.'iCalREXRULEproperty.class.php';
require_once $classLib.'iCalTEXTproperty.class.php';
require_once $classLib.'iCalURIproperty.class.php';
require_once $classLib.'iCalUTC_OFFSETproperty.class.php';
/**
 * iCalcreator internal parameter classes
 */
require_once $classLib.'iCalParameterFactory.class.php';
require_once $classLib.'iCalParameter.class.php';
/**
 * iCalcreator external classes
 */
require_once $classLib.'vcalendar.class.php';
require_once $classLib.'vevent.class.php';
require_once $classLib.'vtodo.class.php';
require_once $classLib.'vjournal.class.php';
require_once $classLib.'vfreebusy.class.php';
require_once $classLib.'valarm.class.php';
require_once $classLib.'vtimezone.class.php';
require_once $classLib.'standard.class.php';
require_once $classLib.'daylight.class.php';
/**
 * iCalcreator optional requirements
 */
require_once $classLib.'iCalvCard.php';
require_once $classLib.'iCalXML.php';
