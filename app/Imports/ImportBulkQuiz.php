<?php
namespace App\Imports;

use App\Models\Course\Question;
use App\Models\Course\Answer;
use Maatwebsite\Excel\Concerns\ToModel;
class ImportBulkQuiz implements ToModel
{
    public function  __construct($quiz_id)
    {
        $this->quiz_id= $quiz_id;
    }

    public function model(array $row)
    {
        \Log::info($row);
        if($row[0] != 'question'){

            $questionId = Question::insertGetId([
                'quiz_id'    => $this->quiz_id,
                'question'   => $row[0],
                'created_at' => date("Y-m-d H:i:s")
            ]);

            for($i = 1; $i < count($row)-1; $i++){

                if($row[$i] != null){

                    $is_correct = 0;
                    if($row[5] == $row[$i]){
                        $is_correct = 1;
                    }

                    Answer::insert([
                        'question_id' => $questionId,
                        'answer'      => $row[$i],
                        'is_correct'  => $is_correct,
                        'created_at'  => date("Y-m-d H:i:s")
                    ]);
                }
            }
            
        }
    }
}
