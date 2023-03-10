<?php

namespace App\Entity;

class Res
{
    private bool $err;
    private array $msg;
    private array $result;

    public function __construct()
    {
        $this->err = false;
        $this->msg = [];
        $this->result = [];
    }

    public function ko(string $type, string $msg)
    {
        $this->err = true;
        $this->msg[$type] = $msg;
        $this->result[$type] = null;
        return $this;
    }

    public function ok(string $type, string $msg, mixed $result)
    {
        $this->err = false;
        $this->msg[$type] = $msg;
        $this->result[$type] = $result;
        return $this;
    }

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
     * @return array
     */
    public function getMsg(): array
    {
        return $this->msg;
    }

    /**
     * @param array $msg
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


