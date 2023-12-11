<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Infrastructure\Delivery\API;

use App\Codechallenge\Auth\Application\Exceptions\UserAlreadyExistException;
use App\Codechallenge\Auth\Application\Exceptions\UserAlreadyRegisteredException;
use App\Codechallenge\Auth\Application\Service\User\CreateGuestUserService;
use App\Codechallenge\Auth\Application\Service\User\CreateTokenService;
use App\Codechallenge\Auth\Application\Service\User\GetUserService;
use App\Codechallenge\Auth\Application\Service\User\SignUpUserRequest;
use App\Codechallenge\Auth\Application\Service\User\SignUpUserService;
use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Auth\Infrastructure\Domain\Model\SecurityUser;
use App\Codechallenge\Billing\Application\Service\Order\GetUserOrdersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Uid\Uuid;

/**
 * @group User management
 *
 * APIs for managing users
 */
class UsersController extends AbstractController
{
    /**
     * GET api/token.
     *
     * Return a json array containing a new auth token for a new GuestUser
     *
     * @unauthenticated
     *
     * @return JsonResponse the access token
     */
    #[Route('/api/token', name: 'api_get_token', methods: ['GET'])]
    public function token(CreateGuestUserService $createGuestUserService,
        CreateTokenService $createTokenService): JsonResponse
    {
        $user = $createGuestUserService->execute();
        $apiToken = $createTokenService->execute($user->id());

        return new JsonResponse(
            [
              'token' => $apiToken->token(),
            ]
        );
    }

    /**
     * POST api/user/login.
     *
     * Return a json array containing a new auth token for registered user.
     * Checks if the email belongs to a existing domain
     *
     * @bodyParam email string required valid email from existing domain. Example: codechallenge@gmail.com
     *
     * @response scenario=success { "token": "34|0JJwyg6oCkEBnlAGFVOlq5f42SY9u476JSCBVUwT"}
     * @response status=401 scenario="unauthenticated" {"message": "missing or bad credentials"}
     *
     * @return JsonResponse status 200 on success. status 400-500 on error
     */
    #[Route('/api/user/login', name: 'api_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?SecurityUser $securityUser,
        CreateTokenService $createTokenService): JsonResponse
    {
        $apiToken = $createTokenService->execute(new UserId($securityUser->getUserUuid()));

        return $this->json([
            'token' => $apiToken->token(),
        ]);
    }

    /**
     * POST api/user/register.
     *
     * Process a registration request.
     * Checks if the email is valid
     *
     * @bodyParam email string required valid email. Example: codechallenge@gmail.com
     *
     * @response scenario=success
     * @response status=401 scenario="unauthenticated" {"message": "Unauthorized"}
     * @response status=400 scenario="invalid parameters" {"message": "The password is empty."}
     *
     * @return JsonResponse status 200 on success. status 400-500 on error
     */
    #[Route('/api/user/register', name: 'api_register', methods: ['POST'])]
    public function register(#[CurrentUser] ?SecurityUser $securityUser, Request $request,
        SignUpUserService $signUpService): JsonResponse
    {
        $request = $request->getPayload();
        $signUpUserRequest = new SignUpUserRequest(
            $request->get('name'),
            $request->get('email'),
            $request->get('password'),
            $request->get('address')
        );

        try {
            $signUpService->execute(new UserId(new Uuid($securityUser->getUserIdentifier())), $signUpUserRequest);
        } catch (UserAlreadyExistException|UserAlreadyRegisteredException|\InvalidArgumentException $e) {
            return new JsonResponse(
                ['message' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(null, Response::HTTP_OK);
    }

    /**
     * GET api/user.
     *
     * Return a Json array containing the information of the user
     *
     * @response scenario=success {
     *  "name": "Javier",
     *  "email": "javier@smartidea.es",
     *  "address": "Mi dirección",
     *  "registered": true
     * }
     * @response status=401 scenario="unauthenticated" {"message": "Unauthorized"}
     *
     * @return JsonResponse user information: name, email, address, bool registered. status 400-500 on error
     */
    #[Route('/api/user', name: 'get_user', methods: ['GET'])]
    public function get(#[CurrentUser] ?SecurityUser $securityUser, GetUserService $getUserService): JsonResponse
    {
        $user = $getUserService->execute(new UserId($securityUser->getUserUuid()));

        return new JsonResponse(
            [
              'name' => $user->name,
              'email' => $user->email,
              'address' => $user->address,
              'registered' => $user->registered,
            ],
            Response::HTTP_OK
        );
    }

    /**
     * GET api/user/orders.
     *
     * Return a Json array containing the orders of the user
     *
     * @response scenario=success {
     *  "orders": [
     *    {
     *        "id": 6,
     *        "user_id": 104,
     *        "address": "Mi dirección",
     *        "total": 35,
     *        "created_at": "2023-04-10T03:31:10.000000Z",
     *    }
     *  ]
     * }
     * @response status=401 scenario="unauthenticated" {"message": "Unauthorized"}
     *
     * @return JsonResponse orders of the user. status 400-500 on error
     */
    #[Route('/api/user/orders', name: 'get_orders', methods: ['GET'])]
    public function orders(#[CurrentUser] ?SecurityUser $securityUser, GetUserOrdersService $getUserOrdersService): JsonResponse
    {
        $orders = $getUserOrdersService->execute(new UserId(new Uuid($securityUser->getUserIdentifier())));
        $jsonArray = [];
        $i = 0;
        foreach ($orders as $order) {
            $jsonArray['orders'][$i] =
            [
              'id' => $order->id()->id(),
              'user_id' => $order->userId()->id(),
              'address' => $order->address(),
              'total' => $order->orderTotal(),
              'created_at' => $order->createdAt(),
            ];
            ++$i;
        }

        return new JsonResponse($jsonArray);
    }
}
