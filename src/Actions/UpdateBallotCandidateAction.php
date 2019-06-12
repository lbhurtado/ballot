<?php

namespace LBHurtado\Ballot\Actions;

use LBHurtado\Tactician\Classes\ActionAbstract;
use LBHurtado\Tactician\Contracts\ActionInterface;
use LBHurtado\Ballot\Actions\Commands\UpdateBallotCandidateCommand;
use LBHurtado\Ballot\Actions\Handlers\UpdateBallotCandidateHandler;
use LBHurtado\Ballot\Actions\Validators\UpdateBallotCandidateValidator;
use LBHurtado\Ballot\Actions\Responders\UpdateBallotCandidateResponder;

class UpdateBallotCandidateAction extends ActionAbstract implements ActionInterface
{
    protected $command = UpdateBallotCandidateCommand::class;

    protected $handler = UpdateBallotCandidateHandler::class;

    protected $middlewares = [
        UpdateBallotCandidateValidator::class,
        UpdateBallotCandidateResponder::class,
    ];

    public function getFields(): array
    {
        return optional(config('tactician.fields.update'), function ($mapping) {
            return array_keys($mapping);
        });
    }
}