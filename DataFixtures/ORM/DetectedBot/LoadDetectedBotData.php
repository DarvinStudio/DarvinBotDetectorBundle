<?php
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2015, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\BotDetectorBundle\DataFixtures\ORM\DetectedBot;

use Darvin\BotDetectorBundle\Entity\DetectedBot;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Vipx\BotDetect\Metadata\MetadataInterface;

/**
 * Detected bot data fixture
 */
class LoadDetectedBotData implements FixtureInterface
{
    const COUNT = 100;

    /**
     * @var array
     */
    private static $botTypes = [
        MetadataInterface::TYPE_BOT,
        MetadataInterface::TYPE_CRAWLER,
        MetadataInterface::TYPE_RSS,
        MetadataInterface::TYPE_SPAMBOT,
        MetadataInterface::TYPE_SPIDER,
        MetadataInterface::TYPE_VALIDATOR,
    ];

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < self::COUNT; $i++) {
            $manager->persist($this->createDetectedBot($faker));
        }

        $manager->flush();
    }

    /**
     * @param \Faker\Generator $faker Faker
     *
     * @return \Darvin\BotDetectorBundle\Entity\DetectedBot
     */
    private function createDetectedBot(Generator $faker)
    {
        $detectedBot = new DetectedBot();
        $detectedBot->setBotType(self::$botTypes[array_rand(self::$botTypes)]);
        $detectedBot->setDate($faker->dateTimeThisYear);
        $detectedBot->setIp($faker->ipv4);
        $detectedBot->setResponseCode($faker->numberBetween(100, 511));
        $detectedBot->setUrl($faker->url);

        return $detectedBot;
    }
}
