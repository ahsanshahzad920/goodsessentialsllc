<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

trait HandleErrors
{
    private function handleException(Exception $e)
    {
        if ($e instanceof QueryException) {
            return to_route('codes.index')
                ->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        } elseif ($e instanceof ModelNotFoundException) {
            return to_route('codes.index')
                ->withErrors(['error' => 'Model not found: ' . $e->getMessage()]);
        } else {
            return to_route('codes.index')
                ->withErrors(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
        }
    }
}
