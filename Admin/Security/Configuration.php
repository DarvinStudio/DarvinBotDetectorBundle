<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\BotDetectorBundle\Admin\Security;

use Darvin\AdminBundle\Security\Configuration\AbstractSecurityConfiguration;
use Darvin\BotDetectorBundle\Entity\DetectedBot;

/**
 * Admin security configuration
 */
class Configuration extends AbstractSecurityConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'darvin_bot_detector_security';
    }

    /**
     * {@inheritdoc}
     */
    protected function getSecurableObjectClasses()
    {
        return [
            'bot' => DetectedBot::DETECTED_BOT_CLASS,
        ];
    }
}