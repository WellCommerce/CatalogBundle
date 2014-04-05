<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 * 
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 * 
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */
namespace WellCommerce\Core\Component\Model;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use WellCommerce\Core\Component\Model\Collection\CustomCollection;

/**
 * Class AbstractModel
 *
 * Extends base Eloquent model and provides additional methods
 *
 * @package WellCommerce\Core\Component\Model
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractModel extends BaseModel
{
    /**
     * Translatable attributes in model
     *
     * @var array
     */
    protected $translatable = [];

    /**
     * Boots Illuminate\Database\Eloquent\Model
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
    }

    /**
     * Returns all model translatable attributes
     *
     * @return array
     */
    protected function getTranslatableAttributes()
    {
        return $this->translatable;
    }

    /**
     * Adds new translatable attribute
     *
     * @param $attribute
     */
    public function addTranslatableAttribute($attribute)
    {
        $this->translatable[] = $attribute;
    }

    /**
     * Checks if attribute is translatable
     *
     * @param $key
     *
     * @return bool
     */
    protected function isTranslatableAttribute($key)
    {
        return array_key_exists($key, array_flip($this->translatable));
    }

    /**
     * Shortcut to get PropertyAccessor
     *
     * @return \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    protected function getPropertyAccessor()
    {
        return PropertyAccess::createPropertyAccessor();
    }

    /**
     * Sets translatable attributes in model
     *
     * @param array $Data
     * @param       $language
     */
    public function setTranslationData(array $Data, $language)
    {
        $accessor = $this->getPropertyAccessor();

        foreach ($this->getTranslatableAttributes() as $attribute) {
            if ($this->isTranslatableAttribute($attribute)
                && isset($Data[$attribute])
                && is_array($Data[$attribute])
                && isset($Data[$attribute][$language])
            ) {
                $accessor->setValue($this, $attribute, $Data[$attribute][$language]);
            }
        }
    }

    /**
     * Returns translation data
     *
     * @return array
     * @throws \LogicException
     */
    public function getTranslationData()
    {
        if (!$this instanceof TranslatableModelInterface) {
            throw new \LogicException('Model must implement TranslatableModelInterface to get translations from it.');
        }

        $collection   = $this->translation;
        $languageData = [];

        foreach ($collection as $item) {
            foreach ($item->translatable as $attribute) {
                $languageData[$item->language_id][$attribute] = $item->$attribute;
            }
        }

        return $languageData;
    }

    /**
     * Synchronizes data in BelongsToMany relation
     *
     * @param BelongsToMany $relation
     * @param array|string  $values
     */
    public function sync(BelongsToMany $relation, $values)
    {
        if (!empty($values)) {
            $relation->sync($values);
        } else {
            $relation->detach();
        }
    }


    public function newCollection(array $models = Array())
    {
        return new CustomCollection($models);
    }

}