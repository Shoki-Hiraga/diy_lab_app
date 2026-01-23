<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;

class BreadcrumbService
{
    public function generate(): array
    {
        // ✅ ルートが無い状況（例外画面・CLI・Queue 等）をガード
        $currentRoute = Route::current();

        if (!$currentRoute) {
            return [];
        }

        $routeName = $currentRoute->getName();
        $params    = $currentRoute->parameters();

        if (!$routeName) {
            return [];
        }

        $map = config('breadcrumbs');

        if (!isset($map[$routeName])) {
            return [];
        }

        return collect($map[$routeName])->map(function ($item) use ($params) {

            // label 解決
            $label = null;
            if (isset($item['label'])) {
                if (is_callable($item['label'])) {
                    $label = $this->callWithRouteParams($item['label'], $params);
                } else {
                    $label = $item['label'];
                }
            }

            // url 解決
            $url = null;
            if (isset($item['route'])) {
                if (is_callable($item['route'])) {
                    $url = $this->callWithRouteParams($item['route'], $params);
                } else {
                    $url = route($item['route']);
                }
            }

            return [
                'label' => $label,
                'url'   => $url,
            ];
        })->toArray();
    }

    /**
     * クロージャの引数名に応じてルートパラメータを注入
     */
    protected function callWithRouteParams(callable $callback, array $routeParams)
    {
        $reflection = new \ReflectionFunction($callback);

        $args = [];

        foreach ($reflection->getParameters() as $param) {
            $name = $param->getName();

            if (array_key_exists($name, $routeParams)) {
                $args[] = $routeParams[$name];
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                // 落とさないための保険
                $args[] = null;
            }
        }

        return $callback(...$args);
    }
}
