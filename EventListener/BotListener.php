<?php
/**
 * @author    Lev Semin <lev@darvin-studio.ru>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\BotDetectorBundle\EventListener;

use Darvin\BotDetectorBundle\Entity\DetectedBot;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Vipx\BotDetectBundle\BotDetector;


class BotListener
{
    /** @var RegistryInterface */
    private $doctrine;

    /** @var  BotDetector */
    private $botDetector;

    /** @var  LoggerInterface */
    private $logger;

    /**
     * BotListener constructor.
     * @param RegistryInterface $doctrine
     * @param BotDetector $botDetector
     * @param LoggerInterface $logger
     */
    public function __construct(RegistryInterface $doctrine, BotDetector $botDetector, LoggerInterface $logger)
    {
        $this->botDetector = $botDetector;
        $this->doctrine = $doctrine;
        $this->logger = $logger;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($event->getRequestType()!=HttpKernel::MASTER_REQUEST) {
            return;
        }

        /** @var Request $request */
        $request = $event->getRequest();
        /** @var EntityManager $em */
        $em = $this->doctrine->getEntityManager();
        /** @var Response $response */
        $response = $event->getResponse();

        $meta = $this->botDetector->detectFromRequest($request);
        if ($meta){
            try {
                $item = new DetectedBot();
                $item->setBotType($meta->getAgent());
                $item->setIp($request->getClientIp());
                $item->setUrl($request->getRequestUri());
                $item->setResponseCode($response->getStatusCode());

                $em->persist($item);
                $em->flush();
            } catch (\Exception $ex){
                $this->logger->err(sprintf("Unable to write detected bot into db: %s", $ex->getMessage()));
            }
        }
    }
}