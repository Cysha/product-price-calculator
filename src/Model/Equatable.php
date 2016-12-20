<?php

namespace Model;

interface Equatable
{
    /**
     * @param object $object
     *
     * @return bool
     */
    public function equals($object);
}
