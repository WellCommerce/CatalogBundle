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
namespace WellCommerce\Tax\Model;

use WellCommerce\Core\Model\AbstractModel;
use WellCommerce\Core\Model\ModelInterface;
use WellCommerce\Core\Model\TranslatableModelInterface;

/**
 * Class Tax
 *
 * @package WellCommerce\Tax\Model
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Tax extends AbstractModel implements ModelInterface, TranslatableModelInterface
{

    protected $table = 'tax';

    public $timestamps = true;

    protected $softDelete = false;

    protected $fillable = ['id', 'value'];

    public function translation()
    {
        return $this->hasMany('WellCommerce\Tax\\Model\TaxTranslation');
    }

    /**
     * {@inheritdoc}
     */
    public function getValidationXmlMapping()
    {
        return __DIR__ . '/../Resources/config/validation.xml';
    }
}