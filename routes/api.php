<?php

use Illuminate\Support\Facades\Route;
use LBHurtado\Ballot\Actions\{UpdateBallotCandidateAction, ReadBallotCandidateAction};

Route::prefix('api')
    ->middleware('api')
    ->match(['post'], 'ballot/candidate', UpdateBallotCandidateAction::class);

Route::prefix('api')
    ->middleware('api')
    ->match(['get'], 'ballot/candidate', ReadBallotCandidateAction::class);