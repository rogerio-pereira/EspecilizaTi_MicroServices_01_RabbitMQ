<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Requests\CreateUpdateCompanyRequest;
use App\Services\EvaluationService;

class CompanyController extends Controller
{
    protected $repository;
    protected $evaluationService;

    public function __construct(Company $model, EvaluationService $service)
    {
        $this->repository = $model;
        $this->evaluationService = $service;
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
    public function show(string $uuid)
    {
        $company = $this->repository->where('uuid', $uuid)->firstOrFail();

        $evaluations = $this->evaluationService->getEvaluationsCompany($uuid);

        return (new CompanyResource($company))
                        ->additional([
                            'evaluations' => json_decode($evaluations)
                        ]);
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
