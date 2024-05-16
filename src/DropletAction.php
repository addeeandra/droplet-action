<?php

namespace Addeeandra\Droplets;

use DigitalOceanV2\Api\AbstractApi;
use DigitalOceanV2\Entity\Action;
use DigitalOceanV2\Entity\Droplet as DropletEntity;
use DigitalOceanV2\Exception\ExceptionInterface;
use DigitalOceanV2\ResultPager;
use GrahamCampbell\DigitalOcean\DigitalOceanManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class DropletAction extends DigitalOceanManager
{
    /**
     * @param string|null $tag
     * @return Collection
     * @throws ExceptionInterface
     */
    public function all(string|null $tag = null): Collection
    {
        return Collection::make($this->fetchAll($this->droplet(), $tag));
    }

    /**
     * @param int|string $tagOrId
     * @return DropletEntity|null
     */
    public function find(int|string $tagOrId): ?DropletEntity
    {
        return Cache::remember("droplet.id-$tagOrId", 300, function () use ($tagOrId) {
            if (is_string($tagOrId)) {
                return $this->all()->first();
            }
            return $this->droplet()->getById($tagOrId);
        });
    }

    /**
     * @param string $dropletName
     * @return Action
     * @throws ExceptionInterface
     * @throws \Throwable
     */
    public function scaleIdle(string $dropletName): Action
    {
        return $this->droplet()->resize($this->getIdFromConfig($dropletName), $this->getSizeFromConfig($dropletName, 'on_idle'), false);
    }

    /**
     * @param string $dropletName
     * @return Action
     * @throws ExceptionInterface
     * @throws \Throwable
     */
    public function scaleUsage(string $dropletName): Action
    {
        return $this->droplet()->resize($this->getIdFromConfig($dropletName), $this->getSizeFromConfig($dropletName, 'on_usage'), false);
    }

    /**
     * @param string $dropletName
     * @return Action
     * @throws ExceptionInterface
     * @throws \Throwable
     */
    public function scalePeak(string $dropletName): Action
    {
        return $this->droplet()->resize($this->getIdFromConfig($dropletName), $this->getSizeFromConfig($dropletName, 'on_peak'), false);
    }

    /**
     * @param string $dropletName
     * @return string|null
     * @throws ExceptionInterface
     * @throws \Throwable
     */
    public function statusCheck(string $dropletName): ?string
    {
        return $this->droplet()->getById($this->getIdFromConfig($dropletName))->status;
    }

    /**
     * @return Collection
     */
    public function getAllSizes(): Collection
    {
        return Collection::make(Cache::remember(
            'droplet.sizes',
            120,
            fn() => $this->fetchAll($this->size())
        ));
    }

    /**
     * @throws ExceptionInterface
     * @throws \Throwable
     */
    public function getSizeFromConfig(string $dropletName, string $mode): string
    {
        $slug = config(sprintf('droplet-action.scale.%s.%s', $dropletName, $mode));

        throw_if($this->getAllSizes()->where('slug', $slug)->isEmpty(), new \RuntimeException("Size with slug '$slug' not available in DigitalOcean."));

        return $slug;
    }

    /**
     * @throws ExceptionInterface
     * @throws \Throwable
     */
    public function getIdFromConfig(string $dropletName): int|string
    {
        $dropletId = config(sprintf('droplet-action.scale.%s.id', $dropletName));

        throw_if(is_null($dropletId), new \RuntimeException("ID not found in '$dropletName' configuration."));

        return $dropletId;
    }

    /**
     * @throws ExceptionInterface
     */
    private function fetchAll(AbstractApi $api, ?string $method = null, ...$parameters): array
    {
        return (new ResultPager($this->connection()))->fetchAll($api, $method ?? 'getAll', $parameters);
    }

}