<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class LiveSearch extends Controller
{
    function index()
    {
     return view('live_search');
    }

    function action(Request $request)
    {
     if($request->ajax())
     {
      $output = '';
      $query = $request->get('query');
      if($query != '')
      {
       $data = DB::table('tbl_customer')
         ->where('CustomerName', 'like', '%'.$query.'%')
         ->orWhere('Address', 'like', '%'.$query.'%')
         ->orWhere('City', 'like', '%'.$query.'%')
         ->orWhere('PostalCode', 'like', '%'.$query.'%')
         ->orWhere('Country', 'like', '%'.$query.'%')
         ->orderBy('CustomerID', 'desc')
         ->get();

      }
      else
      {
       $data = DB::table('tbl_customer')
         ->orderBy('CustomerID', 'desc')
         ->get();
      }
      $total_row = $data->count();
      if($total_row > 0)
      {
       foreach($data as $row)
       {
        $output .= '
        <tr>
         <td>'.$row->CustomerName.'</td>
         <td>'.$row->Address.'</td>
         <td>'.$row->City.'</td>
         <td>'.$row->PostalCode.'</td>
         <td>'.$row->Country.'</td>
        </tr>
        ';
       }
      }
      else
      {
       $output = '
       <tr>
        <td align="center" colspan="5">No Data Found</td>
       </tr>
       ';
      }
      $data = array(
       'table_data'  => $output,
       'total_data'  => $total_row
      );

      echo json_encode($data);
     }
    }

    function add_data(Request $request)
    {
        if($request->ajax())
        {
            $data = array(
                'CustomerName'    =>  $request->CustomerName,
                'Address'     =>  $request->Address,
                'City'    =>  $request->City,
                'PostalCode'     =>  $request->PostalCode,
                'Country'    =>  $request->Conuntry
            );
            $id = DB::table('tbl_customer')->insert($data);
            if($id > 0)
            {
                echo '<div class="alert alert-success">Data Inserted</div>';
            }
        }
    }

    function update_data(Request $request)
    {
        if($request->ajax())
        {
            $data = array(
                $request->column_name       =>  $request->column_value
            );
            DB::table('tbl_customer')
                ->where('id', $request->id)
                ->update($data);
            echo '<div class="alert alert-success">Data Updated</div>';
        }
    }

    function delete_data(Request $request)
    {
        if($request->ajax())
        {
            DB::table('tbl_customer')
                ->where('id', $request->id)
                ->delete();
            echo '<div class="alert alert-success">Data Deleted</div>';
        }
    }
}
