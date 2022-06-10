<?php

namespace App\Http\Controllers;

use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use Jfcherng\Diff\Differ;
use Jfcherng\Diff\DiffHelper;
use Jfcherng\Diff\Factory\RendererFactory;
use Jfcherng\Diff\Renderer\RendererConstant;

class Differentor extends Controller
{
    function view(Request $request)
    {
        if(isset($request->file) && $request->file)
        {
            $live       = new Crawler(Storage::get($request->file));

            $staging_location = str_replace("live\\", "staging\\", $request->file);
            $staging    = new Crawler(Storage::get($staging_location));


            $old = $this->sanitize($live->filterXPath('//*[@id="ContentColumn"]')->extract(['_text'])[0]);
            $new = $this->sanitize($staging->filterXPath('//*[@id="ContentColumn"]')->extract(['_text'])[0]);

            $diffOptions = [
                // show how many neighbor lines
                // Differ::CONTEXT_ALL can be used to show the whole file
                'context' => 1,
                // ignore case difference
                'ignoreCase' => false,
                // ignore whitespace difference
                'ignoreWhitespace' => true,
            ];

            // options for renderer class
            $rendererOptions = [
                // how detailed the rendered HTML is? (none, line, word, char)
                'detailLevel' => 'char',
                // renderer language: eng, cht, chs, jpn, ...
                // or an array which has the same keys with a language file
                'language' => 'eng',
                // show line numbers in HTML renderers
                'lineNumbers' => true,
                // show a separator between different diff hunks in HTML renderers
                'separateBlock' => true,
                // show the (table) header
                'showHeader' => true,
                // the frontend HTML could use CSS "white-space: pre;" to visualize consecutive whitespaces
                // but if you want to visualize them in the backend with "&nbsp;", you can set this to true
                'spacesToNbsp' => false,
                // HTML renderer tab width (negative = do not convert into spaces)
                'tabSize' => 4,
                // this option is currently only for the Combined renderer.
                // it determines whether a replace-type block should be merged or not
                // depending on the content changed ratio, which values between 0 and 1.
                'mergeThreshold' => 0.8,
                // this option is currently only for the Unified and the Context renderers.
                // RendererConstant::CLI_COLOR_AUTO = colorize the output if possible (default)
                // RendererConstant::CLI_COLOR_ENABLE = force to colorize the output
                // RendererConstant::CLI_COLOR_DISABLE = force not to colorize the output
                'cliColorization' => RendererConstant::CLI_COLOR_AUTO,
                // this option is currently only for the Json renderer.
                // internally, ops (tags) are all int type but this is not good for human reading.
                // set this to "true" to convert them into string form before outputting.
                'outputTagAsString' => false,
                // this option is currently only for the Json renderer.
                // it controls how the output JSON is formatted.
                // see available options on https://www.php.net/manual/en/function.json-encode.php
                'jsonEncodeFlags' => \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE,
                // this option is currently effective when the "detailLevel" is "word"
                // characters listed in this array can be used to make diff segments into a whole
                // for example, making "<del>good</del>-<del>looking</del>" into "<del>good-looking</del>"
                // this should bring better readability but set this to empty array if you do not want it
                'wordGlues' => [' ', '-'],
                // change this value to a string as the returned diff if the two input strings are identical
                'resultForIdenticals' => null,
                // extra HTML classes added to the DOM of the diff container
                'wrapperClasses' => ['diff-wrapper'],
            ];

            // or even shorter if you are happy with default options
            $result = DiffHelper::calculate(
                $old,
                $new,
                'SideBySide'
            );

            return view('diff')->with([
                'result' => $result,
                'url' => str_replace("live\\", "https://ouwww.missouristate.edu/Policy/", $request->file),
            ]);

        }
        else
        {
            dd('No file specified');
        }
    }

    function sanitize($raw)
    {
        return trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $raw)));
    }

    function count($dir, $needle, &$results = array()) {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                // $results[] = str_replace("C:\laragon\www\wordcomp\storage\app\\", "", $path);
                $count = substr_count(
                    file_get_contents($path),
                    $needle
                );
                if($count)
                $results[] = [
                    'file' => $path,
                    'count'=> $count
                ];
            } else if ($value != "." && $value != "..") {
                $this->count($path, $needle, $results);
                // dont put folder in the  results
                // $results[] = str_replace("C:\laragon\www\wordcomp\storage\app\\", "", $path);
            }
        }
        return $results;
    }

    function findThis(Request $request)
    {

        dd($this->count(storage_path('app/staging'), "&rsquo;"));
        if(isset($request->needle) && $request->needle)
        {
        }
        else
        {
            dd('No file specified');
        }
    }
}
