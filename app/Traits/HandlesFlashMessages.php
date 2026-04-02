<?php

namespace App\Traits;

use Illuminate\Support\Facades\Session;

trait HandlesFlashMessages
{
    protected function flashSuccess($message)
    {
        Session::flash('success', $message);
    }

    protected function flashError($message)
    {
        Session::flash('error', $message);
    }

    protected function flashWarning($message)
    {
        Session::flash('warning', $message);
    }

    protected function flashInfo($message)
    {
        Session::flash('info', $message);
    }

    protected function hasFlashMessage()
    {
        return Session::has('success') || Session::has('error') || Session::has('warning') || Session::has('info');
    }

    protected function getFlashMessage()
    {
        if (Session::has('success')) {
            return ['message' => Session::get('success'), 'type' => 'success'];
        }
        if (Session::has('error')) {
            return ['message' => Session::get('error'), 'type' => 'error'];
        }
        if (Session::has('warning')) {
            return ['message' => Session::get('warning'), 'type' => 'warning'];
        }
        if (Session::has('info')) {
            return ['message' => Session::get('info'), 'type' => 'info'];
        }

        return null;
    }
}
