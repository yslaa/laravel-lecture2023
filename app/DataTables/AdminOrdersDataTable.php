<?php

namespace App\DataTables;

use App\Models\Order;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Barryvdh\Debugbar\Facade as DebugBar;
use DB;

class AdminOrdersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->collection($query)
            ->addColumn('action',  function($row){
                $actionBtn = '<a href="' . route('admin.orderDetails', $row->orderinfo_id) . '"  class="btn details btn-primary">Details</a>';
                // $actionBtn = '<a href="#!"  class="btn details btn-primary">Details</a>';
               
                return $actionBtn;
            })
            ->addColumn('total', function ($order) {
                // DebugBar::info($row);
                return number_format($order->items->map(function($item) {
                    return  $item->pivot->quantity * $item->sell_price;
                })->sum(),2);
            })
            ->addColumn('items', function ($order) {
                return  $order->items->map(function($item) {
                    return $item->description;
                })->implode('<br />');
            })->rawColumns(['action','items']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\AdminOrder $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        // $orders = DB::table('customer as c')->join('orderinfo as o','o.customer_id', '=', 'c.customer_id')
        // ->join('orderline as ol','o.orderinfo_id', '=', 'ol.orderinfo_id')
        // ->join('item as i','ol.item_id', '=', 'i.item_id')
        // ->select('o.orderinfo_id','c.fname', 'c.lname', 'c.addressline', 'o.date_placed', 'o.status', DB::raw("SUM(ol.quantity * i.sell_price) as total"))
        // ->groupBy('o.orderinfo_id', 'o.date_placed','o.orderinfo_id','c.fname', 'c.lname', 'c.addressline', 'o.status')->get();
        $orders = Order::with(['customer','items'])->whereHas('items', function($query) {
            $query->where('description', 'LIKE', '%nike%');
        })->get();
        DebugBar::info($orders);
        return $orders;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('adminorders-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('orderinfo_id'),
            Column::make('customer.fname')->title('first name'),
            Column::make('customer.lname')->title('last name'),
            Column::make('customer.addressline')->title('address'),
            Column::make('date_placed')->title('date ordered'),
            Column::make('total'),
            Column::make('status'),
            Column::make('items'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'AdminOrders_' . date('YmdHis');
    }
}