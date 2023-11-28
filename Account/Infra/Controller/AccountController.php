<?php

namespace Account\Infra\Controller;

use Account\Application\UseCase\DecreaseBalance;
use Account\Application\UseCase\DTO\DecreaseBalanceInput;
use Account\Application\UseCase\DTO\IncreaseBalanceInput;
use Account\Application\UseCase\DTO\SignupInput;
use Account\Application\UseCase\GetAccount;
use Account\Application\UseCase\IncreaseBalance;
use Account\Application\UseCase\Signup;
use Account\Domain\AccountType;
use Account\Infra\Database\MySqlPromiseAdapter;
use Account\Infra\Repository\AccountRepositoryDatabase;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    public function create(): JsonResponse
    {
        $request = $this->request;
        CreateAccountValidator::validate($this->request);
        $payload = $request->getPayload();
        $input = new SignupInput(
            firstName: $payload->get('name'),
            lastName: $payload->get('lastName'),
            document: $payload->get('document'),
            email: $payload->get('email'),
            password: $payload->get('password'),
            type: AccountType::from($payload->get('type'))
        );
        try {
            $connection = new MySqlPromiseAdapter();
            $accountRepository = new AccountRepositoryDatabase($connection);
            $signup = new Signup($accountRepository);
            $output = $signup->execute($input);
        } catch (Exception $e) {
            return new JsonResponse(["Error" => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse($output, Response::HTTP_CREATED);
    }

    /**
     * @throws Exception
     */
    public function increaseBalance(int $id): JsonResponse
    {
        IncreaseBalanceValidator::validate($this->request);
        $connection = new MySqlPromiseAdapter();
        $accountRepository = new AccountRepositoryDatabase($connection);
        $increaseBalance = new IncreaseBalance($accountRepository);
        $payload = $this->request->getPayload();
        $input = new IncreaseBalanceInput(
            accountId: $id,
            amount: $payload->get('amount')
        );
        try {
            $increaseBalanceResponse =  $increaseBalance->execute($input);
        } catch (Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse($increaseBalanceResponse, Response::HTTP_OK);
    }

    /**
     * @throws Exception
     */
    public function decreaseBalance(int $id): JsonResponse
    {
        DecreaseBalanceValidator::validate($this->request);
        $connection = new MySqlPromiseAdapter();
        $accountRepository = new AccountRepositoryDatabase($connection);
        $decreaseBalance = new DecreaseBalance($accountRepository);
        $payload = $this->request->getPayload();
        $input = new DecreaseBalanceInput(
            accountId: $id,
            amount: $payload->get('amount')
        );
        try {
            $decreaseBalanceOutput =  $decreaseBalance->execute($input);
        } catch (Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse($decreaseBalanceOutput, Response::HTTP_OK);
    }

    public function getAccount(int $id): JsonResponse
    {
        $connection = new MySqlPromiseAdapter();
        $accountRepository = new AccountRepositoryDatabase($connection);
        $getAccount = new GetAccount($accountRepository);
        try {
            $account =  $getAccount->execute($id);
        } catch (Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse($account, Response::HTTP_OK);
    }
}