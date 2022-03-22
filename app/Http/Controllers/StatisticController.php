<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Category;
use App\Models\Federation;

class StatisticController extends Controller
{
    public function index()
    {
        $federations = Federation::count();

        $entity = Entity::select('type', 'edugain', 'hfd', 'rs', 'cocov1', 'sirtfi')->get();
        $entities = $entity->count();
        $edugain = $entity->filter(fn ($e) => $e['edugain'] == 1)->count();
        $hfd = $entity->filter(fn ($e) => $e['hfd'] == 1)->count();
        $rs = $entity->filter(fn ($e) => $e['rs'] == 1)->count();
        $cocov1 = $entity->filter(fn ($e) => $e['cocov1'] == 1)->count();
        $sirtfi = $entity->filter(fn ($e) => $e['sirtfi'] == 1)->count();

        $idp = $entity->filter(fn ($e) => $e['type'] == 'idp');
        $idps = $idp->count();
        $idps_hfd = $idp->filter(fn ($e) => $e['hfd'] == 1)->count();
        $idps_edugain = $idp->filter(fn ($e) => $e['edugain'] == 1)->count();
        $idps_rs = $idp->filter(fn ($e) => $e['rs'] == 1)->count();
        $idps_cocov1 = $idp->filter(fn ($e) => $e['cocov1'] == 1)->count();
        $idps_sirtfi = $idp->filter(fn ($e) => $e['sirtfi'] == 1)->count();

        $categories = Category::select('name')->withCount('entities as count')->get();
        foreach ($categories as $c) $idp_category[$c->name] = $c->count;

        $sp = $entity->filter(fn ($e) => $e['type'] == 'sp');
        $sps = $sp->count();
        $sps_edugain = $sp->filter(fn ($e) => $e['edugain'] == 1)->count();
        $sps_rs = $sp->filter(fn ($e) => $e['rs'] == 1)->count();
        $sps_cocov1 = $sp->filter(fn ($e) => $e['cocov1'] == 1)->count();
        $sps_sirtfi = $sp->filter(fn ($e) => $e['sirtfi'] == 1)->count();

        return json_encode([
            'federations' => [
                'all' => $federations
            ],
            'entities' => [
                'all' => $entities,
                'edugain' => $edugain,
                'hfd' => $hfd,
                'rs' => $rs,
                'cocov1' => $cocov1,
                'sirtfi' => $sirtfi,
                'idp' => [
                    'all' => $idps,
                    'category' => $idp_category,
                    'hfd' => $idps_hfd,
                    'edugain' => $idps_edugain,
                    'rs' => $idps_rs,
                    'cocov1' => $idps_cocov1,
                    'sirtfi' => $idps_sirtfi
                ],
                'sp' => [
                    'all' => $sps,
                    'edugain' => $sps_edugain,
                    'rs' => $sps_rs,
                    'cocov1' => $sps_cocov1,
                    'sirtfi' => $sps_sirtfi
                ]
            ]
        ]);
    }
}
