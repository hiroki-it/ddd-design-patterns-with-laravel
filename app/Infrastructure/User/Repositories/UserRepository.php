<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Repositories;

use App\Domain\User\Ids\UserId;
use App\Domain\User\Repositories\UserRepository as DomainUserRepository;
use App\Domain\User\Entities\User;
use App\Infrastructure\Repository;
use App\Infrastructure\User\DTOs\UserDTO;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * ユーザリポジトリ実装クラス
 */
final class UserRepository extends Repository implements DomainUserRepository
{
    /**
     * ユーザDTOクラス
     *
     * @var UserDTO
     */
    private UserDTO $userDTO;

    /**
     * コンストラクタインジェクション
     *
     * @param UserDTO $userDTO
     */
    public function __construct(UserDTO $userDTO)
    {
        $this->userDTO = $userDTO;
    }

    /**
     * @param User $user
     * @return void
     * @throws Throwable
     */
    public function create(User $user): void
    {
        DB::transaction(function () use ($user) {

            // ドメインモデルのデータをDTOに詰め替えます．
            $this->userDTO
                ->create([
                    'name'         => $user->name,
                    'emailAddress' => $user->emailAddress,
                    'password'     => $user->password
                ]);
        });
    }

    /**
     * @param User $user
     * @return void
     * @throws Throwable
     */
    public function update(User $user): void
    {
        DB::transaction(function () use ($user) {

            $userDTO = $this->userDTO
                ->find($user->id());

            // ドメインモデルのデータをDTOに詰め替えます．
            $userDTO->fill([
                'name'         => $user->name,
                'emailAddress' => $user->emailAddress,
                'password'     => $user->password
            ]);

            $userDTO->save();
        });
    }

    /**
     * @param UserId $userId
     * @return void
     * @throws Throwable
     */
    public function delete(UserId $userId): void
    {
        DB::transaction(function () use ($userId) {

            $userDTO = $this->userDTO
            ->find($userId);

            $userDTO->delete();;
        });
    }
}
