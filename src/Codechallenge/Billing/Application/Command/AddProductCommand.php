<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Command;

use App\Codechallenge\Auth\Domain\Model\UserId;
use App\Codechallenge\Billing\Application\Service\Cart\AddProductRequest;
use App\Codechallenge\Shared\Domain\Bus\Command\Command;

final readonly class AddProductCommand implements Command
{
  public function __construct(
    public UserId $userId,
    public AddProductRequest $request,
  ){ }
}