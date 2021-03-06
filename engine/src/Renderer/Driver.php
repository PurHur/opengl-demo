<?php

/**
 * This file is part of Battleground package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Serafim\Bic\Renderer;

use Serafim\SDL\SDL;

final class Driver
{
    /**
     * @var int
     */
    public int $index;

    /**
     * @var string
     */
    public string $name;

    /**
     * Driver constructor.
     *
     * @param int $index
     * @param string $name
     */
    public function __construct(int $index, string $name)
    {
        $this->index = $index;
        $this->name = $name;
    }

    /**
     * @return Driver
     */
    public static function current(): Driver
    {
        $sdl = SDL::getInstance();

        $driver = self::findByName($sdl->getCurrentVideoDriver());

        if ($driver === null) {
            throw new \LogicException('There is no default video driver');
        }

        return $driver;
    }

    /**
     * @param int $id
     * @return Driver|null
     */
    public static function findById(int $id): ?Driver
    {
        foreach (self::all() as $driver) {
            if ($driver->index === $id) {
                return $driver;
            }
        }

        return null;
    }

    /**
     * @param string $name
     * @return Driver|null
     */
    public static function findByName(string $name): ?Driver
    {
        foreach (self::all() as $driver) {
            if ($driver->name === $name) {
                return $driver;
            }
        }

        return null;
    }

    /**
     * @return iterable|Driver[]
     */
    public static function all(): iterable
    {
        $sdl = SDL::getInstance();

        for ($i = 0, $len = $sdl->getNumVideoDrivers(); $i < $len; ++$i) {
            yield new Driver($i, $sdl->getVideoDriver($i));
        }
    }
}
