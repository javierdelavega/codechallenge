<?php

namespace App\Codechallenge\Auth\Domain\Model;

/**
 * Hasher for the User password
 */
interface PasswordHashingInterface
{
  /**
   * Verifies if the given plain password is valid for the given user
   * 
   * @param User $user
   * @param string $plainPassword
   * @return bool
   */

   public function verify($user, $plainPassword);

   /**
    * Hashes the given plain password for the given user
    * 
    * @param User $user
    * @param string $plainPassword
    * @return string
    */

   public function hash($user, $plainPassword);
}