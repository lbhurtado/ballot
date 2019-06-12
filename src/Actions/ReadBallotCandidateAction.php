<?php

namespace LBHurtado\Ballot\Actions;

use LBHurtado\Tactician\Classes\ActionAbstract;
use LBHurtado\Tactician\Contracts\ActionInterface;
use LBHurtado\Ballot\Actions\Commands\ReadBallotCandidateCommand;
use LBHurtado\Ballot\Actions\Handlers\ReadBallotCandidateHandler;
use LBHurtado\Ballot\Actions\Validators\ReadBallotCandidateValidator;
use LBHurtado\Ballot\Actions\Responders\ReadBallotCandidateResponder;

class ReadBallotCandidateAction extends ActionAbstract implements ActionInterface
{
    protected $command = ReadBallotCandidateCommand::class;

    protected $handler = ReadBallotCandidateHandler::class;

    protected $middlewares = [
        ReadBallotCandidateValidator::class,
        ReadBallotCandidateResponder::class,
    ];

    public function getFields(): array
    {
        return optional(config('tactician.fields.read'), function ($mapping) {
            return array_keys($mapping);
        });
    }
}