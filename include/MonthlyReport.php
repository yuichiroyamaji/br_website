<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Services\EmailSendService;
use App\Report;
use App\User;
use App\Services\DayService;
use Carbon\Carbon;
use App\Items\Constances;
use DB;

class MonthlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:monthlyreport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Commands description:monthlyreport 9:01 on first day of month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('月次報告送信開始');
        DB::beginTransaction();
        try{
            // 配列の初期化
            $first = array();
            $last = array();
            // 一番日付の早いデータの年月を取得
            $first = $this->getMonth(Report::orderBy('date', 'asc')->pluck('date')->first());
            // 一番日付の遅いデータの年月を取得
            $last = $this->getMonth(Report::orderBy('date', 'asc')->pluck('date')->last());
            // 
            $yearly = '-------------------------------'."\r\n";
            $yearly .= '▼年次実績（年商）'."\r\n";
            $yearly .= ' 年 | 売上 | 純利益 | 利益率 '."\r\n";
            $yearly .= '-------------------------------'."\r\n";
            $monthly = '-------------------------------'."\r\n";
            $monthly .= '▼月次実績（月商）'."\r\n";
            $monthly .= ' 月 | 売上 | 純利益 | 利益率 '."\r\n";
            $monthly .= '-------------------------------'."\r\n";
            $chart = '-------------------------------'."\r\n";
            $chart .= '▼売上げチャート'."\r\n";
            $chart .= ' 年月 | 売上 (+:35,000)'."\r\n";
            $chart .= '-------------------------------'."\r\n";
            // $chart .= '------- +--------------------------'."\r\n";
            for($i=$first['year']; $i<=$last['year']; $i++){
                $data[$i]['total_sales'] = Report::whereYear('date', $i)->sum('total_sales');
                $data[$i]['net_sales'] = Report::whereYear('date', $i)->sum('net_sales');
                $data[$i]['profit_rate'] = floor($data[$i]['net_sales'] / $data[$i]['total_sales'] * 100);
                $yearly .= $i.'年 | ￥'.number_format($data[$i]['total_sales']).' | ￥'.number_format($data[$i]['net_sales']).' | '.$data[$i]['profit_rate'].'%'."\r\n";
                if($first['year'] == $last['year']){
                    $start_month = $first['month'];
                    $last_month = $last['month'];
                }elseif($i == $first['year']){
                    $start_month = $first['month'];
                    $last_month = 12;
                }elseif($i == $last['year']){
                    $start_month = 1;
                    $last_month = $last['month'];
                }else{
                    $start_month = 1;
                    $last_month = 12;
                }
                $monthly .= '('.$i.'年)'."\r\n";
                $chart .= '('.$i.'年)'."\r\n";
                for($j=$start_month; $j<=$last_month; $j++){
                    $j_2dgt = sprintf('%02d', $j);
                    // echo $i.'年'.$j.'月'."\r\n";
                    $data[$i][$j]['total_sales'] = Report::whereYear('date', $i)->whereMonth('date', $j)->sum('total_sales');
                    $data[$i][$j]['chart'] = str_repeat('+', floor($data[$i][$j]['total_sales']) / 35000).' '.round($data[$i][$j]['total_sales']/10000, 1);
                    $data[$i][$j]['net_sales'] = Report::whereYear('date', $i)->whereMonth('date', $j)->sum('net_sales');
                    $data[$i][$j]['profit_rate'] = floor($data[$i][$j]['net_sales'] / $data[$i][$j]['total_sales'] * 100);
                    $monthly .= $j_2dgt.'月 | ￥'.number_format($data[$i][$j]['total_sales']).' | ￥'.number_format($data[$i][$j]['net_sales']).' | '.$data[$i][$j]['profit_rate'].'%'."\r\n";
                    $chart .= $j_2dgt.'月 '.$data[$i][$j]['chart']."\r\n";
                }
            }
            // $to = Constances::OWNER_EMAIL;
            $to = Constances::OWNER_EMAIL;
            $subject = '【月次報告】 ～'.$last['year'].'年'.$last['month'].'月';
            $message = $yearly."\r\n".$monthly."\r\n".$chart;
            // echo $message;
            // exit;
            $result = EmailSendService::send($to, $subject, $message);
            DB::commit();
            if($result){Log::info('月次報告送信完了');}
        }catch(Exception $e){
            DB::rollBack();
            Log::error('月次報告送信エラー: '.$e);
        }        

    }

    private function getMonth($date){
        $date = new Carbon($date);
        $param = array();
        $param['year'] = $date->year;
        $param['month'] = $date->month;
        return $param;
    }


}
