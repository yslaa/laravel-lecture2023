<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\CustomerChart;
use App\Charts\TownChart;
use App\Charts\SalesChart;
use App\Charts\ItemChart;
use DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->bgcolor = collect([
            '#7158e2',
            '#3ae374',
            '#ff3838',
            "#FF851B",
            "#7FDBFF",
            "#B10DC9",
            "#FFDC00",
            "#001f3f",
            "#39CCCC",
            "#01FF70",
            "#85144b",
            "#F012BE",
            "#3D9970",
            "#111111",
            "#AAAAAA",
        ]);
    }
    public function index()
    {
        Debugbar::info($customer);
        $customer = DB::table('customer')
            ->whereNotNull('title')
            ->groupBy('title')
            ->orderBy('total')
            ->pluck(DB::raw('count(title) as total'), 'title')
            ->all();
        // $customer = asort($customer,SORT_REGULAR );
        // dd($customer);
        $customerChart = new CustomerChart();
        // dd(array_keys($customer));
        $dataset = $customerChart->labels(array_keys($customer));
        // dd($dataset);
        $dataset = $customerChart->dataset(
            'Customer Demographics',
            'pie',
            array_values($customer)
        );
        // dd($customerChart);
        $dataset = $dataset->backgroundColor([
            '#7158e2',
            '#3ae374',
            '#ff3838',
            "#FF851B",
            "#7FDBFF",
            "#B10DC9",
            "#FFDC00",
            "#001f3f",
            "#39CCCC",
            "#01FF70",
            "#85144b",
            "#F012BE",
            "#3D9970",
            "#111111",
            "#AAAAAA",
        ]);
        // dd($customerChart);
        $customerChart->options([
            'responsive' => true,
            'legend' => ['display' => true],
            'tooltips' => ['enabled' => true],
            // 'maintainAspectRatio' =>true,

            // 'title' => 'test',
            'aspectRatio' => 1,
            'scales' => [
                'yAxes' => [
                    [
                        'display' => false,
                        'ticks' => ['beginAtZero' => true],
                        'gridLines' => ['display' => false],
                    ],
                ],
                'xAxes' => [
                    [
                        'categoryPercentage' => 0.8,
                        //'barThickness' => 100,
                        'barPercentage' => 1,
                        'ticks' => ['beginAtZero' => false],
                        'gridLines' => ['display' => false],
                        'display' => true,
                    ],
                ],
            ],
        ]);

        $town = DB::table('customer')
            ->whereNotNull('town')
            ->groupBy('town')
            ->orderBy('town', 'ASC')
            ->pluck(DB::raw('count(town) as total'), 'town')
            ->all();

        $townChart = new TownChart();
        // dd(array_values($customer));
        $dataset = $townChart->labels(array_keys($town));
        // dd($dataset);
        $dataset = $townChart->dataset(
            'town Demographics',
            'bar',
            array_values($town)
        );
        // dd($customerChart);
        $dataset = $dataset->backgroundColor($this->bgcolor);
        // dd($customerChart);
        $townChart->options([
            'responsive' => true,
            'legend' => ['display' => true],
            'tooltips' => ['enabled' => true],
            // 'maintainAspectRatio' =>true,

            // 'title' => 'test',
            'aspectRatio' => 1,
            'scales' => [
                'yAxes' => [
                    'display' => true,
                    'ticks' => ['beginAtZero' => true, 'max' => 100],
                    'min' => 0,
                    'stepSize' => 10,
                    'gridLines' => ['display' => false],
                ],
                'xAxes' => [
                    'categoryPercentage' => 0.8,
                    //'barThickness' => 100,
                    'barPercentage' => 1,
                    'ticks' => ['beginAtZero' => true, 'min' => 0],
                    'gridLines' => ['display' => false],
                    'display' => true,
                ],
            ],
        ]);

        $sales = DB::table('orderinfo AS o')
            ->join('orderline AS ol', 'o.orderinfo_id', '=', 'ol.orderinfo_id')
            ->join('item AS i', 'ol.item_id', '=', 'i.item_id')
            ->orderBy(DB::raw('month(o.date_placed)'), 'ASC')
            ->groupBy('o.date_placed')
            ->pluck(
                DB::raw('sum(ol.quantity * i.sell_price) AS total'),
                DB::raw('monthname(o.date_placed) AS month')
            )
            ->all();
        // dd($sales);

        $salesChart = new SalesChart();
        // dd(array_values($customer));

        $dataset = $salesChart->labels(array_keys($sales));
        // $dataset = $salesChart->labels(['May','June','July']);
        // dd($dataset);
        $dataset = $salesChart->dataset(
            'Monthly sales',
            'line',
            array_values($sales)
        );
        // dd($customerChart);
        $dataset = $dataset->backgroundColor($this->bgcolor);
        $dataset = $dataset->fill(false);

        // dd($customerChart);
        $salesChart->options([
            'responsive' => true,
            'legend' => ['display' => true],
            'tooltips' => ['enabled' => true],
            'aspectRatio' => 1,
            'scaleBeginAtZero' => true,
            'scales' => [
                'yAxes' => [
                    [
                        'display' => true,
                        'type' => 'linear',
                        'ticks' => [
                            'beginAtZero' => true,
                            'autoSkip' => true,
                            'maxTicksLimit' => 20000,
                            'min' => 0,
                            // 'max'=>20000,

                            'stepSize' => 500,
                        ],
                    ],
                    'gridLines' => ['display' => false],
                ],
                'xAxes' => [
                    'categoryPercentage' => 0.8,
                    'barPercentage' => 1,
                    'gridLines' => ['display' => false],
                    'display' => true,
                    'ticks' => [
                        'beginAtZero' => true,
                        'min' => 0,
                        'stepSize' => 10,
                    ],
                ],
            ],
        ]);

        $items = DB::table('orderline AS ol')
            ->join('item AS i', 'ol.item_id', '=', 'i.item_id')
            ->groupBy('i.description')
            ->orderBy('total', 'DESC')
            ->pluck(DB::raw('sum(ol.quantity) AS total'), 'description')

            ->all();
        // dd($items);

        $itemChart = new ItemChart();
        // dd(array_values($customer));

        $dataset = $itemChart->labels(array_keys($items));
        // dd($dataset);
        $dataset = $itemChart->dataset(
            'Item sold',
            'bar',
            array_values($items)
        );
        // dd($customerChart);
        $dataset = $dataset->backgroundColor($this->bgcolor);
        // dd($customerChart);
        $dataset = $dataset->fill(false);
        $itemChart->options([
            'responsive' => true,
            'legend' => ['display' => true],
            'tooltips' => ['enabled' => true],
            'aspectRatio' => 1,

            'scales' => [
                'yAxes' => [
                    'display' => true,
                    'ticks' => ['beginAtZero' => true],
                    'gridLines' => ['display' => false],
                ],
                'xAxes' => [
                    'categoryPercentage' => 0.8,
                    //'barThickness' => 100,
                    'barPercentage' => 1,

                    'gridLines' => ['display' => false],
                    'display' => true,
                    'ticks' => [
                        'beginAtZero' => true,
                        'min' => 0,
                        'stepSize' => 10,
                    ],
                ],
            ],
        ]);

        return view(
            'dashboard.index',
            compact('customerChart', 'townChart', 'salesChart', 'itemChart')
        );
    }
}
