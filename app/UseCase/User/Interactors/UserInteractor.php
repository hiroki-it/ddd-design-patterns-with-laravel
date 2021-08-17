<?php

declare(strict_types=1);

namespace App\UseCase\User\Interactors;

use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\UserAuthenticationCode;
use App\Domain\User\ValueObjects\UserEmailAddress;
use App\Domain\User\ValueObjects\UserName;
use App\Domain\User\ValueObjects\UserPassword;
use App\Domain\User\ValueObjects\UserPhoneNumber;
use App\UseCase\User\InputBoundaries\UserInputBoundary;
use App\UseCase\User\Requests\UserCreateRequest;
use App\UseCase\User\Responses\UserCreateResponse;
use App\UseCase\User\Services\UserSmsAuthenticationService;

/**
* ユーザユースケースクラス
*/
final class UserInteractor implements UserInputBoundary
{
    /**
     * リポジトリクラス
     *
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**

     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * ユーザを作成します
     *
     * @param UserCreateRequest $request
     * @return UserCreateResponse
     */
    public function createUser(UserCreateRequest $request): UserCreateResponse
    {
        $SmsAuthenticationService = new UserSmsAuthenticationService(config('sms.sns'), auth()->user());

        $user = new User(
            null,
            new UserName($request->name),
            new UserEmailAddress($request->emailAddress),
            new UserPhoneNumber($request->phoneNumber),
            new UserPassword($request->password),
            new UserAuthenticationCode($SmsAuthenticationService->generateAuthenticationCode)
        );

        $this->userRepository->create($user);

        // ここにResponse生成処理
    }
}
