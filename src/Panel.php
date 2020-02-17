<?php

declare(strict_types=1);

namespace DanceEngineer\GuzzleTracy;

use GuzzleHttp\Profiling\Profiler;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tracy;

class Panel implements Tracy\IBarPanel, Profiler
{
    public static $MAX_REQUESTS = 10;

    /** @var int */
    private $count = 0;

    /** @var array */
	private $messages = [];

    /** @var float */
    private $totalTime;

    public function add($start, $end, RequestInterface $request, ResponseInterface $response = null): void
    {
        $this->count     += 1;

        $time            = $end - $start;
        $this->totalTime += $time;

        if(self::$MAX_REQUESTS >= $this->count) {
            $responseText = $response === null ? 'No response' : $response->getReasonPhrase();
            $this->messages[] = [$time, $request->getUri()->__toString(), $responseText,];
        }
    }


    public function getTab(): string
	{
		if (headers_sent() && !session_id()) {
			return '';
		}

		if($this->count === 0) {
		    return '';
        }

        $count = $this->count;
        $totalTime = $this->totalTime;
        $messages = $this->messages;
		ob_start(function () {});
		require __DIR__ . '/templates/Panel.tab.phtml';
		return ob_get_clean();
	}

    public function getPanel(): string
	{
        if($this->count === 0) {
            return '';
        }

        $count = $this->count;
        $totalTime = $this->totalTime;
        $messages = $this->messages;
		ob_start(function () {});
		require __DIR__ . '/templates/Panel.panel.phtml';
		return ob_get_clean();
	}
}
