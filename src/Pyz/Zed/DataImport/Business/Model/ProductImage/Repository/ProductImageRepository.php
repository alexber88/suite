<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage\Repository;

use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery;

class ProductImageRepository implements ProductImageRepositoryInterface
{
    /**
     * @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSet[]
     */
    protected $resolvedProductImageSets = [];

    /**
     * @var \Orm\Zed\ProductImage\Persistence\SpyProductImage[]
     */
    protected $resolvedProductImages = [];

    /**
     * @var \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage[]
     */
    protected $resolvedProductImageSetToProductImageRelations = [];

    /**
     * @param string $name
     * @param int $localeId
     * @param int|null $productAbstractId
     * @param int|null $productConcreteId
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSet
     */
    public function getProductImageSetEntity(string $name, int $localeId, ?int $productAbstractId = null, ?int $productConcreteId = null): SpyProductImageSet
    {
        $key = $this->buildProductImageSetKey($name, $localeId, $productAbstractId, $productConcreteId);

        if (!isset($this->resolvedProductImageSets[$key])) {
            $this->resolvedProductImageSets[$key] = $this->getProductImageSet($name, $localeId, $productAbstractId, $productConcreteId);
        }

        return $this->resolvedProductImageSets[$key];
    }

    /**
     * @param string $externalUrlLarge
     * @param int $productImageSetId
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImage
     */
    public function getProductImageEntity(string $externalUrlLarge, int $productImageSetId): SpyProductImage
    {
        $key = $this->buildProductImageKey($externalUrlLarge, $productImageSetId);

        if (!isset($this->resolvedProductImages[$key])) {
            $this->resolvedProductImages[$key] = $this->getProductImage($externalUrlLarge, $productImageSetId);
        }

        return $this->resolvedProductImages[$key];
    }

    /**
     * @param int $productImageSetId
     * @param int $productImageId
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage
     */
    public function getProductImageSetToProductImageRelationEntity(int $productImageSetId, int $productImageId): SpyProductImageSetToProductImage
    {
        $key = $this->buildProductImageSetToProductImageRelationKey($productImageSetId, $productImageId);

        if (!isset($this->resolvedProductImageSetToProductImageRelations[$key])) {
            $this->resolvedProductImageSetToProductImageRelations[$key] = $this->getProductImageSetToProductImageRelation($productImageSetId, $productImageId);
        }

        return $this->resolvedProductImageSetToProductImageRelations[$key];
    }

    /**
     * @param string $externalUrlLarge
     * @param int $productImageSetId
     *
     * @return string
     */
    protected function buildProductImageKey(string $externalUrlLarge, int $productImageSetId): string
    {
        return sprintf('%d:%s', $productImageSetId, $externalUrlLarge);
    }

    /**
     * @param string $imageUrlLarge
     * @param int $productImageSetId
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImage
     */
    protected function getProductImage(string $imageUrlLarge, int $productImageSetId): SpyProductImage
    {
        $productImageEntity = SpyProductImageQuery::create()
            ->useSpyProductImageSetToProductImageQuery()
                ->filterByFkProductImageSet($productImageSetId)
            ->endUse()
            ->filterByExternalUrlLarge($imageUrlLarge)
            ->findOne();

        if ($productImageEntity) {
            return $productImageEntity;
        }

        return new SpyProductImage();
    }

    /**
     * @param int $productImageSetId
     * @param int $productImageId
     *
     * @return string
     */
    protected function buildProductImageSetToProductImageRelationKey(int $productImageSetId, int $productImageId): string
    {
        return sprintf('%d:%d', $productImageSetId, $productImageId);
    }

    /**
     * @param int $productImageSetId
     * @param int $productImageId
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage
     */
    protected function getProductImageSetToProductImageRelation(int $productImageSetId, int $productImageId): SpyProductImageSetToProductImage
    {
        return SpyProductImageSetToProductImageQuery::create()
            ->filterByFkProductImageSet($productImageSetId)
            ->filterByFkProductImage($productImageId)
            ->findOneOrCreate();
    }

    /**
     * @param string $name
     * @param int $localeId
     * @param int|null $productAbstractId
     * @param int|null $productConcreteId
     *
     * @return string
     */
    protected function buildProductImageSetKey(string $name, int $localeId, ?int $productAbstractId = null, ?int $productConcreteId = null): string
    {
        return sprintf(
            '%s:%d:%d:%d',
            $name,
            $localeId,
            $productAbstractId ?? 0,
            $productConcreteId ?? 0
        );
    }

    /**
     * @param string $name
     * @param int $localeId
     * @param int|null $productAbstractId
     * @param int|null $productConcreteId
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSet
     */
    protected function getProductImageSet(string $name, int $localeId, ?int $productAbstractId = null, ?int $productConcreteId = null): SpyProductImageSet
    {
        $query = SpyProductImageSetQuery::create()
            ->filterByName($name)
            ->filterByFkLocale($localeId);

        if ($productAbstractId) {
            $query->filterByFkProductAbstract($productAbstractId);
        }

        if ($productConcreteId) {
            $query->filterByFkProduct($productConcreteId);
        }

        return $query->findOneOrCreate();
    }
}
