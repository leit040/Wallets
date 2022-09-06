<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportRequest;
use App\Http\Resources\reportResource;
use App\Repositories\ReportRepository;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function report(ReportRequest $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $repo = new ReportRepository();
        return reportResource::collection($repo->generateReport($request->get('dateFrom'), $request->get('dateTo')));
    }
}
