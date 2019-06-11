<?php

use Illuminate\Support\Facades\Route;
use LBHurtado\Ballot\Actions\UpdateBallotCandidateAction;

Route::prefix('api')
    ->middleware('api')
    ->match(['post'], 'ballot/candidate', UpdateBallotCandidateAction::class);