<?php

namespace App\Entity;

class Result
{
    private bool $err;
    private string $msg;
    private mixed $result;

    /** --------------------------------------------- Getters & Setters --------------------------------------------- */
    /**
     * @return bool
     */
    public function isErr(): bool
    {
        return $this->err;
    }

    /**
     * @param bool $err
     */
    public function setErr(bool $err): void
    {
        $this->err = $err;
    }

    /**
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * @param string $msg
     */
    public function setMsg(string $msg): void
    {
        $this->msg = $msg;
    }

    /**
     * @return mixed
     */
    public function getResult(): mixed
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult(mixed $result): void
    {
        $this->result = $result;
    }

}


