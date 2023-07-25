<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Company_Status;
use App\Models\Company_Type;
use Illuminate\Http\Request;
use DB;

class CompanyApiController extends Controller
{
  public function check_company_type_cron_job()
  {
    $results = Company::select('company_type')->groupBy('company_type')->where('company_type', '!=', Null)->get()->toArray();
    $new_result = [];
    foreach ($results as $key => $text) {
      if (!empty($text['company_type'])) {
        if (str_contains($text['company_type'], ",") || str_contains($text['company_type'], "'") || str_contains($text['company_type'], "’")) { 
          if (str_contains($text['company_type'], ",")) {
            $new_result[$key] = explode(",", $text['company_type']);
          }
          if (str_contains($text['company_type'], "'")) {
            $new_result[$key] = explode("'", $text['company_type']);
          }
          if (str_contains($text['company_type'], "’")) {
            $new_result[$key] = explode("’", $text['company_type']);
          }
        }else{
            $new_result[$key][] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $text['company_type']);
        }
      }
    }

    $final_company_type = [];
    foreach ($new_result as $key => $outerwrap) {
      foreach ($outerwrap as $key1 => $innerwrap) {
        $final_company_type[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
      }
    }

    foreach ($final_company_type as $key => $result_company_type) {
      $C_type = Company_Type::select('name')->where('name', 'like', '%' . $result_company_type . '%')->get();
      $C_Type_Count = $C_type->count();
      if ($C_Type_Count == 0) {
        $company_type          = new Company_Type();
        $company_type->name    = ucwords($result_company_type);
        $company_type->save();
      }
    }
  }

  public function check_company_status_cron_job()
  {
    $results = Company::select('status')->groupBy('status')->where('status', '!=', Null)->get()->toArray();
    $new_result = [];
    foreach ($results as $key => $text) {
      if (!empty($text['status'])) {
        if (str_contains($text['status'], ",") || str_contains($text['status'], "'") || str_contains($text['status'], "’")) { 
          if (str_contains($text['status'], ",")) {
            $new_result[$key] = explode(",", $text['status']);
          }
          if (str_contains($text['status'], "'")) {
            $new_result[$key] = explode("'", $text['status']);
          }
          if (str_contains($text['status'], "’")) {
            $new_result[$key] = explode("’", $text['status']);
          }
        }else{
          $new_result[$key][] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $text['status']);
        }
      }
    }

    $final_company_status = [];
    foreach ($new_result as $key => $outerwrap) {
      foreach ($outerwrap as $key1 => $innerwrap) {
        $final_company_status[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
      }
    }

    foreach ($final_company_status as $key => $result_company_status) {
      $C_Status = Company_Status::select('status')->where('status', 'like', '%' . $result_company_status . '%')->get();
      $C_Status_Count = $C_Status->count();
      if ($C_Status_Count == 0) {
        $company_status          = new Company_Status();
        $company_status->status  = ucwords($result_company_status);
        $company_status->save();
      }
    }
  }

  public function check_company_fomo_incharge_cron_job()
  {
    $results = Company::select('fomo_incharge')->groupBy('fomo_incharge')->where('fomo_incharge', '!=', Null)->get()->toArray();
    $new_result = [];
    foreach ($results as $key => $text) {
      if (!empty($text['fomo_incharge'])) {
        if (str_contains($text['fomo_incharge'], ",") || str_contains($text['fomo_incharge'], "'") || str_contains($text['fomo_incharge'], "’")) { 
          if (str_contains($text['fomo_incharge'], ",")) {
            $new_result[$key] = explode(",", $text['fomo_incharge']);
          }
          if (str_contains($text['fomo_incharge'], "'")) {
            $new_result[$key] = explode("'", $text['fomo_incharge']);
          }
          if (str_contains($text['fomo_incharge'], "’")) {
            $new_result[$key] = explode("’", $text['fomo_incharge']);
          }
        }else{
          $new_result[$key][] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $text['fomo_incharge']);
        }
      }
    }

    $final_company_fomo_incharge = [];
    foreach ($new_result as $key => $outerwrap) {
      foreach ($outerwrap as $key1 => $innerwrap) {
        $final_company_fomo_incharge[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
      }
    }

    foreach ($final_company_fomo_incharge as $key => $result_fomo_incharge) {
      $C_fomo_incharge = DB::table('company_fomo_incharge')->select('fomo_incharge')->where('fomo_incharge', 'like', '%' . $result_fomo_incharge . '%')->get();
      $C_fomo_incharge_Count = $C_fomo_incharge->count();
      if ($C_fomo_incharge_Count == 0) {
        $result = DB::table('company_fomo_incharge')->insert([
          'fomo_incharge' => $result_fomo_incharge,
        ]);
      }
    }
  }

  public function company_payment_terms_cron_job()
  {
    $results = Company::select('payment_terms')->groupBy('payment_terms')->where('payment_terms', '!=', Null)->get()->toArray();
    $new_result = [];
    foreach ($results as $key => $text) {
      if (!empty($text['payment_terms'])) {
        if (str_contains($text['payment_terms'], ",") || str_contains($text['payment_terms'], "'") || str_contains($text['payment_terms'], "’")) { 
          if (str_contains($text['payment_terms'], ",")) {
            $new_result[$key] = explode(",", $text['payment_terms']);
          }
          if (str_contains($text['payment_terms'], "'")) {
            $new_result[$key] = explode("'", $text['payment_terms']);
          }
          if (str_contains($text['payment_terms'], "’")) {
            $new_result[$key] = explode("’", $text['payment_terms']);
          }
        }else{
          $new_result[$key][] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $text['payment_terms']);
        }
      }
    }

    $final_payment_terms = [];
    foreach ($new_result as $key => $outerwrap) {
      foreach ($outerwrap as $key1 => $innerwrap) {
        if (!str_contains(strtolower($innerwrap), "days")) {
          $final_payment_terms[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap.' Days');
        }else{
          $final_payment_terms[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
        }
      }
    }

    foreach ($final_payment_terms as $key => $result_payment_terms) {
      $C_company_payment_terms = DB::table('company_payment_terms')->select('payment_terms')->where('payment_terms', '=', $result_payment_terms)->get();
      $C_company_payment_terms_Count = $C_company_payment_terms->count();
      if ($C_company_payment_terms_Count == 0) {
        $result = DB::table('company_payment_terms')->insert([
          'payment_terms' => $result_payment_terms,
        ]);
      }
    }
  }

  public function company_country_cron_job()
  {
    $results = Company::select('country')->groupBy('country')->where('country', '!=', Null)->get()->toArray();
    $new_result = [];
    foreach ($results as $key => $text) {
      if (!empty($text['country'])) {
        if (str_contains($text['country'], ",") || str_contains($text['country'], "'") || str_contains($text['country'], "’")) { 
          if (str_contains($text['country'], ",")) {
            $new_result[$key] = explode(",", $text['country']);
          }
          if (str_contains($text['country'], "'")) {
            $new_result[$key] = explode("'", $text['country']);
          }
          if (str_contains($text['country'], "’")) {
            $new_result[$key] = explode("’", $text['country']);
          }
        }else{
          $new_result[$key][] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $text['country']);
        }
      }
    }

    $final_country = [];
    foreach ($new_result as $key => $outerwrap) {
      foreach ($outerwrap as $key1 => $innerwrap) {
        $final_country[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
      }
    }

    foreach ($final_country as $key => $result_country) {
      $C_country = DB::table('company_country')->select('country')->where('country', 'like', '%' . $result_country . '%')->get();
      $C_country_Count = $C_country->count();
      if ($C_country_Count == 0) {
        $result = DB::table('company_country')->insert([
          'country' => $result_country,
        ]);
      }
    }
  }

  public function company_state_cron_job()
  {
    $results = Company::select('state')->groupBy('state')->where('state', '!=', Null)->get()->toArray();
    $new_result = [];
    foreach ($results as $key => $text) {
      if (!empty($text['state'])) {
        if (str_contains($text['state'], ",") || str_contains($text['state'], "'") || str_contains($text['state'], "’")) { 
          if (str_contains($text['state'], ",")) {
            $new_result[$key] = explode(",", $text['state']);
          }
          if (str_contains($text['state'], "'")) {
            $new_result[$key] = explode("'", $text['state']);
          }
          if (str_contains($text['state'], "’")) {
            $new_result[$key] = explode("’", $text['state']);
          }
        }else{
          $new_result[$key][] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $text['state']);
        }
      }
    }

    $final_state = [];
    foreach ($new_result as $key => $outerwrap) {
      foreach ($outerwrap as $key1 => $innerwrap) {
        $final_state[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
      }
    }

    foreach ($final_state as $key => $result_state) {
      $C_state = DB::table('company_state')->select('state')->where('state', 'like', '%' . $result_state . '%')->get();
      $C_state_Count = $C_state->count();
      if ($C_state_Count == 0) {
        $result = DB::table('company_state')->insert([
          'state' => $result_state,
        ]);
      }
    }
  }
  
  public function company_city_cron_job()
  {
    $results = Company::select('city')->groupBy('city')->where('city', '!=', Null)->get()->toArray();
    $new_result = [];
    foreach ($results as $key => $text) {
      if (!empty($text['city'])) {
        if (str_contains($text['city'], ",") || str_contains($text['city'], "'") || str_contains($text['city'], "’")) { 
          if (str_contains($text['city'], ",")) {
            $new_result[$key] = explode(",", $text['city']);
          }
          if (str_contains($text['city'], "'")) {
            $new_result[$key] = explode("'", $text['city']);
          }
          if (str_contains($text['city'], "’")) {
            $new_result[$key] = explode("’", $text['city']);
          }
        }else{
          $new_result[$key][] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $text['city']);
        }
      }
    }

    $final_city = [];
    foreach ($new_result as $key => $outerwrap) {
      foreach ($outerwrap as $key1 => $innerwrap) {
        $final_city[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $innerwrap);
      }
    }

    foreach ($final_city as $key => $result_city) {
      $C_city = DB::table('company_city')->select('city')->where('city', 'like', '%' . $result_city . '%')->get();
      $C_city_Count = $C_city->count();
      if ($C_city_Count == 0) {
        $result = DB::table('company_city')->insert([
          'city' => $result_city,
        ]);
      }
    }
  }
}
