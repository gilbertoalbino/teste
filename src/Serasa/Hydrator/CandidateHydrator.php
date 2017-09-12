<?php

namespace Serasa\Hydrator;

/**
 * Class Candidate actually is used to sum the scores.
 *
 * @package Serasa\Hydrator
 */
class CandidateHydrator
{
    public $id;
    public $fullname;
    public $resume_score;
    public $interview_score;
    public $test_score;
    public $score;
    public $created_at;
    public $updated_at;

    public function __construct()
    {
        $this->score = (
            $this->resume_score +
            $this->interview_score +
            $this->test_score
        );
    }
}