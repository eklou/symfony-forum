<?php
/**
 * Created by PhpStorm.
 * User: formation
 * Date: 06/09/2017
 * Time: 16:08
 */

namespace AppBundle\Entity;


use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="logs")
 * @ORM\Entity(repositoryClass="Gedmo\Loggable\Entity\Repository\LogEntryRepository")
 * Class Log
 * @package AppBundle\Entity
 */
 class Log extends AbstractLogEntry {

 }
