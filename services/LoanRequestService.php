<?php

namespace app\services;

use app\forms\CreateLoanRequestForm;
use app\models\LoanRequest;

class LoanRequestService
{
    public function create(CreateLoanRequestForm $form): ?LoanRequest
    {
        if (!$form->validate()) {
            return null;
        }

        $hasApprovedRequest = LoanRequest::find()
            ->where([
                'user_id' => $form->user_id,
                'status' => LoanRequest::STATUS_APPROVED,
            ])
            ->exists();

        if ($hasApprovedRequest) {
            return null;
        }

        $request = new LoanRequest();
        $request->user_id = $form->user_id;
        $request->amount = $form->amount;
        $request->term = $form->term;
        $request->status = LoanRequest::STATUS_PENDING;

        return $request->save(false) ? $request : null;
    }
}
