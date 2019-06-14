<?php

namespace LBHurtado\Ballot\Actions;

use LBHurtado\Tactician\Classes\ActionAbstract;
use LBHurtado\Tactician\Contracts\ActionInterface;
use LBHurtado\Ballot\Requests\UpdateBallotCandidateRequest;
use LBHurtado\Ballot\Actions\Commands\UpdateBallotCandidateCommand;
use LBHurtado\Ballot\Actions\Handlers\UpdateBallotCandidateHandler;
use LBHurtado\Ballot\Actions\Responders\UpdateBallotCandidateResponder;

class UpdateBallotCandidateAction extends ActionAbstract implements ActionInterface
{
    protected $command = UpdateBallotCandidateCommand::class;

    protected $handler = UpdateBallotCandidateHandler::class;

    protected $middlewares = [
        UpdateBallotCandidateResponder::class,
    ];

    public function __construct(
        \Joselfonseca\LaravelTactician\CommandBusInterface $bus, 
        \Opis\Events\EventDispatcher $dispatcher, 
        UpdateBallotCandidateRequest $request)
    {
        parent::__construct($bus, $dispatcher, $request);
    }

    public function getFields(): array
    {
        return optional(config('tactician.fields.update'), function ($mapping) {
            return array_keys($mapping);
        });
    }
}