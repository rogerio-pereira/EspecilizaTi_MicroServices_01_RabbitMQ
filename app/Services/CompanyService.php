<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

    public function createNewCompany(array $data, UploadedFile $image)
    {
        $path = $this->uploadImage($image);
        $data['image'] = $path;

        return $this->repository
                    ->create($data);
    }

    public function getCompanyByUuid(string $uuid = null)
    {
        return $this->repository
                    ->where('uuid', $uuid)
                    ->firstOrFail();
    }

    public function updateCompany(string $uuid, array $data, UploadedFile $image = null)
    {
        $company = $this->getCompanyByUuid($uuid);

        if(isset($image)) {
            if(Storage::exists($company->image))
                Storage::delete($company->image);

            $path = $this->uploadImage($image);
            $data['image'] = $path;
        }

        $company->update($data);

        return $company;
    }

    public function deleteCompany(string $uuid)
    {
        $company = $this->getCompanyByUuid($uuid);

        if(Storage::exists($company->image))
            Storage::delete($company->image);
        
        return $company->delete();
    }

    private function uploadImage(UploadedFile $image)
    {
        return $image->store('companies');
    }
}