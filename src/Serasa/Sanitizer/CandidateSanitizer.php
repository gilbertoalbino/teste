<?php

namespace Serasa\Sanitizer;

/**
 * Class CandidateSanitizer cleans up the user input.
 * @package Serasa\Sanitizer
 */
class CandidateSanitizer
{
    public $data;
    public $action;

    /**
     * Parses the request data provided and removes not fillable keys.
     *
     * @param array $data
     * @return CandidateSanitizer
     * @throws \Exception
     */
    public static function factory($data = [])
    {
        if (empty($data)) {
            throw new \Exception(
                sprintf('%s requires request data. None provided', __METHOD__)
            );
        }

        $self = new self();

        /**
         * Fillables must match the db table columns
         * mandatory on insert or update actions.
         */
        $fillable = [
            'fullname', 'birthdate', 'resume_score', 'interview_score', 'test_score'
        ];

        /**
         * As basic as that of removing unwanted data, tags, etc.
         */
        foreach ($data as $key => $value) {
            if (!in_array($key, $fillable)) continue;
            $self->data[$key] = trim(filter_var($value, FILTER_SANITIZE_STRING));
        }

        return $self;
    }

    /**
     * Utility helper for usage on create action.
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->data[$name];
    }

    /**
     * Utility helper for usage on update action.
     * 
     * @return mixed
     */
    public function getAll()
    {
        return $this->data;
    }
}