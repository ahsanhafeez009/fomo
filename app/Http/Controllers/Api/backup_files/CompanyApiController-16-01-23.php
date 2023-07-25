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
    foreach ($results as $key => $text) {
      if (!empty($text['company_type'])) {
        @$company_type[$key] = getKeywordSuggestionsFromGoogle(@$text['company_type']);
      }
    }

    foreach ($results as $key => $outerwrap) {
      if (!empty(@$outerwrap['company_type'])) {
        @$text_match_to[$key] = C_clean(@$outerwrap['company_type']);
        if (!empty(@$company_type[$key])) {
          $text_match_with[$key] = get_words(@$company_type[$key], str_word_count(@$text_match_to[$key]));
        }
      }
    }

    foreach (@$text_match_to as $key => $outerwrap) {
      foreach (@$text_match_with[$key] as $key1 => $innerwrap) {
        if (@$outerwrap == @$innerwrap) {
          @$record_results[$key] = @$outerwrap;
        }
        if (!array_key_exists($key,(array)@$record_results)){
          @$record_results[$key] = @$innerwrap;
        }
      }
    }

    foreach ($record_results as $key1 => $result_company_type) {
      $C_type = Company_Type::select('name')->where('name', '=', $result_company_type)->get();
      $C_Type_Count = $C_type->count();
      if ($C_Type_Count == 0) {
        $company_type             = new Company_Type();
        $company_type->name       = ucwords($result_company_type);
        $company_type->save();
      }
      Company::where('company_type', $results[$key1]['company_type'])->update([
        'company_type' => ucwords($result_company_type)
      ]);
    }
  }

  public function check_company_status_cron_job()
  {
    $results = Company::select('status')->groupBy('status')->where('status', '!=', Null)->get()->toArray();
    foreach ($results as $key => $text) {
      if (!empty($text['status'])) {
        @$status[$key] = getKeywordSuggestionsFromGoogle(@$text['status']);
      }
    }

    foreach ($results as $key => $outerwrap) {
      if (!empty(@$outerwrap['status'])) {
        @$text_match_to[$key] = C_clean(@$outerwrap['status']);
        if (!empty(@$status[$key])) {
          $text_match_with[$key] = get_words(@$status[$key], str_word_count(@$text_match_to[$key]));
        }
      }
    }

    foreach (@$text_match_to as $key => $outerwrap) {
      foreach (@$text_match_with[$key] as $key1 => $innerwrap) {
        if (@$outerwrap == @$innerwrap) {
          @$record_results[$key] = @$outerwrap;
        }
        if (!array_key_exists($key,(array)@$record_results)){
          @$record_results[$key] = @$innerwrap;
        }
      }
    }

    foreach ($record_results as $key1 => $result_company_status) {
      $C_Status = Company_Status::select('status')->where('status', '=', $result_company_status)->get();
      $C_Status_Count = $C_Status->count();
      if ($C_Status_Count == 0) {
        $company_status             = new Company_Status();
        $company_status->status     = ucwords($result_company_status);
        $company_status->save();
      }
      Company::where('status', $results[$key1]['status'])->update([
        'status' => ucwords($result_company_status)
      ]);
    }
  }
}
