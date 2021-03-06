<?php

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('json_decode', [$this, 'jsonDecode']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('set_active_route', [$this, 'setActiveRoute']),
            new TwigFunction('set_bg_color', [$this, 'setBgColor']),
        ];
    }

    public function setActiveRoute(string $route): string
    {
        $currentRoute = $this->requestStack->getCurrentRequest()->getPathInfo();

        return str_starts_with($currentRoute, $route) ? 'active' : '';
    }

    public function setBgColor($value): string
    {
        $color = '';

        if ($value > 0) {
            $color = '#4edb4e';
        } elseif ($value == 0) {
            $color = '#ffbe33';
        } else {
            $color = '#f72323';
        }

        return 'background-color: '.$color;
    }

    public function jsonDecode($str)
    {
        return json_decode($str, true);
    }
}
