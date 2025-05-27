<?php

namespace Suhailparad\LogViewer\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Suhailparad\LogViewer\Utility\LogViewerUtility;
use Illuminate\Routing\Controller;

class IndexController extends Controller{

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->middleware(config('log-viewer.middlewares',[]));
    }

    public function index($file_id=null)
    {
        $showAll = request()->has('show_all');
        $logFiles = [];
        $files = File::files(storage_path('logs'));

        // Sort files by creation time
        usort($files, function ($a, $b) {
            return $b->getCTime() <=> $a->getCTime(); // Compare creation times
        });

        $index = 0;
        foreach ($files as $file) {
            $filename = $file->getFilename();
            if (strpos($filename, 'laravel-') !== 0) {
                continue;
            }
            $index++;
            $logFiles[] = [
                'id' => $index,
                'name' => $file->getFilename(),
                'size' =>  LogViewerUtility::formatBytes($file->getSize())
            ];
        }

        if($file_id!=null && !isset($logFiles[$file_id-1])){
            abort(404);
        }

        $file_name = $logFiles[0]['name'];
        $log_index = 0;

        if(!is_null($file_id)){
            $log_index = $logFiles[$file_id-1]['id']-1;
            $file_name = $logFiles[$file_id-1]['name'];
        }

        $logContents = file_get_contents(base_path("storage/logs/{$file_name}"));

        // Split the contents into individual log entries
        $logEntries = preg_split('/\r\n|\r|\n/', $logContents);

        $groupedLogs = [];
        $currentLog = [];

        foreach ($logEntries as $line) {
            // Match the datetime, type, and message using regex
            if (preg_match('/^\[(.*?)\] (.*?)\s*:\s*(.*)$/', $line, $matches)) {
                // If there's an ongoing log entry, save it
                if (!empty($currentLog)) {
                    $groupedLogs[] = $currentLog;
                    $currentLog = [];
                }

                $addToArray=true;
                if(config('log-viewer.multi_tenant')){
                    $tenant_id = config('app.tenant_id');
                    $log_tenant_id = LogViewerUtility::getLogTenantId($matches[2]);
                    if($showAll){
                        if($log_tenant_id != $tenant_id && $log_tenant_id !=null)
                            $addToArray=false;
                    }else{
                        if($log_tenant_id != $tenant_id){
                            $addToArray=false;
                        }
                    }
                }

                // config('app.current_tenant')
                if($matches[1] !="previous exception" && $addToArray){
                    $currentLog = [
                        'datetime' => $matches[1],
                        'type' =>  LogViewerUtility::getLogType($matches[2]),
                        'title' => LogViewerUtility::truncateString($matches[3],110),
                        'message' => $matches[3],
                        'has_multi_line' => strlen($matches[3])>110
                    ];
                }
            } elseif (!empty($currentLog)) {
                // Append lines to the current log message
                $currentLog['message'] .= '<div style="margin-bottom:2px" ></div>' . $line;
            }
        }

        // Add the last log entry if it exists
        if (!empty($currentLog)) {
            $groupedLogs[] = $currentLog;
        }

        $logsCollection = collect($groupedLogs)->reverse();

        if(request()->has('query')){
            $keyword=strtolower(request()->get('query'));
            $logsCollection = $logsCollection->filter(function ($item) use ($keyword) {
                return strpos( strtolower($item['message']), $keyword) !== false;
            });
        }
        $logsCollection = LogViewerUtility::paginate($logsCollection);

        return view("log-viewer::index",[
            'logs' => $logsCollection,
            'log_files' => $logFiles,
            'log_index' => $log_index,
            'showAll' => $showAll,
            'is_multi_tenant' => config('log-viewer.multi_tenant')
        ]);
    }

    public function download($file_id,$key){
        $secret_key = config('log-viewer.secret_key');

        if($key!=$secret_key){
            abort(403);
        }

        $files = File::files(storage_path('logs'));

        // Sort files by creation time
        usort($files, function ($a, $b) {
            return $b->getCTime() <=> $a->getCTime(); // Compare creation times
        });

        $index = 0;
        foreach ($files as $file) {
            $filename = $file->getFilename();
            if (strpos($filename, 'laravel-') !== 0) {
                continue;
            }
            $index++;
            $logFiles[] = [
                'id' => $index,
                'name' => $file->getFilename(),
                'size' =>  LogViewerUtility::formatBytes($file->getSize())
            ];
        }

        if($file_id!=null && !isset($logFiles[$file_id-1])){
            abort(404);
        }
        $file_name = $logFiles[$file_id-1]['name'];

        $path = storage_path("logs/{$file_name}");

        // Check if the file exists
        if (!File::exists($path)) {
            abort(404, "Log file not found.");
        }

        // Return the file as a download response
        return response()->download($path);

    }
}
