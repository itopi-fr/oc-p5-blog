<?php

namespace App\Controller\Form;

use App\Entity\Res;
use App\Entity\User;
use App\Model\UserModel;

/**
 * Class FormUserChangePass - Manage the user password change form.
 */
class FormUserChangePass extends FormController
{
    /**
     * @var UserModel
     */
    private UserModel $userModel;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }


    /**
     * Treats the form to change the user password.
     * Checks if the old password is correct, if the new password and the new password confirmation match,
     * and if the new password format is correct.
     *
     * @param User $user - The user to update.
     * @param string $old - The old password.
     * @param string $new - The new password.
     * @param string $new_confirm - The new password confirmation.
     * @param bool $reset - If true, the old password is not checked.
     * @return Res
     */
    public function treatFormChangePass(
        User $user,
        string $old,
        string $new,
        string $new_confirm,
        bool $reset = false
    ): Res {
        $res = new Res();

        if ($reset === false) {
            // Check if old password is correct.
            if ($this->checkOldPass($user, $old) === false) {
                $res->ko('user-change-pass', 'user-change-pass-ko-old-pass-incorrect');
                return $res;
            }
        }

        // Check if new password and new password confirmation match.
        if ($this->checkNewPassMatch($new, $new_confirm) === false) {
            $res->ko('user-change-pass', 'user-change-pass-ko-pass-dont-match');
            return $res;
        }

        // Check if new password format is correct.
        if ($this->checkPasswordFormat($new) === false) {
            $res->ko('user-change-pass', 'user-change-pass-ko-wrong-format');
            return $res;
        }

        // If all checks are ok, update the user password.
        $user->setPass($this->hashPassword($new));
        $this->userModel->updateUser($user);
        $res->ok('user-change-pass', 'user-change-pass-ok-updated');
        return $res;
    }


    /**
     * Check if old password is correct
     *
     * @param User $user - The user to update.
     * @param string $old - The old password.
     * @return bool
     */
    protected function checkOldPass(User $user, string $old): bool
    {
        return $user->getPass() === $this->hashPassword($old);
    }


    /**
     * Check if new password and new password confirmation match
     *
     * @param string $new - The new password.
     * @param string $new_confirm - The new password confirmation.
     * @return bool
     */
    protected function checkNewPassMatch(string $new, string $new_confirm): bool
    {
        return $new === $new_confirm;
    }


}
