<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function reactPage(string $page, array $props = [])
    {
        if (! array_key_exists('csrf', $props)) {
            $props['csrf'] = csrf_token();
        }

        return view('app', [
            'page' => $page,
            'props' => $props,
        ]);
    }
}
