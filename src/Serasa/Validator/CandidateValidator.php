<?php

namespace Serasa\Validator;

/**
 * Class CandidateValidator checks if any data inconsistency is provided.
 * @package Serasa\Validator
 */
class CandidateValidator
{
    private $errors = [];

    const IS_INSERT = true;
    const IS_UPDATE = false;

    /**
     * Parses the user input and returns an error array if any is found.
     *
     * @param array $data
     * @param bool $isInsert
     * @return CandidateValidator
     * @throws \Exception
     */
    public static function factory($data = [], $isInsert = true)
    {

        if (empty($data)) {
            throw new \Exception(
                sprintf('%s requires request data. None provided', __METHOD__)
            );
        }

        $self = new self();

        $errors = [];

        /**
         * Validations mandatory upon create action.
         */
        if ((!isset($data['fullname']) || empty($data['fullname'])) && $isInsert) {
            $errors['fullname'] = 'You must provide a fullname for the candidate.';
        }

        if ((!isset($data['birthdate']) || empty($data['birthdate'])) && $isInsert) {
            $errors['birthdate'] = 'You must provide a birthdate for the candidate.';
        }

        /**
         * Validations not mandatory upon update action.
         */
        if (isset($data['fullname']) && !preg_match('/\s+/', $data['fullname'])) {
            $errors['fullname'] = 'You must provide a valid fullname for the candidate.';
        }

        if (isset($data['birthdate']) && (date('Y-m-d', strtotime($data['birthdate'])) == date($data['birthdate'])) == false) {
            $errors['birthdate1'] = 'You must provide a bithdate in YYYY-MM-DD format.';
        }

        /**
         * Utility validation for range score between 0 and 4
         */
        $validateScore = function ($score) {
            return filter_var(
                $score,
                FILTER_VALIDATE_INT,
                array(
                    'options' => array(
                        'min_range' => 0,
                        'max_range' => 4
                    )
                )
            );
        };

        $scoreMessage = 'You must provide an integer value range between 1-4.';

        if (isset($data['resume_score']) && !$validateScore($data['resume_score'])) {
            $errors['resume_score'] = $scoreMessage;
        }

        if (isset($data['interview_score']) && !$validateScore($data['interview_score'])) {
            $errors['interview_score'] = $errors['resume_score'] = $scoreMessage;;
        }

        if (isset($data['test_score']) && !$validateScore($data['test_score'])) {
            $errors['test_score'] = $errors['resume_score'] = $scoreMessage;;
        }

        $self->errors = $errors;

        return $self;
    }

    /**
     * Returns the found errors that may be used for decoration.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

}