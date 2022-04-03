<?php

/**
 * IItems
 * 
 * An interface for item interaction in db
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   2021 Alabian Solutions Limited
 * @version 	1.0 => August 2021
 * @link        alabiansolutions.com
 */
interface IItems
{
    public function isIdValid(int $id): bool;
    public function create(array $data): int;
    public function update(array $data, int $id): void;
    public function delete(int $id): void;
    public function getInfo(int $id): array;
    public function getIds(array $criteria = [], int $limit = null, int $start = null, bool $latest): array;
}
