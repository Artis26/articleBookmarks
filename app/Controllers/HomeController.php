<?php
namespace App\Controllers;


use App\View;

class HomeController {

    public function home(): View {

        $articles = json_decode(file_get_contents('https://www.delfi.lv/misc/task_2020/'));
        //otherwise instead of '' &#039; shows in string.
        foreach ($articles as $one) {
            $one->picture = $one->pictures->{'360x240'};
            $one->title = html_entity_decode($one->title);
            $one->lead = html_entity_decode($one->lead);
            $one->picture_alt = html_entity_decode($one->picture_alt);
        }

        return new View('Html/home.html', [
            'articles' => $articles
        ]);
    }

    public function saveArticles(): void {
        $dataObject = $_POST['all'];
        $json = json_decode($dataObject);
        unlink('data.txt');
        foreach ($json as $one) {
            foreach ($one as $key => $value) {
                $import[$key] = $value;
            }
            file_put_contents('data.txt', json_encode($import) . PHP_EOL, FILE_APPEND);
        }
    }
    
    public function displaySavedArticles(): View {
        $all = file_get_contents('data.txt');
        $all = explode("\n", $all);
        foreach ($all as $o) {
            $articles[] = json_decode($o);
        }
        array_pop($articles);

        return new View('Html/savedArticles.html', [
            'articles' => $articles
        ]);
    }

    public function displayByChannelId($vars) {
        $val = $vars['id'];

        $all = file_get_contents('data.txt');
        $all = explode("\n", $all);
        foreach ($all as $o) {
            $one = json_decode($o);
            if ($one->channel_id == $val) {
                $articles[] = $one;
            }
        }
        return new View('Html/savedFiltered.html', [
            'articles' => $articles
        ]);
    }

}