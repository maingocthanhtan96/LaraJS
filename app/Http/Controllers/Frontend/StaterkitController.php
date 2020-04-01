<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaterkitController extends Controller
{
    // 1 Column
    public function column_1()
    {
        $breadcrumbs = [
            ['link' => 'sk-layout-2-columns', 'name' => 'Home'],
            ['link' => '', 'name' => 'Starter Kit'],
            ['name' => '1 Column'],
        ];
        return view('frontend.pages.sk-layout-1-column', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    // 2 Columns
    public function columns_2()
    {
        $breadcrumbs = [
            ['link' => 'sk-layout-2-columns', 'name' => 'Home'],
            ['link' => '', 'name' => 'Starter Kit'],
            ['name' => '2 Columns'],
        ];
        return view('frontend.pages.sk-layout-2-columns', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    // Fixed Navbar
    public function fixed_navbar()
    {
        $pageConfigs = [
            'navbarType' => 'sticky',
        ];

        $breadcrumbs = [
            ['link' => 'sk-layout-2-columns', 'name' => 'Home'],
            ['link' => '', 'name' => 'Starter Kit'],
            ['name' => 'Fixed Navbar'],
        ];
        return view('frontend.pages.sk-layout-fixed-navbar', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    // Floating Navbar
    public function floating_navbar()
    {
        $breadcrumbs = [
            ['link' => 'sk-layout-2-columns', 'name' => 'Home'],
            ['link' => '', 'name' => 'Starter Kit'],
            ['name' => 'Floating Navbar'],
        ];
        return view('frontend.pages.sk-layout-floating-navbar', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    // Fixed Layout
    public function fixed_layout()
    {
        $pageConfigs = [
            'navbarType' => 'sticky',
            'footerType' => 'sticky',
        ];

        $breadcrumbs = [
            ['link' => 'sk-layout-2-columns', 'name' => 'Home'],
            ['link' => '', 'name' => 'Starter Kit'],
            ['name' => 'Fixed Layout'],
        ];
        return view('frontend.pages.sk-layout-fixed', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    // Static Layout
    public function static_layout()
    {
        $pageConfigs = [
            'navbarType' => 'static',
            'menuType' => 'static',
        ];
        $breadcrumbs = [
            ['link' => 'sk-layout-2-columns', 'name' => 'Home'],
            ['link' => '', 'name' => 'Starter Kit'],
            ['name' => 'Static Layout'],
        ];
        return view('frontend.pages.sk-layout-static', [
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}
