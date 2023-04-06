<?php

namespace App\Controller\Form;

use App\Entity\Res;
use App\Entity\User;
use App\Model\UserModel;



class FormUserChangePass extends FormController
{

    private UserModel $userModel;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }

    public function treatFormChangePass(User $user, string $old, string $new, string $new_confirm): Res
    {
        $res = new Res();

        // TODO : Faire un code plus propre
        if ($this->checkOldPass($user, $old)) {
            if ($this->checkNewPassMatch($new, $new_confirm)) {
                if ($this->checkNewPassFormat($new)) {
                    $user->setPass($this->hashPassword($new));
                    $this->userModel->updateUser($user);
                    $res->ok('user-change-pass', 'user-change-pass-ok-updated', null);
                } else {
                    $res->ko('user-change-pass', 'user-change-pass-ko-wrong-format', null);
                }
            } else {
                $res->ko('user-change-pass', 'user-change-pass-ko-pass-dont-match', null);
            }
        } else {
            $res->ko('user-change-pass', 'user-change-pass-ko-old-pass-incorrect', null);
        }

        return $res;
    }

    protected function checkOldPass(User $user, string $old): bool
    {
        return $user->getPass() === $this->hashPassword($old);
    }

    protected function checkNewPassMatch(string $new, string $new_confirm): bool
    {
        return $new === $new_confirm;
    }

    protected function checkNewPassFormat(string $new): bool
    {
        // TODO : Ajouter un check des champs et de format (regex)
        return true;
    }


}