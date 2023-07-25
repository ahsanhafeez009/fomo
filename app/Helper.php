<?php
if (!function_exists("check_spelling")) {
    function check_spelling($word)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => "https://bing-spell-check2.p.rapidapi.com/spellcheck?mode=spell&text=$word",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 1000,
          CURLOPT_TIMEOUT => 300000,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: bing-spell-check2.p.rapidapi.com",
            "X-RapidAPI-Key: 6f86dadc8amsh3eb146ecba532c5p1da084jsn9cdb195c11b2"
        ],
    ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $data = json_decode($response);
        return $data;
    }
}

if (!function_exists("getKeywordSuggestionsFromGoogle")) {

    function getKeywordSuggestionsFromGoogle($word)
    {
        $keywords = array();
        $url="https://suggestqueries.google.com/complete/search?client=chrome&hl=en-US&q=".urlencode($word);
        $context = stream_context_create(array('http' => array('ignore_errors' => true)));
        $data = file_get_contents($url, false, $context);
        if (($data = json_decode($data, true)) !== null) {
            $keywords = $data[1];
        }
        return $keywords;
    }
}

if (!function_exists("clean")) {
    function clean($word)
    {
        @$string = str_replace("'", ' ', $word);
        // @$string = preg_replace("/[^a-zA-Z]+/", " ", @$word);
        return strtolower(@$string);
    }
}

if (!function_exists("C_clean")) {
    function C_clean($word)
    {
        @$string = preg_replace("/[^a-zA-Z]+/", " ", @$word);
        return strtolower(@$string);
    }
}

if (!function_exists("get_words")) {
    function get_words($sentence, $count) {
        foreach ($sentence as $key => $value) {
          preg_match("/(?:\w+(?:\W+|$)){0,$count}/", $value, $matches);
          $data[]  = $matches[0];
        }
        return $data;
    }
}

if (!function_exists("select")) {
    function select($data, $table, $where = 1, $order = "DESC")
    {
        $result = Illuminate\Support\Facades\DB::table($table)->select($data)->where($where)->orderBy("id", $order)->first();
        if (!empty($result)) {
            return $result->{$data};
        }
        return NULL;
    }
}

if (!function_exists("cache_select")) {
    function cache_select($data, $table, $where = NULL, $or_where = NULL)
    {
        $serialize = serialize($where);
        $qry = cache()->remember($data . $table . $serialize, 14440, function () {
            $qry = Illuminate\Support\Facades\DB::table($table)->select($data);
            if (!blank($where)) {
                $qry = $qry->where($where);
            }
            if (!blank($or_where)) {
                $qry = $qry->orWhere($or_where);
            }
            $result = $qry->first();
            if (!empty($result)) {
                return $result->{$data};
            }
            return NULL;
        });
        return $qry;
    }
}

if (!function_exists("count_all")) {
    function count_all($table, $where = 1)
    {
        $result = Illuminate\Support\Facades\DB::table($table)->where($where)->count("id");
        if (!empty($result)) {
            return $result;
        }
        return 0;
    }
}

if (!function_exists("check_user")) {
    function check_user($userid)
    {
        $result = Illuminate\Support\Facades\DB::table("members")->where("id", id_filter($userid))->count("id");
        if (!empty($result)) {
            return $result;
        }
        return 0;
    }
}

if (!function_exists("msg")) {
    function msg($msg, $type = "success", $close = true)
    {
        $icon = asset("images/tick.png");
        if ($type === "info") {
            $icon = asset("images/info.png");
        }
        if ($type === "warning") {
            $icon = asset("images/warning.png");
        }
        if ($type === "danger") {
            $icon = asset("images/danger.png");
        }
        $str = "<div role=\"alert\" class=\"alert mb-2 mt-1 alert-dismissible fade show alert-" . $type . "\"><img src=\"" . $icon . "\"></span> " . $msg;
        $str .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n    <span aria-hidden=\"true\">&times;</span>\n  </button></div>";
        return $str;
    }
}

if (!function_exists("errorrecord")) {
    function errorrecord($msg = "Some Error occurred. Try again", $url = NULL)
    {
        if ($url !== NULL) {
            $url = "<p class=\"text-center mt-2\"><a class=\"btn btn-warning\" href=\"" . $url . "\">Try Again</a></p>";
        }
        $str = "<div class=\"text-center\"><img class=\"img-fluid\" style=\"max-height: 100px; width:auto\" src=\"" . asset("images/problem.png") . "\"><h2 class=\"mt-1\" style=\"font-weight: 500 !important; font-size: 22px !important;\">" . $msg . "" . $url . "</h2></div>";
        return trim($str);
    }
}

if (!function_exists("norecord")) {
    function norecord($msg = "No Record available to display")
    {
        $str = "<div class=\"text-center\"><img class=\"img-fluid\" style=\"max-height: 100px; width:auto\" src=\"" . asset("images/error.png") . "\"><h3 class=\"mt-1\" style=\"font-weight: 500 !important; font-size: 22px !important;\">" . $msg . "</h4></div>";
        return trim($str);
    }
}

if (!function_exists("warning")) {
    function warning($msg = "Oops ! Thats not available right now")
    {
        $str = "<div class=\"text-center\"><img class=\"img-fluid\" style=\"max-height: 100px; width:auto\" src=\"" . asset("images/crisis.png") . "\"><h3 class=\"mt-1\" style=\"font-weight: 500 !important; font-size: 22px !important;\">" . $msg . "</h4></div>";
        return trim($str);
    }
}

if (!function_exists("success")) {
    function success($msg = "Your record has been successfully saved.")
    {
        $str = "<div class=\"text-center\"><img class=\"img-fluid\" style=\"max-height: 100px; width:auto\" src=\"" . asset("images/success.png") . "\"><h3 class=\"mt-1\" style=\"font-weight: 500 !important; font-size: 22px !important;\">" . $msg . "</h3></div>";
        return trim($str);
    }
}

if (!function_exists("script_redirect")) {
    function script_redirect($url, $msg = NULL)
    {
        if ($msg !== NULL) {
            session()->flash("msg", $msg);
        }
        $str = "<script type=\"text/javascript\">document.location.href=\"" . $url . "\"</script> ";
        return trim($str);
    }
}

if (!function_exists("curl")) {
    function curl($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CAINFO, __DIR__ . "/cacert.pem");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $html = curl_exec($curl);
        curl_close($curl);
        if (trim($html) == "") {
            $html = file_get_contents($url);
        }
        return $html;
    }
}

if (!function_exists("id_filter")) {
    function id_filter($id)
    {
        return trim(str_ireplace(env("ID_EXT"), "", $id));
    }
}

if (!function_exists("number_filter")) {
    function number_filter($number)
    {
        return filter_var($number, FILTER_SANITIZE_NUMBER_INT);
    }
}

// if (!function_exists("run_queue")) {
//     function run_queue()
//     {
//         speedup();
//     }
// }

if (!function_exists("artisan")) {
    function artisan($command)
    {
        Illuminate\Support\Facades\Artisan::call($command);
    }
}

if (!function_exists("json_error")) {
    function json_error($message, $params = [])
    {
        return json_encode(array_merge(["error" => true, "message" => $message], $params), JSON_UNESCAPED_UNICODE);
    }
}

if (!function_exists("json_success")) {
    function json_success($message, $params = [])
    {
        return json_encode(array_merge(["error" => false, "message" => $message], $params), JSON_UNESCAPED_UNICODE);
    }
}

?>