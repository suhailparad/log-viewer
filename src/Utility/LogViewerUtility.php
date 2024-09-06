<?php

namespace Suhailparad\LogViewer\Utility;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class LogViewerUtility{

    public static function truncateString($string, $maxLength) {
        // Check if the string length is greater than the maximum allowed length
        if (strlen($string) > $maxLength) {
            // Truncate the string and add '...'
            return substr($string, 0, $maxLength - 3) . '...';
        } else {
            // Return the original string if it is within the allowed length
            return $string;
        }
    }

    public static function paginate($items){
        $page = request()->get('page', 1);
        $perPage = 15;

        $slicedItems = $items->slice(($page - 1) * $perPage, $perPage)->values();

        // Create the paginator
        $paginator = new LengthAwarePaginator(
            $slicedItems, // The sliced collection
            $items->count(), // Total items
            $perPage, // Items per page
            $page, // Current page
            ['path' => request()->url(), 'query' => request()->query()] // Path and query parameters for pagination links
        );
        return $paginator;
    }

    public static function formatBytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) ." ". $units[$pow];
    }

    public static function getLogType($type){
        $splitted_type = explode(".",$type);
        if(count($splitted_type)>2)
            return strtolower($splitted_type[2]);
        return strtolower($splitted_type[1]);
    }

    public static function getLogTenantId($payload){
        $payload = explode(".",$payload);
        $tenant_data =  $payload[0];
        $tenant = explode('-',$tenant_data);
        if($tenant[0]=='tenant')
            return (int)$tenant[1];
    }
}
