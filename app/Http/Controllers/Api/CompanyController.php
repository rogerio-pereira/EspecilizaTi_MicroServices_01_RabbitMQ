<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Requests\CreateUpdateCompanyRequest;
use App\Jobs\CompanyCreatedJob;
use App\Services\CompanyService;
use App\Services\EvaluationService;

class CompanyController extends Controller
{
    protected $evaluationService;
    protected $companyService;

    public function __construct(EvaluationService $evaluationService, CompanyService $companyService)
    {
        $this->evaluationService = $evaluationService;
        $this->companyService = $companyService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', '');
        $companies = $this->companyService
                        ->getCompanies($filter);

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
        $company = $this->companyService->createNewCompany($request->validated());

        CompanyCreatedJob::dispatch($company->email)
            ->onQueue(env('QUEUE_EMAIL'));

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
        $company = $this->companyService->getCompanyByUuid($uuid);

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
    public function update(CreateUpdateCompanyRequest $request, string $uuid)
    {
        $company = $this->companyService->updateCompany($uuid, $request->validated());

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
        $this->companyService->deleteCompany($uuid);

        return response()->json([], 204);
    }
}
