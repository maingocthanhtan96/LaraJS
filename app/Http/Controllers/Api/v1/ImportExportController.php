<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\MailResetPasswordNotification;
use App\Services\ImportService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImportExportController extends Controller
{
    /**
     * @var ImportService
     */
    protected $importService;

    /**
     * @param ImportService $importService
     */
    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    public function import(ImportRequest $request)
    {
        if ($request->validator->fails()) {
            return redirect()->back()->withErrors($request->validator);
        }

        try {
            $import = $this->importService->import();

            if($import != null) {
                if($import[0] == 'no-finger-leave') {
                    return redirect()->back()->with(['error_vt' => $import[1].' '.trans('message.nofingerprint_at').' '. $import[2]]);
                }
            }

            if($request->notify)
            {
                event(new ImportExcel(1));
            }

        }  catch (\Exception $ex) {
            return redirect()->back()->with('alert_error', $ex->getMessage());
        }

        return redirect()->back()->with('alert_success', trans('message.import.success'));
    }
}
