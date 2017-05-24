<?php
namespace console\controllers;


use Yii;
use yii\console\Controller;
use console\helpers\Console;
use Elasticsearch\ClientBuilder;


class NController extends Controller{
    private $_node = null;
    private $_timeFile = '@console/controllers/data/time.txt';

    /**
     * 增加一条笔记
     * @return [type] [description]
     */
    public function actionA(){
        $others = "全局tag: kzdev, kzread, kztips";
        echo Console::ansiFormat($others, [Console::BG_BLACK, Console::FG_YELLOW]);
        echo "\n\n";
        $new = [
            'title' => Console::prompt("输入title:", ['required' => true]),
            'content' => trim(Console::promptMulitiLine("输入笔记content(以空行结束):\n", ['required' => true])),
            'tags' => Console::prompt("输入tags(空格隔开):", ['default' => '']),
            'example' => trim(Console::promptMulitiLine("example(以空行结束):")),
            'created_at' => time()
        ];
        $data[] = $new;
        file_put_contents($this->getDataFile(), \Spyc::YAMLDump($data), FILE_APPEND);
        $this->actionI();
    }

    /**
     * 查询一条笔记
     * @param  [type] $text [description]
     * @return [type]       [description]
     */
    public function actionF($text){
        $params = [
            'index' => 'db1',
            'type' => 'notes',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'type' => 'most_fields',
                        'fields' => ['title', 'content', 'example'],
                        'query' => $text
                    ]
                ]
            ]
        ];
        $r = $this->c()->search($params);
        if(!empty($r['hits']['hits'])){
            $result = $r['hits']['hits'];
            foreach($result as $item){
                echo sprintf("%s:%s\n", Console::ansiFormat('score', [Console::BG_BLACK, Console::FG_YELLOW]), $item['_score']);
                foreach($item['_source'] as $attr => $value){
                    echo sprintf("%s:%s\n", Console::ansiFormat($attr, [Console::BG_BLACK, Console::FG_YELLOW]), $value);
                }
                echo "\n";
            }
        }else{
            echo "No result.\n";
        }
    }

    /**
     * 手动导入笔记到嗖搜索库
     * @return [type] [description]
     */
    public function actionI(){
        $lastTime = (int)file_get_contents(Yii::getAlias($this->_timeFile));
        $data = $this->getNotes();
        $params = [];
        foreach($data as $item){
            if($lastTime <= $item['created_at']){
                $params['body'][] = [
                    'index' => [
                        '_index' => 'db1',
                        '_type' => 'notes',
                    ]
                ];
                $params['body'][] = $item;
                echo trim($item['title'])."\n";
            }
        }
        if(!empty($params)){
            $r = $this->c()->bulk($params);
            file_put_contents(Yii::getAlias($this->_timeFile), time());
        }
        $logFile = Yii::getAlias('@console/controllers/data/import.log');
        file_put_contents($logFile, (((int)file_get_contents($logFile)) + 1) .  ' ' . date('Y-m-d H:i:s', time()));
    }
    /**
     * 重新创建并导入到搜索库
     * @return [type] [description]
     */
    public function actionC(){
        file_put_contents(Yii::getAlias($this->_timeFile), 0);
        $this->c()->indices()->delete(['index' => 'db1']);
        $this->create();
        $this->actionI();
    }
    public function create(){
        $index = [
            'index' => 'db1',
            'body' => [
                'mappings' => [
                    'notes' => [
                        '_all' => [
                            "analyzer" => "ik_max_word",
                            "search_analyzer" => "ik_max_word",
                            "term_vector" => "no",
                            "store" => "false"
                        ],
                        'properties' => [
                            'title' => [
                                "type" => "string",
                                "analyzer" => "ik_max_word",
                                "search_analyzer" => "ik_max_word",
                                "store" => "no",
                                "term_vector" => "with_positions_offsets",
                                "include_in_all" => "true",
                                "boost" => 5,
                                'fields' => [
                                    'long' => [
                                        'type' => 'string',
                                        "analyzer" => "ik_smart",
                                        "search_analyzer" => "ik_smart",
                                        "store" => "no"
                                    ]
                                ]
                            ],
                            'content' => [
                                "type" => "string",
                                "analyzer" => "ik_max_word",
                                "search_analyzer" => "ik_max_word",
                                "store" => "no",
                                "term_vector" => "with_positions_offsets",
                                "include_in_all" => "true",
                                'fields' => [
                                    'long' => [
                                        'type' => 'string',
                                        "analyzer" => "ik_smart",
                                        "search_analyzer" => "ik_smart",
                                        "store" => "no"
                                    ]
                                ]
                            ],
                            'tags' => [
                                "type" => "string",
                                "analyzer" => "ik_max_word",
                                "search_analyzer" => "ik_max_word",
                                "store" => "no",
                                "term_vector" => "with_positions_offsets",
                                "include_in_all" => "true",
                                "boost" => 9,
                                'fields' => [
                                    'long' => [
                                        'type' => 'string',
                                        "analyzer" => "ik_smart",
                                        "search_analyzer" => "ik_smart",
                                        "store" => "no"
                                    ]
                                ]
                            ],
                            'example' => [
                                "type" => "string",
                                "index" => "not_analyzed",
                            ],
                            'created_at' => [
                                "type" => "integer",
                                "index" => "not_analyzed",
                            ],
                        ]
                    ]
                ]
            ]
        ];
        $this->c()->indices()->create($index);

    }

    protected function getNotes(){
        return spyc_load_file($this->getDataFile());
    }
    protected function getDataFile(){
        return Yii::getAlias('@console/controllers/data/note.yml');
    }

    public function c(){
        if($this->_node){
            return $this->_node;
        }
        $hosts = [
                    "localhost:9200",
                ];
        return $this->_node = ClientBuilder::create()           // Instantiate a new ClientBuilder
                                    ->setHosts($hosts)      // Set the hosts
                                    ->build();;
    }

}
