<?php

namespace App\Services;

use App\Services\Traits\ConsumeExternalService;

class EvaluationService
{
    use ConsumeExternalService;

    protected $url;
    protected $token;

    public function __construct()
    {
        $this->url = config('services.micro_02.url');
        $this->token = config('services.micro_02.token');
    }

    public function getEvaluationsCompany(string $company)
    {
        $response = $this->request('get', "/evaluations/{$company}");   //Method request belongs to ConsumeExternalService Trait

        return $response->body();
    }
}