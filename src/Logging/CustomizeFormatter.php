<?php

namespace Suhailparad\LogViewer\Logging;

use Illuminate\Log\Logger;
use Monolog\Formatter\LineFormatter;

class CustomizeFormatter{

    /**
     * Customize the given logger instance.
     *
     * @param  Logger  $logger
     *
     * @return void
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter($this->getCustomFormatter());
        }
    }

    /**
     * Return a custom formatter instance.
     *
     * @return \Monolog\Formatter\LineFormatter
     */
    protected function getCustomFormatter()
    {
        // Custom format including tenant_id directly
        $output = "[%datetime%] tenant-%extra.tenant_id%.%channel%.%level_name%: %message% %context%\n";

         // Set the datetime format to [YYYY-MM-DD HH:MM:SS]
         $dateFormat = 'Y-m-d H:i:s';

         $formatter = new LineFormatter($output, $dateFormat, true, true);

        // Optional: You may want to remove tenant_id from the context to avoid redundancy
        $formatter->includeStacktraces(true);

        return $formatter;
    }

}
