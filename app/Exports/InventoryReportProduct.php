<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventoryReportProduct implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;
    public function __construct($data)
    {
        $this->data     = $data;
    }

    public function view() : view
    {
        $warehouse_selected  = $this->data["warehouse_selected"];
        $aux = "";
        if($warehouse_selected){
            $aux = "warehouse";
        }else{
            $aux = "warehouses";
        }

        return view('admin.reports.inventories.products.excel',
        [
            'productos'             => $this->data["productos"],
            'business'              => $this->data["business"],
            'quantity'              => $this->data["quantity"],
            $aux                    => $this->data[$aux],
            'warehouse_selected'    => $warehouse_selected,
            'stock_products'        => $this->data["stock_products"],
        ]);
    }
}
