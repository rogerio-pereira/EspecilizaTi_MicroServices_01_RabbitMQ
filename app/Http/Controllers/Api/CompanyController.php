<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Requests\CreateUpdateCompanyRequest;

class CompanyController extends Controller
{
    protected $repository;

    public function __construct(Company $model)
    {
        $this->repository = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', '');
        $companies = $this->repository->getCompanies($filter);

        return CompanyResource::collection($companies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUpdateCompanyRequest $request)
    {
        $company = $this->repository->create($request->validated());

        return new CompanyResource($company);
    }

    /**
     * Display the specified resource.
     *
     * @param  uuid  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $company = $this->repository->where('uuid', $uuid)->firstOrFail();

        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  uuid  $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(CreateUpdateCompanyRequest $request, $uuid)
    {
        $company = $this->repository->where('uuid', $uuid)->firstOrFail();
        $company->update($request->validated());

        return new CompanyResource($company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $company = $this->repository->where('uuid', $uuid)->firstOrFail();
        $company->delete();

        return response()->json([], 204);
    }
}
