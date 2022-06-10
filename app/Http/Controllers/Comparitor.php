<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Comparitor extends Controller
{
    public $output = [];
    public $not_found = [];

    function view(){
        $this->process_data();
        return view('view')->with([
            "output"    => $this->output,
            "not_found" => $this->not_found
        ]);

    }

    function json(){
        $this->process_data();
        return response()->json($this->output);
    }

    function process_data(){
        $files = $this->getHtmlPath(storage_path('app/live'));
        $output = [];
        $not_found = [];
        foreach($files as $file)
        {
            if(Str::of($file)->endswith('.htm'))
            {

            $staging_location = str_replace("live\\", "staging\\", $file);
            if(Storage::exists($staging_location))
            {
                $live       = new Crawler(Storage::get($file));
                $staging    = new Crawler(Storage::get($staging_location));

                $live_count = str_word_count($this->sanitizeData($live->filterXPath('//*[@id="ContentColumn"]')->extract(['_text'])[0]));
                $staging_count = str_word_count($this->sanitizeData($staging->filterXPath('//*[@id="ContentColumn"]')->extract(['_text'])[0]));

                $output[] = [
                    "live" => $file,
                    "staging" => $staging_location,
                    "live_url" => str_replace("live\\", "https://www.missouristate.edu/Policy/", $file),
                    "staging_url" => str_replace("staging\\", "https://ouwww.missouristate.edu/Policy/", $staging_location),
                    "diff_url" => url("/diff/?file=".$file),
                    "live_count" => $live_count,
                    "staging_count" => $staging_count,
                    "diff" => $live_count - $staging_count
                ];
            }
            else
                $not_found[] = $file;
            }
        }
        $this->output = $output;
        $this->not_found = $not_found;
    }
    function crawler(){
        $crawler = new Crawler(Storage::get('staging/Chapter1/G1_01_1_BylawsDefinitions.htm'));
        echo(str_word_count( trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $crawler->filterXPath('//*[@id="ContentColumn"]')->extract(['_text'])[0])))));
        echo "<br>";
        $crawler = new Crawler(Storage::get("live\Chapter1\G1-31-reporting-allegations-of-discrimination.htm"));
        echo(str_word_count($this->sanitizeData($crawler->filterXPath('//*[@id="ContentColumn"]')->extract(['_text'])[0])));

        // foreach ($crawler as $domElement) {
        //     dd($domElement->nodeName);
        // }
    }

    static function sanitizeData($raw){
        return trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $raw)));
    }

    function getHtmlPath($dir, &$results = array()) {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = str_replace("C:\laragon\www\wordcomp\storage\app\\", "", $path);
            } else if ($value != "." && $value != "..") {
                $this->getHtmlPath($path, $results);
                $results[] = str_replace("C:\laragon\www\wordcomp\storage\app\\", "", $path);
            }
        }
        return $results;
    }
}
