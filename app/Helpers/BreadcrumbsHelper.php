<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('generateBreadcrumbs')) {
    function generateBreadcrumbs()
    {
        $breadcrumbs = [];
        $segments = request()->segments();
        $url = '';

        foreach ($segments as $key => $segment) {
            if (preg_match('/^{.*}$/', $segment) || is_numeric($segment) || preg_match('/^PR\d{7,}$/', $segment) || preg_match('/^RQ\d{7,}$/', $segment)) {
                continue;
            }

            $url .= '/' . $segment;
            $fullUrl = url($url);

            $routeName = null;
            foreach (Route::getRoutes() as $route) {
                if ($route->matches(request()->create($url))) {
                    $routeName = $route->getName();
                    break;
                }
            }

            $breadcrumbs[] = [
                'name' => ucwords(str_replace('-', ' ', $segment)),
                'url' => $routeName ? route($routeName) : null,
            ];
        }

        if (!empty($breadcrumbs)) {
            $breadcrumbs[count($breadcrumbs) - 1]['url'] = null;
        }

        return $breadcrumbs;
    }
}
