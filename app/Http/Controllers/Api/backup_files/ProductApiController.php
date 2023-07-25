<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brands;
use App\Models\Company;
use App\Models\ProductGender;

class ProductApiController extends Controller
{
    public function check_product_brand_names_cron_job()
    {
        $results = Product::select('brand_name')->groupBy('brand_name')->get()->toArray();
            foreach ($results as $key => $text) {
                if (!empty($text['brand_name'])) {
                    @$brand_name[$key] = getKeywordSuggestionsFromGoogle(@$text['brand_name']);
                }
                if (empty(@$brand_name[$key])) {
                    @$brand_name[$key][] = @$text['brand_name'];
                }
            }
            foreach ($results as $key => $outerwrap) {
                if (!empty(@$outerwrap['brand_name'])) {
                    if (preg_match('~[0-9]+~', @$outerwrap['brand_name'])) {
                        @$text_match_to[$key] = @$outerwrap['brand_name'];
                    }else{
                        @$text_match_to[$key] = clean(@$outerwrap['brand_name']);
                    }                    
                }
                if (!empty(@$brand_name[$key])) {
                    if (preg_match('~[0-9]+~', @$outerwrap['brand_name'])) {
                        @$text_match_with[$key][] = @$outerwrap['brand_name'];
                    }else{
                        @$text_match_with[$key] = get_words(@$brand_name[$key], str_word_count(@$text_match_to[$key]));
                    }
                }
            }

            if (is_array($text_match_to) || is_object($text_match_to)){
                foreach (@$text_match_to as $key => $outerwrap) {
                    foreach (@$text_match_with[$key] as $key1 => $innerwrap) {
                        if (@$outerwrap == @$innerwrap) {
                            @$record_results[$key] = clean(@$outerwrap);
                        }
                        if (!array_key_exists($key,(array)@$record_results)){
                            @$record_results[$key] = clean(@$innerwrap);
                        }
                    }
                }
            }

            foreach ($record_results as $key1 => $result_brand_name) {
                if (strtolower($result_brand_name) == "dolce gabbana") {
                   $new_result_brand_name = "Dolce & Gabbana";
                }else{
                   $new_result_brand_name = $result_brand_name;
                }

                $new_result_brand_name = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $new_result_brand_name);
                $B_name = Brands::select('brand_name')->where('brand_name', '=', $new_result_brand_name)->get();
                $B_name_count = $B_name->count();
                if ($B_name_count == 0) {
                    $brand_name             = new Brands();
                    $brand_name->brand_name = ucwords($new_result_brand_name);
                    $brand_name->save();
                }
                Product::where('brand_name', $results[$key1]['brand_name'])->update([
                    'brand_name' => ucwords($new_result_brand_name)
                ]);
            }
    }

    public function check_product_gender_cron_job()
    {
        $results = Product::select('product_gender')->groupBy('product_gender')->where('product_gender', '!=', Null)->get()->toArray();
            foreach ($results as $key => $text) {
                if (!empty($text['product_gender'])) {
                    @$product_gender[$key] = getKeywordSuggestionsFromGoogle(@$text['product_gender']);
                }
                if (empty(@$product_gender[$key])) {
                    @$product_gender[$key][] = @$text['product_gender'];
                }
            }
            foreach ($results as $key => $outerwrap) {
                if (!empty(@$outerwrap['product_gender'])) {
                    if (preg_match('~[0-9]+~', @$outerwrap['product_gender'])) {
                        @$text_match_to[$key] = @$outerwrap['product_gender'];
                    }else{
                        @$text_match_to[$key] = clean(@$outerwrap['product_gender']);
                    }                    
                }
                if (!empty(@$product_gender[$key])) {
                    if (preg_match('~[0-9]+~', @$outerwrap['product_gender'])) {
                        @$text_match_with[$key][] = @$outerwrap['product_gender'];
                    }else{
                        @$text_match_with[$key] = get_words(@$product_gender[$key], str_word_count(@$text_match_to[$key]));
                    }
                }
            }

            if (is_array($text_match_to) || is_object($text_match_to)){
                foreach (@$text_match_to as $key => $outerwrap) {
                    foreach (@$text_match_with[$key] as $key1 => $innerwrap) {
                        if (@$outerwrap == @$innerwrap) {
                            @$record_results[$key] = clean(@$outerwrap);
                        }
                        if (!array_key_exists($key,(array)@$record_results)){
                            @$record_results[$key] = clean(@$innerwrap);
                        }
                    }
                }
            }

            foreach ($record_results as $key1 => $result_gender) {
                $P_gender = ProductGender::select('gender')->where('gender', '=', $result_gender)->get();
                $P_gender_count = $P_gender->count();
                if ($P_gender_count == 0) {
                    $new_gender         = new ProductGender();
                    $new_gender->gender = ucwords($result_gender);
                    $new_gender->save();
                }
                Product::where('product_gender', $results[$key1]['product_gender'])->update([
                    'product_gender' => ucwords($result_gender)
                ]);
            }
    }

    public function check_product_name_cron_job()
    {
        $results = Product::select('*')->where('spell_check', '=', 0)->orderBy('id', 'ASC')->limit(100)->get()->toArray();
        $resultant_products = $this->get_result($results);
        foreach ($resultant_products as $key => $value) {
            Product::where('product_name', $results[$key]['product_name'])->update([
                'product_name' => ucwords($value['product_name']),
                'spell_check' => 1
            ]);
        }
    }

    public function get_result($results)
    {
        foreach ($results as $key => $data) {
            if ($data['product_type'] != "Gift Set") {
                $resultant_products[$key] = $data;   
            }
        }
        @$record = $this->get_products_name_without_extra($resultant_products);
        foreach (@$results as $key2 => $outerwrap) {
            if (!empty($record[$key2])) {
                $results[$key2]['product_name'] = @$record[$key2];
            }else{
                $results[$key2] = @$outerwrap;
            }
        }
        return $results;
    }

    public function get_products_name_without_extra($resultant_products){
        $remove_elements = array('EDT','Edt','edt','EDP','Edp','edp');
        
        foreach ($resultant_products as $key => $outerwrap) {
            $result = str_replace($remove_elements, "", $outerwrap['product_name']);
            $results[$key] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $result);
        }
        
        foreach ($results as $key1 => $value) {
            preg_match_all('/\d+(?:\s*x\s*\d+)*\s*ml\b/i', $value, $matches);
            $before_trim_size_array[$key1] = $matches[0];
        }
        
        $remove_size_text = [];
        $bottle_sizes = [];
        foreach ($results as $key2 => $value) {
            $remove_size_text[$key2] = str_replace($before_trim_size_array[$key2][0],"",$value);
            $bottle_sizes[$key2] = preg_replace('/\D/', '', $before_trim_size_array[$key2][0]);
        }

        $resultant_text_after_size_removed = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $remove_size_text);
        foreach ($resultant_text_after_size_removed as $key3 => $row) {
            preg_match('#\((.*?)\)#', $row, $match);
            $extra_paremeters_for_search[$key3] = isset($match[0]) ? $match[0] : '' ;
        }

        foreach ($resultant_text_after_size_removed as $key4 => $outerwrap) {
            $resultant_text[$key4] = str_replace($extra_paremeters_for_search[$key4],"",$outerwrap);
        }

        foreach ($resultant_products as $key5 => $outerwrap) {
            if (stripos(strtolower($outerwrap['product_name']), strtolower('edp')) !== false) {
                $resultant_product_names[$key5] = 'edp';
            }
            if (stripos(strtolower($outerwrap['product_name']), strtolower('edt')) !== false) {
                $resultant_product_names[$key5] = 'edt';
            }
        }
       
        foreach ($resultant_text as $key6 => $outerwrap) {
            $get_suggestion_value[$key6] = getKeywordSuggestionsFromGoogle($outerwrap);
            $newouterwrap = str_replace("'", '', @$outerwrap);
            $new_suggestion_value  = str_replace("'", '', @$get_suggestion_value[$key6][0]);
            $result_records[$key6] = get_words(@$new_suggestion_value, str_word_count(@$newouterwrap));
            if(empty($result_records[$key6])) {
                $result_records[$key6] = $newouterwrap;
            }
        }

        foreach ($result_records as $key7 => $outer_wrap) {
            $product_record[$key7] = preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', @$outer_wrap .' '. @$resultant_product_names[$key7]. ' '. @$bottle_sizes[$key7].'ml');
        }
        return $product_record;
    }
}
