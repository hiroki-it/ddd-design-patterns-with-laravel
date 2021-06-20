<?php

declare(strict_types=1);

namespace App\Domain\Core;

use App\Domain\ValueObject\Id;
use App\Traits\UnsupportedMagicMethodTrait;

/**
 * エンティティ抽象クラス
 */
abstract class Entity
{
    use UnsupportedMagicMethodTrait;

    /**
     * IDクラス
     *
     * @var Id
     */
    protected Id $id;

    /**
     * エンティティの等価性を検証します．
     *
     * @param Entity $entity
     * @return bool
     */
    public function equals(Entity $entity): bool
    {
        return ($entity instanceof $this || $this instanceof $entity) // エンティティのデータ型の等価性
            && $this->id->equals($entity->id()); // IDオブジェクトの等価性
    }

    /**
     * IDクラスを返却します．
     */
    public function id(): Id
    {
        return $this->id;
    }
}
