<?php

namespace App\Services;

use App\Models\Company;

class CompanyService
{
    protected $repository;

    public function __construct(Company $model)
    {
        $this->repository = $model;
    }

    public function getCompanies(string $filter = '')
    {
        return $this->repository
                    ->getCompanies($filter);
    }

    public function createNewCompany(array $data)
    {
        return $this->repository
                    ->create($data);
    }

    public function getCompanyByUuid(string $uuid = null)
    {
        return $this->repository
                    ->where('uuid', $uuid)
                    ->firstOrFail();
    }

    public function updateCompany(string $uuid, array $data)
    {
        $company = $this->getCompanyByUuid($uuid);
        $company->update($data);

        return $company;
    }

    public function deleteCompany(string $uuid)
    {
        $company = $this->getCompanyByUuid($uuid);
        
        return $company->delete();
    }
}